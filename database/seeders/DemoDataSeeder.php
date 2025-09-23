<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\SuratMasuk;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Resolve table name for users based on migration (tabel 'user')
            $userTable = Schema::hasTable('user') ? 'user' : 'users';

            // Define units and their sections
            $unitsData = [
                [
                    'unit_kerja' => 'Divisi Human Capital',
                    'unit_kerja_kode' => 'DHC-01',
                    'kota_kabupaten' => 'Banjarmasin',
                    'bagian_seksi' => [
                        [
                            'bagian_seksi' => 'Bagian Kompensasi & Manfaat',
                            'bagian_seksi_kode' => 'B-KM',
                        ],
                        [
                            'bagian_seksi' => 'Bagian Pendidikan & Pelatihan',
                            'bagian_seksi_kode' => 'B-PPL',
                        ],
                        [
                            'bagian_seksi' => 'Bagian Penerimaan & Pengembangan Human Capital',
                            'bagian_seksi_kode' => 'B-PPH',
                        ],
                    ]
                ],
                [
                    'unit_kerja' => 'Divisi Unit Usaha Syariah',
                    'unit_kerja_kode' => 'DUUS-02',
                    'kota_kabupaten' => 'Banjarmasin',
                    'bagian_seksi' => [
                        [
                            'bagian_seksi' => 'Bagian Pengembangan Produk Syariah',
                            'bagian_seksi_kode' => 'B-PPS',
                        ],
                        [
                            'bagian_seksi' => 'Bagian Risiko & Kepatuhan Syariah',
                            'bagian_seksi_kode' => 'B-RKS',
                        ],
                        [
                            'bagian_seksi' => 'Bagian Marketing & Komunikasi Syariah',
                            'bagian_seksi_kode' => 'B-MKS',
                        ],
                    ]
                ],
                [
                    'unit_kerja' => 'Divisi Teknologi Sistem Informasi',
                    'unit_kerja_kode' => 'DTSI-03',
                    'kota_kabupaten' => 'Banjarmasin',
                    'bagian_seksi' => [
                        [
                            'bagian_seksi' => 'Bagian Pengembangan Sistem',
                            'bagian_seksi_kode' => 'B-PS',
                        ],
                        [
                            'bagian_seksi' => 'Bagian Infrastruktur & Jaringan',
                            'bagian_seksi_kode' => 'B-IJ',
                        ],
                        [
                            'bagian_seksi' => 'Bagian Keamanan Informasi',
                            'bagian_seksi_kode' => 'B-KI',
                        ],
                    ]
                ],
            ];

            $firstUnitId = null;
            $firstBagianSeksiId = null;

            // Seed units and their sections
            foreach ($unitsData as $unitData) {
                $existingUnit = DB::table('unit_kerja')
                    ->where('unit_kerja', $unitData['unit_kerja'])
                    ->first();

                if (!$existingUnit) {
                    $unitId = DB::table('unit_kerja')->insertGetId([
                        'unit_kerja' => $unitData['unit_kerja'],
                        'unit_kerja_kode' => $unitData['unit_kerja_kode'],
                        'kota_kabupaten' => $unitData['kota_kabupaten'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $unitId = $existingUnit->unit_kerja_id;
                }

                // Store first unit ID for later use
                if (!$firstUnitId) {
                    $firstUnitId = $unitId;
                }

                // Seed bagian seksi for this unit
                foreach ($unitData['bagian_seksi'] as $bagianData) {
                    $existingBagian = DB::table('bagian_seksi')
                        ->where('bagian_seksi', $bagianData['bagian_seksi'])
                        ->first();

                    if (!$existingBagian) {
                        $bagianId = DB::table('bagian_seksi')->insertGetId([
                            'bagian_seksi' => $bagianData['bagian_seksi'],
                            'bagian_seksi_kode' => $bagianData['bagian_seksi_kode'],
                            'unit_kerja_id' => $unitId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        // Store first bagian seksi ID for later use
                        if (!$firstBagianSeksiId) {
                            $firstBagianSeksiId = $bagianId;
                        }
                    }
                }
            }

            // Fallback if no units/sections were created
            if (!$firstUnitId) {
                $firstUnitId = DB::table('unit_kerja')->value('unit_kerja_id');
                if (!$firstUnitId) {
                    $firstUnitId = DB::table('unit_kerja')->insertGetId([
                        'unit_kerja' => 'Divisi Human Capital',
                        'unit_kerja_kode' => 'DHC-01',
                        'kota_kabupaten' => 'Banjarmasin',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            if (!$firstBagianSeksiId) {
                $firstBagianSeksiId = DB::table('bagian_seksi')->value('bagian_seksi_id');
                if (!$firstBagianSeksiId) {
                    $firstBagianSeksiId = DB::table('bagian_seksi')->insertGetId([
                        'bagian_seksi' => 'Administrasi',
                        'bagian_seksi_kode' => 'BGS-ADM',
                        'unit_kerja_id' => $firstUnitId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            $jabatanId = DB::table('jabatan')->value('jabatan_id');
            if (! $jabatanId) {
                $jabatanId = DB::table('jabatan')->insertGetId([
                    'jabatan' => 'Staff',
                    'jabatan_kode' => 'STF',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Create a demo pegawai if not exists
            $username = 'pegawai';
            $pegawai = DB::table($userTable)->where('username', $username)->first();

            if (! $pegawai) {
                $pegawaiId = DB::table($userTable)->insertGetId([
                    'npp' => '100001',
                    'nama' => 'Pegawai Demo',
                    'tempat_lahir' => 'Bandung',
                    'tanggal_lahir' => '1995-05-15',
                    'jabatan_id' => $jabatanId,
                    'bagian_seksi_id' => $firstBagianSeksiId,
                    'username' => $username,
                    'password' => Hash::make('password'), // login: pegawai / password
                    'remember_token' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $pegawaiId = $pegawai->user_id ?? $pegawai->id ?? null;
                if (! $pegawaiId) {
                    // fallback: fetch again explicitly selecting PK
                    $pegawaiId = DB::table($userTable)->where('username', $username)->value('user_id');
                }
            }

            // Seed 7 Surat Masuk dengan no_agenda otomatis
            $today = now();
            $tujuanOptions = [
                'Bagian Kompensasi & Manfaat',
                'Bagian Pendidikan & Pelatihan', 
                'Bagian Penerimaan & Pengembangan Human Capital'
            ];

            for ($i = 1; $i <= 7; $i++) {
                $nomor = sprintf('SM-2025-%03d', $i);
                $tanggalSurat = $today->copy()->subDays(14 - $i);
                $tanggalDiterima = $tanggalSurat->copy()->addDays(rand(0, 3));

                SuratMasuk::create([
                    'surat_masuk_nomor' => $nomor,
                    'surat_masuk_tanggal' => $tanggalSurat->toDateString(),
                    'tanggal_diterima' => $tanggalDiterima->toDateString(),
                    'pengirim' => 'PT Contoh ' . chr(64 + $i),
                    'tujuan' => $tujuanOptions[($i - 1) % 3], // Rotasi tujuan
                    'perihal' => 'Permohonan informasi nomor ' . $i . ' terkait administrasi kepegawaian dan surat menyurat divisi HC.',
                    'keterangan' => $i % 2 == 0 ? 'Keterangan untuk surat masuk nomor ' . $i : null, // Sebagian ada keterangan
                    'berkas' => null,
                    'user_id_created' => $pegawaiId,
                ]);
            }

            // Seed 7 Surat Keluar
            for ($i = 1; $i <= 7; $i++) {
                $nomor = sprintf('SK-2025-%03d', $i);
                $tanggalSurat = $today->copy()->subDays(7 - $i);

                DB::table('surat_keluar')->insert([
                    'surat_keluar_nomor' => $nomor,
                    'surat_keluar_tanggal' => $tanggalSurat->toDateString(),
                    'tujuan' => 'Instansi Tujuan ' . $i,
                    'perihal' => 'Tindak lanjut permohonan ' . $i,
                    'berkas' => null,
                    'keterangan' => 'Contoh data surat keluar ' . $i,
                    'unit_kerja_tujuan' => $firstUnitId,
                    'bagian_seksi_tujuan' => $firstBagianSeksiId,
                    'bagian_seksi_pembuat' => $firstBagianSeksiId,
                    'user_id_created' => $pegawaiId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
