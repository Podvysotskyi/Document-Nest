<?php

namespace App\Services;

use App\DTOs\DocumentActivityPayload;
use App\Models\Mongodb\DocumentActivity;

class DocumentActivityService
{
    public function record(DocumentActivityPayload $payload): DocumentActivity
    {
        return DocumentActivity::query()->updateOrCreate(
            ['activity_id' => $payload->activityId],
            $payload->toArray(),
        );
    }
}
