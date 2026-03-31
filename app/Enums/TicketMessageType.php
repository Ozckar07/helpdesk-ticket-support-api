<?php
namespace App\Enums;

enum TicketMessageType: string {

    case REPLY         = 'reply';
    case INTERNAL_NOTE = 'internal_note';
    case SYSTEM        = 'system';

    public function label(): string
    {
        return match ($this) {
            self::REPLY         => 'Reply',
            self::INTERNAL_NOTE => 'Internal Note',
            self::SYSTEM        => 'System Message',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::REPLY         => 'Standard public reply visible according to ticket access rules.',
            self::INTERNAL_NOTE => 'Private note intended for support staff only.',
            self::SYSTEM        => 'Automatically generated system message.',
        };
    }

    public function isInternal(): bool
    {
        return $this === self::INTERNAL_NOTE;
    }

    public function isSystem(): bool
    {
        return $this === self::SYSTEM;
    }
}
