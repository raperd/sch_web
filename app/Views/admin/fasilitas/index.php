<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">Fasilitas Sekolah</h4>
        <p class="text-muted small mb-0">Kelola data sarana dan prasarana</p>
    </div>
    <a href="<?= base_url('admin/fasilitas/create') ?>" class="btn btn-primary btn-lg">
        <i class="bi bi-plus-circle me-1"></i>Tambah
    </a>
</div>

<div class="card border-0 shadow-sm mb-4 text-center p-3 d-inline-block">
    <div class="fs-3 fw-bold text-primary"><?= $total ?></div>
    <small class="text-muted">Total Fasilitas</small>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:50px">#</th>
                    <th>Fasilitas</th>
                    <th class="d-none d-md-table-cell text-center">Jumlah</th>
                    <th class="d-none d-md-table-cell text-center">Kondisi</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($fasilitas)): ?>
                    <?php foreach ($fasilitas as $i => $f): ?>
                        <tr>
                            <td class="text-muted"><?= $i + 1 ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if (!empty($f['foto'])): ?>
                                        <img src="<?= base_url('uploads/fasilitas/' . esc($f['foto'])) ?>"
                                            class="rounded" style="width:42px;height:36px;object-fit:cover" alt="">
                                    <?php elseif (!empty($f['icon'])): ?>
                                        <div class="rounded bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:42px;height:36px">
                                            <i class="bi <?= esc($f['icon']) ?> text-primary"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="fw-semibold"><?= esc($f['nama']) ?></div>
                                        <?php if (!empty($f['deskripsi'])): ?>
                                            <small class="text-muted"><?= truncate_text($f['deskripsi'], 60) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell text-center"><?= $f['jumlah'] ?: '—' ?></td>
                            <td class="d-none d-md-table-cell text-center">
                                <?php
                                $kondisiMap = ['baik' => ['success', 'Baik'], 'rusak_ringan' => ['warning', 'Rusak Ringan'], 'rusak_berat' => ['danger', 'Rusak Berat']];
                                [$cls, $lbl] = $kondisiMap[$f['kondisi']] ?? ['secondary', $f['kondisi']];
                                ?>
                                <span class="badge text-bg-<?= $cls ?>"><?= $lbl ?></span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="<?= base_url('admin/fasilitas/edit/' . $f['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form method="post" action="<?= base_url('admin/fasilitas/delete/' . $f['id']) ?>" class="d-inline" onsubmit="return confirm('Hapus?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center py-5 text-muted">
                        <i class="bi bi-building display-5 d-block mb-2 opacity-25"></i>
                        Belum ada data fasilitas. <a href="<?= base_url('admin/fasilitas/create') ?>">Tambah sekarang</a>
                    </td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
