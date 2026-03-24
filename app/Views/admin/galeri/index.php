<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">Manajemen Galeri</h4>
        <p class="text-muted small mb-0">Kelola foto dan media sekolah</p>
    </div>
    <a href="<?= base_url('admin/galeri/upload') ?>" class="btn btn-primary btn-lg">
        <i class="bi bi-cloud-upload me-1"></i>Upload
    </a>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-3 fw-bold text-primary"><?= $total_all ?></div>
            <small class="text-muted">Total Foto</small>
        </div>
    </div>
    <div class="col-6">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-3 fw-bold text-warning"><?= $total_featured ?></div>
            <small class="text-muted">Unggulan</small>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="get" action="<?= base_url('admin/galeri') ?>" class="row g-2 align-items-end">
            <div class="col-sm-5 col-md-6">
                <label class="form-label form-label-sm">Cari</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="q" value="<?= esc($search) ?>" placeholder="Judul foto...">
                </div>
            </div>
            <div class="col-sm-3 col-md-3">
                <label class="form-label form-label-sm">Kategori</label>
                <select name="kategori" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($kategori as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= $kategori_filter == $k['id'] ? 'selected' : '' ?>>
                            <?= esc($k['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-4 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Filter</button>
                <a href="<?= base_url('admin/galeri') ?>" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Grid Galeri -->
<?php if (!empty($galeri)): ?>
    <div class="row g-3 mb-4">
        <?php foreach ($galeri as $item): ?>
            <?php
            $imgSrc = !empty($item['thumbnail'])
                ? base_url('uploads/galeri/' . $item['thumbnail'])
                : (!empty($item['file_path']) ? base_url('uploads/galeri/' . $item['file_path']) : '');
            ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="position-relative">
                        <?php if ($imgSrc): ?>
                            <img src="<?= esc($imgSrc) ?>" class="card-img-top"
                                style="height:140px;object-fit:cover" alt="">
                        <?php else: ?>
                            <div class="bg-light d-flex align-items-center justify-content-center"
                                style="height:140px">
                                <i class="bi bi-image text-muted fs-1"></i>
                            </div>
                        <?php endif; ?>
                        <?php if ($item['is_featured']): ?>
                            <span class="badge text-bg-warning position-absolute top-0 end-0 m-1">
                                <i class="bi bi-star-fill"></i>
                            </span>
                        <?php endif; ?>
                        <?php if ($item['tipe'] === 'video'): ?>
                            <span class="badge text-bg-danger position-absolute top-0 start-0 m-1">
                                <i class="bi bi-play-fill"></i> Video
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-2">
                        <div class="fw-semibold small text-truncate"><?= esc($item['judul']) ?></div>
                        <small class="text-muted"><?= esc($item['kategori_nama'] ?? '—') ?></small>
                    </div>
                    <div class="card-footer bg-white border-top-0 p-2 d-flex gap-1">
                        <a href="<?= base_url('admin/galeri/edit/' . $item['id']) ?>"
                            class="btn btn-sm btn-outline-primary flex-grow-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="post" action="<?= base_url('admin/galeri/delete/' . $item['id']) ?>"
                            class="d-inline" onsubmit="return confirm('Hapus foto ini?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if (isset($pager)): ?>
        <div class="d-flex justify-content-center">
            <?= $pager->links('galeri', 'default_full') ?>
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5 text-muted">
            <i class="bi bi-images display-5 d-block mb-2 opacity-25"></i>
            Belum ada foto di galeri.
            <a href="<?= base_url('admin/galeri/upload') ?>">Upload sekarang</a>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
