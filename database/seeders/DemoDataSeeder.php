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

            // Ensure base references exist (created by AdminSeeder)
            $unitKerjaId = DB::table('unit_kerja')->value('unit_kerja_id');
            if (! $unitKerjaId) {
                $unitKerjaId = DB::table('unit_kerja')->insertGetId([
                    'unit_kerja' => 'Unit Kerja Pusat',
                    'unit_kerja_kode' => 'UK-001',
                    'kota_kabupaten' => 'Jakarta',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $bagianSeksiId = DB::table('bagian_seksi')->value('bagian_seksi_id');
            if (! $bagianSeksiId) {
                $bagianSeksiId = DB::table('bagian_seksi')->insertGetId([
                    'bagian_seksi' => 'Administrasi',
                    'bagian_seksi_kode' => 'BGS-ADM',
                    'unit_kerja_id' => $unitKerjaId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
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
                    'bagian_seksi_id' => $bagianSeksiId,
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
                    'unit_kerja_tujuan' => $unitKerjaId,
                    'bagian_seksi_tujuan' => $bagianSeksiId,
                    'bagian_seksi_pembuat' => $bagianSeksiId,
                    'user_id_created' => $pegawaiId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
