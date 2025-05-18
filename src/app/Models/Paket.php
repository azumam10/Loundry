<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'harga',
        
    ];
    public function Transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
}
