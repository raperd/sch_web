<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<section class="page-header py-5" style="background: linear-gradient(135deg, var(--bs-primary) 0%, var(--site-secondary) 100%);">
    <div class="container text-center text-white">
        <h1 class="fw-bold mb-2">Link Terkait</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('/') ?>" class="text-white-50">Beranda</a></li>
                <li class="breadcrumb-item active text-white">Link Terkait</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Content -->
<section class="py-5 bg-light min-vh-100">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Link Bermanfaat & Integrasi</h2>
            <p class="text-muted mt-3">Kumpulan tautan yang terhubung langsung dengan sistem sekolah atau website referensi bermanfaat untuk memfasilitasi kegiatan belajar mengajar.</p>
        </div>

        <?php if (!empty($aplikasi)): ?>
            <div class="row g-4 justify-content-center">
                <?php foreach ($aplikasi as $app): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4 text-center">
                                <div class="bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center rounded-circle mb-3 overflow-hidden" style="width: 80px; height: 80px;">
                                    <?php if (!empty($app['icon']) && !str_starts_with($app['icon'], 'bi-')): ?>
                                        <img src="<?= base_url('uploads/aplikasi/' . esc($app['icon'])) ?>" alt="<?= esc($app['nama']) ?>" class="w-100 h-100 object-fit-cover shadow-sm">
                                    <?php else: ?>
                                        <i class="bi <?= esc($app['icon'] ?: 'bi-app') ?> text-primary display-5"></i>
                                    <?php endif; ?>
                                </div>
                                <h4 class="fw-bold mb-2"><?= esc($app['nama']) ?></h4>
                                <?php if (!empty($app['deskripsi'])): ?>
                                    <p class="text-muted mb-4 small" style="line-height: 1.6;">
                                        <?= nl2br(esc($app['deskripsi'])) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer bg-white border-0 text-center pb-4">
                                <a href="<?= esc($app['url']) ?>" class="btn btn-outline-primary px-4 rounded-pill w-100" target="_blank" rel="noopener noreferrer">
                                    Kunjungi Link <i class="bi bi-box-arrow-up-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-box display-1 mb-3 opacity-25"></i>
                <h5>Belum Ada Link Aplikasi</h5>
                <p>Saat ini belum ada aplikasi atau link terkait yang terdaftar di dalam sistem.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>
