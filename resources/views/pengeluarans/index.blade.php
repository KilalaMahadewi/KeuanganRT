@extends('layouts.user_type.auth')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-radius-xl">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Daftar Pengeluaran</h6>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahPengeluaranModal">
                        <i class="fas fa-plus me-1"></i> Tambah Pengeluaran
                    </button>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-3">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jenis Pengeluaran</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah (Rp)</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengeluarans as $index => $item)
                                <tr>
                                    <td class="align-middle text-sm">{{ $index + 1 }}</td>
                                    <td class="align-middle text-sm">{{ $item->jenis_pengeluaran }}</td>
                                    <td class="align-middle text-sm">{{ \Carbon\Carbon::parse($item->tanggal_pengeluaran)->format('d M Y') }}</td>
                                    <td class="align-middle text-sm">Rp {{ number_format($item->jumlah_pengeluaran, 0, ',', '.') }}</td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('pengeluarans.edit', $item->id) }}" class="btn btn-sm btn-warning me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('pengeluarans.destroy', $item->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Yakin ingin menghapus pengeluaran ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data pengeluaran.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  


<!-- Modal Tambah Pengeluaran -->
<div class="modal fade" id="tambahPengeluaranModal" tabindex="-1" aria-labelledby="tambahPengeluaranModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('pengeluarans.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tambahPengeluaranModalLabel">Tambah Pengeluaran</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="jenis_pengeluaran" class="form-label">Jenis Pengeluaran</label>
            <input type="text" class="form-control" id="jenis_pengeluaran" name="jenis_pengeluaran" required>
          </div>
          <div class="mb-3">
            <label for="tanggal_pengeluaran" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tanggal_pengeluaran" name="tanggal_pengeluaran" required>
          </div>
          <div class="mb-3">
            <label for="jumlah_pengeluaran" class="form-label">Jumlah (Rp)</label>
            <input type="number" class="form-control" id="jumlah_pengeluaran" name="jumlah_pengeluaran" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

@endsection
