<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =====================================================================
// PUBLIC ROUTES
// =====================================================================
$routes->get('/', 'Home::index');
$routes->get('profil', 'Profil::index');
$routes->get('akademik', 'Akademik::index');
$routes->get('kehidupan-siswa', 'KehidupanSiswa::index');
$routes->get('direktori', 'Direktori::index');
$routes->get('berita', 'BeritaArtikel::index');
$routes->get('berita/(:segment)', 'BeritaArtikel::detail/$1');
$routes->get('ppdb', 'Ppdb::index');
$routes->get('prestasi', 'Prestasi::index');
$routes->get('album-foto', 'AlbumFoto::index');
$routes->get('link-terkait', 'LinkController::index');

// =====================================================================
// ADMIN AUTH (tidak perlu filter)
// =====================================================================
$adminPfx = env('ADMIN_PREFIX', 'klwshub');

$routes->get($adminPfx . '/login',  'Admin\AuthController::login');
$routes->post($adminPfx . '/login', 'Admin\AuthController::attemptLogin');
$routes->get($adminPfx . '/logout', 'Admin\AuthController::logout');

// Redirect /{prefix} ke dashboard
$routes->get($adminPfx, 'Admin\DashboardController::index', ['filter' => 'admin_auth']);

// =====================================================================
// ADMIN PANEL (dilindungi filter admin_auth)
// =====================================================================
$routes->group($adminPfx, ['filter' => 'admin_auth'], static function (RouteCollection $routes): void {

    // Dashboard
    $routes->get('dashboard', 'Admin\DashboardController::index');

    // -----------------------------------------------------------------
    // Artikel
    // -----------------------------------------------------------------
    $routes->get('artikel', 'Admin\ArtikelController::index');
    $routes->get('artikel/create', 'Admin\ArtikelController::create');
    $routes->post('artikel/store', 'Admin\ArtikelController::store');
    $routes->get('artikel/edit/(:num)', 'Admin\ArtikelController::edit/$1');
    $routes->post('artikel/update/(:num)', 'Admin\ArtikelController::update/$1');
    $routes->post('artikel/delete/(:num)', 'Admin\ArtikelController::delete/$1');
    $routes->post('artikel/toggle-status/(:num)', 'Admin\ArtikelController::toggleStatus/$1');
    $routes->post('artikel/toggle-featured/(:num)', 'Admin\ArtikelController::toggleFeatured/$1');
    $routes->post('artikel/upload-image', 'Admin\ArtikelController::uploadKontenImage');

    // -----------------------------------------------------------------
    // Kategori Artikel
    // -----------------------------------------------------------------
    $routes->get('kategori-artikel', 'Admin\KategoriArtikelController::index');
    $routes->get('kategori-artikel/create', 'Admin\KategoriArtikelController::create');
    $routes->post('kategori-artikel/store', 'Admin\KategoriArtikelController::store');
    $routes->get('kategori-artikel/edit/(:num)', 'Admin\KategoriArtikelController::edit/$1');
    $routes->post('kategori-artikel/update/(:num)', 'Admin\KategoriArtikelController::update/$1');
    $routes->post('kategori-artikel/delete/(:num)', 'Admin\KategoriArtikelController::delete/$1');

    // -----------------------------------------------------------------
    // Kepala Sekolah dari Masa ke Masa
    // -----------------------------------------------------------------
    $routes->get('kepala-sekolah', 'Admin\KepalaSekolahController::index');
    $routes->get('kepala-sekolah/create', 'Admin\KepalaSekolahController::create');
    $routes->post('kepala-sekolah/store', 'Admin\KepalaSekolahController::store');
    $routes->get('kepala-sekolah/edit/(:num)', 'Admin\KepalaSekolahController::edit/$1');
    $routes->post('kepala-sekolah/update/(:num)', 'Admin\KepalaSekolahController::update/$1');
    $routes->post('kepala-sekolah/delete/(:num)', 'Admin\KepalaSekolahController::delete/$1');

    // -----------------------------------------------------------------
    // Guru & Staf
    // -----------------------------------------------------------------
    $routes->get('guru', 'Admin\GuruController::index');
    $routes->get('guru/create', 'Admin\GuruController::create');
    $routes->post('guru/store', 'Admin\GuruController::store');
    $routes->get('guru/edit/(:num)', 'Admin\GuruController::edit/$1');
    $routes->post('guru/update/(:num)', 'Admin\GuruController::update/$1');
    $routes->post('guru/delete/(:num)', 'Admin\GuruController::delete/$1');
    $routes->post('guru/toggle/(:num)', 'Admin\GuruController::toggleActive/$1');
    $routes->post('guru/urutan', 'Admin\GuruController::updateUrutan');

    // -----------------------------------------------------------------
    // Galeri
    // -----------------------------------------------------------------
    $routes->get('galeri', 'Admin\GaleriController::index');
    $routes->get('galeri/upload', 'Admin\GaleriController::upload');
    $routes->post('galeri/store', 'Admin\GaleriController::store');
    $routes->get('galeri/edit/(:num)', 'Admin\GaleriController::edit/$1');
    $routes->post('galeri/update/(:num)', 'Admin\GaleriController::update/$1');
    $routes->post('galeri/delete/(:num)', 'Admin\GaleriController::delete/$1');

    // -----------------------------------------------------------------
    // Album Foto
    // -----------------------------------------------------------------
    $routes->get('album-foto', 'Admin\AlbumFotoController::index');
    $routes->get('album-foto/create', 'Admin\AlbumFotoController::create');
    $routes->post('album-foto/store', 'Admin\AlbumFotoController::store');
    $routes->get('album-foto/edit/(:num)', 'Admin\AlbumFotoController::edit/$1');
    $routes->post('album-foto/update/(:num)', 'Admin\AlbumFotoController::update/$1');
    $routes->post('album-foto/delete/(:num)', 'Admin\AlbumFotoController::delete/$1');
    // Quick Links
    $routes->get('quick-links', 'Admin\QuickLinkController::index');
    $routes->get('quick-links/create', 'Admin\QuickLinkController::create');
    $routes->post('quick-links/store', 'Admin\QuickLinkController::store');
    $routes->get('quick-links/edit/(:num)', 'Admin\QuickLinkController::edit/$1');
    $routes->post('quick-links/update/(:num)', 'Admin\QuickLinkController::update/$1');
    $routes->post('quick-links/toggle/(:num)', 'Admin\QuickLinkController::toggleActive/$1');
    $routes->post('quick-links/delete/(:num)', 'Admin\QuickLinkController::delete/$1');

    // -----------------------------------------------------------------
    // Nilai Sekolah
    // -----------------------------------------------------------------
    $routes->get('nilai-sekolah', 'Admin\NilaiSekolahController::index');
    $routes->get('nilai-sekolah/create', 'Admin\NilaiSekolahController::create');
    $routes->post('nilai-sekolah/store', 'Admin\NilaiSekolahController::store');
    $routes->get('nilai-sekolah/edit/(:num)', 'Admin\NilaiSekolahController::edit/$1');
    $routes->post('nilai-sekolah/update/(:num)', 'Admin\NilaiSekolahController::update/$1');
    $routes->post('nilai-sekolah/delete/(:num)', 'Admin\NilaiSekolahController::delete/$1');

    // -----------------------------------------------------------------
    // Aplikasi Terkait / Tautan Aplikasi
    // -----------------------------------------------------------------
    $routes->get('aplikasi', 'Admin\AplikasiController::index');
    $routes->get('aplikasi/create', 'Admin\AplikasiController::create');
    $routes->post('aplikasi/store', 'Admin\AplikasiController::store');
    $routes->get('aplikasi/edit/(:num)', 'Admin\AplikasiController::edit/$1');
    $routes->post('aplikasi/update/(:num)', 'Admin\AplikasiController::update/$1');
    $routes->post('aplikasi/toggle/(:num)', 'Admin\AplikasiController::toggleActive/$1');
    $routes->post('aplikasi/delete/(:num)', 'Admin\AplikasiController::delete/$1');
    // -----------------------------------------------------------------
    // Kegiatan
    // -----------------------------------------------------------------
    $routes->get('kegiatan', 'Admin\KegiatanController::index');
    $routes->get('kegiatan/create', 'Admin\KegiatanController::create');
    $routes->post('kegiatan/store', 'Admin\KegiatanController::store');
    $routes->get('kegiatan/edit/(:num)', 'Admin\KegiatanController::edit/$1');
    $routes->post('kegiatan/update/(:num)', 'Admin\KegiatanController::update/$1');
    $routes->post('kegiatan/delete/(:num)', 'Admin\KegiatanController::delete/$1');

    // -----------------------------------------------------------------
    // Akademik (Program Unggulan & Kurikulum)
    // -----------------------------------------------------------------
    $routes->get('akademik/program',              'Admin\AkademikController::program');
    $routes->get('akademik/program/create',       'Admin\AkademikController::programCreate');
    $routes->post('akademik/program/store',       'Admin\AkademikController::programStore');
    $routes->get('akademik/program/(:num)/edit',  'Admin\AkademikController::programEdit/$1');
    $routes->post('akademik/program/(:num)/update','Admin\AkademikController::programUpdate/$1');
    $routes->post('akademik/program/(:num)/delete','Admin\AkademikController::programDelete/$1');

    $routes->get('akademik/kurikulum',              'Admin\AkademikController::kurikulum');
    $routes->get('akademik/kurikulum/create',       'Admin\AkademikController::kurikulumCreate');
    $routes->post('akademik/kurikulum/store',       'Admin\AkademikController::kurikulumStore');
    $routes->get('akademik/kurikulum/(:num)/edit',  'Admin\AkademikController::kurikulumEdit/$1');
    $routes->post('akademik/kurikulum/(:num)/update','Admin\AkademikController::kurikulumUpdate/$1');
    $routes->post('akademik/kurikulum/(:num)/delete','Admin\AkademikController::kurikulumDelete/$1');

    // -----------------------------------------------------------------
    // SPMB
    // -----------------------------------------------------------------
    $routes->get('ppdb', 'Admin\PpdbController::index');
    $routes->get('ppdb/create', 'Admin\PpdbController::create');
    $routes->post('ppdb/store', 'Admin\PpdbController::store');
    $routes->get('ppdb/edit/(:num)', 'Admin\PpdbController::edit/$1');
    $routes->post('ppdb/update/(:num)', 'Admin\PpdbController::update/$1');
    $routes->post('ppdb/delete/(:num)', 'Admin\PpdbController::delete/$1');

    // -----------------------------------------------------------------
    // Menu
    // -----------------------------------------------------------------
    $routes->get('menu', 'Admin\MenuController::index');
    $routes->post('menu/store', 'Admin\MenuController::store');
    $routes->post('menu/update/(:num)', 'Admin\MenuController::update/$1');
    $routes->post('menu/delete/(:num)', 'Admin\MenuController::delete/$1');
    $routes->post('menu/urutan', 'Admin\MenuController::updateUrutan');

    // -----------------------------------------------------------------
    // Pengaturan
    // -----------------------------------------------------------------
    $routes->get('pengaturan', 'Admin\PengaturanController::index');
    $routes->post('pengaturan/update', 'Admin\PengaturanController::update');

    // -----------------------------------------------------------------
    // Ekstrakurikuler
    // -----------------------------------------------------------------
    $routes->get('ekskul', 'Admin\EkstrakurikulerController::index');
    $routes->get('ekskul/create', 'Admin\EkstrakurikulerController::create');
    $routes->post('ekskul/store', 'Admin\EkstrakurikulerController::store');
    $routes->get('ekskul/edit/(:num)', 'Admin\EkstrakurikulerController::edit/$1');
    $routes->post('ekskul/update/(:num)', 'Admin\EkstrakurikulerController::update/$1');
    $routes->post('ekskul/delete/(:num)', 'Admin\EkstrakurikulerController::delete/$1');

    // -----------------------------------------------------------------
    // Prestasi
    // -----------------------------------------------------------------
    $routes->get('prestasi', 'Admin\PrestasiController::index');
    $routes->get('prestasi/create', 'Admin\PrestasiController::create');
    $routes->post('prestasi/store', 'Admin\PrestasiController::store');
    $routes->get('prestasi/edit/(:num)', 'Admin\PrestasiController::edit/$1');
    $routes->post('prestasi/update/(:num)', 'Admin\PrestasiController::update/$1');
    $routes->post('prestasi/delete/(:num)', 'Admin\PrestasiController::delete/$1');

    // -----------------------------------------------------------------
    // Fasilitas
    // -----------------------------------------------------------------
    $routes->get('fasilitas', 'Admin\FasilitasController::index');
    $routes->get('fasilitas/create', 'Admin\FasilitasController::create');
    $routes->post('fasilitas/store', 'Admin\FasilitasController::store');
    $routes->get('fasilitas/edit/(:num)', 'Admin\FasilitasController::edit/$1');
    $routes->post('fasilitas/update/(:num)', 'Admin\FasilitasController::update/$1');
    $routes->post('fasilitas/delete/(:num)', 'Admin\FasilitasController::delete/$1');

    // -----------------------------------------------------------------
    // Pengguna (User Management)
    // -----------------------------------------------------------------
    $routes->get('users', 'Admin\UserController::index');
    $routes->get('users/create', 'Admin\UserController::create');
    $routes->post('users/store', 'Admin\UserController::store');
    $routes->get('users/edit/(:num)', 'Admin\UserController::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\UserController::update/$1');
    $routes->post('users/delete/(:num)', 'Admin\UserController::delete/$1');
    $routes->post('users/toggle/(:num)', 'Admin\UserController::toggleActive/$1');

    // -----------------------------------------------------------------
    // Profil & Ganti Password (untuk user yang sedang login)
    // -----------------------------------------------------------------
    $routes->get('profile', 'Admin\ProfileController::index');
    $routes->post('profile/change-password', 'Admin\ProfileController::changePassword');
    $routes->post('profile/update-info', 'Admin\ProfileController::updateInfo');
    $routes->post('profile/update-avatar', 'Admin\ProfileController::updateAvatar');
    $routes->post('profile/delete-avatar', 'Admin\ProfileController::deleteAvatar');
});
