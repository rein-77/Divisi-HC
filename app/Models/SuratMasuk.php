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
        'no_agenda',
        'surat_masuk_nomor',
        'surat_masuk_tanggal',
        'tanggal_diterima',
        'pengirim',
        'tujuan',
        'perihal',
        'keterangan',
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
     * Boot method untuk auto-generate no_agenda.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->no_agenda = self::generateNoAgenda();
        });
    }

    /**
     * Generate nomor agenda otomatis dengan format 001/2025.
     *
     * @return string
     */
    public static function generateNoAgenda(): string
    {
        $currentYear = date('Y');
        
        // Cari nomor terakhir yang pernah digunakan di tahun ini
        $lastSurat = self::whereYear('created_at', $currentYear)
            ->orderByRaw('CAST(SUBSTRING_INDEX(no_agenda, "/", 1) AS UNSIGNED) DESC')
            ->first();
        
        if ($lastSurat) {
            // Ambil nomor dari agenda terakhir dan tambah 1
            $lastNumber = (int) explode('/', $lastSurat->no_agenda)[0];
            $nextNumber = $lastNumber + 1;
        } else {
            // Jika belum ada surat di tahun ini, mulai dari 1
            $nextNumber = 1;
        }
        
        // Format dengan padding 3 digit
        return sprintf('%03d/%s', $nextNumber, $currentYear);
    }

    /**
     * Relasi ke user pembuat data.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_created', 'user_id');
    }

    /**
     * Relasi ke disposisi surat masuk.
     */
    public function disposisi()
    {
        return $this->hasMany(SuratMasukDisposisi::class, 'surat_masuk_id', 'surat_masuk_id');
    }

    /**
     * Cek apakah surat masuk sudah didisposisi.
     *
     * @return bool
     */
    public function sudahDisposisi(): bool
    {
        return $this->disposisi()->exists();
    }

    /**
     * Cek apakah surat masuk bisa dihapus.
     *
     * @return bool
     */
    public function bisaDihapus(): bool
    {
        return !$this->sudahDisposisi();
    }
}
