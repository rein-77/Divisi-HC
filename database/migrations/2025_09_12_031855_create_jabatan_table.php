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
        Schema::create('jabatan', function (Blueprint $table) {
            $table->id('jabatan_id'); // Primary Key (PK) dengan nama kustom
            $table->string('jabatan');
            $table->string('jabatan_kode', 50)->unique(); // unique agar tidak ada kode yg sama
            $table->timestamps(); // membuat kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatans');
    }
};
