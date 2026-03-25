<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<!-- ============================================================
     HERO SECTION
     ============================================================ -->
<section class="hero-section" id="hero">
    <!-- Background -->
    <div class="hero-bg" id="heroBg"
         style="background-image: url('<?= setting('hero_image_path') ? base_url('uploads/pengaturan/' . setting('hero_image_path')) : base_url('assets/images/placeholder/hero-default.jpg') ?>')">
    </div>
    <div class="hero-overlay"></div>

    <div class="container hero-content">
        <div class="row align-items-center min-vh-80">
            <div class="col-12 col-lg-7">
                <div class="hero-badge">
                    <i class="bi bi-patch-check-fill me-1"></i>
                    Akreditasi <?= esc(setting('akreditasi') ?? 'A') ?> &nbsp;|&nbsp; TA <?= esc(setting('tahun_ajaran_aktif') ?? '') ?>
                </div>
                <h1 class="hero-title mb-3">
                    <?= esc(setting('hero_judul') ?? 'Selamat Datang di ' . (setting('site_name') ?? 'Sekolah Kami')) ?>
                </h1>
                <p class="hero-subtitle mb-4">
                    <?= esc(setting('hero_subjudul') ?? setting('site_tagline') ?? 'Membentuk Generasi Unggul dan Berkarakter') ?>
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?= site_url('ppdb') ?>" class="btn btn-warning btn-lg px-4 fw-semibold">
                        <i class="bi bi-clipboard-check me-2"></i>Info SPMB
                    </a>
                    <a href="<?= site_url('profil') ?>" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-building me-2"></i>Profil Sekolah
                    </a>
                </div>

                <!-- Stats -->
                <div class="hero-stats d-flex gap-4 mt-5">
                    <div class="stat-item">
                        <div class="stat-number">1000+</div>
                        <div class="stat-label">Siswa Aktif</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">60+</div>
                        <div class="stat-label">Tenaga Pendidik</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">20+</div>
                        <div class="stat-label">Ekstrakurikuler</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">Akred. <?= esc(setting('akreditasi') ?? 'A') ?></div>
                        <div class="stat-label">BAN-S/M</div>
                    </div>
                </div>
            </div>

            <?php if (setting('hero_video_url')): ?>
            <div class="col-12 col-lg-5 d-none d-lg-block">
                <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow-lg">
                    <iframe src="<?= esc(str_replace('watch?v=', 'embed/', setting('hero_video_url'))) ?>?autoplay=1&mute=1&loop=1"
                            title="Video profil sekolah" allowfullscreen
                            allow="autoplay; encrypted-media"></iframe>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <a href="#quick-links" class="hero-scroll-btn">
        <i class="bi bi-chevron-double-down fs-4"></i>
    </a>
</section>

<!-- ============================================================
     QUICK LINKS
     ============================================================ -->
<section class="quick-links-section py-0" id="quick-links">
    <div class="container">
        <div class="row g-3 justify-content-center">
            <?php foreach ($quick_links as $ql): ?>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="<?= site_url(ltrim($ql['url'], '/')) ?>"
                   target="<?= esc($ql['target']) ?>"
                   class="quick-link-card h-100">
                    <div class="ql-icon text-<?= esc($ql['warna'] ?? 'primary') ?>">
                        <i class="<?= esc($ql['icon'] ?? 'bi-link') ?>"></i>
                    </div>
                    <div class="ql-label"><?= esc($ql['label']) ?></div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     BERITA TERBARU
     ============================================================ -->
<section class="py-5 mt-4">
    <div class="container">
        <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h2 class="section-title mb-3">Berita & Artikel</h2>
                <p class="text-muted mt-3 mb-0">Informasi terkini dari lingkungan sekolah</p>
            </div>
            <a href="<?= site_url('berita') ?>" class="btn btn-outline-primary">
                Semua Berita <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <?php if (empty($berita_terbaru)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-newspaper fs-1 d-block mb-3 opacity-25"></i>
                <p>Belum ada berita yang diterbitkan.</p>
            </div>
        <?php else: ?>
        <div class="row g-4">
            <!-- Artikel Utama -->
            <?php if (! empty($berita_utama)): $utama = $berita_utama[0]; ?>
            <div class="col-12 col-lg-5">
                <a href="<?= site_url('berita/' . $utama['slug']) ?>" class="text-decoration-none">
                    <div class="news-card card h-100">
                        <div class="position-relative overflow-hidden" style="height:280px">
                            <?php if ($utama['thumbnail']): ?>
                                <img src="<?= base_url('uploads/artikel/' . $utama['thumbnail']) ?>"
                                     class="w-100 h-100 object-fit-cover"
                                     alt="<?= esc($utama['judul']) ?>">
                            <?php else: ?>
                                <div class="w-100 h-100 bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-newspaper text-primary opacity-25" style="font-size:4rem"></i>
                                </div>
                            <?php endif; ?>
                            <span class="position-absolute top-0 start-0 m-3 badge bg-warning text-dark">
                                <i class="bi bi-star-fill me-1"></i>Utama
                            </span>
                        </div>
                        <div class="card-body">
                            <span class="badge-kategori badge bg-primary bg-opacity-10 text-primary mb-2">
                                <?= esc($utama['kategori_nama'] ?? 'Berita') ?>
                            </span>
                            <h5 class="card-title fw-bold text-dark"><?= esc($utama['judul']) ?></h5>
                            <p class="card-text text-muted small"><?= esc(truncate_text($utama['ringkasan'] ?? $utama['konten'], 120)) ?></p>
                        </div>
                        <div class="card-footer bg-transparent border-0 d-flex justify-content-between small text-muted">
                            <span><i class="bi bi-calendar3 me-1"></i><?= format_tanggal($utama['published_at'] ?? $utama['created_at']) ?></span>
                            <span><i class="bi bi-eye me-1"></i><?= number_format($utama['view_count']) ?></span>
                        </div>
                    </div>
                </a>
            </div>
            <?php endif; ?>

            <!-- Artikel Lainnya -->
            <div class="col-12 col-lg-7">
                <div class="row g-3">
                    <?php
                    $others = array_filter($berita_terbaru, fn($a) => empty($berita_utama) || $a['id'] !== $berita_utama[0]['id']);
                    foreach (array_slice($others, 0, 4) as $art):
                    ?>
                    <div class="col-12 col-sm-6">
                        <a href="<?= site_url('berita/' . $art['slug']) ?>" class="text-decoration-none">
                            <div class="news-card card h-100">
                                <div class="position-relative overflow-hidden" style="height:160px">
                                    <?php if ($art['thumbnail']): ?>
                                        <img src="<?= base_url('uploads/artikel/' . $art['thumbnail']) ?>"
                                             class="w-100 h-100 object-fit-cover"
                                             alt="<?= esc($art['judul']) ?>">
                                    <?php else: ?>
                                        <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                            <i class="bi bi-newspaper text-secondary opacity-25 fs-1"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body py-2">
                                    <span class="badge-kategori badge bg-primary bg-opacity-10 text-primary mb-1" style="font-size:.65rem">
                                        <?= esc($art['kategori_nama'] ?? '') ?>
                                    </span>
                                    <h6 class="card-title fw-semibold text-dark mb-1 lh-sm"><?= esc(truncate_text($art['judul'], 60)) ?></h6>
                                    <small class="text-muted"><?= format_tanggal($art['published_at'] ?? $art['created_at'], 'short') ?></small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ============================================================
     SPMB BANNER
     ============================================================ -->
<?php if (setting('ppdb_status') == '1' || setting('ppdb_link_external')): ?>
<section class="ppdb-banner py-5">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-12 col-md-8 text-white">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <i class="bi bi-clipboard-check-fill fs-2 text-warning"></i>
                    <h3 class="mb-0 fw-bold">SPMB <?= esc(setting('ppdb_tahun') ?? date('Y') . '/' . (date('Y') + 1)) ?></h3>
                </div>
                <p class="mb-0 opacity-75">Sistem Penerimaan Murid Baru sedang dibuka. Daftarkan putra-putri Anda sekarang melalui portal resmi.</p>
            </div>
            <div class="col-12 col-md-4 d-flex gap-2 justify-content-md-end flex-wrap">
                <a href="<?= site_url('ppdb') ?>" class="btn btn-light btn-lg px-4">
                    <i class="bi bi-info-circle me-2"></i>Panduan
                </a>
                <?php if (setting('ppdb_link_external') && setting('ppdb_link_external') !== '#'): ?>
                <a href="<?= esc(setting('ppdb_link_external')) ?>" target="_blank" rel="noopener" class="btn btn-warning btn-lg px-4 fw-bold">
                    <i class="bi bi-box-arrow-up-right me-2"></i>Daftar Sekarang
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ============================================================
     PRESTASI UNGGULAN
     ============================================================ -->
<?php if (!empty($prestasi_unggulan)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-4">
            <span class="badge text-bg-warning text-dark fs-6 px-3 py-2 mb-2">
                <i class="bi bi-star-fill me-1"></i>Kebanggaan Kami
            </span>
            <h2 class="section-title mx-auto">Prestasi Terkini</h2>
        </div>
        <div class="row g-3 mt-2">
            <?php
            $tingkatColor = ['sekolah'=>'secondary','kecamatan'=>'info','kota_kabupaten'=>'primary','provinsi'=>'warning','nasional'=>'danger','internasional'=>'dark'];
            foreach ($prestasi_unggulan as $p):
                $color = $tingkatColor[$p['tingkat']] ?? 'secondary';
                $tingkatLabel = ucwords(str_replace('_', ' ', $p['tingkat']));
            ?>
                <div class="col-sm-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4 d-flex gap-3 align-items-start">
                            <div class="rounded-circle bg-warning bg-opacity-15 d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:52px;height:52px;">
                                <i class="bi bi-trophy-fill text-warning fs-5"></i>
                            </div>
                            <div>
                                <div class="d-flex gap-1 mb-1 flex-wrap">
                                    <span class="badge text-bg-<?= $color ?> small"><?= $tingkatLabel ?></span>
                                    <span class="badge text-bg-<?= $p['kategori']==='akademik'?'success':'warning text-dark' ?> small">
                                        <?= $p['kategori']==='akademik'?'Akademik':'Non-Akademik' ?>
                                    </span>
                                </div>
                                <h6 class="fw-bold mb-1 small"><?= esc($p['judul']) ?></h6>
                                <?php if (!empty($p['nama_siswa'])): ?>
                                    <div class="text-muted" style="font-size:.75rem"><i class="bi bi-person me-1"></i><?= esc($p['nama_siswa']) ?></div>
                                <?php endif; ?>
                                <div class="text-muted" style="font-size:.75rem"><i class="bi bi-calendar me-1"></i><?= esc($p['tahun']) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?= site_url('prestasi') ?>" class="btn btn-outline-primary px-4">
                <i class="bi bi-trophy me-2"></i>Lihat Semua Prestasi
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ============================================================
     TENTANG SEKOLAH (MINI)
     ============================================================ -->
<section class="py-5 section-light">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-12 col-lg-5">
                <h2 class="section-title mb-4">Tentang Sekolah</h2>
                <p class="text-muted"><?= esc(truncate_text(setting('sejarah') ?? 'Sekolah kami berdiri dengan komitmen menghasilkan lulusan berkualitas.', 300)) ?></p>
                <div class="d-flex flex-wrap gap-3 mt-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-patch-check-fill text-primary fs-4"></i>
                        <div>
                            <div class="fw-bold small">Akreditasi <?= esc(setting('akreditasi') ?? 'A') ?></div>
                            <div class="text-muted" style="font-size:.75rem">BAN-S/M</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-calendar-check-fill text-success fs-4"></i>
                        <div>
                            <div class="fw-bold small">TA <?= esc(setting('tahun_ajaran_aktif') ?? '') ?></div>
                            <div class="text-muted" style="font-size:.75rem">Tahun Ajaran</div>
                        </div>
                    </div>
                </div>
                <a href="<?= site_url('profil') ?>" class="btn btn-primary mt-4">
                    Selengkapnya <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
            <div class="col-12 col-lg-7">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="card border-0 bg-primary text-white rounded-3 p-4 text-center h-100">
                            <div class="display-6 fw-bold mb-1">1000+</div>
                            <div class="small opacity-75">Siswa Aktif</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 bg-warning text-dark rounded-3 p-4 text-center h-100">
                            <div class="display-6 fw-bold mb-1">60+</div>
                            <div class="small">Guru & Staf</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 bg-success text-white rounded-3 p-4 text-center h-100">
                            <div class="display-6 fw-bold mb-1">20+</div>
                            <div class="small opacity-75">Ekstrakurikuler</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 bg-info text-white rounded-3 p-4 text-center h-100">
                            <div class="display-6 fw-bold mb-1">100%</div>
                            <div class="small opacity-75">Kelulusan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
