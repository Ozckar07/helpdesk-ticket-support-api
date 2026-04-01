<?php
namespace App\Http\Requests\Ticket;

use App\Enums\PermissionCode;
use App\Http\Requests\ApiRequest;

class TicketChangePriorityRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return $this->canAnyPermission([
            PermissionCode::TICKET_CHANGE_PRIORITY->value,
        ]);
    }

    public function rules(): array
    {
        return [
            'priority_uuid' => ['required', 'uuid', $this->activeUuidExistsRule('priorities')],
            'comment'       => ['nullable', 'string', 'max:2000'],
        ];
    }
}
