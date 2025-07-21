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
        $statusPembayaranOptions = ['lunas', 'belum'];
        $statusCucianOptions = ['proses', 'selesai', 'diambil'];

        foreach (range(1, 12) as $i) {
            $paket = $pakets->random();
            $berat = rand(1, 10); // 1 - 10 kg
            $tanggal = now()->subMonths(12 - $i)->startOfMonth()->addDays(rand(0, 27));

            Transaksi::create([
                'client_id'         => $clientIds[array_rand($clientIds)],
                'paket_id'          => $paket->id,
                'harga'             => $paket->harga,
                'berat'             => $berat,
                'total'             => $paket->harga * $berat,
                'tanggal'           => $tanggal,
                'metode'            => 'tunai',
                'status_pembayaran' => $statusPembayaranOptions[array_rand($statusPembayaranOptions)],
                'status_cucian' => $statusCucianOptions[array_rand($statusCucianOptions)],
                'bukti'             => null,
            ]);
        }
    }
}
