<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<section class="page-header py-5" style="background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-secondary) 100%);">
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
<section class="bg-white shadow-sm sticky-top" style="top: var(--nav-height); z-index: 900;">
    <div class="container">
        <ul class="nav nav-pills gap-1 py-2 overflow-auto flex-nowrap" id="profilTab" role="tablist">
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
                            <div class="lh-lg text-secondary fs-5"><?= $sejarah ?></div>
                        <?php else: ?>
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-clock-history display-3 mb-3 d-block"></i>
                                <p>Konten sejarah belum diisi. Silakan lengkapi di panel admin melalui Pengaturan &rarr; Profil.</p>
                            </div>
                        <?php endif; ?>

                        <!-- Statistik Singkat -->
                        <div class="row g-3 mt-5">
                            <div class="col-6 col-md-3">
                                <div class="card border-0 shadow-sm text-center p-3 h-100">
                                    <div class="text-primary fs-1 fw-bold">25+</div>
                                    <div class="text-muted small">Tahun Berdiri</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="card border-0 shadow-sm text-center p-3 h-100">
                                    <div class="text-primary fs-1 fw-bold">1.200+</div>
                                    <div class="text-muted small">Siswa Aktif</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="card border-0 shadow-sm text-center p-3 h-100">
                                    <div class="text-primary fs-1 fw-bold">80+</div>
                                    <div class="text-muted small">Tenaga Pendidik</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="card border-0 shadow-sm text-center p-3 h-100">
                                    <div class="text-primary fs-1 fw-bold"><?= esc(setting('akreditasi') ?? 'A') ?></div>
                                    <div class="text-muted small">Akreditasi</div>
                                </div>
                            </div>
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
                                    <p class="lh-lg text-secondary fs-5 fst-italic">"<?= esc($visi) ?>"</p>
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
                                    <div class="lh-lg text-secondary"><?= $misi ?></div>
                                <?php else: ?>
                                    <p class="text-muted">Misi belum diisi.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Nilai Sekolah -->
                <div class="row g-4 mt-2">
                    <div class="col-md-4">
                        <div class="card border-0 bg-light text-center p-4 h-100">
                            <i class="bi bi-award display-4 text-primary mb-3"></i>
                            <h5 class="fw-bold">Keunggulan</h5>
                            <p class="text-muted small mb-0">Mendorong setiap siswa mencapai potensi terbaik di bidang akademik dan non-akademik.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 bg-light text-center p-4 h-100">
                            <i class="bi bi-heart display-4 text-danger mb-3"></i>
                            <h5 class="fw-bold">Karakter</h5>
                            <p class="text-muted small mb-0">Membentuk generasi berkarakter, berakhlak mulia, dan berjiwa Pancasila.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 bg-light text-center p-4 h-100">
                            <i class="bi bi-globe display-4 text-success mb-3"></i>
                            <h5 class="fw-bold">Inovasi</h5>
                            <p class="text-muted small mb-0">Mempersiapkan siswa menjadi warga global yang kreatif, inovatif, dan adaptif.</p>
                        </div>
                    </div>
                </div>
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
                                    style="background: linear-gradient(160deg, var(--bs-primary), var(--bs-secondary));">
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
                                            <div class="lh-lg text-secondary"><?= $sambutan ?></div>
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

    <!-- Tab: Galeri -->
    <div class="tab-pane fade" id="tab-galeri" role="tabpanel">
        <section class="py-5">
            <div class="container">
                <div class="text-center mb-5">
                    <span class="badge text-bg-primary fs-6 px-3 py-2 mb-3">Dokumentasi</span>
                    <h2 class="fw-bold">Galeri Sekolah</h2>
                    <p class="text-muted">Momen-momen berkesan di lingkungan sekolah kami</p>
                </div>
                <?php $galeriTampil = !empty($galeri_fasilitas) ? $galeri_fasilitas : $galeri_unggulan; ?>
                <?php if (!empty($galeriTampil)): ?>
                    <div class="row g-3">
                        <?php foreach ($galeriTampil as $g): ?>
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
