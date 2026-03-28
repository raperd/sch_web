<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Kategori Artikel</h4>
        <small class="text-muted">Kelola kategori untuk pengelompokan berita & artikel</small>
    </div>
    <a href="<?= base_url('admin/kategori-artikel/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah Kategori
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Mobile Cards (xs/sm) -->
<div class="d-md-none">
    <?php if (empty($kategori)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-tags display-4 mb-3 d-block"></i>
            <p>Belum ada kategori. <a href="<?= base_url('admin/kategori-artikel/create') ?>">Tambah sekarang</a>.</p>
        </div>
    <?php else: ?>
        <?php foreach ($kategori as $kat): ?>
            <?php $count = $counts[$kat['id']] ?? 0; ?>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <div class="fw-semibold"><?= esc($kat['nama']) ?></div>
                        <span class="badge text-bg-primary ms-2"><?= $count ?> artikel</span>
                    </div>
                    <code class="text-muted small"><?= esc($kat['slug']) ?></code>
                    <?php if (!empty($kat['deskripsi'])): ?>
                        <div class="text-muted small mt-1"><?= esc(truncate_text($kat['deskripsi'], 80)) ?></div>
                    <?php endif; ?>
                    <small class="text-muted">Urutan: <?= $kat['urutan'] ?></small>
                </div>
                <div class="card-footer bg-white border-top p-2 d-flex gap-2 justify-content-end">
                    <a href="<?= base_url('admin/kategori-artikel/edit/' . $kat['id']) ?>"
                        class="btn btn-sm btn-outline-primary flex-grow-1">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <?php if ($count === 0): ?>
                        <form method="POST" action="<?= base_url('admin/kategori-artikel/delete/' . $kat['id']) ?>"
                            data-confirm="Hapus kategori &quot;<?= esc($kat['nama']) ?>&quot;?" data-confirm-ok="Ya, Hapus" data-confirm-class="btn-danger" data-confirm-type="danger">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-sm btn-outline-secondary" disabled
                            title="Masih digunakan oleh <?= $count ?> artikel">
                            <i class="bi bi-trash"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Desktop Table (md+) -->
<div class="d-none d-md-block">
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <?php if (empty($kategori)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-tags display-4 mb-3 d-block"></i>
                <p>Belum ada kategori. <a href="<?= base_url('admin/kategori-artikel/create') ?>">Tambah sekarang</a>.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:50px">#</th>
                            <th>Nama Kategori</th>
                            <th>Slug</th>
                            <th>Deskripsi</th>
                            <th class="text-center" style="width:100px">Artikel</th>
                            <th style="width:80px">Urutan</th>
                            <th style="width:120px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kategori as $i => $kat): ?>
                            <tr>
                                <td class="text-muted small"><?= $i + 1 ?></td>
                                <td>
                                    <span class="fw-semibold"><?= esc($kat['nama']) ?></span>
                                </td>
                                <td>
                                    <code class="text-muted small"><?= esc($kat['slug']) ?></code>
                                </td>
                                <td class="text-muted small">
                                    <?= esc(truncate_text($kat['deskripsi'] ?? '', 60)) ?: '<span class="text-muted">—</span>' ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge text-bg-primary"><?= $counts[$kat['id']] ?? 0 ?></span>
                                </td>
                                <td class="text-muted small"><?= $kat['urutan'] ?></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="<?= base_url('admin/kategori-artikel/edit/' . $kat['id']) ?>"
                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php if (($counts[$kat['id']] ?? 0) === 0): ?>
                                            <form method="POST" action="<?= base_url('admin/kategori-artikel/delete/' . $kat['id']) ?>"
                                                data-confirm="Hapus kategori &quot;<?= esc($kat['nama']) ?>&quot;?" data-confirm-ok="Ya, Hapus" data-confirm-class="btn-danger" data-confirm-type="danger">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-outline-secondary" disabled
                                                title="Tidak bisa dihapus — masih digunakan oleh <?= $counts[$kat['id']] ?> artikel">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
</div>
<?= $this->endSection() ?>
