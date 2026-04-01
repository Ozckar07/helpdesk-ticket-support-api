<?php
namespace App\Http\Requests\Ticket;

use App\Enums\PermissionCode;
use App\Enums\TicketMessageType;
use App\Http\Requests\ApiRequest;
use Illuminate\Validation\Rule;

class TicketMessageStoreRequest extends ApiRequest
{
    public function authorize(): bool
    {
        $isInternal = $this->boolean('is_internal');

        if ($isInternal) {
            return $this->canAnyPermission([
                PermissionCode::TICKET_ADD_INTERNAL_NOTE->value,
            ]);
        }

        return $this->canAnyPermission([
            PermissionCode::TICKET_ADD_MESSAGE->value,
        ]);
    }

    public function rules(): array
    {
        return [
            'message'       => ['required', 'string', 'max:10000'],
            'is_internal'   => ['sometimes', 'boolean'],
            'type'          => [
                'nullable',
                'string',
                Rule::in([
                    TicketMessageType::REPLY->value,
                    TicketMessageType::INTERNAL_NOTE->value,
                ]),
            ],
            'attachments'   => $this->attachmentArrayRules(),
            'attachments.*' => $this->attachmentFileRules(),
        ];
    }

    protected function prepareForValidation(): void
    {
        $isInternal = $this->boolean('is_internal');

        if (! $this->filled('type')) {
            $this->merge([
                'type' => $isInternal
                    ? TicketMessageType::INTERNAL_NOTE->value
                    : TicketMessageType::REPLY->value,
            ]);
        }
    }
}
