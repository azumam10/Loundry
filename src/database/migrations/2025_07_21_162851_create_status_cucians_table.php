<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusCuciansTable extends Migration
{
    public function up(): void
    {
        Schema::create('status_cucians', function (Blueprint $table) {
            $table->id();
            $table->string('nama_status'); // Contoh: diterima, proses, selesai, diambil
            $table->timestamps();
        });

        // Tambahkan relasi ke tabel transaksis
        Schema::table('transaksis', function (Blueprint $table) {
            $table->foreignId('status_cucian_id')
                  ->nullable()
                  ->constrained('status_cucians')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropForeign(['status_cucian_id']);
            $table->dropColumn('status_cucian_id');
        });

        Schema::dropIfExists('status_cucians');
    }
}
