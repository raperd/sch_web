<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">Album Foto</h4>
        <p class="text-muted small mb-0">Kelola album foto sekolah yang ditautkan ke Google Foto</p>
    </div>
    <a href="<?= base_url('admin/album-foto/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah Album
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill me-2"></i><?= esc(session()->getFlashdata('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc(session()->getFlashdata('error')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (!empty($albums)): ?>
    <div class="row g-4">
        <?php foreach ($albums as $a): ?>
            <div class="col-sm-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <!-- Cover -->
                    <?php if (!empty($a['cover_foto'])): ?>
                        <img src="<?= base_url('uploads/album_foto/' . esc($a['cover_foto'])) ?>"
                            class="card-img-top" style="height:200px;object-fit:cover;" alt="<?= esc($a['judul']) ?>">
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center bg-light" style="height:200px;">
                            <i class="bi bi-images text-muted" style="font-size:3rem;"></i>
                        </div>
                    <?php endif; ?>

                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                            <h6 class="fw-semibold mb-0"><?= esc($a['judul']) ?></h6>
                            <?php if ($a['is_published']): ?>
                                <span class="badge text-bg-success flex-shrink-0">Ditampilkan</span>
                            <?php else: ?>
                                <span class="badge text-bg-secondary flex-shrink-0">Disembunyikan</span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($a['tanggal'])): ?>
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-calendar3 me-1"></i><?= format_tanggal($a['tanggal'], 'short') ?>
                            </small>
                        <?php endif; ?>
                        <?php if (!empty($a['deskripsi'])): ?>
                            <p class="small text-muted mb-0"><?= esc(truncate_text($a['deskripsi'], 80)) ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="card-footer bg-white border-top-0 p-2 d-flex gap-1">
                        <a href="<?= base_url('admin/album-foto/edit/' . $a['id']) ?>"
                            class="btn btn-sm btn-outline-primary flex-grow-1">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                        <a href="<?= esc($a['link_google_foto']) ?>" target="_blank" rel="noopener"
                            class="btn btn-sm btn-outline-secondary" title="Buka Google Foto">
                            <i class="bi bi-google"></i>
                        </a>
                        <form method="post" action="<?= base_url('admin/album-foto/delete/' . $a['id']) ?>"
                            class="d-inline" data-confirm="Hapus album ini?" data-confirm-ok="Ya, Hapus" data-confirm-class="btn-danger" data-confirm-type="danger">
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
<?php else: ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5 text-muted">
            <i class="bi bi-images display-5 d-block mb-3 opacity-25"></i>
            <p class="mb-1">Belum ada album foto.</p>
            <a href="<?= base_url('admin/album-foto/create') ?>" class="btn btn-primary btn-sm mt-2">
                <i class="bi bi-plus-lg me-1"></i>Tambah Album Pertama
            </a>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
