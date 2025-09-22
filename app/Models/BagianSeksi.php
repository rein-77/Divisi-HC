<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BagianSeksi extends Model
{
    use HasFactory;

    /**
     * Tabel kustom sesuai migrasi.
     */
    protected $table = 'bagian_seksi';

    /**
     * Primary key kustom sesuai migrasi.
     */
    protected $primaryKey = 'bagian_seksi_id';

    /**
     * Kolom yang dapat diisi mass-assignment.
     */
    protected $fillable = [
        'bagian_seksi',
        'bagian_seksi_kode',
        'unit_kerja_id',
    ];

    /**
     * Relasi ke unit kerja.
     */
    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id', 'unit_kerja_id');
    }
}
