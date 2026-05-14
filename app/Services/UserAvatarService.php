<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Throwable;

class UserAvatarService
{
    public function storeFromUrl(?string $remoteUrl, string $userId): ?string
    {
        if ($remoteUrl === null || $remoteUrl === '') {
            return null;
        }

        try {
            $response = Http::timeout(10)->get($remoteUrl);
        } catch (Throwable) {
            return null;
        }

        if (! $response->successful()) {
            return null;
        }

        $extension = $this->extensionFromContentType($response->header('Content-Type')) ?? 'jpg';
        $path = "users/{$userId}.{$extension}";

        Storage::disk('public')->put($path, $response->body());

        return Storage::disk('public')->url($path);
    }

    private function extensionFromContentType(?string $contentType): ?string
    {
        if ($contentType === null) {
            return null;
        }

        return match (true) {
            str_contains($contentType, 'image/jpeg') => 'jpg',
            str_contains($contentType, 'image/png') => 'png',
            str_contains($contentType, 'image/webp') => 'webp',
            str_contains($contentType, 'image/gif') => 'gif',
            default => null,
        };
    }
}
