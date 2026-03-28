<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Quick Links</h4>
        <small class="text-muted">Kelola tombol pintasan yang tampil di beranda</small>
    </div>
    <a href="<?= base_url('admin/quick-links/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah Link
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

<!-- Preview beranda -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom py-3">
        <span class="fw-semibold"><i class="bi bi-eye me-2 text-primary"></i>Preview Tampilan di Beranda</span>
    </div>
    <div class="card-body p-3">
        <?php if (!empty($links)): ?>
            <div class="d-flex flex-wrap gap-2">
                <?php foreach ($links as $ql): ?>
                    <?php if ($ql['is_active']): ?>
                        <a href="<?= esc($ql['url']) ?>" target="<?= esc($ql['target']) ?>"
                            class="btn btn-<?= esc($ql['warna']) ?> btn-sm d-flex align-items-center gap-2 shadow-sm" style="pointer-events:none;">
                            <i class="bi <?= esc($ql['icon']) ?>"></i>
                            <?= esc($ql['label']) ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted mb-0 small">Belum ada quick link aktif.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Mobile Cards (xs/sm) -->
<div class="d-md-none">
    <?php if (!empty($links)): ?>
        <?php foreach ($links as $ql): ?>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <span class="badge text-bg-<?= esc($ql['warna']) ?> fs-5 p-2 flex-shrink-0">
                            <i class="bi <?= esc($ql['icon']) ?>"></i>
                        </span>
                        <div>
                            <div class="fw-semibold"><?= esc($ql['label']) ?></div>
                            <code class="text-primary small"><?= esc($ql['url']) ?></code>
                        </div>
                        <div class="ms-auto">
                            <form method="post" action="<?= base_url('admin/quick-links/toggle/' . $ql['id']) ?>">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm border-0 p-0" title="Toggle aktif">
                                    <i class="bi <?= $ql['is_active'] ? 'bi-toggle-on text-success' : 'bi-toggle-off text-secondary' ?>" style="font-size:1.6rem;"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-1 small">
                        <span class="badge text-bg-light border text-dark">#<?= esc($ql['urutan']) ?></span>
                        <span class="badge <?= $ql['target'] === '_blank' ? 'text-bg-warning' : 'text-bg-light border text-dark' ?>">
                            <?= $ql['target'] === '_blank' ? 'Tab Baru' : 'Halaman Ini' ?>
                        </span>
                    </div>
                </div>
                <div class="card-footer bg-white border-top p-2 d-flex gap-2 justify-content-end">
                    <a href="<?= base_url('admin/quick-links/edit/' . $ql['id']) ?>"
                        class="btn btn-sm btn-outline-primary flex-grow-1">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <form method="post" action="<?= base_url('admin/quick-links/delete/' . $ql['id']) ?>"
                        class="d-inline" data-confirm="Hapus quick link ini?" data-confirm-ok="Ya, Hapus" data-confirm-class="btn-danger" data-confirm-type="danger">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-link-45deg display-3 mb-3 d-block"></i>
            <p>Belum ada quick link. <a href="<?= base_url('admin/quick-links/create') ?>">Tambah sekarang</a></p>
        </div>
    <?php endif; ?>
</div>

<!-- Desktop Table (md+) -->
<div class="d-none d-md-block">
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <?php if (!empty($links)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:50px" class="text-center ps-3">Urutan</th>
                            <th>Label &amp; Ikon</th>
                            <th>URL</th>
                            <th>Warna</th>
                            <th>Target</th>
                            <th class="text-center">Aktif</th>
                            <th class="text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($links as $ql): ?>
                        <tr>
                            <td class="text-center ps-3 text-muted"><?= esc($ql['urutan']) ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge text-bg-<?= esc($ql['warna']) ?> fs-6 p-2">
                                        <i class="bi <?= esc($ql['icon']) ?>"></i>
                                    </span>
                                    <span class="fw-semibold"><?= esc($ql['label']) ?></span>
                                </div>
                                <div class="text-muted small font-monospace mt-1"><?= esc($ql['icon']) ?></div>
                            </td>
                            <td>
                                <code class="text-primary small"><?= esc($ql['url']) ?></code>
                            </td>
                            <td>
                                <span class="badge text-bg-<?= esc($ql['warna']) ?>"><?= esc($ql['warna']) ?></span>
                            </td>
                            <td>
                                <span class="badge <?= $ql['target'] === '_blank' ? 'text-bg-warning' : 'text-bg-light border text-dark' ?>">
                                    <?= $ql['target'] === '_blank' ? 'Tab Baru' : 'Halaman Ini' ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <form method="post" action="<?= base_url('admin/quick-links/toggle/' . $ql['id']) ?>">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm border-0 p-0" title="Klik untuk toggle">
                                        <i class="bi <?= $ql['is_active'] ? 'bi-toggle-on text-success' : 'bi-toggle-off text-secondary' ?>" style="font-size:1.5rem;"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="text-end pe-3">
                                <a href="<?= base_url('admin/quick-links/edit/' . $ql['id']) ?>"
                                    class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="post" action="<?= base_url('admin/quick-links/delete/' . $ql['id']) ?>"
                                    class="d-inline" data-confirm="Hapus quick link ini?" data-confirm-ok="Ya, Hapus" data-confirm-class="btn-danger" data-confirm-type="danger">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-link-45deg display-3 mb-3 d-block"></i>
                <p>Belum ada quick link. <a href="<?= base_url('admin/quick-links/create') ?>">Tambah sekarang</a></p>
            </div>
        <?php endif; ?>
    </div>
</div>
</div>
<?= $this->endSection() ?>
