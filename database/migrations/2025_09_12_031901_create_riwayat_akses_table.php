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
        Schema::create('riwayat_akses', function (Blueprint $table) {
            $table->id('riwayat_akses_id');
            $table->string('status');

            // FK ke user yang mengakses
            $table->foreignId('user_id')->constrained('user', 'user_id');

            $table->dateTime('waktu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_akses');
    }
};
