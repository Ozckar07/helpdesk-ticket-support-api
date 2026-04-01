<?php
namespace App\Http\Requests\Ticket;

use App\Enums\PermissionCode;
use App\Enums\RoleCode;
use App\Http\Requests\ApiRequest;
use App\Models\User;
use Closure;

class TicketAssignRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return $this->canAnyPermission([
            PermissionCode::TICKET_ASSIGN->value,
        ]);
    }

    public function rules(): array
    {
        return [
            'assigned_agent_uuid' => [
                'required',
                'uuid',
                $this->activeUuidExistsRule('users'),
                function (string $attribute, mixed $value, Closure $fail): void {
                    $user = User::query()
                        ->where('uuid', $value)
                        ->whereNull('deleted_at')
                        ->where('is_active', true)
                        ->first();

                    if (! $user) {
                        $fail('The selected agent is invalid.');
                        return;
                    }

                    if (! $user->hasAnyRole([
                        RoleCode::ADMIN->value,
                        RoleCode::AGENT->value,
                    ])) {
                        $fail('The selected user cannot be assigned as ticket agent.');
                    }
                },
            ],
        ];
    }
}
