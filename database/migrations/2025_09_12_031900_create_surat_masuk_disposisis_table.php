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
        Schema::create('surat_masuk_disposisi', function (Blueprint $table) {
            $table->id('surat_masuk_disposisi_id');

            // FK ke surat_masuk
            $table->foreignId('surat_masuk_id')->constrained('surat_masuk', 'surat_masuk_id');
            // FK ke user (tujuan disposisi)
            $table->foreignId('user_id')->constrained('user', 'user_id');
            // FK ke bagian_seksi (tujuan disposisi)
            $table->foreignId('bagian_seksi_id')->constrained('bagian_seksi', 'bagian_seksi_id');

            $table->text('keterangan')->nullable();
            $table->dateTime('waktu_disposisi');
            $table->softDeletes(); // Menambahkan kolom deleted_at untuk soft deletes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuk_disposisis');
    }
};
