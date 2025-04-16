<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Iuran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function home()
    {
        $iuranList = Iuran::with('iuranWarga')->get();

        $totalIuran = $iuranList->sum(function ($iuran) {
            $jumlahWargaSudahBayar = $iuran->iuranWarga->where('status', true)->count();
            return $jumlahWargaSudahBayar * $iuran->nominal;
        });

        $labels = [];
        $dataIuran = [];

        foreach ($iuranList as $iuran) {
            $jumlahWargaSudahBayar = $iuran->iuranWarga->where('status', true)->count();
            $totalPerIuran = $jumlahWargaSudahBayar * $iuran->nominal;

            $labels[] = $iuran->judul; 
            $dataIuran[] = $totalPerIuran; 
        }

        return view('dashboard', compact('totalIuran', 'labels', 'dataIuran'));
    }
}
