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
        Schema::create('bagian_seksi', function (Blueprint $table) {
            $table->id('bagian_seksi_id');
            $table->string('bagian_seksi');
            $table->string('bagian_seksi_kode', 50)->unique();

            // Membuat Foreign Key (FK) ke tabel unit_kerja
            $table->foreignId('unit_kerja_id')->constrained('unit_kerja', 'unit_kerja_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bagian_seksis');
    }
};
