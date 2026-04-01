<?php
namespace App\Http\Requests\Status;

use App\Enums\PermissionCode;
use App\Enums\TicketStatusCode;
use App\Http\Requests\ApiRequest;
use App\Models\Status;
use Illuminate\Validation\Rule;

class StatusUpdateRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return $this->canAnyPermission([
            PermissionCode::STATUS_UPDATE->value,
        ]);
    }

    public function rules(): array
    {
        /** @var Status $status */
        $status = $this->route('status');

        return [
            'name'       => ['sometimes', 'required', 'string', 'max:80'],
            'code'       => [
                'sometimes',
                'required',
                'string',
                Rule::in(array_map(fn(TicketStatusCode $case) => $case->value, TicketStatusCode::cases())),
                Rule::unique('statuses', 'code')->ignore($status->id),
            ],
            'sort_order' => [
                'sometimes',
                'required',
                'integer',
                'min:1',
                'max:100',
                Rule::unique('statuses', 'sort_order')->ignore($status->id),
            ],
            'color'      => ['nullable', 'string', 'max:20'],
            'is_final'   => ['sometimes', 'boolean'],
            'is_active'  => ['sometimes', 'boolean'],
        ];
    }
}
