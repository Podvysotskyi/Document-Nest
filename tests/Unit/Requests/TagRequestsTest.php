<?php

namespace Tests\Unit\Requests;

use App\DTOs\StoreTagData;
use App\DTOs\UpdateTagData;
use App\Http\Requests\DestroyTagRequest;
use App\Http\Requests\IndexTagRequest;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use App\Models\User;
use Mockery;
use Tests\TestCase;

class TagRequestsTest extends TestCase
{
    public function test_index_and_store_tag_requests_authorize_and_expose_expected_rules(): void
    {
        $user = User::factory()->create();

        $indexRequest = new IndexTagRequest;
        $indexRequest->setUserResolver(fn () => $user);

        $storeRequest = new StoreTagRequest;
        $storeRequest->setUserResolver(fn () => $user);

        $this->assertTrue($indexRequest->authorize());
        $this->assertTrue($storeRequest->authorize());
        $this->assertArrayHasKey('name', $storeRequest->rules());
    }

    public function test_store_and_update_tag_requests_build_dto_instances(): void
    {
        $storeRequest = Mockery::mock(StoreTagRequest::class)->makePartial();
        $storeRequest->shouldReceive('validated')->andReturn([
            'name' => '  Important  ',
        ]);

        $storeDto = $storeRequest->toDto();

        $this->assertInstanceOf(StoreTagData::class, $storeDto);
        $this->assertSame('Important', $storeDto->name);

        $updateRequest = Mockery::mock(UpdateTagRequest::class)->makePartial();
        $updateRequest->shouldReceive('validated')->andReturn([
            'name' => '  Signed  ',
        ]);

        $updateDto = $updateRequest->toDto();

        $this->assertInstanceOf(UpdateTagData::class, $updateDto);
        $this->assertSame('Signed', $updateDto->name);
    }

    public function test_update_and_destroy_tag_requests_enforce_ownership_authorization(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $tag = Tag::factory()->for($owner)->create([
            'name' => 'Private Tag',
            'slug' => 'private-tag',
        ]);

        $ownerUpdateRequest = new UpdateTagRequest;
        $ownerUpdateRequest->setUserResolver(fn () => $owner);
        $this->bindTagRoute($ownerUpdateRequest, $tag);
        $this->assertTrue($ownerUpdateRequest->authorize());

        $otherUpdateRequest = new UpdateTagRequest;
        $otherUpdateRequest->setUserResolver(fn () => $other);
        $this->bindTagRoute($otherUpdateRequest, $tag);
        $this->assertFalse($otherUpdateRequest->authorize());

        $ownerDestroyRequest = new DestroyTagRequest;
        $ownerDestroyRequest->setUserResolver(fn () => $owner);
        $this->bindTagRoute($ownerDestroyRequest, $tag);
        $this->assertTrue($ownerDestroyRequest->authorize());

        $otherDestroyRequest = new DestroyTagRequest;
        $otherDestroyRequest->setUserResolver(fn () => $other);
        $this->bindTagRoute($otherDestroyRequest, $tag);
        $this->assertFalse($otherDestroyRequest->authorize());
    }

    private function bindTagRoute(object $request, Tag $tag): void
    {
        $request->setRouteResolver(fn () => new class($tag)
        {
            public function __construct(private Tag $tag) {}

            public function parameter(string $name, mixed $default = null): mixed
            {
                return $name === 'tag' ? $this->tag : $default;
            }
        });
    }
}
