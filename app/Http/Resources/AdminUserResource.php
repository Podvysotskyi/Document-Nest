<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'google_id' => $this->google_id,
            'avatar_url' => $this->avatar_url,
            'roles' => $this->roles->map(fn ($role): array => [
                'id' => $role->id,
                'name' => $role->name,
            ])->values()->all(),
            'is_current_user' => $request->user()?->is($this->resource),
            'documents_count' => $this->whenCounted('documents'),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
