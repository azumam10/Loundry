<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatusCucian extends Model
{
    use HasFactory;

    protected $fillable = ['nama_status'];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'status_cucian_id');
    }
}
