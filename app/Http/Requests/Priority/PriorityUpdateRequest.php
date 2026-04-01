<?php
namespace App\Http\Requests\Priority;

use App\Enums\PermissionCode;
use App\Enums\TicketPriorityCode;
use App\Http\Requests\ApiRequest;
use App\Models\Priority;
use Illuminate\Validation\Rule;

class PriorityUpdateRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return $this->canAnyPermission([
            PermissionCode::PRIORITY_UPDATE->value,
        ]);
    }

    public function rules(): array
    {
        /** @var Priority $priority */
        $priority = $this->route('priority');

        return [
            'name'      => ['sometimes', 'required', 'string', 'max:80'],
            'code'      => [
                'sometimes',
                'required',
                'string',
                Rule::in(array_map(fn(TicketPriorityCode $case) => $case->value, TicketPriorityCode::cases())),
                Rule::unique('priorities', 'code')->ignore($priority->id),
            ],
            'level'     => [
                'sometimes',
                'required',
                'integer',
                'min:1',
                'max:100',
                Rule::unique('priorities', 'level')->ignore($priority->id),
            ],
            'color'     => ['nullable', 'string', 'max:20'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
