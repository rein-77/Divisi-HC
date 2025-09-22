<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('surat_masuk_disposisi', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('surat_masuk_disposisi', 'disposisi_oleh')) {
                // Add field to track who created the disposition
                $table->foreignId('disposisi_oleh')->after('bagian_seksi_id')->constrained('user', 'user_id');
            }
            
            if (!Schema::hasColumn('surat_masuk_disposisi', 'terakhir_diedit')) {
                // Add field to track when it was last edited
                $table->timestamp('terakhir_diedit')->after('waktu_disposisi')->nullable();
            }
        });
        
        // Change the structure to support multiple divisions
        Schema::table('surat_masuk_disposisi', function (Blueprint $table) {
            // We'll make bagian_seksi_id nullable since we'll store multiple divisions in a separate table
            $table->foreignId('bagian_seksi_id')->nullable()->change();
        });
        
        // Create pivot table for multiple divisions if it doesn't exist
        if (!Schema::hasTable('surat_masuk_disposisi_bagian')) {
            Schema::create('surat_masuk_disposisi_bagian', function (Blueprint $table) {
                $table->id();
                $table->foreignId('surat_masuk_disposisi_id')->constrained('surat_masuk_disposisi', 'surat_masuk_disposisi_id')->onDelete('cascade');
                $table->foreignId('bagian_seksi_id')->constrained('bagian_seksi', 'bagian_seksi_id')->onDelete('cascade');
                $table->timestamps();
                
                // Prevent duplicate entries with shorter index name
                $table->unique(['surat_masuk_disposisi_id', 'bagian_seksi_id'], 'smd_bagian_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuk_disposisi_bagian');
        
        Schema::table('surat_masuk_disposisi', function (Blueprint $table) {
            if (Schema::hasColumn('surat_masuk_disposisi', 'disposisi_oleh')) {
                $table->dropForeign(['disposisi_oleh']);
                $table->dropColumn('disposisi_oleh');
            }
            
            if (Schema::hasColumn('surat_masuk_disposisi', 'terakhir_diedit')) {
                $table->dropColumn('terakhir_diedit');
            }
            
            // Restore bagian_seksi_id as not nullable
            $table->foreignId('bagian_seksi_id')->nullable(false)->change();
        });
    }
};
