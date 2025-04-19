<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Rename kolom lama
        Schema::table('pengeluarans', function (Blueprint $table) {
            // Rename existing columns
            $table->renameColumn('keterangan', 'jenis_pengeluaran');
            $table->renameColumn('jumlah', 'jumlah_pengeluaran');
        });

        // Tambah kolom baru
        Schema::table('pengeluarans', function (Blueprint $table) {
            if (!Schema::hasColumn('pengeluarans', 'tanggal_pengeluaran')) {
                $table->date('tanggal_pengeluaran')->nullable()->after('jumlah_pengeluaran');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->renameColumn('jenis_pengeluaran', 'keterangan');
            $table->renameColumn('jumlah_pengeluaran', 'jumlah');
            $table->dropColumn('tanggal_pengeluaran');
        });
    }
};
