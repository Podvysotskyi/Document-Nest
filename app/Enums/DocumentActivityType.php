<?php

namespace App\Enums;

enum DocumentActivityType: string
{
    case Created = 'created';
    case Updated = 'updated';
    case Archived = 'archived';
    case Restored = 'restored';
    case Deleted = 'deleted';
    case Downloaded = 'downloaded';
    case Previewed = 'previewed';
    case ReminderSent = 'reminder_sent';
    case BulkArchived = 'bulk_archived';
    case BulkRestored = 'bulk_restored';
    case BulkDeleted = 'bulk_deleted';

    public function label(): string
    {
        return match ($this) {
            self::Created => 'Document created',
            self::Updated => 'Document updated',
            self::Archived => 'Document archived',
            self::Restored => 'Document restored',
            self::Deleted => 'Document deleted',
            self::Downloaded => 'Document downloaded',
            self::Previewed => 'Document previewed',
            self::ReminderSent => 'Reminder sent',
            self::BulkArchived => 'Bulk archived',
            self::BulkRestored => 'Bulk restored',
            self::BulkDeleted => 'Bulk deleted',
        };
    }
}
