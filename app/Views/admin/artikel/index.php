<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">Manajemen Artikel</h4>
        <p class="text-muted small mb-0">Kelola semua artikel dan berita sekolah</p>
    </div>
    <a href="<?= base_url('admin/artikel/create') ?>" class="btn btn-primary btn-lg">
        <i class="bi bi-plus-circle me-1"></i>Tulis Artikel
    </a>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-3 fw-bold text-primary"><?= $total_all ?></div>
            <small class="text-muted">Total</small>
        </div>
    </div>
    <div class="col-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-3 fw-bold text-success"><?= $total_published ?></div>
            <small class="text-muted">Published</small>
        </div>
    </div>
    <div class="col-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-3 fw-bold text-warning"><?= $total_draft ?></div>
            <small class="text-muted">Draft</small>
        </div>
    </div>
</div>

<!-- Filter & Search -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="get" action="<?= base_url('admin/artikel') ?>" class="row g-2 align-items-end">
            <div class="col-sm-5 col-md-6">
                <label class="form-label form-label-sm">Cari Artikel</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="q" value="<?= esc($search) ?>" placeholder="Judul atau ringkasan...">
                </div>
            </div>
            <div class="col-sm-3 col-md-3">
                <label class="form-label form-label-sm">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="published" <?= $status_filter === 'published' ? 'selected' : '' ?>>Published</option>
                    <option value="draft"     <?= $status_filter === 'draft'     ? 'selected' : '' ?>>Draft</option>
                    <option value="archived"  <?= $status_filter === 'archived'  ? 'selected' : '' ?>>Archived</option>
                </select>
            </div>
            <div class="col-sm-4 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Filter</button>
                <a href="<?= base_url('admin/artikel') ?>" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Tabel -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:50px">#</th>
                    <th>Artikel</th>
                    <th class="d-none d-md-table-cell">Kategori</th>
                    <th class="d-none d-lg-table-cell">Tanggal</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Pilihan</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($artikel)): ?>
                    <?php foreach ($artikel as $i => $a): ?>
                        <tr>
                            <td class="text-muted small"><?= $i + 1 ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <?php if (!empty($a['thumbnail'])): ?>
                                        <img src="<?= base_url('uploads/artikel/' . esc($a['thumbnail'])) ?>"
                                            class="rounded" style="width:52px;height:40px;object-fit:cover;flex-shrink:0"
                                            alt="">
                                    <?php else: ?>
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="width:52px;height:40px;">
                                            <i class="bi bi-image text-muted small"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div style="min-width:0">
                                        <div class="fw-semibold text-truncate" style="max-width:280px;">
                                            <?= esc($a['judul']) ?>
                                        </div>
                                        <small class="text-muted"><i class="bi bi-eye me-1"></i><?= number_format($a['view_count'] ?? 0) ?> dibaca</small>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <span class="badge text-bg-light border"><?= esc($a['nama_kategori'] ?? '—') ?></span>
                            </td>
                            <td class="d-none d-lg-table-cell text-muted small">
                                <?= !empty($a['published_at']) ? format_tanggal($a['published_at'], 'short') : format_tanggal($a['created_at'], 'short') ?>
                            </td>
                            <td class="text-center">
                                <form method="post" action="<?= base_url('admin/artikel/toggle-status/' . $a['id']) ?>" class="d-inline">
                                    <?= csrf_field() ?>
                                    <?php
                                    $statusConfig = [
                                        'published' => ['success', 'Published'],
                                        'draft'     => ['warning', 'Draft'],
                                        'archived'  => ['secondary', 'Archived'],
                                    ];
                                    [$cls, $lbl] = $statusConfig[$a['status']] ?? ['secondary', $a['status']];
                                    ?>
                                    <button type="submit" class="btn btn-sm text-bg-<?= $cls ?> border-0"
                                        style="min-width:85px;" title="Klik untuk toggle status">
                                        <?= $lbl ?>
                                    </button>
                                </form>
                            </td>
                            <td class="text-center">
                                <form method="post" action="<?= base_url('admin/artikel/toggle-featured/' . $a['id']) ?>" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-link p-0 <?= $a['is_featured'] ? 'text-warning' : 'text-muted' ?>"
                                        title="Toggle pilihan">
                                        <i class="bi bi-star<?= $a['is_featured'] ? '-fill' : '' ?> fs-5"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="<?= base_url('berita/' . esc($a['slug'])) ?>" target="_blank"
                                        class="btn btn-sm btn-outline-secondary" title="Lihat di publik">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= base_url('admin/artikel/edit/' . $a['id']) ?>"
                                        class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="post" action="<?= base_url('admin/artikel/delete/' . $a['id']) ?>" class="d-inline"
                                        onsubmit="return confirm('Hapus artikel ini?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-newspaper display-5 d-block mb-2 opacity-25"></i>
                            Belum ada artikel.
                            <a href="<?= base_url('admin/artikel/create') ?>">Tulis sekarang</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if (isset($pager)): ?>
        <div class="card-footer bg-white border-top-0 d-flex justify-content-center py-3">
            <?= $pager->links('artikel', 'default_full') ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
