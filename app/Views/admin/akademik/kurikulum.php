<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">Blok Kurikulum</h4>
        <p class="text-muted small mb-0">Konten accordion kurikulum di halaman <strong>/akademik</strong></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= admin_url('akademik/program') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-star me-1"></i>Program Unggulan
        </a>
        <a href="<?= admin_url('akademik/kurikulum/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Tambah Blok
        </a>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= esc(session()->getFlashdata('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Mobile Cards (xs/sm) -->
<div class="d-md-none">
    <?php if (empty($bloks)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
            Belum ada blok kurikulum. <a href="<?= admin_url('akademik/kurikulum/create') ?>">Tambah sekarang</a>
        </div>
    <?php else: ?>
        <?php foreach ($bloks as $b): ?>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <div class="fw-semibold"><?= esc($b['judul']) ?></div>
                        <?= $b['is_active']
                            ? '<span class="badge text-bg-success">Aktif</span>'
                            : '<span class="badge text-bg-secondary">Non-aktif</span>' ?>
                    </div>
                    <?php if (!empty($b['konten'])): ?>
                        <small class="text-muted"><?= esc(mb_strimwidth(strip_tags($b['konten']), 0, 80, '...')) ?></small>
                    <?php endif; ?>
                    <div class="mt-1"><small class="text-muted">Urutan: <?= $b['urutan'] ?></small></div>
                </div>
                <div class="card-footer bg-white border-top p-2 d-flex gap-2 justify-content-end">
                    <a href="<?= admin_url('akademik/kurikulum/' . $b['id'] . '/edit') ?>"
                        class="btn btn-sm btn-outline-primary flex-grow-1">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <form method="post" action="<?= admin_url('akademik/kurikulum/' . $b['id'] . '/delete') ?>"
                        data-confirm="Hapus blok ini?" data-confirm-ok="Ya, Hapus" data-confirm-class="btn-danger" data-confirm-type="danger">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Desktop Table (md+) -->
<div class="d-none d-md-block">
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th width="50">No</th>
                    <th>Judul Blok</th>
                    <th width="80">Urutan</th>
                    <th width="80">Status</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bloks)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Belum ada blok kurikulum. <a href="<?= admin_url('akademik/kurikulum/create') ?>">Tambah sekarang</a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($bloks as $i => $b): ?>
                        <tr>
                            <td class="text-muted"><?= $i + 1 ?></td>
                            <td>
                                <div class="fw-semibold"><?= esc($b['judul']) ?></div>
                                <?php if (!empty($b['konten'])): ?>
                                    <div class="text-muted small"><?= esc(mb_strimwidth(strip_tags($b['konten']), 0, 80, '...')) ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?= $b['urutan'] ?></td>
                            <td>
                                <?= $b['is_active']
                                    ? '<span class="badge text-bg-success">Aktif</span>'
                                    : '<span class="badge text-bg-secondary">Non-aktif</span>' ?>
                            </td>
                            <td>
                                <a href="<?= admin_url('akademik/kurikulum/' . $b['id'] . '/edit') ?>"
                                   class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="post" action="<?= admin_url('akademik/kurikulum/' . $b['id'] . '/delete') ?>"
                                      class="d-inline"
                                      data-confirm="Hapus blok ini?" data-confirm-ok="Ya, Hapus" data-confirm-class="btn-danger" data-confirm-type="danger">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<?= $this->endSection() ?>
