<?php
namespace App\Enums;

use App\Enums\Concerns\EnumToArray;

enum TicketStatusCode: string {

    case OPEN             = 'open';
    case IN_PROGRESS      = 'in_progress';
    case PENDING_CUSTOMER = 'pending_customer';
    case RESOLVED         = 'resolved';
    case CLOSED           = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::OPEN             => 'Open',
            self::IN_PROGRESS      => 'In Progress',
            self::PENDING_CUSTOMER => 'Pending Customer',
            self::RESOLVED         => 'Resolved',
            self::CLOSED           => 'Closed',
        };
    }

    public function order(): int
    {
        return match ($this) {
            self::OPEN             => 1,
            self::IN_PROGRESS      => 2,
            self::PENDING_CUSTOMER => 3,
            self::RESOLVED         => 4,
            self::CLOSED           => 5,
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::OPEN             => '#2563EB',
            self::IN_PROGRESS      => '#7C3AED',
            self::PENDING_CUSTOMER => '#D97706',
            self::RESOLVED         => '#059669',
            self::CLOSED           => '#374151',
        };
    }

    public function isFinal(): bool
    {
        return match ($this) {
            self::RESOLVED, self::CLOSED => true,
            default => false,
        };
    }

    public function canTransitionTo(self $target): bool
    {
        return match ($this) {
            self::OPEN             => in_array($target, [
                self::IN_PROGRESS,
                self::PENDING_CUSTOMER,
                self::RESOLVED,
                self::CLOSED,
            ], true),

            self::IN_PROGRESS      => in_array($target, [
                self::PENDING_CUSTOMER,
                self::RESOLVED,
                self::CLOSED,
                self::OPEN,
            ], true),

            self::PENDING_CUSTOMER => in_array($target, [
                self::IN_PROGRESS,
                self::RESOLVED,
                self::CLOSED,
            ], true),

            self::RESOLVED         => in_array($target, [
                self::CLOSED,
                self::IN_PROGRESS,
                self::OPEN,
            ], true),

            self::CLOSED           => in_array($target, [
                self::OPEN,
                self::IN_PROGRESS,
            ], true),
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::OPEN             => 'Ticket has been created and is awaiting attention.',
            self::IN_PROGRESS      => 'Ticket is actively being worked on.',
            self::PENDING_CUSTOMER => 'Ticket is waiting for customer response or confirmation.',
            self::RESOLVED         => 'A solution has been provided and the issue is considered resolved.',
            self::CLOSED           => 'Ticket has been finalized and closed.',
        };
    }
}
