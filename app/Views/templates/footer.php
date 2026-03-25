<?php
$footerMenus = (new \App\Models\MenuModel())->getFooterMenu();
$siteName    = setting('site_name') ?? 'Nama Sekolah';
$tagline     = setting('site_tagline') ?? '';
$alamat      = setting('alamat') ?? '';
$telepon     = setting('telepon') ?? '';
$email       = setting('email') ?? '';
$fb          = setting('facebook_url');
$ig          = setting('instagram_url');
$yt          = setting('youtube_url');
$tt          = setting('tiktok_url');
?>
<footer class="footer-main pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row g-4">

            <!-- Brand & deskripsi -->
            <div class="col-12 col-md-4 col-lg-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <?php if (setting('logo_path')): ?>
                        <img src="<?= base_url('uploads/pengaturan/' . setting('logo_path')) ?>" alt="Logo" class="footer-logo">
                    <?php else: ?>
                        <i class="bi bi-mortarboard-fill text-warning fs-2"></i>
                    <?php endif; ?>
                    <div>
                        <div class="text-white fw-bold"><?= esc($siteName) ?></div>
                        <small class="text-white-50"><?= esc($tagline) ?></small>
                    </div>
                </div>
                <p class="small mb-3"><?= esc($alamat) ?></p>
                <?php if ($telepon): ?>
                    <p class="small mb-1"><i class="bi bi-telephone me-2"></i><?= esc($telepon) ?></p>
                <?php endif; ?>
                <?php if ($email): ?>
                    <p class="small mb-3"><i class="bi bi-envelope me-2"></i><?= esc($email) ?></p>
                <?php endif; ?>

                <!-- Sosial Media -->
                <?php if ($fb || $ig || $yt || $tt): ?>
                <div class="footer-social d-flex gap-2 mt-3">
                    <?php if ($fb): ?><a href="<?= esc($fb) ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a><?php endif; ?>
                    <?php if ($ig): ?><a href="<?= esc($ig) ?>" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a><?php endif; ?>
                    <?php if ($yt): ?><a href="<?= esc($yt) ?>" target="_blank" rel="noopener" aria-label="YouTube"><i class="bi bi-youtube"></i></a><?php endif; ?>
                    <?php if ($tt): ?><a href="<?= esc($tt) ?>" target="_blank" rel="noopener" aria-label="TikTok"><i class="bi bi-tiktok"></i></a><?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Navigasi Cepat -->
            <div class="col-6 col-md-2 col-lg-2">
                <h6 class="footer-heading">Navigasi</h6>
                <ul class="list-unstyled small">
                    <?php foreach ($footerMenus as $fm): ?>
                        <li class="mb-2"><a href="<?= site_url(ltrim($fm['url'], '/')) ?>"><i class="bi bi-chevron-right small me-1"></i><?= esc($fm['nama']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Info Sekolah -->
            <div class="col-6 col-md-3 col-lg-3">
                <h6 class="footer-heading">Info Sekolah</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="<?= site_url('profil') ?>"><i class="bi bi-chevron-right small me-1"></i>Visi & Misi</a></li>
                    <li class="mb-2"><a href="<?= site_url('profil') ?>"><i class="bi bi-chevron-right small me-1"></i>Sejarah</a></li>
                    <li class="mb-2"><a href="<?= site_url('akademik') ?>"><i class="bi bi-chevron-right small me-1"></i>Kurikulum</a></li>
                    <li class="mb-2"><a href="<?= site_url('direktori') ?>"><i class="bi bi-chevron-right small me-1"></i>Direktori Guru</a></li>
                    <li class="mb-2"><a href="<?= site_url('kehidupan-siswa') ?>"><i class="bi bi-chevron-right small me-1"></i>Ekstrakurikuler</a></li>
                </ul>
            </div>

            <!-- PPDB Info -->
            <div class="col-12 col-md-3 col-lg-3">
                <h6 class="footer-heading">SPMB <?= esc(setting('ppdb_tahun') ?? '') ?></h6>
                <p class="small mb-3">Informasi Penerimaan Peserta Didik Baru. Daftar melalui portal resmi Dinas Pendidikan.</p>
                <a href="<?= site_url('ppdb') ?>" class="btn btn-sm btn-outline-light mb-2 w-100">
                    <i class="bi bi-info-circle me-1"></i>Panduan SPMB
                </a>
                <?php if (setting('ppdb_link_external') && setting('ppdb_link_external') !== '#'): ?>
                <a href="<?= esc(setting('ppdb_link_external')) ?>" target="_blank" rel="noopener" class="btn btn-sm btn-warning w-100">
                    <i class="bi bi-box-arrow-up-right me-1"></i>Portal Pendaftaran
                </a>
                <?php endif; ?>
            </div>
        </div>

        <hr class="mt-4 mb-3" style="border-color:rgba(255,255,255,.1)">

        <div class="footer-bottom py-2 d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
            <span>&copy; <?= date('Y') ?> <?= esc($siteName) ?>. Semua hak dilindungi.</span>
            <span>Akreditasi: <strong class="text-warning"><?= esc(setting('akreditasi') ?? 'A') ?></strong> | TA <?= esc(setting('tahun_ajaran_aktif') ?? '') ?></span>
        </div>
    </div>
</footer>
