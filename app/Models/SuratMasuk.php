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

    /**
     * Generate nomor surat masuk otomatis berdasarkan tujuan dan tahun
     * Penomoran unified untuk semua tujuan (001, 002, 003) tapi tetap menampilkan kode tujuan
     */
    public static function generateNomorSurat($tujuan)
    {
        $year = date('Y');
        
        // Mapping tujuan ke kode surat
        $codes = [
            'Bagian Kompensasi & Manfaat' => 'SM-KM',
            'Bagian Pendidikan & Pelatihan' => 'SM-PP',
            'Bagian Penerimaan & Pengembangan Human Capital' => 'SM-PPH'
        ];
        
        if (!isset($codes[$tujuan])) {
            throw new \InvalidArgumentException('Tujuan tidak valid');
        }
        
        $code = $codes[$tujuan];
        
        // Ambil nomor terakhir dari SEMUA surat masuk di tahun ini (unified numbering)
        // Cari berdasarkan pola nomor/tahun, tidak peduli kode tujuan
        // Urutan berdasarkan tahun dan nomor urut untuk mendapatkan nomor terakhir yang benar
        $lastSurat = self::whereYear('created_at', $year)
                        ->where('surat_masuk_nomor', 'like', "%/{$year}")
                        ->orderByRaw("
                            CAST(SUBSTRING_INDEX(surat_masuk_nomor, '/', 1) AS UNSIGNED) DESC
                        ")
                        ->first();
        
        $nextNumber = 1;
        if ($lastSurat) {
            // Ekstrak nomor dari format: 001/SM-KM/2025, 002/SM-PP/2025, dll
            $parts = explode('/', $lastSurat->surat_masuk_nomor);
            if (count($parts) >= 3) {
                $lastNumber = intval($parts[0]);
                $nextNumber = $lastNumber + 1;
            }
        }
        
        // Format: 001/SM-KM/2025, 002/SM-PP/2025, 003/SM-PPH/2025
        return sprintf('%03d/%s/%s', $nextNumber, $code, $year);
    }

    /**
     * Mendapatkan preview nomor berikutnya untuk tujuan tertentu
     */
    public static function getPreviewNomor($tujuan)
    {
        try {
            return self::generateNomorSurat($tujuan);
        } catch (\Exception $e) {
            return 'Format tidak valid';
        }
    }

    /**
     * Update nomor surat dengan mempertahankan nomor urut tetapi mengubah kode tujuan
     */
    public function updateNomorSurat($tujuanBaru)
    {
        // Mapping tujuan ke kode surat
        $codes = [
            'Bagian Kompensasi & Manfaat' => 'SM-KM',
            'Bagian Pendidikan & Pelatihan' => 'SM-PP',
            'Bagian Penerimaan & Pengembangan Human Capital' => 'SM-PPH'
        ];
        
        if (!isset($codes[$tujuanBaru])) {
            throw new \InvalidArgumentException('Tujuan tidak valid');
        }
        
        $newCode = $codes[$tujuanBaru];
        
        // Ekstrak nomor urut dan tahun dari nomor surat saat ini
        // Format: 001/SM-KM/2025
        $parts = explode('/', $this->surat_masuk_nomor);
        if (count($parts) >= 3) {
            $nomorUrut = $parts[0]; // 001
            $tahun = $parts[2];     // 2025
            
            // Buat nomor baru dengan kode tujuan yang baru
            return sprintf('%s/%s/%s', $nomorUrut, $newCode, $tahun);
        }
        
        // Fallback jika format tidak sesuai
        return $this->surat_masuk_nomor;
    }
}
