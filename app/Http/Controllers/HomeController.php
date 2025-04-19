<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Iuran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Pengeluaran;


class HomeController extends Controller
{
    public function home()
    {
        // Ambil semua iuran dan pengeluaran
        $iuranList = Iuran::with('iuranWarga')->get();
        $pengeluaranList = Pengeluaran::all();

        // Hitung total kas masuk dari semua iuran
        $totalIuran = $iuranList->sum(function ($iuran) {
            $jumlahWargaSudahBayar = $iuran->iuranWarga->where('status', true)->count();
            return $jumlahWargaSudahBayar * $iuran->nominal;
        });

        // Hitung total pengeluaran
        $totalPengeluaran = $pengeluaranList->sum('jumlah_pengeluaran');

        // Hitung sisa kas
        $sisaKas = $totalIuran - $totalPengeluaran;

        // Buat label bulan (Jan - Dec)
        $labels = collect(range(1, 12))->map(function ($month) {
            return \Carbon\Carbon::create()->month($month)->translatedFormat('F');
        });

        // Data iuran dan pengeluaran per bulan
        $dataIuran = [];
        $dataPengeluaran = [];

        foreach (range(1, 12) as $month) {
            // Filter iuran berdasarkan bulan (pakai created_at)
            $iuranBulanan = $iuranList->filter(function ($iuran) use ($month) {
                return \Carbon\Carbon::parse($iuran->created_at)->month == $month;
            });

            $totalPerBulan = $iuranBulanan->sum(function ($iuran) {
                $jumlahWargaSudahBayar = $iuran->iuranWarga->where('status', true)->count();
                return $jumlahWargaSudahBayar * $iuran->nominal;
            });

            $pengeluaranBulanIni = $pengeluaranList->filter(function ($item) use ($month) {
                return \Carbon\Carbon::parse($item->tanggal_pengeluaran)->month == $month;
            })->sum('jumlah_pengeluaran');

            $dataIuran[] = $totalPerBulan;
            $dataPengeluaran[] = $pengeluaranBulanIni;
        }

        return view('dashboard', compact(
            'totalIuran',
            'totalPengeluaran',
            'sisaKas',
            'labels',
            'dataIuran',
            'dataPengeluaran'
        ));
    }


}
