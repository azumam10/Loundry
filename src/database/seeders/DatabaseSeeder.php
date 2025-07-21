<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Cek apakah user admin sudah ada, jika belum maka buat
        $user = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'), // Ganti jika perlu
                'email_verified_at' => now(),
            ]
        );

        $user->assignRole('super_admin');

        // Panggil seeder lain
        $this->call([
            ClientSeeder::class,
            PaketSeeder::class,
            StatusCucianSeeder::class,
            TransaksiSeeder::class,
        ]);
    }
}
