<?php
namespace App\Enums;

use App\Enums\Concerns\EnumToArray;

enum TokenAbility: string {

    case FULL_ACCESS              = '*';
    case AUTH                     = 'auth';
    case PROFILE_READ             = 'profile:read';
    case PROFILE_UPDATE           = 'profile:update';
    case TICKET_READ              = 'ticket:read';
    case TICKET_WRITE             = 'ticket:write';
    case TICKET_ASSIGN            = 'ticket:assign';
    case TICKET_STATUS_CHANGE     = 'ticket:status-change';
    case TICKET_ATTACHMENT_UPLOAD = 'ticket:attachment-upload';
    case TICKET_ACTIVITY_READ     = 'ticket:activity-read';
    case CATALOG_READ             = 'catalog:read';
    case CATALOG_WRITE            = 'catalog:write';
    case USER_READ                = 'user:read';
    case USER_WRITE               = 'user:write';

    public function label(): string
    {
        return match ($this) {
            self::FULL_ACCESS              => 'Full Access',
            self::AUTH                     => 'Authentication',
            self::PROFILE_READ             => 'Read Profile',
            self::PROFILE_UPDATE           => 'Update Profile',
            self::TICKET_READ              => 'Read Tickets',
            self::TICKET_WRITE             => 'Write Tickets',
            self::TICKET_ASSIGN            => 'Assign Tickets',
            self::TICKET_STATUS_CHANGE     => 'Change Ticket Status',
            self::TICKET_ATTACHMENT_UPLOAD => 'Upload Ticket Attachments',
            self::TICKET_ACTIVITY_READ     => 'Read Ticket Activity',
            self::CATALOG_READ             => 'Read Catalogs',
            self::CATALOG_WRITE            => 'Manage Catalogs',
            self::USER_READ                => 'Read Users',
            self::USER_WRITE               => 'Manage Users',
        };
    }
}
