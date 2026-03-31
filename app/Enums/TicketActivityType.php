<?php
namespace App\Enums;

use App\Enums\Concerns\EnumToArray;

enum TicketActivityType: string {

    case CREATED             = 'created';
    case UPDATED             = 'updated';
    case ASSIGNED            = 'assigned';
    case REASSIGNED          = 'reassigned';
    case STATUS_CHANGED      = 'status_changed';
    case PRIORITY_CHANGED    = 'priority_changed';
    case CATEGORY_CHANGED    = 'category_changed';
    case MESSAGE_ADDED       = 'message_added';
    case INTERNAL_NOTE_ADDED = 'internal_note_added';
    case ATTACHMENT_ADDED    = 'attachment_added';
    case RESOLVED            = 'resolved';
    case CLOSED              = 'closed';
    case REOPENED            = 'reopened';

    public function label(): string
    {
        return match ($this) {
            self::CREATED             => 'Created',
            self::UPDATED             => 'Updated',
            self::ASSIGNED            => 'Assigned',
            self::REASSIGNED          => 'Reassigned',
            self::STATUS_CHANGED      => 'Status Changed',
            self::PRIORITY_CHANGED    => 'Priority Changed',
            self::CATEGORY_CHANGED    => 'Category Changed',
            self::MESSAGE_ADDED       => 'Message Added',
            self::INTERNAL_NOTE_ADDED => 'Internal Note Added',
            self::ATTACHMENT_ADDED    => 'Attachment Added',
            self::RESOLVED            => 'Resolved',
            self::CLOSED              => 'Closed',
            self::REOPENED            => 'Reopened',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::CREATED             => 'The ticket was created.',
            self::UPDATED             => 'The ticket was updated.',
            self::ASSIGNED            => 'The ticket was assigned to an agent.',
            self::REASSIGNED          => 'The ticket was reassigned to another agent.',
            self::STATUS_CHANGED      => 'The ticket status changed.',
            self::PRIORITY_CHANGED    => 'The ticket priority changed.',
            self::CATEGORY_CHANGED    => 'The ticket category changed.',
            self::MESSAGE_ADDED       => 'A public message was added to the ticket.',
            self::INTERNAL_NOTE_ADDED => 'An internal note was added to the ticket.',
            self::ATTACHMENT_ADDED    => 'A file was attached to the ticket.',
            self::RESOLVED            => 'The ticket was marked as resolved.',
            self::CLOSED              => 'The ticket was closed.',
            self::REOPENED            => 'The ticket was reopened.',
        };
    }
}
