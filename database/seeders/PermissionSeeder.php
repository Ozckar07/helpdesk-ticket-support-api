<?php
namespace Database\Seeders;

use App\Enums\PermissionCode;
use App\Enums\RoleCode;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PermissionCode::cases() as $permissionEnum) {
            Permission::withTrashed()->updateOrCreate(
                [
                    'code' => $permissionEnum->value,
                ],
                [
                    'name'        => $permissionEnum->label(),
                    'group'       => $permissionEnum->group(),
                    'description' => $permissionEnum->description(),
                    'is_system'   => true,
                    'is_active'   => true,
                    'deleted_at'  => null,
                ]
            );
        }

        $this->syncRolePermissions();
    }

    private function syncRolePermissions(): void
    {
        $adminRole    = Role::query()->where('code', RoleCode::ADMIN->value)->first();
        $agentRole    = Role::query()->where('code', RoleCode::AGENT->value)->first();
        $customerRole = Role::query()->where('code', RoleCode::CUSTOMER->value)->first();

        if (! $adminRole || ! $agentRole || ! $customerRole) {
            return;
        }

        $adminRole->permissions()->sync(
            $this->getPermissionIds(
                array_map(
                    static fn(PermissionCode $permission) => $permission->value,
                    PermissionCode::cases()
                )
            )
        );

        $agentRole->permissions()->sync(
            $this->getPermissionIds([
                PermissionCode::CATEGORY_VIEW_ANY->value,
                PermissionCode::CATEGORY_VIEW->value,
                PermissionCode::PRIORITY_VIEW_ANY->value,
                PermissionCode::PRIORITY_VIEW->value,
                PermissionCode::STATUS_VIEW_ANY->value,
                PermissionCode::STATUS_VIEW->value,

                PermissionCode::TICKET_VIEW_ANY->value,
                PermissionCode::TICKET_VIEW_OWN->value,
                PermissionCode::TICKET_UPDATE->value,
                PermissionCode::TICKET_CHANGE_STATUS->value,
                PermissionCode::TICKET_CHANGE_PRIORITY->value,
                PermissionCode::TICKET_ADD_MESSAGE->value,
                PermissionCode::TICKET_ADD_INTERNAL_NOTE->value,
                PermissionCode::TICKET_ADD_ATTACHMENT->value,
                PermissionCode::TICKET_VIEW_ACTIVITY->value,
                PermissionCode::TICKET_RESOLVE->value,
                PermissionCode::TICKET_CLOSE->value,
                PermissionCode::TICKET_REOPEN->value,
            ])
        );

        $customerRole->permissions()->sync(
            $this->getPermissionIds([
                PermissionCode::CATEGORY_VIEW_ANY->value,
                PermissionCode::CATEGORY_VIEW->value,
                PermissionCode::PRIORITY_VIEW_ANY->value,
                PermissionCode::PRIORITY_VIEW->value,
                PermissionCode::STATUS_VIEW_ANY->value,
                PermissionCode::STATUS_VIEW->value,

                PermissionCode::TICKET_VIEW_OWN->value,
                PermissionCode::TICKET_CREATE->value,
                PermissionCode::TICKET_ADD_MESSAGE->value,
                PermissionCode::TICKET_ADD_ATTACHMENT->value,
                PermissionCode::TICKET_REOPEN->value,
            ])
        );
    }

    private function getPermissionIds(array $codes): array
    {
        return Permission::query()
            ->whereIn('code', $codes)
            ->pluck('id')
            ->all();
    }
}
