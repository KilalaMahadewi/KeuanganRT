@extends('layouts.user_type.auth')

@section('content')

<div class="container py-4">
    <div class="card shadow-lg border-0 p-4">
        <h3 class="mb-4 text-center">Daftar Iuran Warga</h3>

        @if(session('success'))
            <div class="alert alert-success text-white">
                {{ session('success') }}
            </div>
        @endif
        <!-- Ensure this notification is only displayed here, not in the navigation -->

        <div class="d-flex justify-content-end mb-3">
            <button class="btn bg-gradient-primary mb-3 text-white" data-bs-toggle="modal" data-bs-target="#modalTambahIuran">
                <i class="fas fa-plus-circle"></i> Tambah Iuran
            </button>
        </div>
        <form method="GET" action="{{ route('iuran.index') }}" class="d-flex align-items-center mb-3">
            <select name="bulan" class="form-control me-2">
                @foreach (range(1, 12) as $m)
                    <option value="{{ sprintf('%02d', $m) }}" {{ $bulan == $m ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                    </option>
                @endforeach
            </select>

            <select name="tahun" class="form-control me-2">
                @foreach (range(date('Y') - 5, date('Y')) as $y)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn bg-gradient-primary mb-3 text-white">Cari</button>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered align-items-center mb-0 ">
                <thead>
                    <tr>
                        <th class="text-uppercase  text-secondary font-weight-bold">Judul Iuran</th>
                        <th class="text-uppercase text-secondary font-weight-bold">Nominal</th>
                        <!-- <th class="text-uppercase text-secondary font-weight-bold">Keterangan</th> -->                        
                        <th class="text-uppercase text-secondary font-weight-bold">Aksi</th>
                        <th class="text-uppercase text-secondary font-weight-bold">Nama Warga</th>
                        <th class="text-uppercase text-secondary font-weight-bold">Blok</th>
                        <th class="text-uppercase text-secondary font-weight-bold">Nomor</th>
                        <th class="text-uppercase text-secondary font-weight-bold">Validasi</th>
                    </tr>
                </thead>
                <tbody>                
                @foreach ($iurans as $iuran)
                    <tr>
                        <td class="align-top" rowspan="{{ count($iuran->iuranWarga) + 1 }}">{{ $iuran->judul }} <br />{{ $iuran->tanggal ? date('d-M-Y', strtotime($iuran->tanggal)) : '-' }}</td>
                        <td class="align-top" rowspan="{{ count($iuran->iuranWarga) + 1 }}">Rp {{ number_format($iuran->nominal, 0, ',', '.') }}</td>
                        <!-- <td rowspan="{{ count($iuran->iuranWarga) + 1 }}">{{ $iuran->keterangan }}</td> -->                        
                        <td class="align-top" rowspan="{{ count($iuran->iuranWarga) + 1 }}">
                            <form action="{{ route('iuran.destroy', $iuran->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus iuran ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>                            
                            <button onclick="printIuran({{ $iuran->id }})" class="btn btn-sm btn-primary">
                            <i class="fas fa-print"></i> Cetak
                        </button>
                        </td>
                        
                    </tr>
                    
                    @foreach ($iuran->iuranWarga as $iuranWarga)
                    
                    <tr>
                        <td>{{ $iuranWarga->warga->nama }}</td>
                        <td class="text-center">{{ $iuranWarga->warga->blok }}</td> 
                        <td class="text-center">{{ $iuranWarga->warga->nomor_rumah }}</td>
                        <td>
                        @if($iuranWarga->status)
                            <span class="badge bg-gradient-success">Sudah Bayar</span>
                        @else
                            <span class="badge bg-gradient-warning">Belum Bayar</span>
                            <button onclick="confirmBayar('{{ $iuranWarga->id }}')" class="btn btn-sm btn-success">
                                <i class="fas fa-money-bill-wave"></i> Bayar
                            </button>
                            
                            <form id="bayar-form-{{ $iuranWarga->id }}" action="{{ route('iuran.updateStatus', $iuranWarga->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('PATCH')
                            </form>
                        @endif
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="6" class="text-end"><strong>Sudah Bayar:</strong></td>
                        <td>
                            <strong>{{ $iuran->iuranWarga->where('status', true)->count() }} / {{ $iuran->iuranWarga->count() }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-end"><strong>Total Iuran:</strong></td>
                        <td>
                            <strong>Rp {{ number_format($iuran->iuranWarga->where('status', true)->count() * $iuran->nominal, 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                    
                @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end mt-3 me-4">
                <h5>
                    <strong>Total Seluruh Iuran: </strong> 
                    Rp {{ number_format($iurans->sum(function ($iuran) {
                        return $iuran->iuranWarga->where('status', true)->count() * $iuran->nominal;
                    }), 0, ',', '.') }}
                </h5>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $iurans->links() }}
        </div>
    </div>
</div>


<!-- Modal Tambah Iuran -->
<div class="modal fade" id="modalTambahIuran" tabindex="-1" aria-labelledby="modalTambahIuranLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahIuranLabel">Tambah Iuran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('iuran.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul Iuran</label>
                        <input type="text" class="form-control" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="nominal" class="form-label">Nominal Iuran (Rp)</label>
                        <input type="number" class="form-control" name="nominal" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal Iuran</label>
                        <input type="date" class="form-control" name="tanggal">
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" name="keterangan"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmBayar(id) {
        if (confirm("Apakah Anda yakin ingin memvalidasi pembayaran ini?")) {
            document.getElementById('bayar-form-' + id).submit();
        }
    }
</script>
    
    <script>
        function printIuran(iuranId) {
            const iuranElement = document.querySelector(`#iuran-${iuranId}`);
            const sudahBayarRows = Array.from(iuranElement.querySelectorAll('tr')).filter(row => row.innerHTML.includes('Sudah Bayar'));
            const belumBayarRows = Array.from(iuranElement.querySelectorAll('tr')).filter(row => row.innerHTML.includes('Belum Bayar'));

            const judul = iuranElement.querySelector('h4').innerText;
            const tanggal = iuranElement.querySelector('p:nth-of-type(1)').innerText;
            const nominal = iuranElement.querySelector('p:nth-of-type(2)').innerText;
            const totalIuran = iuranElement.querySelector('p:nth-of-type(4)').innerText;

            const printWindow = window.open('', '_blank');
            const printContent = `
                <html>
                    <head>
                        <title>Cetak Iuran</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                margin: 20px;
                            }
                            table {
                                width: 100%;
                                border-collapse: collapse;
                                margin-top: 20px;
                            }
                            th, td {
                                border: 1px solid #ddd;
                                padding: 8px;
                                text-align: left;
                            }
                            th {
                                background-color: #f2f2f2;
                            }
                            .text-center {
                                text-align: center;
                            }
                            .text-end {
                                text-align: right;
                            }
                        </style>
                    </head>
                    <body>
                        <h3 class="text-center">Detail Iuran</h3>
                        <p><strong>Judul:</strong> ${judul}</p>
                        <p><strong>${tanggal}</strong></p>
                        <p><strong>${nominal}</strong></p>
                        <h4>Sudah Bayar</h4>
                        <table>
                            <thead>
                                <tr>
                                    <th>Nama Warga</th>
                                    <th>Blok</th>
                                    <th>Nomor Rumah</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${sudahBayarRows.map(row => row.outerHTML).join('')}
                            </tbody>
                        </table>
                        <h4>Belum Bayar</h4>
                        <table>
                            <thead>
                                <tr>
                                    <th>Nama Warga</th>
                                    <th>Blok</th>
                                    <th>Nomor Rumah</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${belumBayarRows.map(row => row.outerHTML).join('')}
                            </tbody>
                        </table>
                        <p><strong>${totalIuran}</strong></p>
                    </body>
                </html>
            `;
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        }
    </script>

    @foreach ($iurans as $iuran)
        <div id="iuran-{{ $iuran->id }}" style="display: none;">
            <h4>{{ $iuran->judul }}</h4>
            <p><strong>Tanggal:</strong> {{ $iuran->tanggal ? date('d-M-Y', strtotime($iuran->tanggal)) : '-' }}</p>
            <p><strong>Nominal:</strong> Rp {{ number_format($iuran->nominal, 0, ',', '.') }}</p>
            <table>
                <thead>
                    <tr>
                        <th>Nama Warga</th>
                        <th>Blok</th>
                        <th>Nomor Rumah</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($iuran->iuranWarga as $iuranWarga)
                        <tr>
                            <td>{{ $iuranWarga->warga->nama }}</td>
                            <td>{{ $iuranWarga->warga->blok }}</td>
                            <td>{{ $iuranWarga->warga->nomor_rumah }}</td>
                            <td>
                                @if ($iuranWarga->status)
                                    Sudah Bayar
                                @else
                                    Belum Bayar
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p><strong>Sudah Bayar:</strong> {{ $iuran->iuranWarga->where('status', true)->count() }} / {{ $iuran->iuranWarga->count() }}</p>
            <p><strong>Total Iuran:</strong> Rp {{ number_format($iuran->iuranWarga->where('status', true)->count() * $iuran->nominal, 0, ',', '.') }}</p>
        </div>
    @endforeach
@endsection
