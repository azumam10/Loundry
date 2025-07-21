<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('metode');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('paket_id')->constrained('pakets')->onDelete('cascade');
            $table->integer('harga');
            $table->integer('berat');
            $table->integer('total');
            $table->date('tanggal');
            $table->string('bukti')->nullable();
            $table->foreignId('status_cucian_id')->nullable()->constrained('status_cucians')->nullOnDelete();
            $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
