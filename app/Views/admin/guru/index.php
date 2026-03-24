<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">Manajemen Guru & Staf</h4>
        <p class="text-muted small mb-0">Kelola data tenaga pendidik dan kependidikan</p>
    </div>
    <a href="<?= base_url('admin/guru/create') ?>" class="btn btn-primary btn-lg">
        <i class="bi bi-plus-circle me-1"></i>Tambah
    </a>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <?php foreach (['Semua' => $total_all, 'Guru' => $total_guru, 'Staf' => $total_staf, 'Tendik' => $total_tendik] as $lbl => $val): ?>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fs-3 fw-bold text-primary"><?= $val ?></div>
                <small class="text-muted"><?= $lbl ?></small>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Filter -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="get" action="<?= base_url('admin/guru') ?>" class="row g-2 align-items-end">
            <div class="col-sm-5 col-md-6">
                <label class="form-label form-label-sm">Cari</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="q" value="<?= esc($search) ?>" placeholder="Nama atau jabatan...">
                </div>
            </div>
            <div class="col-sm-3 col-md-3">
                <label class="form-label form-label-sm">Tipe</label>
                <select name="tipe" class="form-select form-select-sm">
                    <option value="">Semua Tipe</option>
                    <option value="guru"   <?= $tipe_filter === 'guru'   ? 'selected' : '' ?>>Guru</option>
                    <option value="staf"   <?= $tipe_filter === 'staf'   ? 'selected' : '' ?>>Staf</option>
                    <option value="tendik" <?= $tipe_filter === 'tendik' ? 'selected' : '' ?>>Tendik</option>
                </select>
            </div>
            <div class="col-sm-4 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Filter</button>
                <a href="<?= base_url('admin/guru') ?>" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Tabel -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="guruTable">
            <thead class="table-light">
                <tr>
                    <th style="width:36px" class="text-muted small text-center">#</th>
                    <th>Nama</th>
                    <th class="d-none d-md-table-cell">Jabatan / Bidang</th>
                    <th class="text-center">Tipe</th>
                    <th class="text-center">Aktif</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody id="guruSortable">
                <?php if (!empty($guru)): ?>
                    <?php foreach ($guru as $g): ?>
                        <tr data-id="<?= $g['id'] ?>">
                            <td class="text-center text-muted" style="cursor:grab">
                                <i class="bi bi-grip-vertical"></i>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <?php if (!empty($g['foto'])): ?>
                                        <img src="<?= base_url('uploads/guru/' . esc($g['foto'])) ?>"
                                            class="rounded-circle flex-shrink-0"
                                            style="width:42px;height:42px;object-fit:cover" alt="">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="width:42px;height:42px">
                                            <i class="bi bi-person text-secondary"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="fw-semibold"><?= esc($g['nama']) ?></div>
                                        <?php if (!empty($g['nip'])): ?>
                                            <small class="text-muted">NIP: <?= esc($g['nip']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <div><?= esc($g['jabatan']) ?></div>
                                <?php if (!empty($g['bidang_nama'])): ?>
                                    <small class="text-muted"><?= esc($g['bidang_nama']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php
                                $tipeBadge = ['guru' => 'primary', 'staf' => 'info', 'tendik' => 'secondary'];
                                $tipeLabel = ['guru' => 'Guru', 'staf' => 'Staf', 'tendik' => 'Tendik'];
                                $cls = $tipeBadge[$g['tipe']] ?? 'secondary';
                                $lbl = $tipeLabel[$g['tipe']] ?? $g['tipe'];
                                ?>
                                <span class="badge text-bg-<?= $cls ?>"><?= $lbl ?></span>
                            </td>
                            <td class="text-center">
                                <span class="badge <?= $g['is_active'] ? 'text-bg-success' : 'text-bg-danger' ?>">
                                    <?= $g['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="<?= base_url('admin/guru/edit/' . $g['id']) ?>"
                                        class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="post" action="<?= base_url('admin/guru/delete/' . $g['id']) ?>"
                                        class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-people display-5 d-block mb-2 opacity-25"></i>
                            Belum ada data.
                            <a href="<?= base_url('admin/guru/create') ?>">Tambah sekarang</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if (isset($pager)): ?>
        <div class="card-footer bg-white border-top-0 d-flex justify-content-center py-3">
            <?= $pager->links('guru', 'default_full') ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Drag-drop reorder via native HTML5 drag events (no library needed)
const tbody = document.getElementById('guruSortable');
if (tbody) {
    let dragging = null;

    tbody.querySelectorAll('tr[data-id]').forEach(row => {
        row.draggable = true;
        row.addEventListener('dragstart', () => { dragging = row; row.style.opacity = '.4'; });
        row.addEventListener('dragend',   () => { dragging = null; row.style.opacity = '1'; saveOrder(); });
        row.addEventListener('dragover',  e => { e.preventDefault(); const after = getDragAfterElement(tbody, e.clientY); if (after == null) { tbody.appendChild(dragging); } else { tbody.insertBefore(dragging, after); } });
    });

    function getDragAfterElement(container, y) {
        const rows = [...container.querySelectorAll('tr[data-id]:not(.dragging)')];
        return rows.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            return (offset < 0 && offset > closest.offset) ? { offset, element: child } : closest;
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    function saveOrder() {
        const rows = [...tbody.querySelectorAll('tr[data-id]')];
        const payload = rows.map((row, i) => ({ id: parseInt(row.dataset.id), urutan: i + 1 }));
        fetch('<?= base_url('admin/guru/urutan') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify(payload)
        });
    }
}
</script>
<?= $this->endSection() ?>
