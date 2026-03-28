<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= base_url('admin/nilai-sekolah') ?>" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <h4 class="fw-bold mb-0"><?= esc($title) ?></h4>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <?php if (session()->has('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    <?php foreach (session('errors') as $e): ?>
                        <li><?= esc($e) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('admin/nilai-sekolah/' . ($nilai ? 'update/'.$nilai['id'] : 'store')) ?>">
            <?= csrf_field() ?>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Judul Nilai Sekolah <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nama" value="<?= esc(old('nama', $nilai['nama'] ?? '')) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Icon</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i id="iconPreview" class="bi <?= esc(old('icon', $nilai['icon'] ?? 'bi-award')) ?> text-primary"></i></span>
                        <input type="text" class="form-control" name="icon" id="iconInput" value="<?= esc(old('icon', $nilai['icon'] ?? '')) ?>" placeholder="Misal: bi-award" readonly>
                        <button type="button" class="btn btn-outline-primary" onclick="openIconPicker('iconInput', 'iconPreview')">Pilih</button>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Urutan</label>
                    <input type="number" class="form-control" name="urutan" value="<?= esc(old('urutan', $nilai['urutan'] ?? ($next_urutan ?? 0))) ?>" min="0">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Deskripsi Singkat <span class="text-danger">*</span></label>
                <textarea class="form-control" name="deskripsi" rows="3" required><?= esc(old('deskripsi', $nilai['deskripsi'] ?? '')) ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-save me-1"></i>Simpan
            </button>
        </form>
    </div>
</div>

<!-- Icon Picker Modal -->
<div class="modal fade" id="iconPickerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold">Pilih Icon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <div class="row g-2">
                    <?php
                    $icons = [
                        'bi-award', 'bi-heart', 'bi-globe', 'bi-shield-check', 'bi-star', 'bi-book',
                        'bi-people', 'bi-person', 'bi-gem', 'bi-lightbulb', 'bi-trophy', 'bi-mortarboard',
                        'bi-building', 'bi-flag', 'bi-compass', 'bi-geo-alt', 'bi-laptop', 'bi-check-circle'
                    ];
                    foreach($icons as $ic): ?>
                        <div class="col-2 text-center">
                            <button type="button" class="btn btn-light border w-100 p-2 icon-picker-btn" data-icon="<?= $ic ?>">
                                <i class="bi <?= $ic ?> fs-5"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let currentIconTarget = null;
let currentIconPreview = null;
let iconPickerModalInst = null;
function openIconPicker(targetId, previewId) {
    currentIconTarget = document.getElementById(targetId);
    currentIconPreview = document.getElementById(previewId);
    if (!iconPickerModalInst) iconPickerModalInst = new bootstrap.Modal(document.getElementById('iconPickerModal'));
    iconPickerModalInst.show();
}
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.icon-picker-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if(currentIconTarget) currentIconTarget.value = this.dataset.icon;
            if(currentIconPreview) currentIconPreview.className = 'bi ' + this.dataset.icon + ' text-primary';
            if(iconPickerModalInst) iconPickerModalInst.hide();
        });
    });
});
</script>
<?= $this->endSection() ?>
