<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<section class="page-header py-5" style="background: linear-gradient(135deg, var(--bs-primary) 0%, var(--site-secondary) 100%);">
    <div class="container text-center text-white">
        <h1 class="fw-bold mb-2">Profil &amp; Fasilitas</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('/') ?>" class="text-white-50">Beranda</a></li>
                <li class="breadcrumb-item active text-white">Profil &amp; Fasilitas</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Tab Navigation -->
<section class="bg-white shadow-sm sticky-top" style="top: var(--nav-height-sticky); z-index: 900; transition: top 0.3s;">
    <div class="container">
        <ul class="nav nav-pills gap-2 py-2 flex-wrap justify-content-center" id="profilTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-semibold text-nowrap" id="sejarah-tab" data-bs-toggle="pill"
                    data-bs-target="#tab-sejarah" type="button" role="tab">
                    <i class="bi bi-book me-1"></i>Sejarah
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold text-nowrap" id="visi-misi-tab" data-bs-toggle="pill"
                    data-bs-target="#tab-visi-misi" type="button" role="tab">
                    <i class="bi bi-bullseye me-1"></i>Visi &amp; Misi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold text-nowrap" id="sambutan-tab" data-bs-toggle="pill"
                    data-bs-target="#tab-sambutan" type="button" role="tab">
                    <i class="bi bi-person-badge me-1"></i>Sambutan Kepsek
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold text-nowrap" id="fasilitas-tab" data-bs-toggle="pill"
                    data-bs-target="#tab-fasilitas" type="button" role="tab">
                    <i class="bi bi-building me-1"></i>Fasilitas
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold text-nowrap" id="kepsek-tab" data-bs-toggle="pill"
                    data-bs-target="#tab-kepsek" type="button" role="tab">
                    <i class="bi bi-person-workspace me-1"></i>Kepala Sekolah
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold text-nowrap" id="galeri-tab" data-bs-toggle="pill"
                    data-bs-target="#tab-galeri" type="button" role="tab">
                    <i class="bi bi-images me-1"></i>Galeri
                </button>
            </li>
        </ul>
    </div>
</section>

<div class="tab-content" id="profilTabContent">

    <!-- Tab: Sejarah -->
    <div class="tab-pane fade show active" id="tab-sejarah" role="tabpanel">
        <section class="py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-9">
                        <div class="text-center mb-4">
                            <span class="badge text-bg-primary fs-6 px-3 py-2 mb-3">Sejarah Sekolah</span>
                            <h2 class="fw-bold"><?= esc(setting('site_name') ?? 'Sekolah Kami') ?></h2>
                        </div>

                        <?php $sejarah = setting('sejarah'); ?>
                        <?php if ($sejarah): ?>
                            <div class="lh-lg profil-richtext" style="font-size:1.08rem;color:#212529"><?= $sejarah ?></div>
                        <?php else: ?>
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-clock-history display-3 mb-3 d-block"></i>
                                <p>Konten sejarah belum diisi. Silakan lengkapi di panel admin melalui Pengaturan &rarr; Profil.</p>
                            </div>
                        <?php endif; ?>

                        <!-- Statistik dari pengaturan -->
                        <?php
                        $stats = [
                            [setting('stat_tahun_berdiri') ?: '25+',  'Tahun Berdiri',  'bi-calendar2-check', 'primary'],
                            [setting('stat_siswa')         ?: '1.000+','Siswa Aktif',   'bi-people-fill',     'success'],
                            [setting('stat_guru')          ?: '60+',  'Tenaga Pendidik','bi-person-badge',    'info'],
                            [setting('stat_prestasi')      ?: '50+',  'Prestasi Diraih','bi-trophy-fill',     'warning'],
                        ];
                        ?>
                        <div class="row g-3 mt-5">
                            <?php foreach ($stats as [$val, $label, $icon, $color]): ?>
                            <div class="col-6 col-md-3">
                                <div class="card border-0 shadow-sm text-center p-3 h-100">
                                    <i class="bi <?= $icon ?> text-<?= $color ?> fs-2 mb-2"></i>
                                    <div class="text-<?= $color ?> fs-2 fw-bold lh-1"><?= esc($val) ?></div>
                                    <div class="text-muted small mt-1"><?= $label ?></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Tab: Visi & Misi -->
    <div class="tab-pane fade" id="tab-visi-misi" role="tabpanel">
        <section class="py-5">
            <div class="container">
                <div class="text-center mb-5">
                    <span class="badge text-bg-primary fs-6 px-3 py-2 mb-3">Arah Pengembangan</span>
                    <h2 class="fw-bold">Visi &amp; Misi</h2>
                </div>
                <div class="row g-4 justify-content-center">
                    <!-- Visi -->
                    <div class="col-lg-5">
                        <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid var(--bs-primary) !important;">
                            <div class="card-body p-4 p-lg-5">
                                <div class="d-flex align-items-center mb-3 gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0"
                                        style="width:52px;height:52px;background:var(--bs-primary);">
                                        <i class="bi bi-eye fs-5"></i>
                                    </div>
                                    <h3 class="fw-bold mb-0 text-primary">VISI</h3>
                                </div>
                                <?php $visi = setting('visi'); ?>
                                <?php if ($visi): ?>
                                    <div class="lh-lg fs-5 fst-italic profil-richtext" style="color:#212529"><?= $visi ?></div>
                                <?php else: ?>
                                    <p class="text-muted">Visi belum diisi.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <!-- Misi -->
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid var(--accent-gold) !important;">
                            <div class="card-body p-4 p-lg-5">
                                <div class="d-flex align-items-center mb-3 gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0"
                                        style="width:52px;height:52px;background:var(--accent-gold);">
                                        <i class="bi bi-flag fs-5"></i>
                                    </div>
                                    <h3 class="fw-bold mb-0" style="color:var(--accent-gold)">MISI</h3>
                                </div>
                                <?php $misi = setting('misi'); ?>
                                <?php if ($misi): ?>
                                    <div class="lh-lg profil-richtext" style="color:#212529"><?= $misi ?></div>
                                <?php else: ?>
                                    <p class="text-muted">Misi belum diisi.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tujuan -->
                <?php 
                    $tujuan = setting('tujuan'); 
                    $isTujuanEmpty = empty(trim(str_replace(['<p><br></p>', '<p></p>', '&nbsp;'], '', $tujuan)));
                ?>
                <?php if (!$isTujuanEmpty): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm" style="border-top: 4px solid var(--bs-success) !important;">
                            <div class="card-body p-4 p-lg-5">
                                <div class="d-flex align-items-center mb-3 gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0"
                                        style="width:52px;height:52px;background:var(--bs-success);">
                                        <i class="bi bi-bullseye fs-5"></i>
                                    </div>
                                    <h3 class="fw-bold mb-0 text-success">TUJUAN</h3>
                                </div>
                                <div class="lh-lg profil-richtext" style="color:#212529"><?= $tujuan ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Nilai Sekolah -->
                <?php if (!empty($nilai_sekolah)): ?>
                <div class="row justify-content-center mt-5 mb-4">
                    <div class="col-12 text-center">
                        <h3 class="fw-bold mb-0">Nilai-Nilai Sekolah</h3>
                        <div class="mx-auto mt-2" style="width: 60px; height: 3px; background: var(--accent-gold); border-radius: 2px;"></div>
                    </div>
                </div>
                <div class="row g-4 justify-content-center">
                    <?php 
                    $colors = ['text-primary', 'text-danger', 'text-success', 'text-warning', 'text-info'];
                    foreach ($nilai_sekolah as $idx => $ns):
                        $iconColor = $colors[$idx % count($colors)];
                    ?>
                    <div class="col-md-4">
                        <div class="card border-0 bg-light text-center p-4 h-100 shadow-sm transition" style="border-bottom: 3px solid transparent;">
                            <i class="bi <?= esc($ns['icon']) ?> display-4 <?= $iconColor ?> mb-3"></i>
                            <h5 class="fw-bold"><?= esc($ns['nama']) ?></h5>
                            <p class="text-muted small mb-0"><?= esc($ns['deskripsi']) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <!-- Tab: Sambutan Kepsek -->
    <div class="tab-pane fade" id="tab-sambutan" role="tabpanel">
        <section class="py-5">
            <div class="container">
                <div class="text-center mb-5">
                    <span class="badge text-bg-primary fs-6 px-3 py-2 mb-3">Dari Pemimpin Kami</span>
                    <h2 class="fw-bold">Sambutan Kepala Sekolah</h2>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="card border-0 shadow-lg overflow-hidden">
                            <div class="row g-0">
                                <div class="col-md-4 text-center p-4 p-md-5 text-white d-flex flex-column align-items-center justify-content-center"
                                    style="background: linear-gradient(160deg, var(--bs-primary), var(--site-secondary));">
                                    <?php $fotoKepsek = setting('foto_kepsek'); ?>
                                    <?php if ($fotoKepsek): ?>
                                        <img src="<?= base_url('uploads/pengaturan/' . esc($fotoKepsek)) ?>"
                                            class="rounded-circle mb-3 border border-3 border-white shadow"
                                            style="width:140px;height:140px;object-fit:cover" alt="Kepala Sekolah">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-white mb-3 d-flex align-items-center justify-content-center"
                                            style="width:140px;height:140px;">
                                            <i class="bi bi-person-fill text-primary" style="font-size:4rem;"></i>
                                        </div>
                                    <?php endif; ?>
                                    <h5 class="fw-bold mb-1"><?= esc(setting('nama_kepsek') ?? 'Kepala Sekolah') ?></h5>
                                    <?php $nip = setting('nip_kepsek'); ?>
                                    <?php if ($nip): ?>
                                        <small class="text-white-50">NIP. <?= esc($nip) ?></small>
                                    <?php endif; ?>
                                    <span class="badge bg-white text-primary mt-2 fw-semibold">Kepala Sekolah</span>
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body p-4 p-lg-5">
                                        <i class="bi bi-quote text-primary display-3 opacity-25 d-block" style="line-height:1;margin-bottom:-1rem;"></i>
                                        <?php $sambutan = setting('sambutan_kepsek'); ?>
                                        <?php if ($sambutan): ?>
                                            <div class="lh-lg" style="color:#212529"><?= $sambutan ?></div>
                                        <?php else: ?>
                                            <div class="text-center py-5 text-muted">
                                                <i class="bi bi-chat-quote display-3 mb-3 d-block"></i>
                                                <p>Sambutan kepala sekolah belum diisi.</p>
                                            </div>
                                        <?php endif; ?>
                                        <div class="border-start border-4 border-primary ps-3 mt-4 text-muted small fst-italic">
                                            "Bersama membangun generasi unggul dan berkarakter."
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Tab: Fasilitas -->
    <div class="tab-pane fade" id="tab-fasilitas" role="tabpanel">
        <section class="py-5">
            <div class="container">
                <div class="text-center mb-5">
                    <span class="badge text-bg-primary fs-6 px-3 py-2 mb-3">Penunjang Belajar</span>
                    <h2 class="fw-bold">Fasilitas Sekolah</h2>
                    <p class="text-muted">Sarana dan prasarana berkualitas untuk mendukung proses belajar mengajar</p>
                </div>
                <?php if (!empty($fasilitas)): ?>
                    <div class="row g-4">
                        <?php foreach ($fasilitas as $f): ?>
                            <div class="col-sm-6 col-lg-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <?php if (!empty($f['foto'])): ?>
                                        <img src="<?= base_url('uploads/fasilitas/' . esc($f['foto'])) ?>"
                                            class="card-img-top" style="height:200px;object-fit:cover"
                                            alt="<?= esc($f['nama']) ?>">
                                    <?php else: ?>
                                        <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10" style="height:200px;">
                                            <i class="<?= esc($f['icon'] ?? 'bi bi-building') ?> text-primary" style="font-size:3.5rem;"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <div class="d-flex align-items-start gap-2 mb-2">
                                            <i class="<?= esc($f['icon'] ?? 'bi bi-building') ?> text-primary mt-1 flex-shrink-0"></i>
                                            <h5 class="card-title fw-bold mb-0"><?= esc($f['nama']) ?></h5>
                                        </div>
                                        <?php if (!empty($f['deskripsi'])): ?>
                                            <p class="card-text text-muted small"><?= esc(truncate_text($f['deskripsi'], 100)) ?></p>
                                        <?php endif; ?>
                                        <div class="d-flex gap-2 flex-wrap mt-2">
                                            <?php if (!empty($f['jumlah'])): ?>
                                                <span class="badge text-bg-light border"><i class="bi bi-hash me-1"></i><?= esc($f['jumlah']) ?> unit</span>
                                            <?php endif; ?>
                                            <?php if (!empty($f['kondisi'])): ?>
                                                <?php
                                                $kondisiClass = match($f['kondisi']) {
                                                    'baik'         => 'text-bg-success',
                                                    'rusak_ringan' => 'text-bg-warning',
                                                    'rusak_berat'  => 'text-bg-danger',
                                                    default        => 'text-bg-secondary'
                                                };
                                                $kondisiLabel = match($f['kondisi']) {
                                                    'baik'         => 'Baik',
                                                    'rusak_ringan' => 'Rusak Ringan',
                                                    'rusak_berat'  => 'Rusak Berat',
                                                    default        => esc($f['kondisi'])
                                                };
                                                ?>
                                                <span class="badge <?= $kondisiClass ?>"><?= $kondisiLabel ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-building display-3 mb-3 d-block"></i>
                        <p>Data fasilitas belum tersedia.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <!-- Tab: Kepala Sekolah dari Masa ke Masa -->
    <div class="tab-pane fade" id="tab-kepsek" role="tabpanel">
        <section class="py-5">
            <div class="container">
                <div class="text-center mb-5">
                    <span class="badge text-bg-primary fs-6 px-3 py-2 mb-3">Pemimpin Kami</span>
                    <h2 class="fw-bold">Kepala Sekolah dari Masa ke Masa</h2>
                    <p class="text-muted">Tokoh-tokoh yang telah mengabdi dan memimpin sekolah kami</p>
                </div>

                <?php if (!empty($kepala_sekolah)): ?>
                    <div class="kepsek-timeline position-relative">
                        <!-- garis vertikal -->
                        <div class="kepsek-timeline-line"></div>

                        <?php foreach ($kepala_sekolah as $i => $ks):
                            $aktif   = empty($ks['periode_selesai']);
                            $namaLen = trim(
                                ($ks['gelar_depan'] ? $ks['gelar_depan'] . ' ' : '') .
                                $ks['nama'] .
                                ($ks['gelar_belakang'] ? ', ' . $ks['gelar_belakang'] : '')
                            );
                        ?>
                        <div class="kepsek-item <?= ($i % 2 === 0) ? 'kepsek-left' : 'kepsek-right' ?>">
                            <!-- dot -->
                            <div class="kepsek-dot <?= $aktif ? 'kepsek-dot-active' : '' ?>"></div>
                            <div class="card border-0 shadow-sm kepsek-card <?= $aktif ? 'border-start border-3 border-success' : '' ?>">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <?php if (!empty($ks['foto'])): ?>
                                            <img src="<?= base_url('uploads/kepala_sekolah/' . esc($ks['foto'])) ?>"
                                                class="rounded-circle flex-shrink-0"
                                                style="width:72px;height:72px;object-fit:cover;border:3px solid <?= $aktif ? '#198754' : 'var(--bs-primary)' ?>"
                                                alt="<?= esc($ks['nama']) ?>">
                                        <?php else: ?>
                                            <div class="rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center"
                                                style="width:72px;height:72px;background:<?= $aktif ? '#d1e7dd' : '#e8edf5' ?>;border:3px solid <?= $aktif ? '#198754' : 'var(--bs-primary)' ?>">
                                                <i class="bi bi-person-fill" style="font-size:2rem;color:<?= $aktif ? '#198754' : 'var(--bs-primary)' ?>"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <h5 class="fw-bold mb-1"><?= esc($namaLen) ?></h5>
                                            <span class="badge <?= $aktif ? 'text-bg-success' : 'text-bg-light border' ?> fw-normal">
                                                <?= esc($ks['periode_mulai']) ?> –
                                                <?= $aktif
                                                    ? '<span>Sekarang</span>'
                                                    : esc($ks['periode_selesai'])
                                                ?>
                                            </span>
                                            <?php if ($aktif): ?>
                                                <span class="badge text-bg-success ms-1"><i class="bi bi-circle-fill me-1" style="font-size:.5rem"></i>Menjabat Saat Ini</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if (!empty($ks['keterangan'])): ?>
                                        <p class="text-muted mb-0 small lh-lg"><?= esc($ks['keterangan']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <style>
                    .kepsek-timeline { padding: 1rem 0; }
                    .kepsek-timeline-line {
                        position: absolute;
                        left: 50%; top: 0; bottom: 0;
                        width: 3px;
                        background: linear-gradient(to bottom, var(--bs-primary), #adb5bd);
                        transform: translateX(-50%);
                        z-index: 0;
                    }
                    .kepsek-item {
                        position: relative;
                        width: 47%;
                        margin-bottom: 2rem;
                        z-index: 1;
                    }
                    .kepsek-left  { margin-left: 0; margin-right: auto; text-align: right; }
                    .kepsek-right { margin-left: auto; margin-right: 0; }
                    .kepsek-dot {
                        position: absolute;
                        top: 28px;
                        width: 16px; height: 16px;
                        border-radius: 50%;
                        background: var(--bs-primary);
                        border: 3px solid #fff;
                        box-shadow: 0 0 0 3px var(--bs-primary);
                        z-index: 2;
                    }
                    .kepsek-left  .kepsek-dot { right: -8%; }
                    .kepsek-right .kepsek-dot { left: -8%; }
                    .kepsek-dot-active {
                        background: #198754;
                        box-shadow: 0 0 0 3px #198754, 0 0 0 6px rgba(25,135,84,.25);
                        animation: pulse-dot 2s infinite;
                    }
                    @keyframes pulse-dot {
                        0%, 100% { box-shadow: 0 0 0 3px #198754, 0 0 0 6px rgba(25,135,84,.25); }
                        50%       { box-shadow: 0 0 0 3px #198754, 0 0 0 10px rgba(25,135,84,.08); }
                    }
                    .kepsek-card { transition: transform .2s, box-shadow .2s; }
                    .kepsek-card:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.12) !important; }
                    /* Mobile: stacked single column */
                    @media (max-width: 767px) {
                        .kepsek-timeline-line { left: 20px; }
                        .kepsek-item { width: 100%; margin-left: 44px !important; margin-right: 0 !important; text-align: left !important; }
                        .kepsek-left .kepsek-dot,
                        .kepsek-right .kepsek-dot { left: -32px; right: auto; }
                    }
                    </style>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-person-badge display-3 mb-3 d-block"></i>
                        <p>Data kepala sekolah belum tersedia.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <!-- Tab: Galeri -->
    <div class="tab-pane fade" id="tab-galeri" role="tabpanel">
        <section class="py-5">
            <div class="container">
                <div class="text-center mb-4">
                    <span class="badge text-bg-primary fs-6 px-3 py-2 mb-3">Dokumentasi</span>
                    <h2 class="fw-bold">Galeri Sekolah</h2>
                    <p class="text-muted">Momen-momen berkesan di lingkungan sekolah kami</p>
                </div>

                <?php
                $galeriSections = [];
                if (!empty($galeri_fasilitas))  $galeriSections['Fasilitas']          = $galeri_fasilitas;
                if (!empty($galeri_lingkungan)) $galeriSections['Lingkungan Sekolah'] = $galeri_lingkungan;
                if (empty($galeriSections) && !empty($galeri_unggulan)) $galeriSections['Unggulan'] = $galeri_unggulan;

                ?>

                <?php if (!empty($galeriSections)): ?>
                    <?php if (count($galeriSections) > 1): ?>
                        <!-- Filter tabs antar kategori -->
                        <ul class="nav nav-pills justify-content-center gap-2 mb-4" id="galeriCatTabs">
                            <?php $first = true; foreach ($galeriSections as $catName => $items): ?>
                                <li class="nav-item">
                                    <button class="nav-link <?= $first ? 'active' : '' ?>"
                                        data-galeri-cat="<?= esc($catName) ?>">
                                        <?= esc($catName) ?> <span class="badge bg-white text-primary ms-1"><?= count($items) ?></span>
                                    </button>
                                </li>
                            <?php $first = false; endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <?php $first = true; foreach ($galeriSections as $catName => $items): ?>
                        <div class="galeri-cat-panel <?= !$first ? 'd-none' : '' ?>" data-cat="<?= esc($catName) ?>">
                            <div class="row g-3">
                                <?php foreach ($items as $g): ?>
                                    <div class="col-6 col-md-4 col-lg-3">
                                        <a href="#" class="d-block rounded overflow-hidden shadow-sm galeri-thumb-link"
                                            data-src="<?= base_url('uploads/galeri/' . esc($g['file_path'])) ?>"
                                            data-caption="<?= esc($g['judul']) ?>">
                                            <?php $thumb = !empty($g['thumbnail']) ? $g['thumbnail'] : $g['file_path']; ?>
                                            <img src="<?= base_url('uploads/galeri/' . esc($thumb)) ?>"
                                                class="w-100 galeri-thumb-img" style="height:180px;object-fit:cover;transition:.3s;"
                                                alt="<?= esc($g['judul']) ?>">
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php $first = false; endforeach; ?>

                    <script>
                    document.querySelectorAll('[data-galeri-cat]').forEach(btn => {
                        btn.addEventListener('click', function() {
                            document.querySelectorAll('[data-galeri-cat]').forEach(b => b.classList.remove('active'));
                            this.classList.add('active');
                            const cat = this.dataset.galeriCat;
                            document.querySelectorAll('.galeri-cat-panel').forEach(p => {
                                p.classList.toggle('d-none', p.dataset.cat !== cat);
                            });
                        });
                    });
                    </script>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-images display-3 mb-3 d-block"></i>
                        <p>Galeri belum tersedia.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>

</div><!-- /tab-content -->

<!-- Galeri Modal -->
<div class="modal fade" id="galeriModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark border-0">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title text-white" id="galeriModalCaption"></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-2">
                <img id="galeriModalImg" src="" class="img-fluid rounded" style="max-height:80vh;" alt="">
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Galeri lightbox
document.querySelectorAll('.galeri-thumb-link').forEach(function(el) {
    el.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('galeriModalImg').src = this.dataset.src;
        document.getElementById('galeriModalCaption').textContent = this.dataset.caption;
        new bootstrap.Modal(document.getElementById('galeriModal')).show();
    });
});
document.querySelectorAll('.galeri-thumb-img').forEach(function(img) {
    img.addEventListener('mouseenter', function() { this.style.transform = 'scale(1.05)'; });
    img.addEventListener('mouseleave', function() { this.style.transform = 'scale(1)'; });
});
// Activate tab from URL hash
const hash = window.location.hash;
if (hash) {
    const tab = document.querySelector('[data-bs-target="' + hash + '"]');
    if (tab) bootstrap.Tab.getOrCreateInstance(tab).show();
}
document.querySelectorAll('[data-bs-toggle="pill"]').forEach(function(tab) {
    tab.addEventListener('shown.bs.tab', function(e) {
        history.replaceState(null, null, e.target.dataset.bsTarget);
    });
});
</script>
<?= $this->endSection() ?>
