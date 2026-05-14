<?php

namespace Tests\Unit\Requests;

use App\DTOs\DocumentFiltersData;
use App\DTOs\StoreDocumentData;
use App\DTOs\UpdateDocumentData;
use App\Enums\DocumentStatus;
use App\Http\Requests\IndexDocumentRequest;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Mockery;
use Tests\TestCase;

class DocumentDataRequestsTest extends TestCase
{
    public function test_store_document_request_exposes_expected_rules_and_builds_dto(): void
    {
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

        $this->assertInstanceOf(StoreDocumentData::class, $dto);
        $this->assertSame('Passport', $dto->title);
        $this->assertSame(['tag-1', 'tag-2'], $dto->tagIds);
        $this->assertSame(DocumentStatus::Active, $dto->status);
    }

    public function test_update_document_request_exposes_expected_rules_and_builds_dto(): void
    {
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

        $this->assertInstanceOf(UpdateDocumentData::class, $dto);
        $this->assertSame('Passport Updated', $dto->title);
        $this->assertSame(DocumentStatus::Archived, $dto->status);
    }

    public function test_index_document_request_rules_authorize_and_dto_mapping(): void
    {
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

        $this->assertTrue($request->authorize());

        foreach ([
            'q',
            'category_id',
            'tag_id',
            'status',
            'expiry_from',
            'expiry_to',
            'sort',
        ] as $rule) {
            $this->assertArrayHasKey($rule, $request->rules());
        }

        $dto = $request->toDto();
        $this->assertInstanceOf(DocumentFiltersData::class, $dto);
        $this->assertSame('passport', $dto->query);
        $this->assertSame('title', $dto->sort);
        $this->assertSame(DocumentStatus::Active, $dto->status);
    }

    public function test_index_document_request_dto_uses_default_sort_when_missing(): void
    {
        $request = Mockery::mock(IndexDocumentRequest::class)->makePartial();
        $request->shouldReceive('validated')->andReturn([
            'q' => null,
        ]);

        $dto = $request->toDto();

        $this->assertSame('newest', $dto->sort);
    }

    public function test_index_document_request_maps_uncategorized_category_filter(): void
    {
        $request = Mockery::mock(IndexDocumentRequest::class)->makePartial();
        $request->shouldReceive('validated')->andReturn([
            'category_id' => 'uncategorized',
        ]);

        $dto = $request->toDto();

        $this->assertNull($dto->categoryId);
        $this->assertTrue($dto->uncategorizedOnly);
    }
}
