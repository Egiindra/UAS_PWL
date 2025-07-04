# Toko Online CodeIgniter 4

Proyek ini adalah platform toko online yang dibangun menggunakan [CodeIgniter 4](https://codeigniter.com/). Sistem ini menyediakan beberapa fungsionalitas untuk toko online, termasuk manajemen produk, keranjang belanja, dan sistem transaksi.

## Daftar Isi

- [Fitur](#fitur)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Struktur Proyek](#struktur-proyek)

## Fitur

- Katalog Produk
  - Tampilan produk dengan gambar
  - Pencarian produk
- Keranjang Belanja
  - Tambah/hapus produk
  - Update jumlah produk
- Sistem Transaksi
  - Proses checkout
  - Riwayat transaksi
- Panel Admin
  - Manajemen produk (CRUD)
  - Manajemen kategori
  - Laporan transaksi
  - Export data ke PDF
- Sistem Autentikasi
  - Login/Register pengguna
  - Manajemen akun
- UI Responsif dengan NiceAdmin template

## Persyaratan Sistem

- PHP >= 8.2
- Composer
- Web server (XAMPP)

## Instalasi

1. **Clone repository ini**
   ```bash
   git clone [URL repository]
   cd belajar-ci-tugas
   ```
2. **Install dependensi**
   ```bash
   composer install
   ```
3. **Konfigurasi database**

   - Start module Apache dan MySQL pada XAMPP
   - Buat database **db_ci4** di phpmyadmin.
   - copy file .env dari tutorial https://www.notion.so/april-ns/Codeigniter4-Migration-dan-Seeding-045ffe5f44904e5c88633b2deae724d2

4. **Jalankan migrasi database**
   ```bash
   php spark migrate
   ```
5. **Seeder data**
   ```bash
   php spark db:seed ProductSeeder
   ```
   ```bash
   php spark db:seed ProducCategorytSeeder
   ```
   ```bash
   php spark db:seed UserSeeder
   ```
6. **Jalankan server**
   ```bash
   php spark serve
   ```
7. **Akses aplikasi**
   Buka browser dan akses `http://localhost:8080` untuk melihat aplikasi.

## Struktur Proyek

Proyek menggunakan struktur MVC CodeIgniter 4:

- app/Controllers - Logika aplikasi dan penanganan request
  - AuthController.php            - Autentikasi pengguna (login, register, logout, dll)
  - BaseController.php            - Controller dasar yang biasanya digunakan untuk inheritance controller lain
  - ContactController.php         - Menangani halaman/formulir kontak dan pengiriman pesan dari user
  - Home.php                      - Menangani halaman utama (beranda) aplikasi/website
  - Location.php                  - Menangani data lokasi (provinsi, kota, kecamatan, kelurahan, dll)
  - ProductCategoryController.php - Manajemen kategori produk (tambah, edit, hapus kategori)
  - ProdukController.php          - Manajemen produk (tambah, edit, hapus, detail produk)
  - TransaksiController.php       - Proses transaksi (checkout, pembayaran, riwayat transaksi)
- app/Models - Model untuk interaksi database
  - ProductModel.php              - Model produk
  - ProductCategoryModel.php      - Model produk category
  - TransactionModel.php          - Model transaction
  - TransactionDetailModel.php    - Model transaction detail
  - UserModel.php                 - Model pengguna
- app/Views - Template dan komponen UI
  - layout_clear.php              - Layout dasar tanpa elemen tambahan (biasanya untuk halaman khusus)
  - layout.php                    - Layout utama yang digunakan untuk membungkus tampilan halaman lain
  - v_checkout.php                - Halaman checkout (proses pembayaran dan pengisian alamat)
  - v_contact.php                 - Halaman kontak (formulir untuk menghubungi admin)
  - v_home.php                    - Halaman utama/beranda website
  - v_kategori.php                - Tampilan daftar kategori produk
  - v_keranjang.php               - Halaman keranjang belanja
  - v_login.php                   - Halaman login pengguna
  - v_produk.php                  - Tampilan daftar produk
  - v_produkPDF.php               - Tampilan produk dalam format PDF (biasanya untuk export/print)
  - v_prifile.php                 - Tampilan prifile
- public/img - Gambar produk dan aset
- public/NiceAdmin - Template admin
"# UAS_PWL" 
"# UAS_PWL" 
"# UAS_PWL" 
"# UAS_PWL1" 
"# UAS_PWL1" 
"# UAS_PWL1" 
