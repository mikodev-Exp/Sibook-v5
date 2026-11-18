<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $user = User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name'              => 'Admin BPS',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $user->syncRoles(['admin']);
    }
}
