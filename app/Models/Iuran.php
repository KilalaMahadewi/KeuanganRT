<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iuran extends Model
{
    use HasFactory;
    protected $fillable = ['judul', 'nominal', 'keterangan', 'tanggal'];

    public function iuranWarga()
    {
        return $this->hasMany(IuranWarga::class);
    }
}



