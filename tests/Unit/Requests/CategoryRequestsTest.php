<?php

namespace Tests\Unit\Requests;

use App\DTOs\StoreCategoryData;
use App\DTOs\UpdateCategoryData;
use App\Http\Requests\DestroyCategoryRequest;
use App\Http\Requests\IndexCategoryRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\User;
use Mockery;
use Tests\TestCase;

class CategoryRequestsTest extends TestCase
{
    public function test_index_and_store_category_requests_authorize_and_expose_expected_rules(): void
    {
        $user = User::factory()->create();

        $indexRequest = new IndexCategoryRequest;
        $indexRequest->setUserResolver(fn () => $user);

        $storeRequest = new StoreCategoryRequest;
        $storeRequest->setUserResolver(fn () => $user);

        $this->assertTrue($indexRequest->authorize());
        $this->assertTrue($storeRequest->authorize());
        $this->assertArrayHasKey('name', $storeRequest->rules());
    }

    public function test_store_and_update_category_requests_build_dto_instances(): void
    {
        $storeRequest = Mockery::mock(StoreCategoryRequest::class)->makePartial();
        $storeRequest->shouldReceive('validated')->andReturn([
            'name' => '  Insurance  ',
        ]);

        $storeDto = $storeRequest->toDto();

        $this->assertInstanceOf(StoreCategoryData::class, $storeDto);
        $this->assertSame('Insurance', $storeDto->name);

        $updateRequest = Mockery::mock(UpdateCategoryRequest::class)->makePartial();
        $updateRequest->shouldReceive('validated')->andReturn([
            'name' => '  Legal Docs  ',
        ]);

        $updateDto = $updateRequest->toDto();

        $this->assertInstanceOf(UpdateCategoryData::class, $updateDto);
        $this->assertSame('Legal Docs', $updateDto->name);
    }

    public function test_update_and_destroy_category_requests_enforce_ownership_authorization(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $category = Category::factory()->for($owner)->create([
            'name' => 'Private Category',
            'slug' => 'private-category',
        ]);

        $ownerUpdateRequest = new UpdateCategoryRequest;
        $ownerUpdateRequest->setUserResolver(fn () => $owner);
        $this->bindCategoryRoute($ownerUpdateRequest, $category);
        $this->assertTrue($ownerUpdateRequest->authorize());

        $otherUpdateRequest = new UpdateCategoryRequest;
        $otherUpdateRequest->setUserResolver(fn () => $other);
        $this->bindCategoryRoute($otherUpdateRequest, $category);
        $this->assertFalse($otherUpdateRequest->authorize());

        $ownerDestroyRequest = new DestroyCategoryRequest;
        $ownerDestroyRequest->setUserResolver(fn () => $owner);
        $this->bindCategoryRoute($ownerDestroyRequest, $category);
        $this->assertTrue($ownerDestroyRequest->authorize());

        $otherDestroyRequest = new DestroyCategoryRequest;
        $otherDestroyRequest->setUserResolver(fn () => $other);
        $this->bindCategoryRoute($otherDestroyRequest, $category);
        $this->assertFalse($otherDestroyRequest->authorize());
    }

    private function bindCategoryRoute(object $request, Category $category): void
    {
        $request->setRouteResolver(fn () => new class($category)
        {
            public function __construct(private Category $category) {}

            public function parameter(string $name, mixed $default = null): mixed
            {
                return $name === 'category' ? $this->category : $default;
            }
        });
    }
}
