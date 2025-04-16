<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IuranWarga extends Model
{
    use HasFactory;

    protected $table = 'iuran_warga'; // Pastikan nama tabel sesuai

    protected $fillable = ['iuran_id', 'warga_id', 'status'];

    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }

    public function iuran()
    {
        return $this->belongsTo(Iuran::class);
    }
}
