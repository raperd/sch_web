<?= $this->extend('layouts/admin') ?>

<?= $this->section('styles') ?>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" rel="stylesheet">
<style>
#quill-editor { min-height: 360px; font-size: 1rem; }
.ql-toolbar.ql-snow { border-radius: .375rem .375rem 0 0; }
.ql-container.ql-snow { border-radius: 0 0 .375rem .375rem; font-size: 1rem; }
.thumb-preview { max-height: 200px; object-fit: cover; border-radius: .5rem; width:100%; }
#cropImageThumb { max-width: 100%; display: block; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?= base_url('admin/artikel') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-0">Edit Artikel</h4>
        <p class="text-muted small mb-0"><?= esc(truncate_text($artikel['judul'], 60)) ?></p>
    </div>
</div>

<?php if (session()->has('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Periksa kembali:</strong>
        <ul class="mb-0 mt-1">
            <?php foreach (session('errors') as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="post" action="<?= base_url('admin/artikel/update/' . $artikel['id']) ?>" enctype="multipart/form-data" id="artikelForm">
    <?= csrf_field() ?>

    <div class="row g-4">
        <!-- Main -->
        <div class="col-lg-8">

            <!-- Judul -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Artikel <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" name="judul" id="judulInput"
                            value="<?= esc(old('judul', $artikel['judul'])) ?>" required>
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Slug URL</label>
                        <div class="input-group">
                            <span class="input-group-text text-muted small"><?= base_url('berita/') ?></span>
                            <input type="text" class="form-control font-monospace" id="slugPreview"
                                value="<?= esc($artikel['slug']) ?>" readonly>
                        </div>
                        <div class="form-text">Slug hanya berubah jika judul diubah.</div>
                    </div>
                </div>
            </div>

            <!-- Ringkasan -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <label class="form-label fw-semibold">Ringkasan / Excerpt</label>
                    <textarea class="form-control" name="ringkasan" rows="3"><?= esc(old('ringkasan', $artikel['ringkasan'])) ?></textarea>
                </div>
            </div>

            <!-- Konten -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <label class="form-label fw-semibold">Konten <span class="text-danger">*</span></label>
                    <div id="quill-editor"></div>
                    <textarea name="konten" id="kontenInput" class="d-none"><?= old('konten', $artikel['konten']) ?></textarea>
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">

            <!-- Publikasi -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom">
                    <i class="bi bi-send me-1 text-primary"></i>Publikasi
                </div>
                <div class="card-body p-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select" id="statusSelect">
                            <?php foreach (['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'] as $val => $lbl): ?>
                                <option value="<?= $val ?>" <?= old('status', $artikel['status']) === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3" id="publishDateGroup">
                        <label class="form-label fw-semibold">Tanggal Publikasi</label>
                        <input type="datetime-local" class="form-control" name="published_at"
                            value="<?= old('published_at', !empty($artikel['published_at']) ? date('Y-m-d\TH:i', strtotime($artikel['published_at'])) : date('Y-m-d\TH:i')) ?>">
                    </div>
                    <div class="mb-0">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="isFeatured" name="is_featured" value="1"
                                <?= (old('is_featured', $artikel['is_featured']) == 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="isFeatured">
                                <i class="bi bi-star-fill text-warning me-1"></i>Artikel Pilihan
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
                    </button>
                    <a href="<?= base_url('berita/' . esc($artikel['slug'])) ?>" target="_blank" class="btn btn-outline-secondary">
                        <i class="bi bi-eye me-1"></i>Lihat di Publik
                    </a>
                </div>
            </div>

            <!-- Kategori -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom">
                    <i class="bi bi-tag me-1 text-primary"></i>Kategori
                </div>
                <div class="card-body p-3">
                    <select name="kategori_id" class="form-select" required>
                        <option value="">— Pilih Kategori —</option>
                        <?php foreach ($kategori as $k): ?>
                            <option value="<?= $k['id'] ?>" <?= old('kategori_id', $artikel['kategori_id']) == $k['id'] ? 'selected' : '' ?>>
                                <?= esc($k['nama']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Thumbnail -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom">
                    <i class="bi bi-image me-1 text-primary"></i>Thumbnail
                </div>
                <div class="card-body p-3">
                    <div id="thumbPreviewWrap" class="mb-3 text-center <?= empty($artikel['thumbnail']) ? 'd-none' : '' ?>">
                        <img id="thumbPreview"
                            src="<?= !empty($artikel['thumbnail']) ? base_url('uploads/artikel/' . esc($artikel['thumbnail'])) : '' ?>"
                            class="thumb-preview" alt="Thumbnail">
                        <div class="d-flex justify-content-center gap-2 mt-2">
                            <small class="text-muted" id="thumbLabel"><?= !empty($artikel['thumbnail']) ? 'Thumbnail saat ini' : '' ?></small>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="reCropBtn">
                                <i class="bi bi-crop me-1"></i>Ganti & Crop
                            </button>
                        </div>
                    </div>
                    <!-- Hidden inputs -->
                    <input type="file" name="thumbnail" id="thumbnailInput"
                        accept="image/jpeg,image/png,image/webp" style="display:none">
                    <input type="hidden" name="thumbnail_cropped" id="thumbnailCropped">
                    <div class="d-flex gap-2 align-items-center <?= !empty($artikel['thumbnail']) ? 'd-none' : '' ?>" id="pickBtnWrap">
                        <button type="button" class="btn btn-outline-primary" id="pickThumbBtn">
                            <i class="bi bi-upload me-1"></i>Pilih Gambar
                        </button>
                        <span class="text-muted small" id="thumbFileName">Belum ada gambar dipilih</span>
                    </div>
                    <?php if (!empty($artikel['thumbnail'])): ?>
                    <div class="mt-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="pickThumbBtn">
                            <i class="bi bi-upload me-1"></i>Ganti Thumbnail
                        </button>
                    </div>
                    <?php endif; ?>
                    <div class="form-text mt-2">Upload baru untuk mengganti. Disarankan rasio 16:9.</div>
                </div>
            </div>

            <!-- Tags -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom">
                    <i class="bi bi-tags me-1 text-primary"></i>Tags
                </div>
                <div class="card-body p-3">
                    <input type="text" class="form-control" name="tags"
                        value="<?= esc(old('tags', $artikel['tags'])) ?>"
                        placeholder="berita, kegiatan, prestasi">
                    <div class="form-text">Pisahkan dengan koma.</div>
                </div>
            </div>

            <!-- Meta -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
                    <small class="text-muted d-block"><i class="bi bi-calendar3 me-1"></i>Dibuat: <?= format_tanggal($artikel['created_at'], 'full') ?></small>
                    <small class="text-muted d-block mt-1"><i class="bi bi-eye me-1"></i><?= number_format($artikel['view_count'] ?? 0) ?> kali dibaca</small>
                </div>
            </div>

        </div>
    </div>

</form>

<!-- Modal Crop Thumbnail -->
<div class="modal fade" id="thumbCropModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-crop me-2"></i>Crop Thumbnail (16:9)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-dark p-2" style="max-height:65vh;overflow:auto;">
                <img id="cropImageThumb" src="" alt="Crop" style="max-width:100%;display:block;">
            </div>
            <div class="modal-footer justify-content-between">
                <span class="text-muted small"><i class="bi bi-info-circle me-1"></i>Geser/zoom untuk menyesuaikan area crop. Rasio 16:9 otomatis.</span>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="thumbCropConfirm">
                        <i class="bi bi-check-lg me-1"></i>Terapkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script>
// ===================== QUILL IMAGE UPLOAD TO SERVER =====================
const UPLOAD_URL = '<?= base_url('admin/artikel/upload-image') ?>';
const CSRF_TOKEN = '<?= csrf_token() ?>';
const CSRF_HASH  = '<?= csrf_hash() ?>';

async function uploadImageToServer(file) {
    const b64 = await new Promise((res, rej) => {
        const reader = new FileReader();
        reader.onload = ev => {
            const img = new Image();
            img.onload = () => {
                const maxPx = 1400;
                let { width: w, height: h } = img;
                if (w > maxPx || h > maxPx) {
                    if (w >= h) { h = Math.round(h * maxPx / w); w = maxPx; }
                    else        { w = Math.round(w * maxPx / h); h = maxPx; }
                }
                const canvas = document.createElement('canvas');
                canvas.width = w; canvas.height = h;
                canvas.getContext('2d').drawImage(img, 0, 0, w, h);
                res(canvas.toDataURL('image/jpeg', 0.88));
            };
            img.onerror = rej;
            img.src = ev.target.result;
        };
        reader.onerror = rej;
        reader.readAsDataURL(file);
    });

    const arr  = b64.split(',');
    const mime = arr[0].match(/:(.*?);/)[1];
    const bstr = atob(arr[1]);
    const u8   = new Uint8Array(bstr.length);
    for (let i = 0; i < bstr.length; i++) u8[i] = bstr.charCodeAt(i);
    const blob = new Blob([u8], { type: mime });

    const origKB = Math.round(file.size / 1024);
    const compKB = Math.round(blob.size / 1024);

    const fd = new FormData();
    fd.append(CSRF_TOKEN, CSRF_HASH);
    fd.append('image', blob, 'image.jpg');

    showUploadProgress(true);
    try {
        const resp = await fetch(UPLOAD_URL, { method: 'POST', body: fd });
        const json = await resp.json();
        showUploadProgress(false);
        if (json.success) {
            showImgUploadToast(origKB, compKB);
            return json.url;
        } else {
            alert('Gagal upload gambar: ' + (json.error || 'Unknown error'));
            return null;
        }
    } catch (e) {
        showUploadProgress(false);
        alert('Gagal upload gambar: ' + e.message);
        return null;
    }
}

function showUploadProgress(show) {
    let el = document.getElementById('quillUploadBadge');
    if (!el) {
        el = document.createElement('div');
        el.id = 'quillUploadBadge';
        el.style.cssText = 'position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;';
        document.body.appendChild(el);
    }
    el.innerHTML = show
        ? `<div class="badge text-bg-secondary fs-6 shadow p-3">
               <span class="spinner-border spinner-border-sm me-2"></span>Mengupload gambar...
           </div>`
        : '';
}

function showImgUploadToast(origKB, compKB) {
    const saved = origKB > compKB ? Math.round((1 - compKB / origKB) * 100) : 0;
    let wrap = document.getElementById('imgCompressToast');
    if (!wrap) {
        wrap = document.createElement('div');
        wrap.id = 'imgCompressToast';
        wrap.style.cssText = 'position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;min-width:280px;';
        document.body.appendChild(wrap);
    }
    wrap.innerHTML = `
        <div class="toast show align-items-center text-bg-success border-0 shadow" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-cloud-upload me-2"></i>
                    Gambar tersimpan di server.<br>
                    <small>${origKB} KB → ${compKB} KB${saved > 0 ? ` <span class="badge bg-white text-success">hemat ${saved}%</span>` : ''}</small>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="this.closest('.toast').remove()"></button>
            </div>
        </div>`;
    setTimeout(() => { if (wrap.firstChild) wrap.firstChild.remove(); }, 5000);
}

// Init Quill dengan konten yang ada
const quill = new Quill('#quill-editor', {
    theme: 'snow',
    modules: {
        toolbar: {
            container: [
                [{ header: [2, 3, 4, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['blockquote', 'code-block'],
                ['link', 'image', 'video'],
                [{ align: [] }],
                ['clean']
            ],
            handlers: {
                image: function() {
                    const input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/jpeg,image/png,image/webp,image/gif');
                    input.click();
                    input.onchange = async () => {
                        const file = input.files[0];
                        if (!file) return;
                        const url = await uploadImageToServer(file);
                        if (!url) return;
                        const range = quill.getSelection(true);
                        quill.insertEmbed(range.index, 'image', url);
                        quill.setSelection(range.index + 1);
                    };
                }
            }
        }
    }
});

// Set existing content — gunakan dangerouslyPasteHTML agar Quill 2.x memuat HTML dengan benar
const existing = document.getElementById('kontenInput').value;
if (existing && existing.trim()) {
    quill.clipboard.dangerouslyPasteHTML(existing);
}

// Sync ke hidden textarea saat submit
document.getElementById('artikelForm').addEventListener('submit', function() {
    document.getElementById('kontenInput').value = quill.root.innerHTML;
});

// Auto-update slug preview jika judul berubah
const origJudul = <?= json_encode($artikel['judul']) ?>;
const origSlug  = <?= json_encode($artikel['slug']) ?>;
function toSlug(str) {
    return str.toLowerCase().replace(/[^a-z0-9\s-]/g, '').trim().replace(/\s+/g, '-');
}
document.getElementById('judulInput').addEventListener('input', function() {
    if (this.value !== origJudul) {
        document.getElementById('slugPreview').value = toSlug(this.value) + ' (baru)';
    } else {
        document.getElementById('slugPreview').value = origSlug;
    }
});

// Toggle publish date visibility
function togglePublishDate() {
    const v = document.getElementById('statusSelect').value;
    document.getElementById('publishDateGroup').style.display = v === 'published' ? '' : 'none';
}
document.getElementById('statusSelect').addEventListener('change', togglePublishDate);
togglePublishDate();

// ===================== THUMBNAIL CROP =====================
let thumbCropper = null, thumbCropApplied = false;

document.getElementById('pickThumbBtn').addEventListener('click', () => {
    document.getElementById('thumbnailInput').click();
});
document.getElementById('reCropBtn')?.addEventListener('click', () => {
    document.getElementById('thumbnailInput').click();
});

document.getElementById('thumbnailInput').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const nameEl = document.getElementById('thumbFileName');
    if (nameEl) nameEl.textContent = file.name;
    // Capture state before opening modal (for cancel restore)
    const previewWrap  = document.getElementById('thumbPreviewWrap');
    const wasVisible   = !previewWrap.classList.contains('d-none');
    const prevSrc      = document.getElementById('thumbPreview')?.src || '';
    const prevLabel    = document.getElementById('thumbLabel')?.textContent || '';
    const reader = new FileReader();
    reader.onload = e => openThumbCropModal(e.target.result, wasVisible, prevSrc, prevLabel);
    reader.readAsDataURL(file);
});

function openThumbCropModal(src, wasVisible, prevSrc, prevLabel) {
    document.getElementById('cropImageThumb').src = src;
    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('thumbCropModal'));
    modal.show();
    document.getElementById('thumbCropModal').addEventListener('shown.bs.modal', () => {
        if (thumbCropper) thumbCropper.destroy();
        thumbCropper = new Cropper(document.getElementById('cropImageThumb'), {
            aspectRatio: 16 / 9,
            viewMode: 1,
            dragMode: 'move',
            autoCropArea: 0.9,
        });
    }, { once: true });
    document.getElementById('thumbCropModal').addEventListener('hidden.bs.modal', function () {
        if (thumbCropper) { thumbCropper.destroy(); thumbCropper = null; }
        if (!thumbCropApplied) {
            document.getElementById('thumbnailCropped').value = '';
            document.getElementById('thumbnailInput').value = '';
            if (wasVisible) {
                // Existing thumbnail was showing — restore it, do NOT hide
                document.getElementById('thumbPreview').src = prevSrc;
                const lbl = document.getElementById('thumbLabel');
                if (lbl) lbl.textContent = prevLabel;
            } else {
                document.getElementById('thumbPreviewWrap').classList.add('d-none');
                document.getElementById('pickBtnWrap')?.classList.remove('d-none');
            }
        }
        thumbCropApplied = false;
    }, { once: true });
}

document.getElementById('thumbCropConfirm').addEventListener('click', function() {
    if (!thumbCropper) return;
    const canvas = thumbCropper.getCroppedCanvas({ width: 1200, height: 675, imageSmoothingQuality: 'high' });
    canvas.toBlob(blob => {
        const url = URL.createObjectURL(blob);
        document.getElementById('thumbPreview').src = url;
        document.getElementById('thumbPreviewWrap').classList.remove('d-none');
        document.getElementById('pickBtnWrap')?.classList.add('d-none');
        const lbl = document.getElementById('thumbLabel');
        if (lbl) lbl.textContent = 'Thumbnail baru (belum disimpan)';
        const reader = new FileReader();
        reader.onload = e => { document.getElementById('thumbnailCropped').value = e.target.result; };
        reader.readAsDataURL(blob);
        thumbCropApplied = true;
        bootstrap.Modal.getInstance(document.getElementById('thumbCropModal')).hide();
        thumbCropper.destroy(); thumbCropper = null;
    }, 'image/jpeg', 0.88);
});
</script>
<?= $this->endSection() ?>
