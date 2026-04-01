<?php
namespace App\Http\Requests\Ticket;

use App\Enums\PermissionCode;
use App\Http\Requests\ApiRequest;

class TicketStoreRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return $this->canAnyPermission([
            PermissionCode::TICKET_CREATE->value,
        ]);
    }

    public function rules(): array
    {
        return [
            'subject'       => ['required', 'string', 'max:180'],
            'description'   => ['required', 'string', 'max:10000'],
            'category_uuid' => ['required', 'uuid', $this->activeUuidExistsRule('categories')],
            'priority_uuid' => ['required', 'uuid', $this->activeUuidExistsRule('priorities')],
            'attachments'   => $this->attachmentArrayRules(),
            'attachments.*' => $this->attachmentFileRules(),
        ];
    }
}
