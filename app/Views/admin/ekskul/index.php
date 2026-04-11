<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">Ekstrakurikuler</h4>
        <p class="text-muted small mb-0">Kelola kegiatan pengembangan diri siswa</p>
    </div>
    <a href="<?= base_url('admin/ekskul/create') ?>" class="btn btn-primary btn-lg">
        <i class="bi bi-plus-circle me-1"></i>Tambah
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6"><div class="card border-0 shadow-sm text-center p-3"><div class="fs-3 fw-bold text-primary"><?= $total ?></div><small class="text-muted">Total</small></div></div>
    <div class="col-6"><div class="card border-0 shadow-sm text-center p-3"><div class="fs-3 fw-bold text-success"><?= $aktif ?></div><small class="text-muted">Aktif</small></div></div>
</div>

<!-- Mobile Cards (xs/sm) -->
<div class="d-md-none">
    <?php if (!empty($ekskul)): ?>
        <?php foreach ($ekskul as $e): ?>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-3">
                    <div class="d-flex gap-3 mb-2">
                        <?php if (!empty($e['foto'])): ?>
                            <img src="<?= base_url('uploads/ekskul/' . esc($e['foto'])) ?>"
                                class="rounded flex-shrink-0" style="width:52px;height:42px;object-fit:cover" alt="">
                        <?php endif; ?>
                        <div>
                            <div class="fw-semibold"><?= esc($e['nama']) ?></div>
                            <span class="badge <?= $e['is_active'] ? 'text-bg-success' : 'text-bg-secondary' ?>">
                                <?= $e['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                            </span>
                        </div>
                    </div>
                    <?php if (!empty($e['pembina']) || !empty($e['jadwal'])): ?>
                        <div class="small text-muted">
                            <?php if (!empty($e['pembina'])): ?>
                                <div><i class="bi bi-person me-1"></i><?= esc($e['pembina']) ?></div>
                            <?php endif; ?>
                            <?php if (!empty($e['jadwal'])): ?>
                                <div><i class="bi bi-clock me-1"></i><?= esc($e['jadwal']) ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-white border-top p-2 d-flex gap-2 justify-content-end">
                    <a href="<?= base_url('admin/ekskul/edit/' . $e['id']) ?>" class="btn btn-sm btn-outline-primary flex-grow-1">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <form method="post" action="<?= base_url('admin/ekskul/delete/' . $e['id']) ?>"
                        data-confirm="Hapus?" data-confirm-ok="Ya, Hapus" data-confirm-class="btn-danger" data-confirm-type="danger">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-trophy display-5 d-block mb-2 opacity-25"></i>
            Belum ada ekstrakurikuler. <a href="<?= base_url('admin/ekskul/create') ?>">Tambah sekarang</a>
        </div>
    <?php endif; ?>
</div>

<!-- Desktop Table (md+) -->
<div class="d-none d-md-block">
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:50px">#</th>
                    <th>Nama</th>
                    <th class="d-none d-md-table-cell">Pembina</th>
                    <th class="d-none d-md-table-cell">Jadwal</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($ekskul)): ?>
                    <?php foreach ($ekskul as $i => $e): ?>
                        <tr>
                            <td class="text-muted"><?= $i + 1 ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if (!empty($e['foto'])): ?>
                                        <img src="<?= base_url('uploads/ekskul/' . esc($e['foto'])) ?>"
                                            class="rounded" style="width:42px;height:36px;object-fit:cover" alt="">
                                    <?php endif; ?>
                                    <div class="fw-semibold"><?= esc($e['nama']) ?></div>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell text-muted small"><?= esc($e['pembina']) ?: '—' ?></td>
                            <td class="d-none d-md-table-cell text-muted small"><?= esc($e['jadwal']) ?: '—' ?></td>
                            <td class="text-center">
                                <span class="badge <?= $e['is_active'] ? 'text-bg-success' : 'text-bg-secondary' ?>">
                                    <?= $e['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="<?= base_url('admin/ekskul/edit/' . $e['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form method="post" action="<?= base_url('admin/ekskul/delete/' . $e['id']) ?>" class="d-inline" data-confirm="Hapus?" data-confirm-ok="Ya, Hapus" data-confirm-class="btn-danger" data-confirm-type="danger">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-trophy display-5 d-block mb-2 opacity-25"></i>
                        Belum ada ekstrakurikuler. <a href="<?= base_url('admin/ekskul/create') ?>">Tambah sekarang</a>
                    </td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<?= $this->endSection() ?>
