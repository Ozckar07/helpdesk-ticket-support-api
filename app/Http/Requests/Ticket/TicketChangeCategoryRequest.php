<?php
namespace App\Http\Requests\Ticket;

use App\Enums\PermissionCode;
use App\Http\Requests\ApiRequest;

class TicketChangeCategoryRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return $this->canAnyPermission([
            PermissionCode::TICKET_CHANGE_CATEGORY->value,
        ]);
    }

    public function rules(): array
    {
        return [
            'category_uuid' => ['required', 'uuid', $this->activeUuidExistsRule('categories')],
            'comment'       => ['nullable', 'string', 'max:2000'],
        ];
    }
}
