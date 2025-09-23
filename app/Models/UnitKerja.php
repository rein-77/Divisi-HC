<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnitKerja extends Model
{
    use HasFactory;

    /**
     * Tabel kustom sesuai migrasi.
     */
    protected $table = 'unit_kerja';

    /**
     * Primary key kustom sesuai migrasi.
     */
    protected $primaryKey = 'unit_kerja_id';

    /**
     * Kolom yang dapat diisi mass-assignment.
     */
    protected $fillable = [
        'unit_kerja',
        'unit_kerja_kode',
        'kota_kabupaten',
    ];

    /**
     * Relasi ke bagian seksi.
     */
    public function bagianSeksi()
    {
        return $this->hasMany(BagianSeksi::class, 'unit_kerja_id', 'unit_kerja_id');
    }
}
