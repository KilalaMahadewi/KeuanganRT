<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $table = 'pengeluarans'; // pastikan ini plural

    protected $fillable = [
        'jenis_pengeluaran',
        'jumlah_pengeluaran',
        'tanggal_pengeluaran'
    ];
}
