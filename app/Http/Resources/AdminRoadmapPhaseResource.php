<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminRoadmapPhaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource['id'],
            'label' => $this->resource['label'],
            'title' => $this->resource['title'],
            'status' => $this->resource['status'],
            'sort_order' => $this->resource['sort_order'],
            'is_visible' => $this->resource['is_visible'],
            'items' => AdminRoadmapItemResource::collection($this->resource['items'])->resolve(),
        ];
    }
}
