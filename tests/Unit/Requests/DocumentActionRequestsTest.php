<?php

use App\Http\Requests\ArchiveDocumentRequest;
use App\Http\Requests\CreateDocumentRequest;
use App\Http\Requests\DestroyDocumentRequest;
use App\Http\Requests\DownloadDocumentRequest;
use App\Http\Requests\EditDocumentRequest;
use App\Http\Requests\PreviewDocumentRequest;
use App\Http\Requests\ShowDocumentRequest;
use App\Models\Document;
use App\Models\User;

function bindDocumentRoute(object $request, Document $document): void
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

test('create document request authorize uses policy', function () {
    $user = User::factory()->create();
    $request = new CreateDocumentRequest;
    $request->setUserResolver(fn () => $user);

    expect($request->authorize())->toBeTrue();
    expect($request->rules())->toBeArray();
});

test('document action requests enforce ownership authorization', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $document = Document::factory()->for($owner)->create([
        'title' => 'Passport',
        'status' => Document::STATUS_ACTIVE,
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
    ];

    foreach ($requestClasses as $requestClass) {
        $ownerRequest = new $requestClass;
        $ownerRequest->setUserResolver(fn () => $owner);
        bindDocumentRoute($ownerRequest, $document);
        expect($ownerRequest->authorize())->toBeTrue();

        $otherRequest = new $requestClass;
        $otherRequest->setUserResolver(fn () => $other);
        bindDocumentRoute($otherRequest, $document);
        expect($otherRequest->authorize())->toBeFalse();
    }
});
