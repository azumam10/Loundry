<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StatusCucian;

class StatusCucianSeeder extends Seeder
{
    public function run(): void
    {
        StatusCucian::insert([
            ['nama_status' => 'diterima'],
            ['nama_status' => 'proses'], 
            ['nama_status' => 'selesai'],
            ['nama_status' => 'diambil'],
        ]);
    }
}