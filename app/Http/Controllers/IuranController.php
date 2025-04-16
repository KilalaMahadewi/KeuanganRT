<?php

namespace App\Http\Controllers;

use App\Models\Iuran;
use App\Models\IuranWarga;
use App\Models\Warga;
use Illuminate\Http\Request;

class IuranController extends Controller
{
    // Menampilkan semua iuran dengan status pembayaran per warga
    public function index(Request $request)
    {
        // Ambil bulan & tahun dari request, jika tidak ada gunakan bulan & tahun sekarang
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // Ambil daftar iuran berdasarkan bulan & tahun
        $iurans = Iuran::with('iuranWarga.warga')
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->orderBy('created_at', 'desc')
                    ->paginate(2); // Menggunakan pagination

        // Ambil semua data warga untuk form tambah iuran
        $wargas = Warga::all();

        return view('iuran.index', compact('iurans', 'wargas', 'bulan', 'tahun'));
    }


    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'judul' => 'required|string',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'tanggal' => 'nullable|date'
        ]);

        // Membuat iuran baru
        $iuran = Iuran::create([
            'judul' => $request->judul,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal ?? now()
        ]);

        // Menambahkan semua warga ke dalam iuran baru
        $wargas = Warga::all();
        foreach ($wargas as $warga) {
            IuranWarga::create([
                'iuran_id' => $iuran->id,
                'warga_id' => $warga->id,
                'status' => false // Status belum bayar
            ]);
        }

        // Kembali ke halaman daftar iuran dengan pesan sukses
        return redirect()->route('iuran.index')->with('success', 'Iuran berhasil ditambahkan.');
    }

    // Mengupdate status pembayaran iuran untuk warga tertentu
    public function updateStatus($id)
    {
        // Mencari data iuran warga berdasarkan ID
        $iuranWarga = IuranWarga::findOrFail($id);

        // Memperbarui status menjadi sudah bayar
        $iuranWarga->update(['status' => true]);

        // Kembali ke halaman daftar iuran dengan pesan sukses
        return redirect()->route('iuran.index')->with('success', 'Status pembayaran diperbarui.');
    }
    public function destroy($id)
    {
        $iuran = Iuran::findOrFail($id);
        
        // Hapus semua iuran_warga terkait
        IuranWarga::where('iuran_id', $id)->delete();

        // Hapus iuran
        $iuran->delete();

        return redirect()->route('iuran.index')->with('success', 'Iuran berhasil dihapus.');
    }

}
