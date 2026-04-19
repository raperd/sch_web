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
        <form method="post" action="<?= admin_url('menu/store') ?>">
            <?= csrf_field() ?>
            <div class="row g-2">
                <div class="col-6 col-md-2">
                    <input type="text" class="form-control form-control-sm" name="nama" placeholder="Nama Menu *" required value="<?= esc(old('nama')) ?>">
                </div>
                <div class="col-6 col-md-2">
                    <input type="text" class="form-control form-control-sm" name="url" placeholder="URL *" required value="<?= esc(old('url')) ?>">
                </div>
                <div class="col-6 col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i id="addIconPreview" class="bi bi-app text-muted"></i></span>
                        <input type="text" class="form-control" name="icon" id="addIcon" placeholder="Icon" value="<?= esc(old('icon')) ?>" readonly>
                        <button type="button" class="btn btn-outline-primary" onclick="openIconPicker('addIcon', 'addIconPreview')">Pilih</button>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <select name="lokasi" class="form-select form-select-sm" required>
                        <option value="publik" <?= old('lokasi') === 'publik' ? 'selected' : '' ?>>Publik</option>
                        <option value="footer" <?= old('lokasi') === 'footer' ? 'selected' : '' ?>>Footer</option>
                    </select>
                </div>
                <div class="col-4 col-md-1">
                    <input type="number" class="form-control form-control-sm" name="urutan" placeholder="Urutan" value="<?= esc(old('urutan', 0)) ?>" min="0">
                </div>
                <div class="col-4 col-md-1">
                    <select name="target" class="form-select form-select-sm">
                        <option value="_self">_self</option>
                        <option value="_blank">_blank</option>
                    </select>
                </div>
                <div class="col-4 col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-plus"></i> Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Menu Publik -->
<!-- Mobile Cards -->
<div class="d-md-none mb-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold border-bottom">
            <i class="bi bi-globe me-1 text-primary"></i>Menu Navigasi Publik
        </div>
        <div class="card-body p-3">
            <?php if (!empty($menus_publik)): ?>
                <?php foreach ($menus_publik as $m): ?>
                    <div class="d-flex align-items-center gap-2 py-2 border-bottom">
                        <div class="flex-grow-1" style="min-width:0">
                            <div class="fw-semibold"><?php if ($m['icon']): ?><i class="bi <?= esc($m['icon']) ?> me-1 text-muted"></i><?php endif; ?><?= esc($m['nama']) ?></div>
                            <code class="text-muted small"><?= esc($m['url']) ?></code>
                        </div>
                        <span class="badge <?= $m['is_active'] ? 'text-bg-success' : 'text-bg-secondary' ?> flex-shrink-0">
                            <?= $m['is_active'] ? 'Aktif' : 'Off' ?>
                        </span>
                        <button type="button" class="btn btn-sm btn-outline-primary flex-shrink-0"
                            onclick="openEditModal(<?= htmlspecialchars(json_encode($m), ENT_QUOTES) ?>)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form method="post" action="<?= admin_url('menu/delete/' . $m['id']) ?>"
                            data-confirm="Hapus menu ini?" data-confirm-ok="Ya, Hapus" data-confirm-class="btn-danger" data-confirm-type="danger">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger flex-shrink-0"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted text-center py-3 mb-0">Belum ada menu publik.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Desktop Table -->
<div class="d-none d-md-block">
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
                            <td class="fw-semibold">
                                <?php if ($m['icon']): ?><i class="bi <?= esc($m['icon']) ?> me-1 text-muted"></i><?php endif; ?>
                                <?= esc($m['nama']) ?>
                            </td>
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
                                <form method="post" action="<?= admin_url('menu/delete/' . $m['id']) ?>"
                                    class="d-inline" data-confirm="Hapus menu ini?" data-confirm-ok="Ya, Hapus" data-confirm-class="btn-danger" data-confirm-type="danger">
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
</div>

<!-- Menu Footer -->
<!-- Mobile Cards -->
<div class="d-md-none mb-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold border-bottom">
            <i class="bi bi-layout-text-window-reverse me-1 text-primary"></i>Menu Footer
        </div>
        <div class="card-body p-3">
            <?php if (!empty($menus_footer)): ?>
                <?php foreach ($menus_footer as $m): ?>
                    <div class="d-flex align-items-center gap-2 py-2 border-bottom">
                        <div class="flex-grow-1" style="min-width:0">
                            <div class="fw-semibold"><?php if ($m['icon']): ?><i class="bi <?= esc($m['icon']) ?> me-1 text-muted"></i><?php endif; ?><?= esc($m['nama']) ?></div>
                            <code class="text-muted small"><?= esc($m['url']) ?></code>
                        </div>
                        <span class="badge <?= $m['is_active'] ? 'text-bg-success' : 'text-bg-secondary' ?> flex-shrink-0">
                            <?= $m['is_active'] ? 'Aktif' : 'Off' ?>
                        </span>
                        <button type="button" class="btn btn-sm btn-outline-primary flex-shrink-0"
                            onclick="openEditModal(<?= htmlspecialchars(json_encode($m), ENT_QUOTES) ?>)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form method="post" action="<?= admin_url('menu/delete/' . $m['id']) ?>"
                            data-confirm="Hapus menu ini?" data-confirm-ok="Ya, Hapus" data-confirm-class="btn-danger" data-confirm-type="danger">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger flex-shrink-0"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted text-center py-3 mb-0">Belum ada menu footer.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Desktop Table -->
<div class="d-none d-md-block">
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
                            <td class="fw-semibold">
                                <?php if ($m['icon']): ?><i class="bi <?= esc($m['icon']) ?> me-1 text-muted"></i><?php endif; ?>
                                <?= esc($m['nama']) ?>
                            </td>
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
                                <form method="post" action="<?= admin_url('menu/delete/' . $m['id']) ?>"
                                    class="d-inline" data-confirm="Hapus menu ini?" data-confirm-ok="Ya, Hapus" data-confirm-class="btn-danger" data-confirm-type="danger">
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
                    <div class="mb-3">
                        <label class="form-label">Icon (Opsional)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i id="editIconPreview" class="bi bi-app"></i></span>
                            <input type="text" class="form-control" name="icon" id="editIcon" placeholder="Misal: bi-person" readonly>
                            <button type="button" class="btn btn-outline-primary" onclick="openIconPicker('editIcon', 'editIconPreview')">Pilih</button>
                        </div>
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

<!-- Icon Picker Modal -->
<div class="modal fade" id="iconPickerModal" tabindex="-1" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold">Pilih Icon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <div class="mb-3 text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm w-100 icon-picker-btn" data-icon="" title="Tanpa Icon">
                        <i class="bi bi-x-circle me-1"></i>Hapus Icon (Tanpa Icon)
                    </button>
                </div>
                <div class="row g-2">
                    <?php
                    $icons = [
                        'bi-house', 'bi-building', 'bi-mortarboard', 'bi-book', 'bi-book-half', 'bi-journal-text',
                        'bi-people', 'bi-person', 'bi-person-badge', 'bi-person-workspace', 'bi-briefcase', 'bi-tools',
                        'bi-newspaper', 'bi-megaphone', 'bi-calendar-event', 'bi-images', 'bi-camera', 'bi-award',
                        'bi-trophy', 'bi-info-circle', 'bi-envelope', 'bi-telephone', 'bi-geo-alt', 'bi-map',
                        'bi-globe', 'bi-link-45deg', 'bi-box-arrow-up-right', 'bi-chat-dots', 'bi-star', 'bi-laptop',
                        'bi-heart', 'bi-shield-check', 'bi-file-earmark-text', 'bi-list', 'bi-grid', 'bi-collection'
                    ];
                    foreach($icons as $icon): ?>
                        <div class="col-2 text-center">
                            <button type="button" class="btn btn-light border w-100 p-2 icon-picker-btn" data-icon="<?= $icon ?>" title="<?= $icon ?>">
                                <i class="bi <?= $icon ?> fs-5"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function openEditModal(menu) {
    document.getElementById('editMenuForm').action = '<?= admin_url('menu/update/') ?>' + menu.id;
    document.getElementById('editNama').value      = menu.nama;
    document.getElementById('editUrl').value       = menu.url;
    document.getElementById('editIcon').value      = menu.icon || '';
    document.getElementById('editIconPreview').className = menu.icon ? 'bi ' + menu.icon : 'bi bi-app';
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
    fetch('<?= admin_url('menu/urutan') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify(payload)
    });
}

// Icon Picker Logic
let currentIconTarget = null;
let currentIconPreview = null;
let iconPickerModalInstance = null;
function openIconPicker(targetId, previewId) {
    currentIconTarget = document.getElementById(targetId);
    currentIconPreview = document.getElementById(previewId);
    if (!iconPickerModalInstance) {
        iconPickerModalInstance = new bootstrap.Modal(document.getElementById('iconPickerModal'));
    }
    iconPickerModalInstance.show();
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.icon-picker-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if(currentIconTarget) {
                currentIconTarget.value = this.dataset.icon;
            }
            if(currentIconPreview) {
                currentIconPreview.className = this.dataset.icon ? 'bi ' + this.dataset.icon : 'bi bi-app text-muted';
            }
            if (iconPickerModalInstance) {
                iconPickerModalInstance.hide();
            } else {
                bootstrap.Modal.getInstance(document.getElementById('iconPickerModal')).hide();
            }
        });
    });
});
</script>

<?= $this->endSection() ?>
