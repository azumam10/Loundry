<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'metode',
        'client_id',
        'paket_id',
        'harga',
        'berat',
        'total',
        'tanggal',
        'bukti',
        'status_pembayaran',
        'status_cucian_id',

    ];

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function statusCucian()
{
    return $this->belongsTo(StatusCucian::class, 'status_cucian_id');
}

 
}
