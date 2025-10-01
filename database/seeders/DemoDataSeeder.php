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
                    ], 'unit_kerja_id');
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
                        ], 'bagian_seksi_id');

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
                    ], 'unit_kerja_id');
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
                ], 'jabatan_id');
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
                ], 'user_id');
            } else {
                $pegawaiId = $pegawai->user_id ?? $pegawai->id ?? null;
                if (! $pegawaiId) {
                    // fallback: fetch again explicitly selecting PK
                    $pegawaiId = DB::table($userTable)->where('username', $username)->value('user_id');
                }
            }

            // Seed 25 Surat Masuk dengan no_agenda otomatis
            $today = now();
            $tujuanOptions = [
                'Bagian Kompensasi & Manfaat',
                'Bagian Pendidikan & Pelatihan',
                'Bagian Penerimaan & Pengembangan Human Capital'
            ];

            $pengirimOptions = [
                'PT Bank Mandiri',
                'PT Telkom Indonesia',
                'Kementerian Keuangan RI',
                'Otoritas Jasa Keuangan (OJK)',
                'PT Bank Rakyat Indonesia',
                'PT Asuransi Jiwasraya',
                'Pemerintah Provinsi Kalimantan Selatan',
                'PT Pertamina',
                'Bank Indonesia',
                'PT PLN (Persero)',
                'Badan Kepegawaian Negara',
                'PT Pos Indonesia',
                'Kementerian BUMN',
                'PT Bank Negara Indonesia',
                'Dinas Tenaga Kerja Kota Banjarmasin',
                'PT Garuda Indonesia',
                'BPJS Kesehatan',
                'BPJS Ketenagakerjaan',
                'Kementerian Dalam Negeri',
                'Kantor Wilayah BRI Kalimantan Selatan',
                'PT Semen Baturaja',
                'PT Pupuk Kalimantan Timur',
                'Universitas Lambung Mangkurat',
                'Pemerintah Kota Banjarmasin',
                'PT Taspen (Persero)'
            ];

            $perihalOptions = [
                'Permohonan informasi terkait administrasi kepegawaian',
                'Undangan rapat koordinasi pembinaan SDM',
                'Pemberitahuan perubahan regulasi kepegawaian',
                'Permohonan data karyawan untuk audit eksternal',
                'Surat edaran kebijakan kompensasi dan benefit',
                'Undangan workshop pengembangan kompetensi',
                'Permohonan kerja sama program pelatihan',
                'Pemberitahuan evaluasi kinerja triwulanan',
                'Permintaan konfirmasi data remunerasi',
                'Undangan sosialisasi peraturan ketenagakerjaan',
                'Permohonan verifikasi dokumen kepegawaian',
                'Surat pemberitahuan program pengembangan karir',
                'Undangan seminar nasional human capital',
                'Permohonan data untuk laporan kepatuhan',
                'Pemberitahuan kebijakan cuti dan absensi',
                'Undangan rapat koordinasi antar divisi',
                'Permohonan laporan kinerja bulanan',
                'Surat edaran prosedur baru rekrutment',
                'Undangan pelatihan leadership development',
                'Permohonan update data pegawai',
                'Pemberitahuan perubahan struktur organisasi',
                'Undangan assessment center pegawai',
                'Permohonan konfirmasi budget training',
                'Surat edaran kebijakan work from home',
                'Undangan review kebijakan kompensasi'
            ];

            $suratMasukIds = [];

            for ($i = 1; $i <= 25; $i++) {
                $nomor = sprintf('SM-2025-%03d', $i);
                $tanggalSurat = $today->copy()->subDays(45 - $i);
                $tanggalDiterima = $tanggalSurat->copy()->addDays(rand(0, 3));

                $suratMasuk = SuratMasuk::create([
                    'surat_masuk_nomor' => $nomor,
                    'surat_masuk_tanggal' => $tanggalSurat->toDateString(),
                    'tanggal_diterima' => $tanggalDiterima->toDateString(),
                    'pengirim' => $pengirimOptions[($i - 1) % count($pengirimOptions)],
                    'tujuan' => $tujuanOptions[($i - 1) % 3], // Rotasi tujuan
                    'perihal' => $perihalOptions[($i - 1) % count($perihalOptions)],
                    'keterangan' => $i % 4 == 0 ? 'Keterangan untuk surat masuk nomor ' . $i . ' - Segera ditindaklanjuti' : null,
                    'berkas' => null,
                    'user_id_created' => $pegawaiId,
                ]);

                $suratMasukIds[] = $suratMasuk->surat_masuk_id;
            }

            // Seed Disposisi Surat Masuk (hanya untuk 15 surat pertama dari 25 surat)
            // Sisanya (10 surat) akan tetap belum didisposisi
            // Ambil daftar user dan bagian seksi untuk disposisi
            $allUsers = DB::table($userTable)->pluck('user_id')->toArray();
            $allBagianSeksi = DB::table('bagian_seksi')->pluck('bagian_seksi_id')->toArray();

            // Hanya disposisi 15 surat pertama, sisanya biarkan belum didisposisi
            $suratUntukDisposisi = array_slice($suratMasukIds, 0, 15);

            foreach ($suratUntukDisposisi as $index => $suratMasukId) {
                // Buat 1-2 disposisi untuk setiap surat masuk
                $jumlahDisposisi = rand(1, 2);
                
                for ($j = 0; $j < $jumlahDisposisi; $j++) {
                    // Pilih user dan bagian seksi secara random
                    $userId = $allUsers[array_rand($allUsers)];
                    $bagianSeksiId = $allBagianSeksi[array_rand($allBagianSeksi)];
                    
                    // Variasi waktu disposisi untuk membuat data lebih realistis
                    $waktuDisposisiBase = $today->copy()->subDays(40 - $index - $j);
                    $waktuDisposisi = $waktuDisposisiBase->addHours(rand(8, 17))->addMinutes(rand(0, 59));
                    
                    $disposisiId = DB::table('surat_masuk_disposisi')->insertGetId([
                        'surat_masuk_id' => $suratMasukId,
                        'user_id' => $userId,
                        'bagian_seksi_id' => $bagianSeksiId,
                        'keterangan' => $j == 0 && $index % 4 == 0 ? 'Disposisi mendesak - deadline 3 hari' : 'Mohon ditindaklanjuti sesuai prosedur',
                        'waktu_disposisi' => $waktuDisposisi,
                        'disposisi_oleh' => $pegawaiId,
                        'terakhir_diedit' => $index % 6 == 0 ? $waktuDisposisi->copy()->addHours(2) : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    // Tambahkan ke tabel pivot surat_masuk_disposisi_bagian (multiple divisions)
                    // Tambahkan 1-2 bagian seksi tambahan secara random
                    $jumlahBagianTambahan = rand(0, 2);
                    for ($k = 0; $k < $jumlahBagianTambahan; $k++) {
                        $bagianTambahanId = $allBagianSeksi[array_rand($allBagianSeksi)];
                        
                        // Cek apakah kombinasi sudah ada
                        $exists = DB::table('surat_masuk_disposisi_bagian')
                            ->where('surat_masuk_disposisi_id', $disposisiId)
                            ->where('bagian_seksi_id', $bagianTambahanId)
                            ->exists();
                            
                        if (!$exists) {
                            DB::table('surat_masuk_disposisi_bagian')->insert([
                                'surat_masuk_disposisi_id' => $disposisiId,
                                'bagian_seksi_id' => $bagianTambahanId,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }

            // Seed 15 Surat Keluar
            $tujuanSuratKeluarOptions = [
                'Bank Indonesia Kantor Pusat',
                'Otoritas Jasa Keuangan Regional Kalimantan',
                'Kementerian Keuangan RI Direktorat SDM',
                'PT Bank Mandiri - Divisi HC',
                'Badan Kepegawaian Negara',
                'Pemerintah Provinsi Kalimantan Selatan',
                'Dinas Tenaga Kerja Kota Banjarmasin',
                'PT Bank Rakyat Indonesia - HR Division',
                'Asosiasi Bank Pembangunan Daerah',
                'Lembaga Sertifikasi Profesi Perbankan',
                'Universitas Lambung Mangkurat',
                'PT Taspen (Persero)',
                'BPJS Ketenagakerjaan Cabang Banjarmasin',
                'Kementerian BUMN Biro SDM',
                'Chamber of Commerce Kalimantan Selatan'
            ];

            $perihalKeluarOptions = [
                'Tanggapan atas permohonan informasi kepegawaian',
                'Konfirmasi kehadiran dalam rapat koordinasi',
                'Penyampaian laporan data karyawan semester I',
                'Permohonan kerja sama program pelatihan SDM',
                'Pemberitahuan pelaksanaan audit internal',
                'Undangan kunjungan kerja dan studi banding',
                'Laporan hasil evaluasi kinerja triwulanan',
                'Permohonan narasumber untuk seminar HC',
                'Penyampaian data remunerasi untuk verifikasi',
                'Konfirmasi pelaksanaan program pengembangan',
                'Laporan implementasi kebijakan kepegawaian',
                'Permohonan perpanjangan kerja sama pelatihan',
                'Pemberitahuan perubahan struktur organisasi',
                'Undangan dialog pembinaan SDM regional',
                'Laporan pelaksanaan program kesejahteraan karyawan'
            ];

            for ($i = 1; $i <= 15; $i++) {
                $nomor = sprintf('%03d/%s', $i, date('Y'));
                $tanggalSurat = $today->copy()->subDays(25 - $i);

                // Variasi tujuan antara manual atau bagian/seksi
                $useBagianSeksi = $i % 3 != 0; // 2 dari 3 surat menggunakan bagian/seksi

                // Variasi bagian seksi untuk yang menggunakan bagian/seksi
                $bagianSeksiTujuanId = $useBagianSeksi ? $allBagianSeksi[array_rand($allBagianSeksi)] : null;
                $unitKerjaTujuanId = $useBagianSeksi ? $firstUnitId : null;

                DB::table('surat_keluar')->insert([
                    'surat_keluar_nomor' => $nomor,
                    'surat_keluar_tanggal' => $tanggalSurat->toDateString(),
                    'tujuan' => $useBagianSeksi ? '-' : $tujuanSuratKeluarOptions[($i - 1) % count($tujuanSuratKeluarOptions)],
                    'perihal' => $perihalKeluarOptions[($i - 1) % count($perihalKeluarOptions)],
                    'berkas' => null,
                    'keterangan' => $i % 4 == 0 ? 'Surat keluar prioritas tinggi nomor ' . $i : null,
                    'unit_kerja_tujuan' => $unitKerjaTujuanId,
                    'bagian_seksi_tujuan' => $bagianSeksiTujuanId,
                    'bagian_seksi_pembuat' => $firstBagianSeksiId,
                    'user_id_created' => $pegawaiId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
