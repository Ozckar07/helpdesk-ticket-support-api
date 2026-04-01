<?php
namespace App\Http\Requests\Ticket;

use App\Enums\PermissionCode;
use App\Http\Requests\ApiRequest;
use Illuminate\Validation\Rule;

class TicketIndexRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return $this->canAnyPermission([
            PermissionCode::TICKET_VIEW_ANY->value,
            PermissionCode::TICKET_VIEW_OWN->value,
        ]);
    }

    public function rules(): array
    {
        return [
            'search'              => ['nullable', 'string', 'max:180'],
            'status_uuid'         => ['nullable', 'uuid', $this->activeUuidExistsRule('statuses')],
            'priority_uuid'       => ['nullable', 'uuid', $this->activeUuidExistsRule('priorities')],
            'category_uuid'       => ['nullable', 'uuid', $this->activeUuidExistsRule('categories')],
            'assigned_agent_uuid' => ['nullable', 'uuid', $this->activeUuidExistsRule('users')],
            'customer_uuid'       => ['nullable', 'uuid', $this->activeUuidExistsRule('users')],
            'date_from'           => ['nullable', 'date'],
            'date_to'             => ['nullable', 'date', 'after_or_equal:date_from'],
            'only_assigned'       => ['nullable', 'boolean'],
            'only_unassigned'     => ['nullable', 'boolean'],
            'only_mine'           => ['nullable', 'boolean'],
            'sort_by'             => [
                'nullable',
                'string',
                Rule::in(['created_at', 'updated_at', 'resolved_at', 'closed_at', 'ticket_number', 'subject']),
            ],
            'sort_direction'      => $this->sortDirectionRules(),
            'per_page'            => $this->perPageRules(),
        ];
    }
}
