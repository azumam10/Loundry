<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Paket;
use App\Models\Transaksi;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $clientIds = Client::pluck('id')->toArray();
        $pakets = Paket::all();

        for ($i = 1; $i <= 12; $i++) {
            $paket = $pakets->random();
            $berat = rand(1, 10); // 1-10 kg
            $tanggal = now()->subMonths(12 - $i)->startOfMonth()->addDays(rand(0, 27));

            Transaksi::create([
                'metode' => 'tunai',
                'client_id' => $clientIds[array_rand($clientIds)],
                'paket_id' => $paket->id,
                'harga' => $paket->harga,
                'berat' => $berat,
                'total' => $paket->harga * $berat,
                'tanggal' => $tanggal,
                'status_pembayaran' => ['lunas', 'belum'][rand(0, 1)],
                'bukti' => null,
            ]);
        }
    }
}
