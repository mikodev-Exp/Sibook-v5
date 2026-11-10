<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'superuser@bps.go.id'],
            [
                'name'              => 'Super User',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $user->syncRoles(['super_user']);
    }
}