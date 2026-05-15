<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentDetailResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status,
            'issue_date' => $this->issue_date?->toDateString(),
            'expiry_date' => $this->expiry_date?->toDateString(),
            'notes' => $this->notes,
            'original_filename' => $this->original_filename,
            'mime_type' => $this->mime_type,
            'file_size' => $this->file_size,
            'category' => $this->whenLoaded('category', fn () => CategoryResource::make($this->category)->resolve($request)),
            'tags' => $this->whenLoaded('tags', fn () => TagResource::collection($this->tags)->resolve($request), []),
            'reminders' => $this->whenLoaded('reminders', fn () => $this->buildReminderSummary(), $this->emptyReminderSummary()),
        ];
    }

    /**
     * @return array{upcoming: array<int, array{id: string, remind_on: string}>, last_sent_at: ?string}
     */
    private function buildReminderSummary(): array
    {
        $upcoming = $this->reminders
            ->whereNull('sent_at')
            ->sortBy('remind_on')
            ->values()
            ->map(fn ($reminder): array => [
                'id' => (string) $reminder->id,
                'remind_on' => $reminder->remind_on->toDateString(),
            ])
            ->all();

        $lastSent = $this->reminders
            ->whereNotNull('sent_at')
            ->sortByDesc('sent_at')
            ->first();

        return [
            'upcoming' => $upcoming,
            'last_sent_at' => $lastSent?->sent_at?->toIso8601String(),
        ];
    }

    /**
     * @return array{upcoming: array<int, array{id: string, remind_on: string}>, last_sent_at: null}
     */
    private function emptyReminderSummary(): array
    {
        return [
            'upcoming' => [],
            'last_sent_at' => null,
        ];
    }
}
