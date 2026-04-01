<?php
namespace App\Http\Requests\Ticket;

use App\Enums\PermissionCode;
use App\Http\Requests\ApiRequest;

class TicketUpdateRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return $this->canAnyPermission([
            PermissionCode::TICKET_UPDATE->value,
        ]);
    }

    public function rules(): array
    {
        return [
            'subject'     => ['sometimes', 'required', 'string', 'max:180'],
            'description' => ['sometimes', 'required', 'string', 'max:10000'],
        ];
    }
}
