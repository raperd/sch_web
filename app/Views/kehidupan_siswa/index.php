<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<section class="page-header py-5" style="background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-secondary) 100%); margin-top: var(--nav-height);">
    <div class="container text-center text-white">
        <h1 class="fw-bold mb-2">Kehidupan Siswa &amp; OSIS</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('/') ?>" class="text-white-50">Beranda</a></li>
                <li class="breadcrumb-item active text-white">Kehidupan Siswa</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Kegiatan Upcoming -->
<?php if (!empty($upcoming)): ?>
<section class="py-4 bg-warning bg-opacity-10 border-bottom">
    <div class="container">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-calendar-event text-warning fs-4"></i>
            <h5 class="fw-bold mb-0">Kegiatan Mendatang</h5>
        </div>
        <div class="row g-3">
            <?php foreach ($upcoming as $k): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 border-start border-4 border-warning shadow-sm h-100">
                        <div class="card-body py-3">
                            <div class="d-flex gap-3 align-items-start">
                                <div class="text-center flex-shrink-0 bg-warning bg-opacity-10 rounded p-2" style="min-width:52px;">
                                    <div class="fw-bold text-warning fs-5 lh-1"><?= date('d', strtotime($k['tanggal'])) ?></div>
                                    <small class="text-muted text-uppercase"><?= date('M', strtotime($k['tanggal'])) ?></small>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1"><?= esc($k['judul']) ?></h6>
                                    <?php if (!empty($k['lokasi'])): ?>
                                        <small class="text-muted"><i class="bi bi-geo-alt me-1"></i><?= esc($k['lokasi']) ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Kegiatan Grid -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge text-bg-primary fs-6 px-3 py-2 mb-3">Dokumentasi Kegiatan</span>
            <h2 class="fw-bold">Kegiatan Sekolah</h2>
            <p class="text-muted">Berbagai kegiatan yang memperkaya pengalaman belajar dan berkarya siswa</p>
        </div>

        <?php if (!empty($kegiatan)): ?>
            <div class="row g-4">
                <?php foreach ($kegiatan as $k): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100 overflow-hidden">
                            <?php if (!empty($k['foto'])): ?>
                                <img src="<?= base_url('uploads/kegiatan/' . esc($k['foto'])) ?>"
                                    class="card-img-top" style="height:200px;object-fit:cover;"
                                    alt="<?= esc($k['judul']) ?>">
                            <?php else: ?>
                                <?php
                                $tipeIcons = [
                                    'event'   => ['bi-calendar-check', 'primary'],
                                    'lomba'   => ['bi-trophy', 'warning'],
                                    'sosial'  => ['bi-heart', 'danger'],
                                    'osis'    => ['bi-people', 'success'],
                                    'lainnya' => ['bi-star', 'secondary'],
                                ];
                                $ti = $tipeIcons[$k['tipe'] ?? 'lainnya'] ?? ['bi-star', 'secondary'];
                                ?>
                                <div class="d-flex align-items-center justify-content-center bg-<?= $ti[1] ?> bg-opacity-10" style="height:200px;">
                                    <i class="bi <?= $ti[0] ?> text-<?= $ti[1] ?>" style="font-size:3rem;"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <?php
                                    $tipeLabel = [
                                        'event'  => ['Acara',     'primary'],
                                        'lomba'  => ['Lomba',     'warning'],
                                        'sosial' => ['Sosial',    'danger'],
                                        'osis'   => ['OSIS',      'success'],
                                        'lainnya'=> ['Lainnya',   'secondary'],
                                    ];
                                    $tl = $tipeLabel[$k['tipe'] ?? 'lainnya'] ?? ['Lainnya', 'secondary'];
                                    ?>
                                    <span class="badge text-bg-<?= $tl[1] ?>"><?= $tl[0] ?></span>
                                    <?php
                                    $statusLabel = [
                                        'upcoming' => ['Mendatang', 'warning'],
                                        'ongoing'  => ['Berlangsung', 'success'],
                                        'selesai'  => ['Selesai', 'secondary'],
                                    ];
                                    $sl = $statusLabel[$k['status'] ?? 'selesai'] ?? ['Selesai', 'secondary'];
                                    ?>
                                    <span class="badge text-bg-<?= $sl[1] ?>"><?= $sl[0] ?></span>
                                </div>
                                <h6 class="card-title fw-bold"><?= esc($k['judul']) ?></h6>
                                <?php if (!empty($k['deskripsi'])): ?>
                                    <p class="card-text text-muted small"><?= esc(truncate_text($k['deskripsi'], 90)) ?></p>
                                <?php endif; ?>
                                <div class="d-flex flex-wrap gap-2 mt-auto pt-1">
                                    <small class="text-muted"><i class="bi bi-calendar3 me-1"></i><?= format_tanggal($k['tanggal'], 'short') ?></small>
                                    <?php if (!empty($k['lokasi'])): ?>
                                        <small class="text-muted"><i class="bi bi-geo-alt me-1"></i><?= esc(truncate_text($k['lokasi'], 30)) ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-calendar-x display-3 mb-3 d-block"></i>
                <p>Belum ada data kegiatan.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Ekstrakurikuler -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge text-bg-success fs-6 px-3 py-2 mb-3">Pengembangan Diri</span>
            <h2 class="fw-bold">Ekstrakurikuler</h2>
            <p class="text-muted">Wadah kreativitas dan pengembangan bakat di luar jam pelajaran</p>
        </div>

        <?php if (!empty($ekskul)): ?>
            <div class="row g-4">
                <?php foreach ($ekskul as $e): ?>
                    <div class="col-sm-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100">
                            <?php if (!empty($e['foto'])): ?>
                                <img src="<?= base_url('uploads/ekstrakurikuler/' . esc($e['foto'])) ?>"
                                    class="card-img-top" style="height:180px;object-fit:cover;"
                                    alt="<?= esc($e['nama']) ?>">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center bg-success bg-opacity-10" style="height:180px;">
                                    <i class="bi bi-star-fill text-success" style="font-size:3rem;"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title fw-bold"><?= esc($e['nama']) ?></h5>
                                <?php if (!empty($e['deskripsi'])): ?>
                                    <p class="card-text text-muted small"><?= esc(truncate_text($e['deskripsi'], 100)) ?></p>
                                <?php endif; ?>
                                <div class="d-flex flex-column gap-1 mt-2">
                                    <?php if (!empty($e['jadwal'])): ?>
                                        <small class="text-muted"><i class="bi bi-clock me-1"></i><?= esc($e['jadwal']) ?></small>
                                    <?php endif; ?>
                                    <?php if (!empty($e['pembina'])): ?>
                                        <small class="text-muted"><i class="bi bi-person me-1"></i>Pembina: <?= esc($e['pembina']) ?></small>
                                    <?php endif; ?>
                                    <?php if (!empty($e['prestasi'])): ?>
                                        <small class="text-success fw-semibold"><i class="bi bi-trophy me-1"></i><?= esc(truncate_text($e['prestasi'], 60)) ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-star display-3 mb-3 d-block"></i>
                <p>Belum ada data ekstrakurikuler.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- OSIS Section -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge text-bg-primary fs-6 px-3 py-2 mb-3">Organisasi Siswa</span>
                <h2 class="fw-bold mb-3">OSIS &amp; Organisasi Intra Sekolah</h2>
                <p class="text-muted lh-lg">
                    Organisasi Intra Sekolah (OSIS) adalah wadah pengembangan kepemimpinan, kreativitas, dan jiwa sosial siswa. OSIS berperan aktif dalam menyelenggarakan berbagai kegiatan sekolah, mulai dari peringatan hari besar nasional, pentas seni, bakti sosial, hingga lomba-lomba antar kelas.
                </p>
                <ul class="list-unstyled">
                    <?php foreach ([
                        'Menyelenggarakan acara dan peringatan hari nasional',
                        'Menjembatani aspirasi siswa kepada pihak sekolah',
                        'Menggelar festival seni dan kreativitas tahunan',
                        'Mengoordinasikan kegiatan ekstrakurikuler',
                        'Program bakti sosial dan peduli lingkungan',
                    ] as $item): ?>
                        <li class="mb-2 d-flex gap-2">
                            <i class="bi bi-check-circle-fill text-primary mt-1 flex-shrink-0"></i>
                            <span class="text-secondary"><?= $item ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-lg-6">
                <!-- Social Media Embed Placeholder -->
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="card-header bg-primary text-white">
                        <i class="bi bi-instagram me-2"></i>Follow OSIS kami di Instagram
                    </div>
                    <div class="card-body text-center py-5 bg-light">
                        <?php $igUrl = setting('instagram_url'); ?>
                        <?php if ($igUrl): ?>
                            <i class="bi bi-instagram text-danger" style="font-size:4rem;"></i>
                            <p class="mt-3 mb-3 text-muted">Ikuti kegiatan terkini OSIS dan siswa di Instagram kami</p>
                            <a href="<?= esc($igUrl) ?>" target="_blank" rel="noopener"
                                class="btn btn-danger btn-lg fw-semibold">
                                <i class="bi bi-instagram me-2"></i>Follow di Instagram
                            </a>
                        <?php else: ?>
                            <i class="bi bi-instagram text-muted" style="font-size:4rem;"></i>
                            <p class="mt-3 text-muted">Akun Instagram belum dikonfigurasi.</p>
                        <?php endif; ?>
                    </div>
                    <?php $ytUrl = setting('youtube_url'); ?>
                    <?php if ($ytUrl): ?>
                        <div class="card-footer text-center">
                            <a href="<?= esc($ytUrl) ?>" target="_blank" rel="noopener" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-youtube me-1"></i>Subscribe YouTube Kami
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
