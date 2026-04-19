<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Nilai Sekolah</h4>
        <small class="text-muted">Kelola nilai-nilai unggulan yang tampil di halaman Profil Visi & Misi</small>
    </div>
    <a href="<?= admin_url('nilai-sekolah/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah Nilai
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <?php if (!empty($nilai)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center ps-3" style="width: 50px;">Urutan</th>
                            <th>Icon</th>
                            <th>Judul Nilai</th>
                            <th>Deskripsi</th>
                            <th class="text-end pe-3" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($nilai as $n): ?>
                        <tr>
                            <td class="text-center ps-3 text-muted"><?= esc($n['urutan']) ?></td>
                            <td>
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                    <i class="bi <?= esc($n['icon']) ?> fs-4"></i>
                                </div>
                            </td>
                            <td class="fw-semibold"><?= esc($n['nama']) ?></td>
                            <td class="text-muted small"><?= esc($n['deskripsi']) ?></td>
                            <td class="text-end pe-3">
                                <a href="<?= admin_url('nilai-sekolah/edit/' . $n['id']) ?>" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="post" action="<?= admin_url('nilai-sekolah/delete/' . $n['id']) ?>" class="d-inline" data-confirm="Hapus nilai sekolah ini?">
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
                <i class="bi bi-award display-3 mb-3 d-block"></i>
                <p>Belum ada nilai sekolah. <a href="<?= admin_url('nilai-sekolah/create') ?>">Tambah sekarang</a></p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
