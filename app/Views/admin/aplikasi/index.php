<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Link Terkait</h4>
        <small class="text-muted">Kelola link bermanfaat atau link aplikasi terintegrasi sekolah</small>
    </div>
    <a href="<?= base_url('admin/aplikasi/create') ?>" class="btn btn-primary">
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

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <?php if (!empty($apps)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center ps-3" style="width: 50px;">Urut</th>
                            <th style="width: 60px;">Ikon</th>
                            <th>Nama Link / Aplikasi</th>
                            <th>Deskripsi (Tampil di Publik)</th>
                            <th>Status</th>
                            <th class="text-end pe-3" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($apps as $a): ?>
                        <tr>
                            <td class="text-center ps-3 text-muted"><?= esc($a['urutan']) ?></td>
                            <td>
                                <div class="bg-primary bg-opacity-10 text-primary rounded d-flex align-items-center justify-content-center overflow-hidden" style="width:40px;height:40px;">
                                    <?php if (!empty($a['icon'])): ?>
                                        <?php if (str_starts_with($a['icon'], 'bi-')): ?>
                                            <i class="bi <?= esc($a['icon']) ?> fs-5"></i>
                                        <?php else: ?>
                                            <img src="<?= base_url('uploads/aplikasi/' . esc($a['icon'])) ?>" alt="Icon" class="w-100 h-100 object-fit-cover">
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <i class="bi bi-app fs-5 opacity-50"></i>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold"><?= esc($a['nama']) ?></div>
                                <code class="small text-primary"><a href="<?= esc($a['url']) ?>" target="_blank"><?= esc($a['url']) ?></a></code>
                            </td>
                            <td class="text-muted small">
                                <?= $a['deskripsi'] ? esc(truncate_text($a['deskripsi'], 60)) : '<em>Tidak ada deskripsi</em>' ?>
                            </td>
                            <td>
                                <form method="post" action="<?= base_url('admin/aplikasi/toggle/' . $a['id']) ?>" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm border-0 p-0" title="Klik untuk ubah status">
                                        <i class="bi <?= $a['is_active'] ? 'bi-toggle-on text-success' : 'bi-toggle-off text-muted' ?>" style="font-size:1.5rem;"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="text-end pe-3">
                                <a href="<?= base_url('admin/aplikasi/edit/' . $a['id']) ?>" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="post" action="<?= base_url('admin/aplikasi/delete/' . $a['id']) ?>" class="d-inline"
                                      data-confirm="Hapus link &quot;<?= esc($a['nama']) ?>&quot;?" data-confirm-ok="Ya, Hapus" data-confirm-class="btn-danger" data-confirm-type="danger">
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
                <i class="bi bi-grid-3x3-gap-fill display-3 mb-3 d-block"></i>
                <p>Belum ada link yang ditambahkan. <a href="<?= base_url('admin/aplikasi/create') ?>">Tambah sekarang</a></p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
