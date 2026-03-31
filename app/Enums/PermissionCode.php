<?php
namespace App\Enums;

use App\Enums\Concerns\EnumToArray;

enum PermissionCode: string {

    case USER_VIEW_ANY = 'user.view.any';
    case USER_VIEW     = 'user.view';
    case USER_CREATE   = 'user.create';
    case USER_UPDATE   = 'user.update';
    case USER_DELETE   = 'user.delete';

    case ROLE_VIEW_ANY = 'role.view.any';
    case ROLE_VIEW     = 'role.view';
    case ROLE_CREATE   = 'role.create';
    case ROLE_UPDATE   = 'role.update';
    case ROLE_DELETE   = 'role.delete';

    case CATEGORY_VIEW_ANY = 'category.view.any';
    case CATEGORY_VIEW     = 'category.view';
    case CATEGORY_CREATE   = 'category.create';
    case CATEGORY_UPDATE   = 'category.update';
    case CATEGORY_DELETE   = 'category.delete';

    case PRIORITY_VIEW_ANY = 'priority.view.any';
    case PRIORITY_VIEW     = 'priority.view';
    case PRIORITY_CREATE   = 'priority.create';
    case PRIORITY_UPDATE   = 'priority.update';
    case PRIORITY_DELETE   = 'priority.delete';

    case STATUS_VIEW_ANY = 'status.view.any';
    case STATUS_VIEW     = 'status.view';
    case STATUS_CREATE   = 'status.create';
    case STATUS_UPDATE   = 'status.update';
    case STATUS_DELETE   = 'status.delete';

    case TICKET_VIEW_ANY          = 'ticket.view.any';
    case TICKET_VIEW_OWN          = 'ticket.view.own';
    case TICKET_CREATE            = 'ticket.create';
    case TICKET_UPDATE            = 'ticket.update';
    case TICKET_DELETE            = 'ticket.delete';
    case TICKET_ASSIGN            = 'ticket.assign';
    case TICKET_CHANGE_STATUS     = 'ticket.change.status';
    case TICKET_CHANGE_PRIORITY   = 'ticket.change.priority';
    case TICKET_CHANGE_CATEGORY   = 'ticket.change.category';
    case TICKET_ADD_MESSAGE       = 'ticket.add.message';
    case TICKET_ADD_INTERNAL_NOTE = 'ticket.add.internal.note';
    case TICKET_ADD_ATTACHMENT    = 'ticket.add.attachment';
    case TICKET_VIEW_ACTIVITY     = 'ticket.view.activity';
    case TICKET_REOPEN            = 'ticket.reopen';
    case TICKET_CLOSE             = 'ticket.close';
    case TICKET_RESOLVE           = 'ticket.resolve';

    public function label(): string
    {
        return match ($this) {
            self::USER_VIEW_ANY            => 'View all users',
            self::USER_VIEW                => 'View user',
            self::USER_CREATE              => 'Create user',
            self::USER_UPDATE              => 'Update user',
            self::USER_DELETE              => 'Delete user',

            self::ROLE_VIEW_ANY            => 'View all roles',
            self::ROLE_VIEW                => 'View role',
            self::ROLE_CREATE              => 'Create role',
            self::ROLE_UPDATE              => 'Update role',
            self::ROLE_DELETE              => 'Delete role',

            self::CATEGORY_VIEW_ANY        => 'View all categories',
            self::CATEGORY_VIEW            => 'View category',
            self::CATEGORY_CREATE          => 'Create category',
            self::CATEGORY_UPDATE          => 'Update category',
            self::CATEGORY_DELETE          => 'Delete category',

            self::PRIORITY_VIEW_ANY        => 'View all priorities',
            self::PRIORITY_VIEW            => 'View priority',
            self::PRIORITY_CREATE          => 'Create priority',
            self::PRIORITY_UPDATE          => 'Update priority',
            self::PRIORITY_DELETE          => 'Delete priority',

            self::STATUS_VIEW_ANY          => 'View all statuses',
            self::STATUS_VIEW              => 'View status',
            self::STATUS_CREATE            => 'Create status',
            self::STATUS_UPDATE            => 'Update status',
            self::STATUS_DELETE            => 'Delete status',

            self::TICKET_VIEW_ANY          => 'View all tickets',
            self::TICKET_VIEW_OWN          => 'View own tickets',
            self::TICKET_CREATE            => 'Create ticket',
            self::TICKET_UPDATE            => 'Update ticket',
            self::TICKET_DELETE            => 'Delete ticket',
            self::TICKET_ASSIGN            => 'Assign ticket',
            self::TICKET_CHANGE_STATUS     => 'Change ticket status',
            self::TICKET_CHANGE_PRIORITY   => 'Change ticket priority',
            self::TICKET_CHANGE_CATEGORY   => 'Change ticket category',
            self::TICKET_ADD_MESSAGE       => 'Add ticket message',
            self::TICKET_ADD_INTERNAL_NOTE => 'Add internal note',
            self::TICKET_ADD_ATTACHMENT    => 'Add attachment',
            self::TICKET_VIEW_ACTIVITY     => 'View ticket activity',
            self::TICKET_REOPEN            => 'Reopen ticket',
            self::TICKET_CLOSE             => 'Close ticket',
            self::TICKET_RESOLVE           => 'Resolve ticket',
        };
    }

    public function group(): string
    {
        return match ($this) {
            self::USER_VIEW_ANY,
            self::USER_VIEW,
            self::USER_CREATE,
            self::USER_UPDATE,
            self::USER_DELETE     => 'users',

            self::ROLE_VIEW_ANY,
            self::ROLE_VIEW,
            self::ROLE_CREATE,
            self::ROLE_UPDATE,
            self::ROLE_DELETE     => 'roles',

            self::CATEGORY_VIEW_ANY,
            self::CATEGORY_VIEW,
            self::CATEGORY_CREATE,
            self::CATEGORY_UPDATE,
            self::CATEGORY_DELETE => 'categories',

            self::PRIORITY_VIEW_ANY,
            self::PRIORITY_VIEW,
            self::PRIORITY_CREATE,
            self::PRIORITY_UPDATE,
            self::PRIORITY_DELETE => 'priorities',

            self::STATUS_VIEW_ANY,
            self::STATUS_VIEW,
            self::STATUS_CREATE,
            self::STATUS_UPDATE,
            self::STATUS_DELETE   => 'statuses',

            self::TICKET_VIEW_ANY,
            self::TICKET_VIEW_OWN,
            self::TICKET_CREATE,
            self::TICKET_UPDATE,
            self::TICKET_DELETE,
            self::TICKET_ASSIGN,
            self::TICKET_CHANGE_STATUS,
            self::TICKET_CHANGE_PRIORITY,
            self::TICKET_CHANGE_CATEGORY,
            self::TICKET_ADD_MESSAGE,
            self::TICKET_ADD_INTERNAL_NOTE,
            self::TICKET_ADD_ATTACHMENT,
            self::TICKET_VIEW_ACTIVITY,
            self::TICKET_REOPEN,
            self::TICKET_CLOSE,
            self::TICKET_RESOLVE  => 'tickets',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::USER_VIEW_ANY            => 'Allows listing all users.',
            self::USER_VIEW                => 'Allows viewing a single user.',
            self::USER_CREATE              => 'Allows creating users.',
            self::USER_UPDATE              => 'Allows updating users.',
            self::USER_DELETE              => 'Allows deleting users.',

            self::ROLE_VIEW_ANY            => 'Allows listing all roles.',
            self::ROLE_VIEW                => 'Allows viewing a single role.',
            self::ROLE_CREATE              => 'Allows creating roles.',
            self::ROLE_UPDATE              => 'Allows updating roles.',
            self::ROLE_DELETE              => 'Allows deleting roles.',

            self::CATEGORY_VIEW_ANY        => 'Allows listing all categories.',
            self::CATEGORY_VIEW            => 'Allows viewing a single category.',
            self::CATEGORY_CREATE          => 'Allows creating categories.',
            self::CATEGORY_UPDATE          => 'Allows updating categories.',
            self::CATEGORY_DELETE          => 'Allows deleting categories.',

            self::PRIORITY_VIEW_ANY        => 'Allows listing all priorities.',
            self::PRIORITY_VIEW            => 'Allows viewing a single priority.',
            self::PRIORITY_CREATE          => 'Allows creating priorities.',
            self::PRIORITY_UPDATE          => 'Allows updating priorities.',
            self::PRIORITY_DELETE          => 'Allows deleting priorities.',

            self::STATUS_VIEW_ANY          => 'Allows listing all statuses.',
            self::STATUS_VIEW              => 'Allows viewing a single status.',
            self::STATUS_CREATE            => 'Allows creating statuses.',
            self::STATUS_UPDATE            => 'Allows updating statuses.',
            self::STATUS_DELETE            => 'Allows deleting statuses.',

            self::TICKET_VIEW_ANY          => 'Allows viewing any ticket in the system.',
            self::TICKET_VIEW_OWN          => 'Allows viewing only own tickets.',
            self::TICKET_CREATE            => 'Allows creating new tickets.',
            self::TICKET_UPDATE            => 'Allows updating ticket data.',
            self::TICKET_DELETE            => 'Allows deleting tickets.',
            self::TICKET_ASSIGN            => 'Allows assigning or reassigning tickets to an agent.',
            self::TICKET_CHANGE_STATUS     => 'Allows changing the ticket status.',
            self::TICKET_CHANGE_PRIORITY   => 'Allows changing the ticket priority.',
            self::TICKET_CHANGE_CATEGORY   => 'Allows changing the ticket category.',
            self::TICKET_ADD_MESSAGE       => 'Allows adding public replies to a ticket.',
            self::TICKET_ADD_INTERNAL_NOTE => 'Allows adding internal notes to a ticket.',
            self::TICKET_ADD_ATTACHMENT    => 'Allows attaching files to a ticket or ticket message.',
            self::TICKET_VIEW_ACTIVITY     => 'Allows viewing ticket activity history.',
            self::TICKET_REOPEN            => 'Allows reopening resolved or closed tickets.',
            self::TICKET_CLOSE             => 'Allows closing tickets.',
            self::TICKET_RESOLVE           => 'Allows resolving tickets.',
        };
    }
}
