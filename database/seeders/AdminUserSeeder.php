<?php
namespace Database\Seeders;

use App\Enums\RoleCode;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::query()
            ->where('code', RoleCode::ADMIN->value)
            ->first();

        if (! $adminRole) {
            return;
        }

        $name     = env('ADMIN_DEFAULT_NAME', 'System Administrator');
        $email    = env('ADMIN_DEFAULT_EMAIL', 'admin@helpdesk.local');
        $password = env('ADMIN_DEFAULT_PASSWORD', 'Admin12345*');

        $user = User::withTrashed()->updateOrCreate(
            [
                'email' => $email,
            ],
            [
                'uuid'              => (string) Str::uuid(),
                'name'              => $name,
                'password'          => Hash::make($password),
                'is_active'         => true,
                'email_verified_at' => now(),
                'deleted_at'        => null,
            ]
        );

        $user->roles()->syncWithoutDetaching([$adminRole->id]);
    }
}
