<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">Program Unggulan</h4>
        <p class="text-muted small mb-0">Kartu program yang tampil di halaman <strong>/akademik</strong></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('admin/akademik/kurikulum') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-journal-text me-1"></i>Kurikulum
        </a>
        <a href="<?= base_url('admin/akademik/program/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Tambah Program
        </a>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= esc(session()->getFlashdata('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th width="50">No</th>
                    <th>Program</th>
                    <th width="100">Icon</th>
                    <th width="80">Urutan</th>
                    <th width="80">Status</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($programs)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Belum ada program. <a href="<?= base_url('admin/akademik/program/create') ?>">Tambah sekarang</a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($programs as $i => $p): ?>
                        <tr>
                            <td class="text-muted"><?= $i + 1 ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="rounded-circle bg-<?= esc($p['warna']) ?> bg-opacity-15 d-flex align-items-center justify-content-center flex-shrink-0"
                                          style="width:42px;height:42px;">
                                        <i class="bi <?= esc($p['icon']) ?> text-<?= esc($p['warna']) ?>"></i>
                                    </span>
                                    <div>
                                        <div class="fw-semibold"><?= esc($p['judul']) ?></div>
                                        <div class="text-muted small"><?= esc(mb_strimwidth($p['deskripsi'] ?? '', 0, 70, '...')) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><code class="small"><?= esc($p['icon']) ?></code></td>
                            <td><?= $p['urutan'] ?></td>
                            <td>
                                <?php if ($p['is_active']): ?>
                                    <span class="badge text-bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge text-bg-secondary">Non-aktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= base_url('admin/akademik/program/' . $p['id'] . '/edit') ?>"
                                   class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="post" action="<?= base_url('admin/akademik/program/' . $p['id'] . '/delete') ?>"
                                      class="d-inline"
                                      onsubmit="return confirm('Hapus program ini?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
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

<?= $this->endSection() ?>
