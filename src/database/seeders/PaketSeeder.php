<?php

namespace Database\Seeders;

use App\Models\Paket;
use Illuminate\Database\Seeder;

class PaketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Paket::insert([
            [
                'nama' => 'Cuci',
                'harga' => 7000,
            ],
            [
                'nama' => 'Cuci Gosok',
                'harga' => 9000,
            ],
            [
                'nama' => 'Gosok',
                'harga' => 5000,
            ],
        ]);
    }
}
