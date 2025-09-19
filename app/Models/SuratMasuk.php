<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratMasuk extends Model
{
    use HasFactory, SoftDeletes;
    
    /**
     * Tabel kustom sesuai migrasi.
     */
    protected $table = 'surat_masuk';

    /**
     * Primary key kustom sesuai migrasi.
     */
    protected $primaryKey = 'surat_masuk_id';

    /**
     * Kolom yang dapat diisi mass-assignment.
     *
     * @var string[]
     */
    protected $fillable = [
        'surat_masuk_nomor',
        'surat_masuk_tanggal',
        'tanggal_diterima',
        'pengirim',
        'tujuan',
        'perihal',
        'berkas',
        'user_id_created',
    ];

    /**
     * Casting kolom tanggal ke instance Carbon.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'surat_masuk_tanggal' => 'date',
        'tanggal_diterima' => 'date',
    ];

    /**
     * Relasi ke user pembuat data.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_created', 'user_id');
    }
}
