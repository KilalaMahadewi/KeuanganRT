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
        Schema::create('iuran_warga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('iuran_id')->constrained('iurans')->onDelete('cascade');
            $table->foreignId('warga_id')->constrained('wargas')->onDelete('cascade');
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iuran_warga');
    }
};
