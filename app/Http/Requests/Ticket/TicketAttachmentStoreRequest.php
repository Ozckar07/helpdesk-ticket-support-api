<?php
namespace App\Http\Requests\Ticket;

use App\Enums\PermissionCode;
use App\Http\Requests\ApiRequest;

class TicketAttachmentStoreRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return $this->canAnyPermission([
            PermissionCode::TICKET_ADD_ATTACHMENT->value,
        ]);
    }

    public function rules(): array
    {
        return [
            'ticket_message_uuid' => ['nullable', 'uuid', $this->uuidExistsRule('ticket_messages')],
            'files'               => ['required', 'array', 'min:1', 'max:5'],
            'files.*'             => $this->attachmentFileRules(),
        ];
    }
}
