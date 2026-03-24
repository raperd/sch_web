<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Dashboard</h4>
        <small class="text-muted">Selamat datang, <?= esc(session('admin_nama')) ?>!</small>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('admin/artikel/create') ?>" class="btn btn-primary btn-action">
            <i class="bi bi-plus-lg me-1"></i><span class="d-none d-sm-inline">Tulis Artikel</span>
        </a>
    </div>
</div>

<!-- ---- STAT CARDS ---- -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-newspaper"></i>
                </div>
                <div>
                    <div class="stat-number text-primary"><?= $total_artikel ?></div>
                    <div class="stat-label">Artikel Published</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div>
                    <div class="stat-number text-warning"><?= $total_draft ?></div>
                    <div class="stat-label">Draft Artikel</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-person-badge"></i>
                </div>
                <div>
                    <div class="stat-number text-success"><?= $total_guru ?></div>
                    <div class="stat-label">Guru Aktif</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-calendar-event"></i>
                </div>
                <div>
                    <div class="stat-number text-info"><?= $total_kegiatan ?></div>
                    <div class="stat-label">Kegiatan Upcoming</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ---- QUICK ACTIONS ---- -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card table-card">
            <div class="card-header fw-semibold"><i class="bi bi-lightning-fill text-warning me-2"></i>Aksi Cepat</div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="<?= site_url('admin/artikel/create') ?>" class="btn btn-outline-primary w-100 btn-action d-flex flex-column align-items-center py-3">
                            <i class="bi bi-pencil-square fs-4 mb-1"></i>
                            <small>Artikel Baru</small>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="<?= site_url('admin/galeri/upload') ?>" class="btn btn-outline-success w-100 btn-action d-flex flex-column align-items-center py-3">
                            <i class="bi bi-upload fs-4 mb-1"></i>
                            <small>Upload Galeri</small>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="<?= site_url('admin/guru/create') ?>" class="btn btn-outline-info w-100 btn-action d-flex flex-column align-items-center py-3">
                            <i class="bi bi-person-plus fs-4 mb-1"></i>
                            <small>Tambah Guru</small>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="<?= site_url('admin/kegiatan/create') ?>" class="btn btn-outline-warning w-100 btn-action d-flex flex-column align-items-center py-3">
                            <i class="bi bi-calendar-plus fs-4 mb-1"></i>
                            <small>Kegiatan Baru</small>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="<?= site_url('admin/ppdb') ?>" class="btn btn-outline-danger w-100 btn-action d-flex flex-column align-items-center py-3">
                            <i class="bi bi-clipboard-check fs-4 mb-1"></i>
                            <small>Kelola PPDB</small>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="<?= site_url('admin/pengaturan') ?>" class="btn btn-outline-secondary w-100 btn-action d-flex flex-column align-items-center py-3">
                            <i class="bi bi-gear fs-4 mb-1"></i>
                            <small>Pengaturan</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Artikel Terbaru -->
    <div class="col-12 col-xl-7">
        <div class="card table-card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="fw-semibold"><i class="bi bi-newspaper me-2 text-primary"></i>Artikel Terbaru</span>
                <a href="<?= site_url('admin/artikel') ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Judul</th>
                            <th class="d-none d-md-table-cell">Kategori</th>
                            <th>Status</th>
                            <th class="d-none d-sm-table-cell">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($artikel_terbaru)): ?>
                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada artikel</td></tr>
                        <?php else: ?>
                        <?php foreach ($artikel_terbaru as $art): ?>
                        <tr>
                            <td>
                                <a href="<?= site_url('admin/artikel/edit/' . $art['id']) ?>" class="text-decoration-none fw-semibold">
                                    <?= esc(truncate_text($art['judul'], 50)) ?>
                                </a>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <span class="badge bg-light text-dark"><?= esc($art['kategori_nama'] ?? '-') ?></span>
                            </td>
                            <td>
                                <?php if ($art['status'] === 'published'): ?>
                                    <span class="badge bg-success-subtle text-success">Published</span>
                                <?php elseif ($art['status'] === 'draft'): ?>
                                    <span class="badge bg-warning-subtle text-warning">Draft</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary">Archived</span>
                                <?php endif; ?>
                            </td>
                            <td class="d-none d-sm-table-cell small text-muted"><?= format_tanggal($art['created_at'], 'short') ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Kegiatan Upcoming -->
    <div class="col-12 col-xl-5">
        <div class="card table-card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="fw-semibold"><i class="bi bi-calendar-event me-2 text-info"></i>Kegiatan Mendatang</span>
                <a href="<?= site_url('admin/kegiatan') ?>" class="btn btn-sm btn-outline-info">Lihat Semua</a>
            </div>
            <div class="list-group list-group-flush">
                <?php if (empty($kegiatan_upcoming)): ?>
                    <div class="list-group-item text-center text-muted py-4">Tidak ada kegiatan mendatang</div>
                <?php else: ?>
                <?php foreach ($kegiatan_upcoming as $kg): ?>
                <a href="<?= site_url('admin/kegiatan/edit/' . $kg['id']) ?>" class="list-group-item list-group-item-action">
                    <div class="d-flex align-items-start gap-3">
                        <div class="text-center bg-primary bg-opacity-10 rounded p-2" style="min-width:48px">
                            <div class="text-primary fw-bold"><?= date('d', strtotime($kg['tanggal'])) ?></div>
                            <small class="text-muted"><?= date('M', strtotime($kg['tanggal'])) ?></small>
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <div class="fw-semibold text-truncate"><?= esc($kg['judul']) ?></div>
                            <small class="text-muted"><?= $kg['lokasi'] ? esc($kg['lokasi']) : 'Lokasi belum ditentukan' ?></small>
                        </div>
                        <?php if ($kg['status'] === 'ongoing'): ?>
                            <span class="badge bg-success-subtle text-success flex-shrink-0">Berlangsung</span>
                        <?php else: ?>
                            <span class="badge bg-primary-subtle text-primary flex-shrink-0">Upcoming</span>
                        <?php endif; ?>
                    </div>
                </a>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
