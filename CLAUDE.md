# CLAUDE.md — sch_web Project Rules

## Deskripsi Proyek
Website resmi sekolah negeri. Tujuan: penceritaan visual komunitas, informasi akademik,
publikasi berita, wadah kreativitas siswa, dan panduan PPDB.

## Tech Stack
- **Backend**: CodeIgniter 4.x (PHP 8.1+)
- **Database**: MySQL 8.x
- **Frontend**: Bootstrap 5.3 (CDN), Bootstrap Icons (CDN)
- **Rich Text Editor**: Quill.js (CDN) — hanya untuk form artikel admin
- **Testing**: PHPUnit bawaan CI4

## Batasan Bisnis (WAJIB DIPATUHI)
- **JANGAN** membuat halaman, section, atau referensi: Biaya Pendidikan, Tuition, Donasi
- **JANGAN** membuat halaman Karir atau Lowongan Pekerjaan
- Sekolah negeri = gratis, rekrutmen guru oleh pemerintah

## Konvensi Penamaan
| Komponen | Konvensi | Contoh |
|---|---|---|
| Controllers publik | PascalCase | `BeritaArtikel.php` |
| Controllers admin | Namespace `Admin\` | `Admin/ArtikelController.php` |
| Models | PascalCase + `Model` suffix | `ArtikelModel.php` |
| Tabel database | snake_case, plural | `kategori_artikel` |
| Views folder | lowercase, underscore | `views/kehidupan_siswa/` |
| Routes URL | kebab-case | `/kehidupan-siswa` |
| JS/CSS files | kebab-case | `admin.css`, `app.js` |

## Namespace CI4
- Controllers: `namespace App\Controllers;`
- Admin Controllers: `namespace App\Controllers\Admin;`
- Models: `namespace App\Models;`
- Filters: `namespace App\Filters;`

## Perintah CLI Spark
```bash
php spark serve                    # Jalankan dev server
php spark migrate                  # Jalankan semua migrasi
php spark migrate:rollback         # Rollback batch terakhir
php spark migrate:status           # Cek status migrasi
php spark db:seed DatabaseSeeder   # Seed semua data awal
php spark make:controller Name     # Scaffold controller
php spark make:model Name          # Scaffold model
php spark make:migration Name      # Scaffold migration
php spark test                     # Jalankan PHPUnit tests
php spark routes                   # Tampilkan semua route terdaftar
php spark cache:clear              # Bersihkan cache
```

## Aturan Pengembangan
1. Semua mutasi data menggunakan **POST** (tidak ada DELETE/PUT method spoofing)
2. CSRF token **wajib** di setiap form: `<?= csrf_field() ?>`
3. Gambar disimpan di `writable/uploads/{module}/` (artikel, guru, galeri, ppdb)
4. Soft delete aktif pada tabel: `artikel`, `guru_staf`, `galeri`
5. Admin views **wajib** extend `layouts/admin`: `<?= $this->extend('layouts/admin') ?>`
6. Public views **wajib** extend `layouts/public`: `<?= $this->extend('layouts/public') ?>`
7. Flash messages: `session()->setFlashdata('success'/'error', 'pesan')`
8. Slug auto-generate dari judul, URL-safe, via `slug_generate()` di `app_helper.php`
9. `view_count` hanya bertambah di `BeritaArtikel::detail()`
10. Validasi input di semua method `store()` dan `update()`
11. Jangan commit file `.env`

## Struktur Route
```
GET  /                       → Home::index
GET  /profil                 → Profil::index
GET  /akademik               → Akademik::index
GET  /kehidupan-siswa        → KehidupanSiswa::index
GET  /direktori              → Direktori::index
GET  /berita                 → BeritaArtikel::index
GET  /berita/(:segment)      → BeritaArtikel::detail/$1
GET  /ppdb                   → Ppdb::index

GET  /admin/login            → Admin\AuthController::login
POST /admin/login            → Admin\AuthController::attemptLogin
GET  /admin/logout           → Admin\AuthController::logout

/admin/* (filter: admin_auth)
     /admin/dashboard        → Admin\DashboardController::index
     /admin/artikel/*        → Admin\ArtikelController
     /admin/guru/*           → Admin\GuruController
     /admin/galeri/*         → Admin\GaleriController
     /admin/kegiatan/*       → Admin\KegiatanController
     /admin/ppdb/*           → Admin\PpdbController
     /admin/menu/*           → Admin\MenuController
     /admin/pengaturan/*     → Admin\PengaturanController
```

## Konfigurasi Cloudflare Tunnel
- `app.baseURL` **dikosongkan** di `app/Config/App.php` — CI4 auto-detect dari `HTTP_HOST`
- Cloudflare IP ranges sudah terdaftar di `$proxyIPs` di `App.php`
- `site_url()` dan `base_url()` otomatis benar di localhost maupun Cloudflare Tunnel

## Konvensi Git Commit
```
feat:   fitur baru
fix:    perbaikan bug
chore:  tooling/konfigurasi
style:  CSS/visual saja
test:   menambah test
docs:   dokumentasi
```
**Satu commit per fitur/fix. Jangan commit `.env`.**

## Helpers yang Tersedia (`app/Helpers/app_helper.php`)
- `setting($key)` — ambil nilai pengaturan situs dari tabel `pengaturan`
- `slug_generate($string)` — buat slug URL-safe dari string
- `format_tanggal($date, $format)` — format tanggal ke Bahasa Indonesia
- `truncate_text($text, $length)` — potong teks dengan elipsis

## Upload Gambar
Gunakan `App\Libraries\ImageUpload` untuk semua upload gambar.
```php
$uploader = new \App\Libraries\ImageUpload();
$path = $uploader->upload('thumbnail', 'artikel'); // simpan ke writable/uploads/artikel/
```
