<?php

use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

Route::prefix('transaksis')->middleware('apikey')->group(function () {
    Route::get('/', [TransaksiController::class, 'index']);
    Route::post('/decrypt', [TransaksiController::class, 'decryptResponse']);
    Route::get('{id}', [TransaksiController::class, 'show']);
    Route::post('/', [TransaksiController::class, 'store']);
    Route::put('{id}', [TransaksiController::class, 'update']);
    Route::delete('{id}', [TransaksiController::class, 'destroy']);
});