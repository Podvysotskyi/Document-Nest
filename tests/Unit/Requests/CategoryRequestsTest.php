<?php

use App\DTOs\StoreCategoryData;
use App\DTOs\UpdateCategoryData;
use App\Http\Requests\DestroyCategoryRequest;
use App\Http\Requests\IndexCategoryRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\User;

function bindCategoryRoute(object $request, Category $category): void
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

test('index and store category requests authorize and expose expected rules', function () {
    $user = User::factory()->create();

    $indexRequest = new IndexCategoryRequest;
    $indexRequest->setUserResolver(fn () => $user);

    $storeRequest = new StoreCategoryRequest;
    $storeRequest->setUserResolver(fn () => $user);

    expect($indexRequest->authorize())->toBeTrue();
    expect($storeRequest->authorize())->toBeTrue();
    expect($storeRequest->rules())->toHaveKey('name');
});

test('store and update category requests build dto instances', function () {
    $storeRequest = Mockery::mock(StoreCategoryRequest::class)->makePartial();
    $storeRequest->shouldReceive('validated')->andReturn([
        'name' => '  Insurance  ',
    ]);

    $storeDto = $storeRequest->toDto();

    expect($storeDto)->toBeInstanceOf(StoreCategoryData::class);
    expect($storeDto->name)->toBe('Insurance');

    $updateRequest = Mockery::mock(UpdateCategoryRequest::class)->makePartial();
    $updateRequest->shouldReceive('validated')->andReturn([
        'name' => '  Legal Docs  ',
    ]);

    $updateDto = $updateRequest->toDto();

    expect($updateDto)->toBeInstanceOf(UpdateCategoryData::class);
    expect($updateDto->name)->toBe('Legal Docs');
});

test('update and destroy category requests enforce ownership authorization', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $category = Category::factory()->for($owner)->create([
        'name' => 'Private Category',
        'slug' => 'private-category',
    ]);

    $ownerUpdateRequest = new UpdateCategoryRequest;
    $ownerUpdateRequest->setUserResolver(fn () => $owner);
    bindCategoryRoute($ownerUpdateRequest, $category);
    expect($ownerUpdateRequest->authorize())->toBeTrue();

    $otherUpdateRequest = new UpdateCategoryRequest;
    $otherUpdateRequest->setUserResolver(fn () => $other);
    bindCategoryRoute($otherUpdateRequest, $category);
    expect($otherUpdateRequest->authorize())->toBeFalse();

    $ownerDestroyRequest = new DestroyCategoryRequest;
    $ownerDestroyRequest->setUserResolver(fn () => $owner);
    bindCategoryRoute($ownerDestroyRequest, $category);
    expect($ownerDestroyRequest->authorize())->toBeTrue();

    $otherDestroyRequest = new DestroyCategoryRequest;
    $otherDestroyRequest->setUserResolver(fn () => $other);
    bindCategoryRoute($otherDestroyRequest, $category);
    expect($otherDestroyRequest->authorize())->toBeFalse();
});
