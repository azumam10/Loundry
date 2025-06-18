<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;

/* NOTE: Do Not Remove
/ Livewire asset handling if using sub folder in domain
*/
Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});
/*
/ END
*/

// ✅ Halaman Utama
Route::get('/', function () {
    return view('welcome');
});

// ✅ Cetak Struk PDF untuk Transaksi
Route::get('/transaksi/{id}/struk', function ($id) {
    $transaksi = Transaksi::findOrFail($id);
    $pdf = Pdf::loadView('struk', compact('transaksi'));
    return $pdf->download('struk_' . $transaksi->id . '.pdf');
})->name('transaksi.struk');

// ✅ (Opsional) Halaman Dashboard Filament
Route::get('/dashboard', function () {
    return redirect('/admin'); // jika kamu menggunakan Filament Admin
})->middleware(['auth'])->name('dashboard');
