<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<section class="page-header py-5" style="background: linear-gradient(135deg, var(--bs-primary) 0%, var(--site-secondary) 100%);">
    <div class="container text-center text-white">
        <h1 class="fw-bold mb-2">Berita &amp; Artikel</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('/') ?>" class="text-white-50">Beranda</a></li>
                <li class="breadcrumb-item active text-white">Berita &amp; Artikel</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Content -->
<section class="py-5">
    <div class="container">

        <?php if (!empty($artikel)): ?>
            <!-- Artikel Featured (baris pertama) -->
            <?php $featured = array_shift($artikel); ?>
            <?php if ($featured): ?>
                <div class="card border-0 shadow-sm mb-5 overflow-hidden">
                    <div class="row g-0">
                        <div class="col-md-6">
                            <?php if (!empty($featured['thumbnail'])): ?>
                                <img src="<?= base_url('uploads/artikel/' . esc($featured['thumbnail'])) ?>"
                                    class="img-fluid h-100 w-100" style="object-fit:cover;min-height:280px;"
                                    alt="<?= esc($featured['judul']) ?>">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 h-100" style="min-height:280px;">
                                    <i class="bi bi-newspaper text-primary" style="font-size:4rem;"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 d-flex flex-column">
                            <div class="card-body p-4 p-lg-5 d-flex flex-column">
                                <div class="d-flex gap-2 mb-3 flex-wrap">
                                    <?php if (!empty($featured['nama_kategori'])): ?>
                                        <span class="badge text-bg-primary"><?= esc($featured['nama_kategori']) ?></span>
                                    <?php endif; ?>
                                    <?php if ($featured['is_featured']): ?>
                                        <span class="badge text-bg-warning"><i class="bi bi-star-fill me-1"></i>Pilihan</span>
                                    <?php endif; ?>
                                </div>
                                <h2 class="fw-bold h4 mb-3">
                                    <a href="<?= base_url('berita/' . esc($featured['slug'])) ?>"
                                        class="text-decoration-none text-dark stretched-link">
                                        <?= esc($featured['judul']) ?>
                                    </a>
                                </h2>
                                <?php if (!empty($featured['ringkasan'])): ?>
                                    <p class="text-muted lh-lg"><?= esc(truncate_text($featured['ringkasan'], 200)) ?></p>
                                <?php endif; ?>
                                <div class="mt-auto pt-3 border-top d-flex flex-wrap gap-3 text-muted small">
                                    <?php if (!empty($featured['penulis'])): ?>
                                        <span><i class="bi bi-person me-1"></i><?= esc($featured['penulis']) ?></span>
                                    <?php endif; ?>
                                    <span><i class="bi bi-calendar3 me-1"></i><?= format_tanggal($featured['published_at'] ?? $featured['created_at'], 'long') ?></span>
                                    <span><i class="bi bi-eye me-1"></i><?= number_format($featured['view_count'] ?? 0) ?> dibaca</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Grid Artikel -->
            <?php if (!empty($artikel)): ?>
                <div class="row g-4">
                    <?php foreach ($artikel as $a): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card border-0 shadow-sm h-100 news-card">
                                <?php if (!empty($a['thumbnail'])): ?>
                                    <img src="<?= base_url('uploads/artikel/' . esc($a['thumbnail'])) ?>"
                                        class="card-img-top" style="height:190px;object-fit:cover;"
                                        alt="<?= esc($a['judul']) ?>">
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center bg-light" style="height:190px;">
                                        <i class="bi bi-newspaper text-muted" style="font-size:2.5rem;"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex gap-2 mb-2">
                                        <?php if (!empty($a['nama_kategori'])): ?>
                                            <span class="badge text-bg-primary small"><?= esc($a['nama_kategori']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <h5 class="card-title fw-bold">
                                        <a href="<?= base_url('berita/' . esc($a['slug'])) ?>"
                                            class="text-decoration-none text-dark stretched-link">
                                            <?= esc(truncate_text($a['judul'], 80)) ?>
                                        </a>
                                    </h5>
                                    <?php if (!empty($a['ringkasan'])): ?>
                                        <p class="card-text text-muted small"><?= esc(truncate_text($a['ringkasan'], 110)) ?></p>
                                    <?php endif; ?>
                                    <div class="mt-auto pt-2 border-top text-muted" style="font-size:.8rem;">
                                        <?php if (!empty($a['penulis'])): ?>
                                            <div class="mb-1"><i class="bi bi-person me-1"></i><?= esc($a['penulis']) ?></div>
                                        <?php endif; ?>
                                        <div class="d-flex justify-content-between">
                                            <span><i class="bi bi-calendar3 me-1"></i><?= format_tanggal($a['published_at'] ?? $a['created_at'], 'short') ?></span>
                                            <span><i class="bi bi-eye me-1"></i><?= number_format($a['view_count'] ?? 0) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Paginasi -->
            <?php if (isset($pager)): ?>
                <div class="d-flex justify-content-center mt-5">
                    <?= $pager->links('artikel', 'default_full') ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-newspaper display-3 mb-3 d-block"></i>
                <h5>Belum ada artikel yang dipublikasikan</h5>
                <p>Nantikan artikel dan berita terbaru dari kami.</p>
                <a href="<?= base_url('/') ?>" class="btn btn-primary">Kembali ke Beranda</a>
            </div>
        <?php endif; ?>

    </div>
</section>

<?= $this->endSection() ?>
