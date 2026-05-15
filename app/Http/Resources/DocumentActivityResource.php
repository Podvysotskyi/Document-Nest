<?php

namespace App\Http\Resources;

use App\Enums\DocumentActivityType;
use App\Models\Mongodb\DocumentActivity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin DocumentActivity
 */
class DocumentActivityResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $type = $this->type instanceof DocumentActivityType ? $this->type : DocumentActivityType::from((string) $this->type);

        return [
            'id' => (string) $this->getKey(),
            'type' => $type->value,
            'label' => $type->label(),
            'description' => $this->description,
            'actor_id' => $this->actor_id,
            'document_title' => $this->document_title,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
