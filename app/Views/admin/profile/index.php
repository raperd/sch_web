<?= $this->extend('layouts/admin') ?>

<?= $this->section('styles') ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" rel="stylesheet">
<style>
#avatarPreviewWrap { position:relative; display:inline-block; }
#avatarPreviewWrap .avatar-edit-btn {
    position:absolute; bottom:4px; right:4px;
    width:28px; height:28px; border-radius:50%;
    background:var(--bs-primary); color:#fff;
    border:2px solid #fff; cursor:pointer;
    display:flex; align-items:center; justify-content:center;
    font-size:.75rem; transition:.2s;
}
#avatarPreviewWrap .avatar-edit-btn:hover { transform:scale(1.1); }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Profil Saya</h4>
        <small class="text-muted">Kelola informasi akun dan ganti password</small>
    </div>
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

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0">
            <?php foreach ((array) session()->getFlashdata('errors') as $err): ?>
                <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-4">

    <!-- Kartu Informasi Akun -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom fw-semibold py-3">
                <i class="bi bi-person-circle me-2 text-primary"></i>Informasi Akun
            </div>
            <div class="card-body">
                <!-- Foto Profil + Nama -->
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <div id="avatarPreviewWrap">
                        <?php if (!empty($user['avatar'])): ?>
                            <img id="avatarCurrentImg"
                                src="<?= base_url('uploads/users/' . esc($user['avatar'])) ?>"
                                class="rounded-circle"
                                style="width:72px;height:72px;object-fit:cover;border:3px solid var(--bs-primary);"
                                alt="Avatar">
                        <?php else: ?>
                            <div id="avatarPlaceholder" class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold"
                                style="width:72px;height:72px;font-size:1.75rem;">
                                <?= strtoupper(mb_substr($user['nama'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                        <button type="button" class="avatar-edit-btn" title="Ubah foto" onclick="document.getElementById('avatarFileInput').click()">
                            <i class="bi bi-camera-fill"></i>
                        </button>
                    </div>
                    <div>
                        <div class="fw-bold fs-5"><?= esc($user['nama']) ?></div>
                        <span class="badge <?= $user['role'] === 'superadmin' ? 'text-bg-danger' : ($user['role'] === 'admin' ? 'text-bg-primary' : 'text-bg-secondary') ?>">
                            <?= ucfirst($user['role']) ?>
                        </span>
                        <?php if (!empty($user['avatar'])): ?>
                        <form method="post" action="<?= admin_url('profile/delete-avatar') ?>" class="d-inline ms-1"
                            data-confirm="Hapus foto profil?" data-confirm-ok="Ya, Hapus" data-confirm-class="btn-danger" data-confirm-type="danger">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Hapus foto">
                                <i class="bi bi-trash small"></i>
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Hidden file input for avatar crop -->
                <input type="file" id="avatarFileInput" accept="image/jpeg,image/png,image/webp" class="d-none">

                <form action="<?= admin_url('profile/update-info') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control"
                            value="<?= esc(old('nama', $user['nama'])) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" class="form-control bg-light" value="<?= esc($user['username']) ?>" disabled>
                        <div class="form-text">Username tidak dapat diubah sendiri.</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control"
                            value="<?= esc(old('email', $user['email'] ?? '')) ?>" placeholder="email@contoh.com">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Kartu Ganti Password -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom fw-semibold py-3">
                <i class="bi bi-shield-lock me-2 text-warning"></i>Ganti Password
            </div>
            <div class="card-body">
                <p class="text-muted small mb-4">Password baru minimal 8 karakter. Gunakan kombinasi huruf, angka, dan simbol untuk keamanan lebih baik.</p>

                <form action="<?= admin_url('profile/change-password') ?>" method="POST" id="changePassForm">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Lama <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password_lama" id="passLama" class="form-control" required autocomplete="current-password">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePass('passLama')">
                                <i class="bi bi-eye" id="eyePassLama"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password_baru" id="passBaru" class="form-control" required
                                minlength="8" autocomplete="new-password" oninput="checkStrength(this.value)">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePass('passBaru')">
                                <i class="bi bi-eye" id="eyePassBaru"></i>
                            </button>
                        </div>
                        <!-- Password strength bar -->
                        <div class="progress mt-2" style="height:4px;">
                            <div class="progress-bar" id="strengthBar" style="width:0%;transition:.3s;"></div>
                        </div>
                        <div class="form-text" id="strengthText"></div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="konfirmasi" id="passKonfirmasi" class="form-control" required
                                minlength="8" autocomplete="new-password" oninput="checkMatch()">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePass('passKonfirmasi')">
                                <i class="bi bi-eye" id="eyePassKonfirmasi"></i>
                            </button>
                        </div>
                        <div class="form-text" id="matchText"></div>
                    </div>
                    <button type="submit" class="btn btn-warning text-dark">
                        <i class="bi bi-shield-check me-1"></i>Ubah Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Info Login Terakhir -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="row text-center g-3">
                    <div class="col-md-4">
                        <div class="text-muted small">Username</div>
                        <div class="fw-semibold"><?= esc($user['username']) ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Login Terakhir</div>
                        <div class="fw-semibold">
                            <?= $user['last_login_at'] ? format_tanggal($user['last_login_at'], 'full') : '-' ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Akun Dibuat</div>
                        <div class="fw-semibold"><?= format_tanggal($user['created_at'], 'full') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- ===== Modal Crop Avatar ===== -->
<div class="modal fade" id="avatarCropModal" tabindex="-1" aria-labelledby="avatarCropModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="avatarCropModalLabel"><i class="bi bi-crop me-2"></i>Sesuaikan Foto Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="max-height:65vh;overflow:hidden;background:#1a1a1a;">
                <img id="cropperAvatarImg" src="" style="display:block;max-width:100%;" alt="Crop preview">
            </div>
            <div class="modal-footer justify-content-between">
                <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Geser & zoom untuk memilih area wajah</small>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btnCropAvatar">
                        <i class="bi bi-check-lg me-1"></i>Gunakan Foto Ini
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form tersembunyi untuk upload avatar -->
<form id="avatarUploadForm" method="post" action="<?= admin_url('profile/update-avatar') ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="avatar_cropped" id="avatarCroppedInput">
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script>
function togglePass(id) {
    const input = document.getElementById(id);
    const eye   = document.getElementById('eye' + id.charAt(0).toUpperCase() + id.slice(1));
    if (input.type === 'password') {
        input.type = 'text';
        eye.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        eye.className = 'bi bi-eye';
    }
}

function checkStrength(val) {
    const bar  = document.getElementById('strengthBar');
    const text = document.getElementById('strengthText');
    let score  = 0;
    if (val.length >= 8)  score++;
    if (val.length >= 12) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const levels = [
        { pct: 20,  cls: 'bg-danger',  label: 'Sangat Lemah' },
        { pct: 40,  cls: 'bg-danger',  label: 'Lemah' },
        { pct: 60,  cls: 'bg-warning', label: 'Cukup' },
        { pct: 80,  cls: 'bg-info',    label: 'Kuat' },
        { pct: 100, cls: 'bg-success', label: 'Sangat Kuat' },
    ];
    const lvl = levels[Math.max(0, score - 1)] || levels[0];
    bar.style.width = val.length ? lvl.pct + '%' : '0%';
    bar.className   = 'progress-bar ' + (val.length ? lvl.cls : '');
    text.textContent = val.length ? lvl.label : '';
}

function checkMatch() {
    const baru   = document.getElementById('passBaru').value;
    const konfirm = document.getElementById('passKonfirmasi').value;
    const text   = document.getElementById('matchText');
    if (!konfirm) { text.textContent = ''; return; }
    if (baru === konfirm) {
        text.textContent = '✓ Password cocok';
        text.className   = 'form-text text-success';
    } else {
        text.textContent = '✗ Password tidak cocok';
        text.className   = 'form-text text-danger';
    }
}

// ===== AVATAR CROP =====
let avatarCropper = null;
const avatarFileInput  = document.getElementById('avatarFileInput');
const cropperImg       = document.getElementById('cropperAvatarImg');
const avatarCropModal  = new bootstrap.Modal(document.getElementById('avatarCropModal'));

avatarFileInput.addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        cropperImg.src = e.target.result;
        avatarCropModal.show();
        // Init/re-init cropper setelah modal tampil
        document.getElementById('avatarCropModal').addEventListener('shown.bs.modal', function initCropper() {
            if (avatarCropper) { avatarCropper.destroy(); avatarCropper = null; }
            avatarCropper = new Cropper(cropperImg, {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.75,
                restore: false,
                guides: true,
                center: true,
                highlight: true,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
                minContainerHeight: 300,
            });
            this.removeEventListener('shown.bs.modal', initCropper);
        }, { once: true });
    };
    reader.readAsDataURL(file);
    this.value = ''; // reset agar bisa pilih file sama lagi
});

document.getElementById('btnCropAvatar').addEventListener('click', function() {
    if (!avatarCropper) return;
    const canvas = avatarCropper.getCroppedCanvas({ width: 200, height: 200 });
    const dataUrl = canvas.toDataURL('image/jpeg', 0.88);

    // Tampilkan preview langsung
    const existing = document.getElementById('avatarCurrentImg');
    const placeholder = document.getElementById('avatarPlaceholder');
    if (existing) {
        existing.src = dataUrl;
    } else if (placeholder) {
        const img = document.createElement('img');
        img.id = 'avatarCurrentImg';
        img.src = dataUrl;
        img.className = 'rounded-circle';
        img.style.cssText = 'width:72px;height:72px;object-fit:cover;border:3px solid var(--bs-primary);';
        img.alt = 'Avatar';
        placeholder.replaceWith(img);
    }

    // Set ke hidden input & submit
    document.getElementById('avatarCroppedInput').value = dataUrl;
    avatarCropModal.hide();

    if (avatarCropper) { avatarCropper.destroy(); avatarCropper = null; }

    document.getElementById('avatarUploadForm').submit();
});
</script>
<?= $this->endSection() ?>
