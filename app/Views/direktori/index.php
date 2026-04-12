<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<section class="page-header py-5" style="background: linear-gradient(135deg, var(--bs-primary) 0%, var(--site-secondary) 100%);">
    <div class="container text-center text-white">
        <h1 class="fw-bold mb-2">Direktori Guru &amp; Staf</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('/') ?>" class="text-white-50">Beranda</a></li>
                <li class="breadcrumb-item active text-white">Direktori</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Filter Tabs -->
<section class="bg-white shadow-sm sticky-top" style="top: var(--nav-height-sticky); z-index: 900; transition: top 0.3s;">
    <div class="container">
        <ul class="nav nav-pills gap-2 py-2 flex-wrap justify-content-center" id="direktoriTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-semibold text-nowrap" data-bs-toggle="pill" data-bs-target="#tab-guru" type="button">
                    <i class="bi bi-mortarboard me-1"></i>Guru
                    <span class="badge bg-white text-primary ms-1"><?= count($guru) ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold text-nowrap" data-bs-toggle="pill" data-bs-target="#tab-staf" type="button">
                    <i class="bi bi-briefcase me-1"></i>Staf
                    <span class="badge bg-white text-primary ms-1"><?= count($staf) ?></span>
                </button>
            </li>
            <?php if (!empty($tendik)): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold text-nowrap" data-bs-toggle="pill" data-bs-target="#tab-tendik" type="button">
                        <i class="bi bi-tools me-1"></i>Tenaga Kependidikan
                        <span class="badge bg-white text-primary ms-1"><?= count($tendik) ?></span>
                    </button>
                </li>
            <?php endif; ?>
            <?php if (!empty($alumni)): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold text-nowrap" data-bs-toggle="pill" data-bs-target="#tab-alumni" type="button">
                        <i class="bi bi-clock-history me-1"></i>Masa ke Masa
                        <span class="badge bg-white text-primary ms-1"><?= count($alumni) ?></span>
                    </button>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</section>

<div class="tab-content">

    <!-- Guru -->
    <div class="tab-pane fade show active" id="tab-guru" role="tabpanel">
        <section class="py-5">
            <div class="container">
                <div class="text-center mb-5">
                    <span class="badge text-bg-primary fs-6 px-3 py-2 mb-3">Tenaga Pengajar</span>
                    <h2 class="fw-bold">Daftar Guru</h2>
                    <p class="text-muted">Para pendidik berdedikasi yang membimbing perjalanan belajar siswa</p>
                </div>

                <!-- Filter by bidang -->
                <?php if (!empty($bidang)): ?>
                    <div class="d-flex flex-wrap gap-2 justify-content-center mb-4">
                        <button class="btn btn-primary btn-sm filter-bidang active" data-bidang="">Semua</button>
                        <?php foreach ($bidang as $b): ?>
                            <button class="btn btn-outline-primary btn-sm filter-bidang" data-bidang="<?= $b['id'] ?>">
                                <?= esc($b['nama']) ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($guru)): ?>
                    <div class="row g-4" id="guru-grid">
                        <?php foreach ($guru as $g): ?>
                            <div class="col-sm-6 col-lg-4 guru-item" data-bidang="<?= $g['bidang_id'] ?? '' ?>">
                                <div class="card border-0 shadow-sm h-100 guru-card">
                                    <div class="card-body text-center p-4">
                                        <?php if (!empty($g['foto'])): ?>
                                            <img src="<?= base_url('uploads/guru/' . esc($g['foto'])) ?>"
                                                class="rounded-circle mb-3 border border-3"
                                                style="width:100px;height:100px;object-fit:cover;border-color:var(--bs-primary)!important;"
                                                alt="<?= esc($g['nama']) ?>">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-primary bg-opacity-10 mx-auto mb-3 d-flex align-items-center justify-content-center"
                                                style="width:100px;height:100px;">
                                                <i class="bi bi-person-fill text-primary" style="font-size:2.5rem;"></i>
                                            </div>
                                        <?php endif; ?>
                                        <h5 class="fw-bold mb-1"><?= esc($g['nama']) ?></h5>
                                        <?php if (!empty($g['jabatan'])): ?>
                                            <p class="text-primary small fw-semibold mb-1"><?= esc($g['jabatan']) ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($g['mata_pelajaran'])): ?>
                                            <p class="text-muted small mb-2">
                                                <i class="bi bi-book me-1"></i><?= esc($g['mata_pelajaran']) ?>
                                            </p>
                                        <?php endif; ?>
                                        <?php if (!empty($g['pendidikan'])): ?>
                                            <span class="badge text-bg-light border small"><?= esc($g['pendidikan']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($g['filosofi_mengajar'])): ?>
                                        <div class="card-footer bg-light border-0 p-3">
                                            <blockquote class="mb-0 fst-italic text-muted small text-center">
                                                <i class="bi bi-quote text-primary opacity-50"></i>
                                                <?= esc(truncate_text($g['filosofi_mengajar'], 100)) ?>
                                            </blockquote>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($g['email_publik'])): ?>
                                        <div class="card-footer text-center border-0 pb-3 pt-0 bg-white">
                                            <a href="mailto:<?= esc($g['email_publik']) ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-envelope me-1"></i>Kirim Email
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-people display-3 mb-3 d-block"></i>
                        <p>Belum ada data guru.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <!-- Staf -->
    <div class="tab-pane fade" id="tab-staf" role="tabpanel">
        <section class="py-5">
            <div class="container">
                <div class="text-center mb-5">
                    <span class="badge text-bg-primary fs-6 px-3 py-2 mb-3">Tenaga Administratif</span>
                    <h2 class="fw-bold">Daftar Staf</h2>
                    <p class="text-muted">Staf administrasi dan penunjang yang memastikan sekolah berjalan lancar</p>
                </div>

                <?php if (!empty($staf)): ?>
                    <div class="row g-4">
                        <?php foreach ($staf as $s): ?>
                            <div class="col-sm-6 col-lg-4">
                                <div class="card border-0 shadow-sm h-100 guru-card">
                                    <div class="card-body text-center p-4">
                                        <?php if (!empty($s['foto'])): ?>
                                            <img src="<?= base_url('uploads/guru/' . esc($s['foto'])) ?>"
                                                class="rounded-circle mb-3 border border-3"
                                                style="width:100px;height:100px;object-fit:cover;border-color:var(--site-secondary)!important;"
                                                alt="<?= esc($s['nama']) ?>">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-secondary bg-opacity-10 mx-auto mb-3 d-flex align-items-center justify-content-center"
                                                style="width:100px;height:100px;">
                                                <i class="bi bi-person-fill text-secondary" style="font-size:2.5rem;"></i>
                                            </div>
                                        <?php endif; ?>
                                        <h5 class="fw-bold mb-1"><?= esc($s['nama']) ?></h5>
                                        <?php if (!empty($s['jabatan'])): ?>
                                            <p class="text-secondary small fw-semibold mb-1"><?= esc($s['jabatan']) ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($s['pendidikan'])): ?>
                                            <span class="badge text-bg-light border small"><?= esc($s['pendidikan']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($s['email_publik'])): ?>
                                        <div class="card-footer text-center border-0 pb-3 bg-white">
                                            <a href="mailto:<?= esc($s['email_publik']) ?>" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-envelope me-1"></i>Kirim Email
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-briefcase display-3 mb-3 d-block"></i>
                        <p>Belum ada data staf.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <!-- Tendik -->
    <?php if (!empty($tendik)): ?>
        <div class="tab-pane fade" id="tab-tendik" role="tabpanel">
            <section class="py-5">
                <div class="container">
                    <div class="text-center mb-5">
                        <span class="badge text-bg-primary fs-6 px-3 py-2 mb-3">Tenaga Kependidikan</span>
                        <h2 class="fw-bold">Tenaga Kependidikan</h2>
                    </div>
                    <div class="row g-4">
                        <?php foreach ($tendik as $t): ?>
                            <div class="col-sm-6 col-lg-4">
                                <div class="card border-0 shadow-sm h-100 guru-card">
                                    <div class="card-body text-center p-4">
                                        <?php if (!empty($t['foto'])): ?>
                                            <img src="<?= base_url('uploads/guru/' . esc($t['foto'])) ?>"
                                                class="rounded-circle mb-3 border border-3 border-info"
                                                style="width:100px;height:100px;object-fit:cover;"
                                                alt="<?= esc($t['nama']) ?>">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-info bg-opacity-10 mx-auto mb-3 d-flex align-items-center justify-content-center"
                                                style="width:100px;height:100px;">
                                                <i class="bi bi-tools text-info" style="font-size:2.5rem;"></i>
                                            </div>
                                        <?php endif; ?>
                                        <h5 class="fw-bold mb-1"><?= esc($t['nama']) ?></h5>
                                        <?php if (!empty($t['jabatan'])): ?>
                                            <p class="text-info small fw-semibold mb-0"><?= esc($t['jabatan']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        </div>
    <?php endif; ?>

    <!-- Alumni / Masa ke Masa -->
    <?php if (!empty($alumni)): ?>
        <div class="tab-pane fade" id="tab-alumni" role="tabpanel">
            <section class="py-5 bg-light">
                <div class="container">
                    <div class="text-center mb-5">
                        <span class="badge text-bg-secondary fs-6 px-3 py-2 mb-3">Purna Tugas</span>
                        <h2 class="fw-bold">Guru &amp; Staf dari Masa ke Masa</h2>
                        <p class="text-muted">Para pendidik dan staf yang telah mengabdi dan membangun sekolah ini</p>
                    </div>
                    <div class="row g-3 justify-content-center">
                        <?php foreach ($alumni as $a):
                            $tipeLabel = ['guru' => 'Guru', 'staf' => 'Staf', 'tendik' => 'Tendik'];
                            $tipeCls   = ['guru' => 'text-bg-primary', 'staf' => 'text-bg-secondary', 'tendik' => 'text-bg-info'];
                        ?>
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-0 shadow-sm h-100 text-center p-3" style="filter:grayscale(30%)">
                                <div class="card-body p-2">
                                    <?php if (!empty($a['foto'])): ?>
                                        <img src="<?= base_url('uploads/guru/' . esc($a['foto'])) ?>"
                                            class="rounded-circle mb-3 border border-2 border-secondary"
                                            style="width:90px;height:90px;object-fit:cover;"
                                            alt="<?= esc($a['nama']) ?>">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-secondary bg-opacity-10 mx-auto mb-3 d-flex align-items-center justify-content-center"
                                            style="width:90px;height:90px;">
                                            <i class="bi bi-person-fill text-secondary" style="font-size:2.2rem;"></i>
                                        </div>
                                    <?php endif; ?>
                                    <h6 class="fw-bold mb-1"><?= esc($a['nama']) ?></h6>
                                    <?php if (!empty($a['jabatan'])): ?>
                                        <p class="text-muted small mb-2"><?= esc($a['jabatan']) ?></p>
                                    <?php endif; ?>
                                    <div class="d-flex flex-wrap gap-1 justify-content-center">
                                        <span class="badge <?= $tipeCls[$a['tipe']] ?? 'text-bg-secondary' ?> small">
                                            <?= $tipeLabel[$a['tipe']] ?? esc($a['tipe']) ?>
                                        </span>
                                        <?php if (!empty($a['tahun_masuk']) || !empty($a['tahun_keluar'])): ?>
                                            <span class="badge text-bg-light border text-muted small">
                                                <i class="bi bi-calendar3 me-1"></i><?= esc($a['tahun_masuk'] ?? '?') ?> &ndash; <?= esc($a['tahun_keluar'] ?? '?') ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        </div>
    <?php endif; ?>

</div><!-- /tab-content -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Filter guru by bidang
document.querySelectorAll('.filter-bidang').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-bidang').forEach(b => b.classList.remove('active', 'btn-primary'));
        document.querySelectorAll('.filter-bidang').forEach(b => b.classList.add('btn-outline-primary'));
        this.classList.add('active', 'btn-primary');
        this.classList.remove('btn-outline-primary');
        const bidang = this.dataset.bidang;
        document.querySelectorAll('.guru-item').forEach(function(item) {
            if (!bidang || item.dataset.bidang == bidang) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
// Tab from hash
const hash = window.location.hash;
if (hash) {
    const tab = document.querySelector('[data-bs-target="' + hash + '"]');
    if (tab) bootstrap.Tab.getOrCreateInstance(tab).show();
}
</script>
<?= $this->endSection() ?>
