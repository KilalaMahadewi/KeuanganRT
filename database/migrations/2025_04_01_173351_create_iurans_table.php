<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('iurans', function (Blueprint $table) {
            $table->id();
            $table->string('judul'); // Contoh: Iuran Keamanan
            $table->decimal('nominal', 10, 2); // Nominal tetap untuk semua warga
            $table->text('keterangan')->nullable(); // Keterangan opsional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iurans');
    }
};
