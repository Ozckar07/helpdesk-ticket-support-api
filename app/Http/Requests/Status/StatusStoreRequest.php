<?php
namespace App\Http\Requests\Status;

use App\Enums\PermissionCode;
use App\Enums\TicketStatusCode;
use App\Http\Requests\ApiRequest;
use Illuminate\Validation\Rule;

class StatusStoreRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return $this->canAnyPermission([
            PermissionCode::STATUS_CREATE->value,
        ]);
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:80'],
            'code'       => [
                'required',
                'string',
                Rule::in(array_map(fn(TicketStatusCode $case) => $case->value, TicketStatusCode::cases())),
                Rule::unique('statuses', 'code'),
            ],
            'sort_order' => ['required', 'integer', 'min:1', 'max:100', Rule::unique('statuses', 'sort_order')],
            'color'      => ['nullable', 'string', 'max:20'],
            'is_final'   => ['sometimes', 'boolean'],
            'is_active'  => ['sometimes', 'boolean'],
        ];
    }
}
