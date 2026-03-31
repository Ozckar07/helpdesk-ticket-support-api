<?php
namespace App\Enums;

enum RoleCode: string {

    case ADMIN    = 'admin';
    case AGENT    = 'agent';
    case CUSTOMER = 'customer';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN    => 'Administrator',
            self::AGENT    => 'Support Agent',
            self::CUSTOMER => 'Customer',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::ADMIN    => 'Full access to administration, configuration, and operational management.',
            self::AGENT    => 'Can manage assigned tickets, reply, update statuses, and work on support operations.',
            self::CUSTOMER => 'Can create tickets, reply to their own tickets, and view their own support history.',
        };
    }
}
