<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Livewire Script & Update Route Handling (DO NOT REMOVE)
|--------------------------------------------------------------------------
*/
Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});

/*
|--------------------------------------------------------------------------
| Front-End Laundry Routes
|--------------------------------------------------------------------------
*/

// ✅ Halaman Depan
Route::get('/', function () {
    return view('front'); // views/front.blade.php
})->name('home');

// ✅ Cari Transaksi berdasarkan Nama Client
Route::get('/cari-transaksi', function (Request $request) {
    $nama = $request->input('nama');

    $hasil = Transaksi::with('client')->whereHas('client', function ($q) use ($nama) {
        $q->where('name', 'like', '%' . $nama . '%');
    })->get();

    return redirect()->route('home')->with('hasil', $hasil);
})->name('cari.transaksi');

// ✅ Cetak Struk PDF
Route::get('/transaksi/{id}/struk', function ($id) {
    $transaksi = Transaksi::findOrFail($id);
    $pdf = Pdf::loadView('struk', compact('transaksi'));
    return $pdf->download('struk_' . $transaksi->id . '.pdf');
})->name('transaksi.struk');

/*
|--------------------------------------------------------------------------
| Dashboard & Filament (jika menggunakan)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return redirect('/admin');
})->middleware(['auth'])->name('dashboard');
