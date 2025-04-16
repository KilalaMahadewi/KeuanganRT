@extends('layouts.user_type.auth')

@section('content')

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <button class="btn bg-gradient-primary mb-3 text-white" data-bs-toggle="modal" data-bs-target="#tambahWargaModal">Tambah Warga</button>
                
                <!-- Modal Tambah Warga -->
                <div class="modal fade" id="tambahWargaModal" tabindex="-1" aria-labelledby="tambahWargaModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="tambahWargaModalLabel">Tambah Warga</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="formTambahWarga" method="POST" action="{{ route('warga.store') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama</label>
                                        <input type="text" class="form-control" id="nama" name="nama" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="blok" class="form-label">Blok</label>
                                        <select class="form-select" id="blok" name="blok" required>
                                            <option value="" disabled selected>Pilih Blok</option>
                                            <option value="B6">B6</option>
                                            <option value="B7">B7</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nomor_rumah" class="form-label">Nomor Rumah</label>
                                        <input type="number" class="form-control" id="nomor_rumah" name="nomor_rumah" min="1" max="22" required>
                                    </div>
                                    <button type="submit" class="btn bg-gradient-primary text-white">Simpan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Edit Warga -->
                <div class="modal fade" id="editWargaModal" tabindex="-1" aria-labelledby="editWargaModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editWargaModalLabel">Edit Warga</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="formEditWarga" method="POST" action="">
                                    @csrf
                                    @method('PUT')                                    
                                    <input type="hidden" id="edit_id" name="id">
                                    <div class="mb-3">
                                        <label for="edit_nama" class="form-label">Nama</label>
                                        <input type="text" class="form-control" id="edit_nama" name="nama" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_blok" class="form-label">Blok</label>
                                        <select class="form-select" id="edit_blok" name="blok" required>
                                            <option value="B6">B6</option>
                                            <option value="B7">B7</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_nomor_rumah" class="form-label">Nomor Rumah</label>
                                        <input type="number" class="form-control" id="edit_nomor_rumah" name="nomor_rumah" required>
                                    </div>
                                    <button type="submit" class="btn bg-gradient-primary text-white">Perbarui</button>
                                </form>
                            </div>
                        </div>
                        </div>
                </div>

                <!-- Notifikasi Sukses -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h5>Daftar Warga</h5>
                        <input type="text" id="searchInput" class="form-control w-25 mb-3" placeholder="Search...">
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">              
                            <table class="table table-hover align-items-center mb-0" id="wargaTable">
                                <thead class="bg-gradient-primary text-white">
                                    <tr>
                                        <th class="text-uppercase text-center text-xxs font-weight-bolder opacity-7">No</th>
                                        <th class="text-uppercase text-center text-xxs font-weight-bolder opacity-7">Nama</th>
                                        <th class="text-uppercase text-center text-xxs font-weight-bolder opacity-7">Blok</th>
                                        <th class="text-uppercase text-center text-xxs font-weight-bolder opacity-7">Nomor Rumah</th>
                                        <th class="text-uppercase text-center text-xxs font-weight-bolder opacity-7">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach($wargas as $w)
                                    <tr class="align-middle">
                                        <td class="text-center">
                                            <span class="text-sm font-weight-bold">{{ $no++ }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-sm font-weight-bold">{{ $w->nama }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-sm font-weight-bold">{{ $w->blok }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-sm font-weight-bold">{{ $w->nomor_rumah }}</span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-info btn-sm edit-button text-white"
                                                data-id="{{ $w->id }}" 
                                                data-nama="{{ $w->nama }}" 
                                                data-blok="{{ $w->blok }}" 
                                                data-nomor="{{ $w->nomor_rumah }}" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editWargaModal">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <form action="{{ route('warga.destroy', $w->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm text-white"
                                                    onclick="return confirm('Yakin ingin menghapus?')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>



<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("searchInput");
        const tableRows = document.querySelectorAll("#wargaTable tbody tr");

        searchInput.addEventListener("input", function () {
            const searchTerm = searchInput.value.toLowerCase();

            tableRows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchTerm)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("searchInput");
        const wargaTable = document.getElementById("wargaTable");

        searchInput.addEventListener("input", function () {
            const searchTerm = searchInput.value.toLowerCase();

            fetch(`/warga/search?query=${searchTerm}`)
                .then(response => response.json())
                .then(data => {
                    const tbody = wargaTable.querySelector("tbody");
                    tbody.innerHTML = ""; // Clear existing rows

                    data.forEach((warga, index) => {
                        const row = `
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1 pl-2">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-center text-sm">${index + 1}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-center text-sm">${warga.nama}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs text-center font-weight-bold mb-0">${warga.blok}</p>
                                </td>
                                <td>
                                    <p class="text-xs text-center text-secondary mb-0">${warga.nomor_rumah}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <button class="btn btn-info btn-sm edit-button text-white"
                                        data-id="${warga.id}" 
                                        data-nama="${warga.nama}" 
                                        data-blok="${warga.blok}" 
                                        data-nomor="${warga.nomor_rumah}" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editWargaModal">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <form action="/warga/${warga.id}" method="POST" style="display:inline;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-danger btn-sm text-white"
                                            onclick="return confirm('Yakin ingin menghapus?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        `;
                        tbody.innerHTML += row;
                    });
                });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const rowsPerPage = 10;
        const table = document.getElementById("wargaTable");
        const tbody = table.querySelector("tbody");
        const rows = Array.from(tbody.querySelectorAll("tr"));
        const paginationContainer = document.createElement("nav");
        paginationContainer.classList.add("mt-3");
        paginationContainer.setAttribute("aria-label", "Page navigation");
        table.parentElement.appendChild(paginationContainer);

        function renderTable(page) {
            tbody.innerHTML = "";
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            rows.slice(start, end).forEach((row, index) => {
            const clonedRow = row.cloneNode(true);
            const noCell = clonedRow.querySelector("td:first-child h6");
            if (noCell) {
                noCell.textContent = start + index + 1; // Update row number
            }
            tbody.appendChild(clonedRow);
            });
        }

        function renderPagination() {
            paginationContainer.innerHTML = "";
            const totalPages = Math.ceil(rows.length / rowsPerPage);

            const ul = document.createElement("ul");
            ul.classList.add("pagination", "justify-content-center");

            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement("li");
                li.classList.add("page-item");

                const button = document.createElement("button");
                button.textContent = i;
                button.classList.add("page-link");
                button.addEventListener("click", () => {
                    renderTable(i);
                    document.querySelectorAll(".pagination .page-item").forEach(item => item.classList.remove("active"));
                    li.classList.add("active");
                });

                li.appendChild(button);
                ul.appendChild(li);

                if (i === 1) {
                    li.classList.add("active");
                }
            }

            paginationContainer.appendChild(ul);
        }

        renderTable(1);
        renderPagination();
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Ambil semua tombol dengan class 'edit-button'
        document.querySelectorAll(".edit-button").forEach(button => {
            button.addEventListener("click", function () {
                // Ambil data dari atribut data-*
                var id = this.getAttribute("data-id");
                var nama = this.getAttribute("data-nama");
                var blok = this.getAttribute("data-blok");
                var nomor = this.getAttribute("data-nomor");

                console.log("Edit Clicked!"); // Debugging
                console.log("ID: " + id);
                console.log("Nama: " + nama);
                console.log("Blok: " + blok);
                console.log("Nomor Rumah: " + nomor);

                // Set nilai ke dalam modal menggunakan document.getElementById
                document.getElementById("edit_id").value = id;
                document.getElementById("edit_nama").value = nama;
                document.getElementById("edit_blok").value = blok;
                document.getElementById("edit_nomor_rumah").value = nomor;

                // Update action form untuk edit
                document.getElementById("formEditWarga").setAttribute("action", "/warga/" + id);
            });
        });
    });
</script>

@endsection