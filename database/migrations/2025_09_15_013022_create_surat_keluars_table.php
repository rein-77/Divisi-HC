<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id('surat_keluar_id');
            $table->string('surat_keluar_nomor')->unique();
            $table->date('surat_keluar_tanggal');
            $table->string('tujuan');
            $table->text('perihal');
            $table->string('berkas')->nullable();
            $table->text('keterangan')->nullable();

            // Foreign Keys
            $table->foreignId('unit_kerja_tujuan')->constrained('unit_kerja', 'unit_kerja_id');
            $table->foreignId('bagian_seksi_tujuan')->constrained('bagian_seksi', 'bagian_seksi_id');
            $table->foreignId('bagian_seksi_pembuat')->constrained('bagian_seksi', 'bagian_seksi_id');
            $table->foreignId('user_id_created')->constrained('user', 'user_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluars');
    }
};
