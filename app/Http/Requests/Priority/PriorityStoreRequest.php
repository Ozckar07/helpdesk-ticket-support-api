<?php
namespace App\Http\Requests\Priority;

use App\Enums\PermissionCode;
use App\Enums\TicketPriorityCode;
use App\Http\Requests\ApiRequest;
use Illuminate\Validation\Rule;

class PriorityStoreRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return $this->canAnyPermission([
            PermissionCode::PRIORITY_CREATE->value,
        ]);
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:80'],
            'code'      => [
                'required',
                'string',
                Rule::in(array_map(fn(TicketPriorityCode $case) => $case->value, TicketPriorityCode::cases())),
                Rule::unique('priorities', 'code'),
            ],
            'level'     => ['required', 'integer', 'min:1', 'max:100', Rule::unique('priorities', 'level')],
            'color'     => ['nullable', 'string', 'max:20'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
