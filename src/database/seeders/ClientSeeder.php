<?php

namespace Database\Seeders;
use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            ['name' => 'Ahmad', 'alamat' => 'Jakarta', 'kontak' => '081234567890'],
            ['name' => 'Budi', 'alamat' => 'Bandung', 'kontak' => '082345678901'],
            ['name' => 'Citra', 'alamat' => 'Surabaya', 'kontak' => '083456789012'],
            ['name' => 'Dewi', 'alamat' => 'Medan', 'kontak' => '084567890123'],
            ['name' => 'Eka', 'alamat' => 'Yogyakarta', 'kontak' => '085678901234'],
            ['name' => 'Fajar', 'alamat' => 'Semarang', 'kontak' => '086789012345'],
            ['name' => 'Gita', 'alamat' => 'Bali', 'kontak' => '087890123456'],
            ['name' => 'Hari', 'alamat' => 'Makassar', 'kontak' => '088901234567'],
            ['name' => 'Intan', 'alamat' => 'Padang', 'kontak' => '089012345678'],
            ['name' => 'Joko', 'alamat' => 'Palembang', 'kontak' => '081112223333'],
            ['name' => 'Kiki', 'alamat' => 'Aceh', 'kontak' => '082223334444'],
            ['name' => 'Lina', 'alamat' => 'Manado', 'kontak' => '083334445555'],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}