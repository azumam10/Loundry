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
            ['name' => 'Ahmad', 'alamat' => 'KP. Kosambi, Desa Cibadak', 'kontak' => '081234567890'],
            ['name' => 'Budi', 'alamat' => 'KP. Cikareo, Desa Rancailat', 'kontak' => '082345678901'],
            ['name' => 'Citra', 'alamat' => 'KP. Panggang, Desa Mekarsari', 'kontak' => '083456789012'],
            ['name' => 'Dewi', 'alamat' => 'KP. Lio Baru, Desa Sukasari', 'kontak' => '084567890123'],
            ['name' => 'Eka', 'alamat' => 'KP. Jambu, Desa Pangadegan', 'kontak' => '085678901234'],
            ['name' => 'Fajar', 'alamat' => 'KP. Gandaria, Desa Gintung', 'kontak' => '086789012345'],
            ['name' => 'Gita', 'alamat' => 'KP. Kalapa Tiga, Desa Saga', 'kontak' => '087890123456'],
            ['name' => 'Hari', 'alamat' => 'KP. Rawa Rengas, Desa Tegal Angus', 'kontak' => '088901234567'],
            ['name' => 'Intan', 'alamat' => 'KP. Talok, Desa Talok', 'kontak' => '089012345678'],
            ['name' => 'Joko', 'alamat' => 'KP. Bugel, Desa Bugel', 'kontak' => '081112223333'],
            ['name' => 'Kiki', 'alamat' => 'KP. Cilongok, Desa Sukamanah', 'kontak' => '082223334444'],
            ['name' => 'Lina', 'alamat' => 'KP. Bitung Jaya, Desa Bitung', 'kontak' => '083334445555'],
            ['name' => 'Maya', 'alamat' => 'KP. Kebon Melati, Desa Tegal Kunir', 'kontak' => '084445556666'],
            ['name' => 'Nina', 'alamat' => 'KP. Rancagede, Desa Gembong', 'kontak' => '085556667777'],
            ['name' => 'Opik', 'alamat' => 'KP. Pasir Gadung, Desa Pasirgadung', 'kontak' => '086667778888'],
            ['name' => 'Putri', 'alamat' => 'KP. Kronjo, Desa Pagenjahan', 'kontak' => '087778889999'],
            ['name' => 'Qori', 'alamat' => 'KP. Cibogo, Desa Cibogo', 'kontak' => '088889990000'],
            ['name' => 'Raka', 'alamat' => 'KP. Tegal Sari, Desa Rawa Kidang', 'kontak' => '089990001111'],
            ['name' => 'Sinta', 'alamat' => 'KP. Suka Asih, Desa Suka Asih', 'kontak' => '081101112131'],
            ['name' => 'Tari', 'alamat' => 'KP. Bunder, Desa Bunder', 'kontak' => '082121314151'],
            ['name' => 'Umar', 'alamat' => 'KP. Sukamulya, Desa Pangadegan', 'kontak' => '083141516171'],
            ['name' => 'Vina', 'alamat' => 'KP. Kayu Bongkok, Desa Sukaharja', 'kontak' => '084161718192'],
            ['name' => 'Wawan', 'alamat' => 'KP. Sarakan, Desa Sarakan', 'kontak' => '085181920212'],
            ['name' => 'Xena', 'alamat' => 'KP. Pondok Jaya, Desa Pondok Jaya', 'kontak' => '086202122232'],
            ['name' => 'Yusuf', 'alamat' => 'KP. Gelam Jaya, Desa Gelam Jaya', 'kontak' => '087222324252'],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}