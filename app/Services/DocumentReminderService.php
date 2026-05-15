<?php

namespace App\Services;

use App\Enums\DocumentStatus;
use App\Models\Document;
use Illuminate\Support\Carbon;

class DocumentReminderService
{
    /**
     * Lead times (in days before expiry) at which a reminder should be sent.
     *
     * @var array<int, int>
     */
    public const LEAD_TIMES_IN_DAYS = [30, 7, 1];

    /**
     * Compute the dates on which reminders should be sent for the given expiry date.
     *
     * Reminders that would land on or before today are skipped, so syncing for a
     * document whose expiry is already close does not create stale unsent rows.
     *
     * @return array<int, string> ISO date strings, ascending
     */
    public function reminderDatesFor(?Carbon $expiryDate, ?Carbon $today = null): array
    {
        if ($expiryDate === null) {
            return [];
        }

        $today = ($today ?? Carbon::today())->startOfDay();
        $expiry = $expiryDate->copy()->startOfDay();

        if ($expiry->lt($today)) {
            return [];
        }

        $dates = [];

        foreach (self::LEAD_TIMES_IN_DAYS as $leadDays) {
            $remindOn = $expiry->copy()->subDays($leadDays);

            if ($remindOn->lt($today)) {
                continue;
            }

            $dates[$remindOn->toDateString()] = true;
        }

        $dates = array_keys($dates);
        sort($dates);

        return $dates;
    }

    /**
     * Sync the document's unsent reminder rows to match its current expiry policy.
     */
    public function syncForDocument(Document $document): void
    {
        if ($this->shouldHaveNoReminders($document)) {
            $document->reminders()->whereNull('sent_at')->delete();

            return;
        }

        $desired = $this->reminderDatesFor($document->expiry_date);

        if ($desired === []) {
            $document->reminders()->whereNull('sent_at')->delete();

            return;
        }

        $document->reminders()
            ->whereNull('sent_at')
            ->whereNotIn('remind_on', $desired)
            ->delete();

        $existing = $document->reminders()
            ->whereIn('remind_on', $desired)
            ->pluck('remind_on')
            ->map(fn (Carbon $date): string => $date->toDateString())
            ->all();

        foreach (array_diff($desired, $existing) as $remindOn) {
            $document->reminders()->create([
                'user_id' => $document->user_id,
                'remind_on' => $remindOn,
            ]);
        }
    }

    private function shouldHaveNoReminders(Document $document): bool
    {
        if ($document->expiry_date === null) {
            return true;
        }

        return $document->status === DocumentStatus::Archived;
    }
}
