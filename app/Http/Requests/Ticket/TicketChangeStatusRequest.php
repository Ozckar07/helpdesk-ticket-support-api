<?php
namespace App\Http\Requests\Ticket;

use App\Enums\PermissionCode;
use App\Http\Requests\ApiRequest;

class TicketChangeStatusRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return $this->canAnyPermission([
            PermissionCode::TICKET_CHANGE_STATUS->value,
            PermissionCode::TICKET_RESOLVE->value,
            PermissionCode::TICKET_CLOSE->value,
            PermissionCode::TICKET_REOPEN->value,
        ]);
    }

    public function rules(): array
    {
        return [
            'status_uuid' => ['required', 'uuid', $this->activeUuidExistsRule('statuses')],
            'comment'     => ['nullable', 'string', 'max:2000'],
        ];
    }
}
