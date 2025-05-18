<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'alamat',
        'kontak',
    ];
    public function Transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
}
