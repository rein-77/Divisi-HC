<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuratMasukDisposisi extends Model
{
    use HasFactory;

    /**
     * Tabel kustom sesuai migrasi.
     */
    protected $table = 'surat_masuk_disposisi';

    /**
     * Primary key kustom sesuai migrasi.
     */
    protected $primaryKey = 'surat_masuk_disposisi_id';

    /**
     * Kolom yang dapat diisi mass-assignment.
     */
    protected $fillable = [
        'surat_masuk_id',
        'user_id',
        'bagian_seksi_id',
        'disposisi_oleh',
        'keterangan',
        'waktu_disposisi',
        'terakhir_diedit',
    ];

    /**
     * Casting kolom tanggal ke instance Carbon.
     */
    protected $casts = [
        'waktu_disposisi' => 'datetime',
        'terakhir_diedit' => 'datetime',
    ];

    /**
     * Relasi ke surat masuk.
     */
    public function suratMasuk(): BelongsTo
    {
        return $this->belongsTo(SuratMasuk::class, 'surat_masuk_id', 'surat_masuk_id');
    }

    /**
     * Relasi ke user yang menerima disposisi.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relasi ke bagian seksi tujuan.
     */
    public function bagianSeksi(): BelongsTo
    {
        return $this->belongsTo(BagianSeksi::class, 'bagian_seksi_id', 'bagian_seksi_id');
    }

    /**
     * Relasi ke user yang membuat disposisi.
     */
    public function disposisiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disposisi_oleh', 'user_id');
    }

    /**
     * Relasi many-to-many ke bagian seksi (untuk multiple divisions).
     */
    public function bagianSeksiMultiple()
    {
        return $this->belongsToMany(
            BagianSeksi::class, 
            'surat_masuk_disposisi_bagian', 
            'surat_masuk_disposisi_id', 
            'bagian_seksi_id',
            'surat_masuk_disposisi_id',
            'bagian_seksi_id'
        );
    }
}
