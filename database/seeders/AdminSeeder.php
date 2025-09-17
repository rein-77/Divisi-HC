<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Pastikan Unit Kerja ada
            $unitKerjaKode = 'UK-001';
            $unitKerjaId = DB::table('unit_kerja')
                ->where('unit_kerja_kode', $unitKerjaKode)
                ->value('unit_kerja_id');

            if (! $unitKerjaId) {
                $unitKerjaId = DB::table('unit_kerja')->insertGetId([
                    'unit_kerja' => 'Unit Kerja Pusat',
                    'unit_kerja_kode' => $unitKerjaKode,
                    'kota_kabupaten' => 'Jakarta',
                    'created_at' => now(),
                    'updated_at' => now(),
                ], 'unit_kerja_id');
            }

            // Pastikan Bagian/Seksi ada
            $bagianSeksiKode = 'BGS-ADM';
            $bagianSeksiId = DB::table('bagian_seksi')
                ->where('bagian_seksi_kode', $bagianSeksiKode)
                ->value('bagian_seksi_id');

            if (! $bagianSeksiId) {
                $bagianSeksiId = DB::table('bagian_seksi')->insertGetId([
                    'bagian_seksi' => 'Administrasi',
                    'bagian_seksi_kode' => $bagianSeksiKode,
                    'unit_kerja_id' => $unitKerjaId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ], 'bagian_seksi_id');
            }

            // Pastikan Jabatan ada
            $jabatanKode = 'ADM';
            $jabatanId = DB::table('jabatan')
                ->where('jabatan_kode', $jabatanKode)
                ->value('jabatan_id');

            if (! $jabatanId) {
                $jabatanId = DB::table('jabatan')->insertGetId([
                    'jabatan' => 'Administrator',
                    'jabatan_kode' => $jabatanKode,
                    'created_at' => now(),
                    'updated_at' => now(),
                ], 'jabatan_id');
            }

            // Buat atau perbarui user admin
            // Catatan: migrasi membuat tabel 'user' (bukan 'users') dengan kolom 'username'
            $userTable = Schema::hasTable('user') ? 'user' : 'users';

            $username = 'admin';

            $exists = DB::table($userTable)->where('username', $username)->exists();

            $data = [
                // Skema kolom menyesuaikan migrasi 2025_09_12_031858_create_users_table
                'nama' => 'Administrator',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-01-01',
                'jabatan_id' => $jabatanId,
                'bagian_seksi_id' => $bagianSeksiId,
                'password' => Hash::make('admin'),
                'remember_token' => Str::random(10),
                'updated_at' => now(),
            ];

            if ($exists) {
                DB::table($userTable)->where('username', $username)->update($data);
            } else {
                DB::table($userTable)->insert(array_merge($data, [
                    'npp' => '000000',
                    'username' => $username,
                    'created_at' => now(),
                ]));
            }
        });
    }
}
