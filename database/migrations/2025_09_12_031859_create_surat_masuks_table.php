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
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->id('surat_masuk_id');
            $table->string('surat_masuk_nomor');
            $table->date('surat_masuk_tanggal');
            $table->date('tanggal_diterima');
            $table->string('pengirim');
            $table->string('tujuan');
            $table->text('perihal'); // Gunakan text untuk isi yang lebih panjang
            $table->text('keterangan')->nullable();
            $table->string('berkas')->nullable();

            // FK ke user yang membuat surat
            $table->foreignId('user_id_created')->constrained('user', 'user_id');

            $table->softDeletes(); // Menambahkan kolom deleted_at untuk soft deletes

            $table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuks');
    }
};
