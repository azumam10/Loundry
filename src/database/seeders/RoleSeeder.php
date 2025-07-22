<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Pastikan role ada
        $ownerRole = Role::firstOrCreate(['name' => 'owner']);
        $kasirRole = Role::firstOrCreate(['name' => 'kasir']);

        // User Owner
        $owner = User::firstOrCreate(
            ['email' => 'owner@laundry.com'],
            [
                'name' => 'Owner Laundry',
                'password' => Hash::make('password'), // ganti password kalau perlu
            ]
        );
        $owner->assignRole($ownerRole);

        // User Kasir
        $kasir = User::firstOrCreate(
            ['email' => 'kasir@laundry.com'],
            [
                'name' => 'Kasir Laundry',
                'password' => Hash::make('password'),
            ]
        );
        $kasir->assignRole($kasirRole);
    }
}
