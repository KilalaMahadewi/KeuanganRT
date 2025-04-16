<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use Illuminate\Http\Request;

class WargaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Menambahkan middleware auth
    }
    public function index()
    {
        $wargas = Warga::orderBy('blok')->orderByRaw('CAST(nomor_rumah AS UNSIGNED)')->get();
        return view('warga.index', compact('wargas'));
    }

    public function create()
    {
        return view('warga.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'blok' => 'required|string|max:10',
            'nomor_rumah' => 'required|string|max:10',
        ]);

        // Cek apakah kombinasi blok dan nomor rumah sudah ada
        if (Warga::where('blok', $request->blok)->where('nomor_rumah', $request->nomor_rumah)->exists()) {
            return redirect()->back()->with('error', 'Blok dan Nomor Rumah sudah terdaftar!');
        }

        Warga::create([
            'nama' => $request->nama,
            'blok' => $request->blok,
            'nomor_rumah' => $request->nomor_rumah,
        ]);

        return redirect()->route('warga.index')->with('success', 'Data berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        // Validasi data yang diterima
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'blok' => 'required|string|max:255',
            'nomor_rumah' => 'required|integer|min:1|max:22',
        ]);
        // Cek apakah kombinasi blok dan nomor rumah sudah ada, kecuali untuk data yang sedang diperbarui
        if (Warga::where('blok', $request->blok)
        ->where('nomor_rumah', $request->nomor_rumah)
        ->where('id', '!=', $id)
        ->exists()) {
        return redirect()->back()->with('error', 'Blok dan Nomor Rumah sudah digunakan!');
    }

        // Temukan warga berdasarkan ID dan perbarui datanya
        $warga = Warga::findOrFail($id);
        $warga->update($validatedData);

        // Redirect kembali dengan notifikasi sukses
        return redirect()->route('warga.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function edit(Warga $warga)
    {
        return view('warga.edit', compact('warga'));
    }

    public function destroy(Warga $warga)
    {
        $warga->delete();
        return redirect()->route('warga.index')->with('success', 'Warga berhasil dihapus.');
    }
}

