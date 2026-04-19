<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Kepala Sekolah</h4>
        <small class="text-muted">Data kepala sekolah dari masa ke masa</small>
    </div>
    <a href="<?= admin_url('kepala-sekolah/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah Data
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

<?php if (empty($list)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5 text-muted">
            <i class="bi bi-person-badge display-3 mb-3 d-block"></i>
            <p>Belum ada data. <a href="<?= admin_url('kepala-sekolah/create') ?>">Tambah sekarang</a>.</p>
        </div>
    </div>
<?php else: ?>
    <!-- Mobile Cards (xs/sm) -->
    <div class="d-md-none">
        <?php foreach ($list as $item): ?>
            <?php $aktif = empty($item['periode_selesai']); ?>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-3">
                    <div class="d-flex gap-3 mb-2">
                        <?php if (!empty($item['foto'])): ?>
                            <img src="<?= base_url('uploads/kepala_sekolah/' . esc($item['foto'])) ?>"
                                class="rounded-circle object-fit-cover flex-shrink-0"
                                style="width:52px;height:52px;" alt="<?= esc($item['nama']) ?>">
                        <?php else: ?>
                            <div class="rounded-circle bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:52px;height:52px;">
                                <i class="bi bi-person text-secondary fs-5"></i>
                            </div>
                        <?php endif; ?>
                        <div>
                            <div class="fw-semibold">
                                <?php if ($item['gelar_depan']): ?>
                                    <span class="text-muted small"><?= esc($item['gelar_depan']) ?> </span>
                                <?php endif; ?>
                                <?= esc($item['nama']) ?>
                                <?php if ($item['gelar_belakang']): ?>
                                    <span class="text-muted small">, <?= esc($item['gelar_belakang']) ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if ($aktif): ?>
                                <span class="badge text-bg-success">Menjabat Saat Ini</span>
                            <?php endif; ?>
                            <div class="small text-muted mt-1">
                                <?= $item['periode_mulai'] ?> –
                                <?= $aktif ? '<span class="text-success fw-semibold">Sekarang</span>' : esc($item['periode_selesai']) ?>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($item['keterangan'])): ?>
                        <p class="small text-muted mb-0"><?= esc(truncate_text($item['keterangan'], 80)) ?></p>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-white border-top p-2 d-flex gap-2 justify-content-end">
                    <a href="<?= admin_url('kepala-sekolah/edit/' . $item['id']) ?>"
                        class="btn btn-sm btn-outline-primary flex-grow-1">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <form method="POST" action="<?= admin_url('kepala-sekolah/delete/' . $item['id']) ?>"
                        data-confirm="Hapus data &quot;<?= esc($item['nama']) ?>&quot;?" data-confirm-ok="Ya, Hapus" data-confirm-class="btn-danger" data-confirm-type="danger">
                        <?= csrf_field() ?>
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Desktop Table (md+) -->
    <div class="d-none d-md-block">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px"></th>
                            <th>Nama</th>
                            <th style="width:180px">Periode</th>
                            <th>Keterangan</th>
                            <th style="width:60px">Urutan</th>
                            <th style="width:120px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($list as $item): ?>
                            <?php $aktif = empty($item['periode_selesai']); ?>
                            <tr>
                                <td class="text-center">
                                    <?php if (!empty($item['foto'])): ?>
                                        <img src="<?= base_url('uploads/kepala_sekolah/' . esc($item['foto'])) ?>"
                                            class="rounded-circle object-fit-cover"
                                            style="width:48px;height:48px;" alt="<?= esc($item['nama']) ?>">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center mx-auto"
                                            style="width:48px;height:48px;">
                                            <i class="bi bi-person text-secondary fs-5"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-semibold">
                                        <?php if ($item['gelar_depan']): ?>
                                            <span class="text-muted small"><?= esc($item['gelar_depan']) ?> </span>
                                        <?php endif; ?>
                                        <?= esc($item['nama']) ?>
                                        <?php if ($item['gelar_belakang']): ?>
                                            <span class="text-muted small">, <?= esc($item['gelar_belakang']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($aktif): ?>
                                        <span class="badge text-bg-success">Menjabat Saat Ini</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge text-bg-light border fw-normal">
                                        <?= $item['periode_mulai'] ?> –
                                        <?= $aktif ? '<span class="text-success fw-semibold">Sekarang</span>' : $item['periode_selesai'] ?>
                                    </span>
                                </td>
                                <td class="text-muted small"><?= esc(truncate_text($item['keterangan'] ?? '', 80)) ?></td>
                                <td class="text-muted small text-center"><?= $item['urutan'] ?></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="<?= admin_url('kepala-sekolah/edit/' . $item['id']) ?>"
                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="<?= admin_url('kepala-sekolah/delete/' . $item['id']) ?>"
                                            data-confirm="Hapus data &quot;<?= esc($item['nama']) ?>&quot;?" data-confirm-ok="Ya, Hapus" data-confirm-class="btn-danger" data-confirm-type="danger">
                                            <?= csrf_field() ?>
                                            <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

    <div class="text-muted small mt-2">
        <i class="bi bi-info-circle me-1"></i>
        Total: <?= count($list) ?> kepala sekolah. Urutan tampil dari yang paling lama menjabat.
    </div>
<?php endif; ?>
<?= $this->endSection() ?>
