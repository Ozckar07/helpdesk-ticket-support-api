<?php
namespace Database\Seeders;

use App\Enums\RoleCode;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'code'        => RoleCode::ADMIN->value,
                'name'        => RoleCode::ADMIN->label(),
                'description' => RoleCode::ADMIN->description(),
            ],
            [
                'code'        => RoleCode::AGENT->value,
                'name'        => RoleCode::AGENT->label(),
                'description' => RoleCode::AGENT->description(),
            ],
            [
                'code'        => RoleCode::CUSTOMER->value,
                'name'        => RoleCode::CUSTOMER->label(),
                'description' => RoleCode::CUSTOMER->description(),
            ],
        ];

        foreach ($roles as $roleData) {
            Role::withTrashed()->updateOrCreate(
                [
                    'code' => $roleData['code'],
                ],
                [
                    'name'        => $roleData['name'],
                    'description' => $roleData['description'],
                    'is_system'   => true,
                    'is_active'   => true,
                    'deleted_at'  => null,
                ]
            );
        }
    }
}
