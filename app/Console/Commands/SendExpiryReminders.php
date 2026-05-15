<?php

namespace App\Console\Commands;

use App\Enums\DocumentStatus;
use App\Events\Documents\DocumentReminderSent;
use App\Models\DocumentReminder;
use App\Notifications\DocumentExpiringSoon;
use App\Services\DocumentActivityPayloadFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class SendExpiryReminders extends Command
{
    protected $signature = 'documents:send-expiry-reminders';

    protected $description = 'Email document owners reminders for documents that are about to expire.';

    public function handle(DocumentActivityPayloadFactory $payloadFactory): int
    {
        $today = Carbon::today();

        $reminders = DocumentReminder::query()
            ->pending()
            ->dueBy($today->toDateString())
            ->with(['document', 'user'])
            ->get();

        $sentCount = 0;

        foreach ($reminders as $reminder) {
            $document = $reminder->document;
            $user = $reminder->user;

            if ($document === null || $user === null) {
                continue;
            }

            if ($document->status === DocumentStatus::Archived) {
                continue;
            }

            if ($document->user_id !== $user->id) {
                continue;
            }

            DB::transaction(function () use ($reminder, $document, $user, $payloadFactory): void {
                Notification::send($user, new DocumentExpiringSoon($document, $reminder));

                $reminder->forceFill(['sent_at' => now()])->save();

                DocumentReminderSent::dispatch($payloadFactory->forReminderSent(
                    document: $document,
                    remindOn: $reminder->remind_on->toDateString(),
                    reminderId: (string) $reminder->id,
                ));
            });

            $sentCount++;
        }

        $this->info("Sent {$sentCount} reminder(s).");

        return self::SUCCESS;
    }
}
