<?php

use App\DTOs\DocumentFiltersData;
use App\DTOs\StoreDocumentData;
use App\DTOs\UpdateDocumentData;
use App\Enums\DocumentStatus;
use App\Http\Requests\IndexDocumentRequest;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\User;
use Illuminate\Http\UploadedFile;

test('store document request exposes expected rules and builds dto', function () {
    $request = Mockery::mock(StoreDocumentRequest::class)->makePartial();
    $request->shouldReceive('validated')->andReturn([
        'file' => UploadedFile::fake()->create('passport.pdf', 20, 'application/pdf'),
        'title' => 'Passport',
        'category_id' => 'category-id',
        'tag_ids' => ['tag-1', 'tag-2'],
        'notes' => 'notes',
        'issue_date' => '2024-01-01',
        'expiry_date' => '2028-01-01',
        'status' => 'active',
    ]);

    $dto = $request->toDto();

    expect($dto)->toBeInstanceOf(StoreDocumentData::class);
    expect($dto->title)->toBe('Passport');
    expect($dto->tagIds)->toBe(['tag-1', 'tag-2']);
    expect($dto->status)->toBe(DocumentStatus::Active);
});

test('update document request exposes expected rules and builds dto', function () {
    $request = Mockery::mock(UpdateDocumentRequest::class)->makePartial();
    $request->shouldReceive('validated')->andReturn([
        'title' => 'Passport Updated',
        'category_id' => 'category-id',
        'tag_ids' => ['tag-1'],
        'notes' => 'notes',
        'issue_date' => '2024-01-01',
        'expiry_date' => '2029-01-01',
        'status' => 'archived',
    ]);

    $dto = $request->toDto();

    expect($dto)->toBeInstanceOf(UpdateDocumentData::class);
    expect($dto->title)->toBe('Passport Updated');
    expect($dto->status)->toBe(DocumentStatus::Archived);
});

test('index document request rules, authorize and dto mapping', function () {
    $user = User::factory()->create();
    $request = Mockery::mock(IndexDocumentRequest::class)->makePartial();
    $request->setUserResolver(fn () => $user);
    $request->shouldReceive('validated')->andReturn([
        'q' => 'passport',
        'category_id' => 'category-id',
        'tag_id' => 'tag-id',
        'status' => 'active',
        'expiry_from' => '2025-01-01',
        'expiry_to' => '2026-01-01',
        'sort' => 'title',
    ]);

    expect($request->authorize())->toBeTrue();
    expect($request->rules())->toHaveKeys([
        'q',
        'category_id',
        'tag_id',
        'status',
        'expiry_from',
        'expiry_to',
        'sort',
    ]);

    $dto = $request->toDto();
    expect($dto)->toBeInstanceOf(DocumentFiltersData::class);
    expect($dto->query)->toBe('passport');
    expect($dto->sort)->toBe('title');
    expect($dto->status)->toBe(DocumentStatus::Active);
});

test('index document request dto uses default sort when missing', function () {
    $request = Mockery::mock(IndexDocumentRequest::class)->makePartial();
    $request->shouldReceive('validated')->andReturn([
        'q' => null,
    ]);

    $dto = $request->toDto();

    expect($dto->sort)->toBe('newest');
});
