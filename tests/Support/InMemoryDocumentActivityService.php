<?php

namespace Tests\Support;

use App\DTOs\DocumentActivityPayload;
use App\Models\Mongodb\DocumentActivity;
use App\Services\DocumentActivityService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class InMemoryDocumentActivityService extends DocumentActivityService
{
    /**
     * @var array<int, DocumentActivityPayload>
     */
    public array $recorded = [];

    public function record(DocumentActivityPayload $payload): DocumentActivity
    {
        $this->recorded[] = $payload;

        $activity = new DocumentActivity($payload->toArray());
        $activity->setAttribute('_id', (string) Str::uuid());
        $activity->setAttribute('created_at', Carbon::now());
        $activity->setAttribute('updated_at', Carbon::now());

        return $activity;
    }

    /**
     * @return array<int, DocumentActivityPayload>
     */
    public function payloads(): array
    {
        return $this->recorded;
    }
}
