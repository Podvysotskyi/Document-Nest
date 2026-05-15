<?php

namespace App\Notifications;

use App\Models\Document;
use App\Models\DocumentReminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentExpiringSoon extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Document $document,
        public DocumentReminder $reminder,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $expiryDate = $this->document->expiry_date?->toFormattedDateString();
        $daysUntilExpiry = $this->document->expiry_date?->diffInDays(now()->startOfDay(), absolute: true);

        $message = (new MailMessage)
            ->subject("Document expiring soon: {$this->document->title}")
            ->greeting('Heads up!');

        if ($expiryDate !== null && $daysUntilExpiry !== null) {
            $message->line("Your document \"{$this->document->title}\" expires on {$expiryDate} ({$daysUntilExpiry} day(s) from now).");
        } else {
            $message->line("Your document \"{$this->document->title}\" is approaching its expiry date.");
        }

        return $message
            ->action('View document', route('documents.show', $this->document))
            ->line('You can update the expiry date or archive the document at any time.');
    }
}
