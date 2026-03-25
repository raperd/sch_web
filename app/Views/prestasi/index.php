<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<!-- Page Header -->
<section class="page-header py-5" style="background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-secondary) 100%);">
    <div class="container text-center text-white">
        <h1 class="fw-bold mb-2">Prestasi Sekolah</h1>
        <p class="opacity-75 mb-3">Kebanggaan yang kami raih, inspirasi untuk terus berprestasi</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('/') ?>" class="text-white-50">Beranda</a></li>
                <li class="breadcrumb-item active text-white">Prestasi</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Stat Bar -->
<section class="py-4 bg-white border-bottom shadow-sm">
    <div class="container">
        <div class="row g-3 text-center">
            <div class="col-6 col-md-3">
                <div class="fw-bold fs-4 text-primary"><?= $total_akademik + $total_non_akademik ?></div>
                <div class="text-muted small">Total Prestasi</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-bold fs-4 text-success"><?= $total_akademik ?></div>
                <div class="text-muted small">Akademik</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-bold fs-4 text-warning"><?= $total_non_akademik ?></div>
                <div class="text-muted small">Non-Akademik</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-bold fs-4 text-danger"><?= date('Y') ?></div>
                <div class="text-muted small">Tahun Aktif</div>
            </div>
        </div>
    </div>
</section>

<!-- Filter -->
<section class="py-4 bg-light border-bottom">
    <div class="container">
        <form method="get" class="row g-2 justify-content-center align-items-end">
            <div class="col-md-3">
                <select name="kategori" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    <option value="akademik" <?= $kategori_filter === 'akademik' ? 'selected' : '' ?>>🎓 Akademik</option>
                    <option value="non_akademik" <?= $kategori_filter === 'non_akademik' ? 'selected' : '' ?>>🏆 Non-Akademik</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="tahun" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Tahun</option>
                    <?php foreach ($tahun_list as $t): ?>
                        <option value="<?= $t ?>" <?= $tahun_filter == $t ? 'selected' : '' ?>><?= $t ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if ($kategori_filter || $tahun_filter): ?>
                <div class="col-auto">
                    <a href="<?= base_url('prestasi') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>Reset
                    </a>
                </div>
            <?php endif; ?>
        </form>
    </div>
</section>

<!-- Daftar Prestasi -->
<section class="py-5">
    <div class="container">
        <?php if (empty($prestasi)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-trophy fs-1 d-block mb-3 opacity-25"></i>
                <p>Belum ada data prestasi.</p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php
                $tingkatBadge = [
                    'sekolah'        => ['Sekolah',       'secondary'],
                    'kecamatan'      => ['Kecamatan',     'info'],
                    'kota_kabupaten' => ['Kota/Kab.',     'primary'],
                    'provinsi'       => ['Provinsi',      'warning'],
                    'nasional'       => ['Nasional',      'danger'],
                    'internasional'  => ['Internasional', 'dark'],
                ];
                foreach ($prestasi as $p):
                    [$tingkatLabel, $tingkatColor] = $tingkatBadge[$p['tingkat']] ?? [$p['tingkat'], 'secondary'];
                ?>
                    <div class="col-sm-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100">
                            <?php if (!empty($p['foto'])): ?>
                                <img src="<?= base_url('uploads/prestasi/' . esc($p['foto'])) ?>"
                                    class="card-img-top" style="height:180px;object-fit:cover;" alt="<?= esc($p['judul']) ?>">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center bg-warning bg-opacity-10"
                                    style="height:120px;">
                                    <i class="bi bi-trophy-fill text-warning" style="font-size:3rem;"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body p-4">
                                <div class="d-flex gap-2 mb-2 flex-wrap">
                                    <span class="badge text-bg-<?= $tingkatColor ?>"><?= $tingkatLabel ?></span>
                                    <?php if ($p['kategori'] === 'akademik'): ?>
                                        <span class="badge text-bg-success">Akademik</span>
                                    <?php else: ?>
                                        <span class="badge text-bg-warning text-dark">Non-Akademik</span>
                                    <?php endif; ?>
                                    <?php if ($p['is_featured']): ?>
                                        <span class="badge text-bg-light border"><i class="bi bi-star-fill text-warning me-1"></i>Unggulan</span>
                                    <?php endif; ?>
                                </div>
                                <h6 class="fw-bold mb-2"><?= esc($p['judul']) ?></h6>
                                <?php if (!empty($p['deskripsi'])): ?>
                                    <p class="text-muted small mb-2"><?= esc(truncate_text($p['deskripsi'], 100)) ?></p>
                                <?php endif; ?>
                                <div class="text-muted small border-top pt-2 mt-auto">
                                    <?php if (!empty($p['nama_siswa'])): ?>
                                        <div><i class="bi bi-person me-1"></i><?= esc($p['nama_siswa']) ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($p['pembimbing'])): ?>
                                        <div><i class="bi bi-mortarboard me-1"></i>Pembimbing: <?= esc($p['pembimbing']) ?></div>
                                    <?php endif; ?>
                                    <div><i class="bi bi-calendar me-1"></i><?= esc($p['tahun']) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($pager): ?>
                <div class="mt-5 d-flex justify-content-center">
                    <?= $pager->links('prestasi', 'default_full') ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>
