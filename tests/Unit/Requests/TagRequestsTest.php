<?php

use App\DTOs\StoreTagData;
use App\DTOs\UpdateTagData;
use App\Http\Requests\DestroyTagRequest;
use App\Http\Requests\IndexTagRequest;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use App\Models\User;

function bindTagRoute(object $request, Tag $tag): void
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

test('index and store tag requests authorize and expose expected rules', function () {
    $user = User::factory()->create();

    $indexRequest = new IndexTagRequest;
    $indexRequest->setUserResolver(fn () => $user);

    $storeRequest = new StoreTagRequest;
    $storeRequest->setUserResolver(fn () => $user);

    expect($indexRequest->authorize())->toBeTrue();
    expect($storeRequest->authorize())->toBeTrue();
    expect($storeRequest->rules())->toHaveKey('name');
});

test('store and update tag requests build dto instances', function () {
    $storeRequest = Mockery::mock(StoreTagRequest::class)->makePartial();
    $storeRequest->shouldReceive('validated')->andReturn([
        'name' => '  Important  ',
    ]);

    $storeDto = $storeRequest->toDto();

    expect($storeDto)->toBeInstanceOf(StoreTagData::class);
    expect($storeDto->name)->toBe('Important');

    $updateRequest = Mockery::mock(UpdateTagRequest::class)->makePartial();
    $updateRequest->shouldReceive('validated')->andReturn([
        'name' => '  Signed  ',
    ]);

    $updateDto = $updateRequest->toDto();

    expect($updateDto)->toBeInstanceOf(UpdateTagData::class);
    expect($updateDto->name)->toBe('Signed');
});

test('update and destroy tag requests enforce ownership authorization', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $tag = Tag::factory()->for($owner)->create([
        'name' => 'Private Tag',
        'slug' => 'private-tag',
    ]);

    $ownerUpdateRequest = new UpdateTagRequest;
    $ownerUpdateRequest->setUserResolver(fn () => $owner);
    bindTagRoute($ownerUpdateRequest, $tag);
    expect($ownerUpdateRequest->authorize())->toBeTrue();

    $otherUpdateRequest = new UpdateTagRequest;
    $otherUpdateRequest->setUserResolver(fn () => $other);
    bindTagRoute($otherUpdateRequest, $tag);
    expect($otherUpdateRequest->authorize())->toBeFalse();

    $ownerDestroyRequest = new DestroyTagRequest;
    $ownerDestroyRequest->setUserResolver(fn () => $owner);
    bindTagRoute($ownerDestroyRequest, $tag);
    expect($ownerDestroyRequest->authorize())->toBeTrue();

    $otherDestroyRequest = new DestroyTagRequest;
    $otherDestroyRequest->setUserResolver(fn () => $other);
    bindTagRoute($otherDestroyRequest, $tag);
    expect($otherDestroyRequest->authorize())->toBeFalse();
});
