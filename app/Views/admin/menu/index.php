<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="mb-4">
    <h4 class="fw-bold mb-0">Manajemen Menu</h4>
    <p class="text-muted small mb-0">Kelola menu navigasi publik, footer, dan quick links</p>
</div>

<!-- Tambah Menu -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-semibold border-bottom">
        <i class="bi bi-plus-circle me-1 text-primary"></i>Tambah Menu Baru
    </div>
    <div class="card-body p-3">
        <?php if (session()->has('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show py-2">
                <ul class="mb-0 small"><?php foreach (session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <form method="post" action="<?= base_url('admin/menu/store') ?>">
            <?= csrf_field() ?>
            <div class="row g-2">
                <div class="col-6 col-md-3">
                    <input type="text" class="form-control form-control-sm" name="nama" placeholder="Nama Menu *" required value="<?= esc(old('nama')) ?>">
                </div>
                <div class="col-6 col-md-3">
                    <input type="text" class="form-control form-control-sm" name="url" placeholder="URL (misal: /profil) *" required value="<?= esc(old('url')) ?>">
                </div>
                <div class="col-6 col-md-2">
                    <select name="lokasi" class="form-select form-select-sm" required>
                        <option value="publik" <?= old('lokasi') === 'publik' ? 'selected' : '' ?>>Publik</option>
                        <option value="footer" <?= old('lokasi') === 'footer' ? 'selected' : '' ?>>Footer</option>
                    </select>
                </div>
                <div class="col-6 col-md-1">
                    <input type="number" class="form-control form-control-sm" name="urutan" placeholder="Urutan" value="<?= esc(old('urutan', 0)) ?>" min="0">
                </div>
                <div class="col-6 col-md-2">
                    <select name="target" class="form-select form-select-sm">
                        <option value="_self">_self</option>
                        <option value="_blank">_blank</option>
                    </select>
                </div>
                <div class="col-6 col-md-1">
                    <button type="submit" class="btn btn-primary btn-sm w-100">Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Menu Publik -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-semibold border-bottom">
        <i class="bi bi-globe me-1 text-primary"></i>Menu Navigasi Publik
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:30px"></th>
                    <th>Nama</th>
                    <th>URL</th>
                    <th class="text-center">Urutan</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody id="menuPubSortable">
                <?php if (!empty($menus_publik)): ?>
                    <?php foreach ($menus_publik as $m): ?>
                        <tr data-id="<?= $m['id'] ?>">
                            <td class="text-muted" style="cursor:grab"><i class="bi bi-grip-vertical"></i></td>
                            <td class="fw-semibold"><?= esc($m['nama']) ?></td>
                            <td class="text-muted small font-monospace"><?= esc($m['url']) ?></td>
                            <td class="text-center"><?= $m['urutan'] ?></td>
                            <td class="text-center">
                                <span class="badge <?= $m['is_active'] ? 'text-bg-success' : 'text-bg-secondary' ?>">
                                    <?= $m['is_active'] ? 'Aktif' : 'Off' ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                    onclick="openEditModal(<?= htmlspecialchars(json_encode($m), ENT_QUOTES) ?>)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="post" action="<?= base_url('admin/menu/delete/' . $m['id']) ?>"
                                    class="d-inline" onsubmit="return confirm('Hapus menu ini?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center text-muted py-3">Belum ada menu publik.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Menu Footer -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-semibold border-bottom">
        <i class="bi bi-layout-text-window-reverse me-1 text-primary"></i>Menu Footer
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:30px"></th>
                    <th>Nama</th>
                    <th>URL</th>
                    <th class="text-center">Urutan</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody id="menuFootSortable">
                <?php if (!empty($menus_footer)): ?>
                    <?php foreach ($menus_footer as $m): ?>
                        <tr data-id="<?= $m['id'] ?>">
                            <td class="text-muted" style="cursor:grab"><i class="bi bi-grip-vertical"></i></td>
                            <td class="fw-semibold"><?= esc($m['nama']) ?></td>
                            <td class="text-muted small font-monospace"><?= esc($m['url']) ?></td>
                            <td class="text-center"><?= $m['urutan'] ?></td>
                            <td class="text-center">
                                <span class="badge <?= $m['is_active'] ? 'text-bg-success' : 'text-bg-secondary' ?>">
                                    <?= $m['is_active'] ? 'Aktif' : 'Off' ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                    onclick="openEditModal(<?= htmlspecialchars(json_encode($m), ENT_QUOTES) ?>)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="post" action="<?= base_url('admin/menu/delete/' . $m['id']) ?>"
                                    class="d-inline" onsubmit="return confirm('Hapus menu ini?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center text-muted py-3">Belum ada menu footer.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editMenuModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" id="editMenuForm">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold">Edit Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control" name="nama" id="editNama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL</label>
                        <input type="text" class="form-control" name="url" id="editUrl" required>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Urutan</label>
                            <input type="number" class="form-control" name="urutan" id="editUrutan" min="0">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Target</label>
                            <select name="target" class="form-select" id="editTarget">
                                <option value="_self">_self</option>
                                <option value="_blank">_blank</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="editIsActive" name="is_active" value="1">
                            <label class="form-check-label" for="editIsActive">Aktif</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function openEditModal(menu) {
    document.getElementById('editMenuForm').action = '<?= base_url('admin/menu/update/') ?>' + menu.id;
    document.getElementById('editNama').value      = menu.nama;
    document.getElementById('editUrl').value       = menu.url;
    document.getElementById('editUrutan').value    = menu.urutan;
    document.getElementById('editTarget').value    = menu.target || '_self';
    document.getElementById('editIsActive').checked = menu.is_active == 1;
    new bootstrap.Modal(document.getElementById('editMenuModal')).show();
}

// Drag-drop for each table body
['menuPubSortable', 'menuFootSortable'].forEach(id => {
    const tbody = document.getElementById(id);
    if (!tbody) return;
    let dragging = null;
    tbody.querySelectorAll('tr[data-id]').forEach(row => {
        row.draggable = true;
        row.addEventListener('dragstart', () => { dragging = row; row.style.opacity = '.4'; });
        row.addEventListener('dragend', () => { dragging = null; row.style.opacity = '1'; saveOrder(tbody); });
        row.addEventListener('dragover', e => {
            e.preventDefault();
            const rows = [...tbody.querySelectorAll('tr[data-id]')];
            const after = rows.find(r => { const b = r.getBoundingClientRect(); return e.clientY < b.top + b.height / 2; });
            if (after) tbody.insertBefore(dragging, after); else tbody.appendChild(dragging);
        });
    });
});

function saveOrder(tbody) {
    const rows = [...tbody.querySelectorAll('tr[data-id]')];
    const payload = rows.map((r, i) => ({ id: parseInt(r.dataset.id), urutan: i + 1 }));
    fetch('<?= base_url('admin/menu/urutan') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify(payload)
    });
}
</script>
<?= $this->endSection() ?>
