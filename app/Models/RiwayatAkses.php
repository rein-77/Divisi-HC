<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatAkses extends Model
{
    /**
     * Nama tabel kustom.
     */
    protected $table = 'riwayat_akses';

    /**
     * Primary key kustom.
     */
    protected $primaryKey = 'riwayat_akses_id';

    /**
     * Kolom yang dapat diisi mass-assignment.
     */
    protected $fillable = [
        'status',
        'user_id',
        'waktu',
    ];

    /**
     * Casting kolom tanggal ke instance Carbon.
     */
    protected $casts = [
        'waktu' => 'datetime',
    ];

    /**
     * Relasi ke user yang mengakses.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Catat riwayat akses login.
     */
    public static function catatLogin($userId)
    {
        return self::create([
            'status' => 'login',
            'user_id' => $userId,
            'waktu' => now(),
        ]);
    }

    /**
     * Catat riwayat akses logout.
     */
    public static function catatLogout($userId)
    {
        return self::create([
            'status' => 'logout',
            'user_id' => $userId,
            'waktu' => now(),
        ]);
    }
}
