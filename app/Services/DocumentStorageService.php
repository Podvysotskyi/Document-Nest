<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentStorageService
{
    public function store(UploadedFile $file, string $userId): string
    {
        return $file->store("documents/{$userId}", 'local');
    }

    public function delete(string $storedPath): void
    {
        Storage::disk('local')->delete($storedPath);
    }

    public function streamPreview(string $storedPath, string $mimeType): StreamedResponse
    {
        return Storage::disk('local')->response(
            $storedPath,
            headers: ['Content-Type' => $mimeType],
        );
    }

    public function streamDownload(string $storedPath, string $originalFilename): StreamedResponse
    {
        return Storage::disk('local')->download($storedPath, $originalFilename);
    }
}
