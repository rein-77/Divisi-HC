<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratKeluar extends Model
{
    protected $table = 'surat_keluar';
    protected $primaryKey = 'surat_keluar_id';

    protected $fillable = [
        'surat_keluar_nomor',
        'surat_keluar_tanggal',
        'tujuan',
        'perihal',
        'berkas',
        'keterangan',
        'unit_kerja_tujuan',
        'bagian_seksi_tujuan',
        'bagian_seksi_pembuat',
        'user_id_created',
    ];

    protected $casts = [
        'surat_keluar_tanggal' => 'date',
    ];

    /**
     * Generate nomor surat keluar otomatis dengan format 001/2025.
     *
     * @return string
     */
    public static function generateNomorSurat(): string
    {
        $currentYear = date('Y');
        
        // Cari nomor terakhir yang pernah digunakan di tahun ini
        $lastSurat = self::whereYear('created_at', $currentYear)
            ->orderByRaw('CAST(SUBSTRING_INDEX(surat_keluar_nomor, "/", 1) AS UNSIGNED) DESC')
            ->first();
        
        if ($lastSurat) {
            // Ambil nomor dari surat terakhir dan tambah 1
            $lastNumber = (int) explode('/', $lastSurat->surat_keluar_nomor)[0];
            $nextNumber = $lastNumber + 1;
        } else {
            // Jika belum ada surat di tahun ini, mulai dari 1
            $nextNumber = 1;
        }
        
        // Format dengan padding 3 digit
        return sprintf('%03d/%s', $nextNumber, $currentYear);
    }

    /**
     * Relasi ke user yang membuat surat.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_created', 'user_id');
    }

    /**
     * Relasi ke unit kerja tujuan.
     */
    public function unitKerjaTujuan(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_tujuan', 'unit_kerja_id');
    }

    /**
     * Relasi ke bagian seksi tujuan.
     */
    public function bagianSeksiTujuan(): BelongsTo
    {
        return $this->belongsTo(BagianSeksi::class, 'bagian_seksi_tujuan', 'bagian_seksi_id');
    }

    /**
     * Relasi ke bagian seksi pembuat.
     */
    public function bagianSeksiPembuat(): BelongsTo
    {
        return $this->belongsTo(BagianSeksi::class, 'bagian_seksi_pembuat', 'bagian_seksi_id');
    }
}
