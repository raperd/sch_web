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

// =====================================================================
// ADMIN AUTH (tidak perlu filter)
// =====================================================================
$routes->get('admin/login', 'Admin\AuthController::login');
$routes->post('admin/login', 'Admin\AuthController::attemptLogin');
$routes->get('admin/logout', 'Admin\AuthController::logout');

// Redirect /admin ke dashboard
$routes->get('admin', 'Admin\DashboardController::index', ['filter' => 'admin_auth']);

// =====================================================================
// ADMIN PANEL (dilindungi filter admin_auth)
// =====================================================================
$routes->group('admin', ['filter' => 'admin_auth'], static function (RouteCollection $routes): void {

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

    // -----------------------------------------------------------------
    // Guru & Staf
    // -----------------------------------------------------------------
    $routes->get('guru', 'Admin\GuruController::index');
    $routes->get('guru/create', 'Admin\GuruController::create');
    $routes->post('guru/store', 'Admin\GuruController::store');
    $routes->get('guru/edit/(:num)', 'Admin\GuruController::edit/$1');
    $routes->post('guru/update/(:num)', 'Admin\GuruController::update/$1');
    $routes->post('guru/delete/(:num)', 'Admin\GuruController::delete/$1');
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
});
