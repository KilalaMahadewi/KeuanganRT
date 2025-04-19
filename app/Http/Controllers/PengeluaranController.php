<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index()
    {
        $pengeluarans = Pengeluaran::orderBy('tanggal_pengeluaran', 'desc')->get();
        return view('pengeluarans.index', compact('pengeluarans'));
    }

    public function create()
    {
        return view('pengeluaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_pengeluaran' => 'required|string|max:255',
            'tanggal_pengeluaran' => 'required|date',
            'jumlah_pengeluaran' => 'required|numeric',
        ]);

        Pengeluaran::create([
            'jenis_pengeluaran' => $request->jenis_pengeluaran,
            'tanggal_pengeluaran' => $request->tanggal_pengeluaran,
            'jumlah_pengeluaran' => $request->jumlah_pengeluaran,
        ]);

        return redirect()->route('pengeluarans.index')->with('success', 'Pengeluaran berhasil ditambahkan!');
    }

}

