<?php

namespace Tests\Unit\Requests;

use App\Enums\DocumentStatus;
use App\Http\Requests\ArchiveDocumentRequest;
use App\Http\Requests\BulkArchiveDocumentsRequest;
use App\Http\Requests\BulkDeleteDocumentsRequest;
use App\Http\Requests\BulkRestoreDocumentsRequest;
use App\Http\Requests\CreateDocumentRequest;
use App\Http\Requests\DestroyDocumentRequest;
use App\Http\Requests\DownloadDocumentRequest;
use App\Http\Requests\EditDocumentRequest;
use App\Http\Requests\PreviewDocumentRequest;
use App\Http\Requests\RestoreDocumentRequest;
use App\Http\Requests\ShowDocumentRequest;
use App\Models\Document;
use App\Models\User;
use Tests\TestCase;

class DocumentActionRequestsTest extends TestCase
{
    public function test_create_document_request_authorize_uses_policy(): void
    {
        $user = User::factory()->create();
        $request = new CreateDocumentRequest;
        $request->setUserResolver(fn () => $user);

        $this->assertTrue($request->authorize());
        $this->assertIsArray($request->rules());
    }

    public function test_document_action_requests_enforce_ownership_authorization(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $document = Document::factory()->for($owner)->create([
            'title' => 'Passport',
            'status' => DocumentStatus::Active,
            'original_filename' => 'passport.pdf',
            'stored_path' => 'documents/passport.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 1000,
        ]);

        $requestClasses = [
            ShowDocumentRequest::class,
            EditDocumentRequest::class,
            DestroyDocumentRequest::class,
            ArchiveDocumentRequest::class,
            PreviewDocumentRequest::class,
            DownloadDocumentRequest::class,
            RestoreDocumentRequest::class,
        ];

        foreach ($requestClasses as $requestClass) {
            $ownerRequest = new $requestClass;
            $ownerRequest->setUserResolver(fn () => $owner);
            $this->bindDocumentRoute($ownerRequest, $document);
            $this->assertTrue($ownerRequest->authorize());

            $otherRequest = new $requestClass;
            $otherRequest->setUserResolver(fn () => $other);
            $this->bindDocumentRoute($otherRequest, $document);
            $this->assertFalse($otherRequest->authorize());
        }
    }

    public function test_bulk_document_action_requests_expose_document_id_rules(): void
    {
        $user = User::factory()->create();

        foreach ([BulkArchiveDocumentsRequest::class, BulkRestoreDocumentsRequest::class, BulkDeleteDocumentsRequest::class] as $requestClass) {
            $request = new $requestClass;
            $request->setUserResolver(fn () => $user);

            $this->assertTrue($request->authorize());
            $this->assertArrayHasKey('document_ids', $request->rules());
            $this->assertArrayHasKey('document_ids.*', $request->rules());
        }
    }

    private function bindDocumentRoute(object $request, Document $document): void
    {
        $request->setRouteResolver(fn () => new class($document)
        {
            public function __construct(private Document $document) {}

            public function parameter(string $name, mixed $default = null): mixed
            {
                return $name === 'document' ? $this->document : $default;
            }
        });
    }
}
