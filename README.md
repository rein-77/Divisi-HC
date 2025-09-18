# Divisi-HC - Sistem Persuratan

Sistem aplikasi web untuk mengelola persuratan di Divisi Human Capital (HC). Dibangun menggunakan Laravel 11 dengan Tailwind CSS untuk antarmuka yang modern dan responsif.

## Fitur Utama

- **Manajemen Surat Masuk**: Tambah, edit, lihat, dan hapus surat masuk dengan fitur soft delete
- **Upload Berkas**: Mendukung file PDF, DOC, DOCX, JPG, JPEG, PNG (maksimal 10MB)
- **Pencarian**: Cari surat berdasarkan nomor, pengirim, perihal, dll.
- **Autentikasi**: Sistem login/logout dengan middleware auth
- **Dashboard**: Halaman utama dengan ringkasan data
- **Responsive Design**: Antarmuka yang responsif untuk desktop dan mobile

## Persyaratan Sistem

- PHP >= 8.1
- Composer
- Node.js & npm
- MySQL atau database lain yang didukung Laravel
- Git

## Instalasi

1. **Clone Repository**:
   ```bash
   git clone https://github.com/rein-77/Divisi-HC.git
   cd Divisi-HC
   ```

2. **Install Dependensi PHP**:
   ```bash
   composer install
   ```

3. **Install Dependensi Node.js**:
   ```bash
   npm install
   ```

4. **Setup Environment**:
   - Copy file `.env.example` ke `.env`:
     ```bash
     cp .env.example .env
     ```
   - Edit [`.env`](.env ) dan konfigurasikan database, APP_NAME, dll.

5. **Generate Key**:
   ```bash
   php artisan key:generate
   ```

6. **Jalankan Migrasi dan Seeder**:
   ```bash
   php artisan migrate
   php artisan db:seed 
   ```

7. **Buat Storage Link** (untuk upload file):
   ```bash
   php artisan storage:link
   ```

## Menjalankan Aplikasi

1. **Jalankan Server Laravel**:
   ```bash
   php artisan serve
   ```
   Aplikasi akan berjalan di `http://localhost:8000`

2. **Compile Assets (Tailwind CSS)**:
   ```bash
   npm run dev
   ```
   Atau untuk production:
   ```bash
   npm run build
   ```

3. **Akses Aplikasi**:
   - Buka browser dan kunjungi `http://localhost:8000`
   - Login dengan akun admin (default: username `admin`, password `admin`)

## Struktur Database

- **users**: Tabel pengguna (custom primary key `user_id`)
- **surat_masuk**: Tabel surat masuk dengan soft deletes
- **unit_kerja, bagian_seksi, jabatan**: Tabel referensi (untuk ekspansi fitur)

