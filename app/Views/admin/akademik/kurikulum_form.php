<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="mb-4">
    <a href="<?= admin_url('akademik/kurikulum') ?>" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Kurikulum
    </a>
    <h4 class="fw-bold mt-1 mb-0"><?= esc($title) ?></h4>
</div>

<?php if (session()->getFlashdata('errors') || isset($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
            <?php foreach ((session()->getFlashdata('errors') ?? $errors ?? []) as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php
$isEdit = !empty($blok);
$action = $isEdit
    ? admin_url('akademik/kurikulum/' . $blok['id'] . '/update')
    : admin_url('akademik/kurikulum/store');
$val    = fn(string $k, $d = '') => old($k, $isEdit ? ($blok[$k] ?? $d) : $d);
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="post" action="<?= $action ?>">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Blok <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="judul"
                               value="<?= esc($val('judul')) ?>" required maxlength="150"
                               placeholder="cth: Mata Pelajaran Inti">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Konten</label>
                        <div id="kontenEditor"><?= $val('konten') ?></div>
                        <input type="hidden" name="konten" id="kontenInput">
                        <div class="form-text">Tulis isi accordion ini — bisa daftar mapel, deskripsi kurikulum, dll.</div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Urutan</label>
                            <input type="number" class="form-control" name="urutan"
                                   value="<?= esc($val('urutan', $next_urutan ?? 0)) ?>" min="0" max="99">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_active"
                                       value="1" id="isActive"
                                       <?= $val('is_active', 1) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-semibold" for="isActive">Tampilkan di halaman publik</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-semibold px-4" id="submitBtn">
                            <i class="bi bi-save me-1"></i><?= $isEdit ? 'Perbarui' : 'Simpan' ?>
                        </button>
                        <a href="<?= admin_url('akademik/kurikulum') ?>" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->section('styles') ?>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/quill-table-better@1/dist/quill-table-better.min.css" rel="stylesheet">
<style>
    /* Beri ruang lebih untuk toolbar tabel */
    .ql-toolbar.ql-snow { flex-wrap: wrap; }
    /* Perbesar area editor sedikit supaya tabel tidak sempit */
    #kontenEditor { height: 320px; }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill-table-better@1/dist/quill-table-better.min.js"></script>
<script>
(function () {
    // Daftarkan modul tabel ke Quill
    Quill.register({ 'modules/table-better': QuillTableBetter }, true);

    // Simpan HTML awal (untuk mode edit) sebelum Quill mengambil alih container
    const initialHtml = document.getElementById('kontenEditor').innerHTML.trim();
    document.getElementById('kontenEditor').innerHTML = '';

    const quill = new Quill('#kontenEditor', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['link'],
                ['table-better'],
                ['clean'],
            ],
            'table-better': {
                operationMenu: {
                    items: {
                        insertColumnLeft:  { text: 'Sisipkan Kolom Kiri' },
                        insertColumnRight: { text: 'Sisipkan Kolom Kanan' },
                        insertRowAbove:    { text: 'Sisipkan Baris Atas' },
                        insertRowBelow:    { text: 'Sisipkan Baris Bawah' },
                        mergeCells:        { text: 'Gabung Sel' },
                        unmergeCells:      { text: 'Pisahkan Sel' },
                        deleteColumn:      { text: 'Hapus Kolom' },
                        deleteRow:         { text: 'Hapus Baris' },
                        deleteTable:       { text: 'Hapus Tabel' },
                    },
                },
            },
        },
    });

    // Muat konten awal (jika edit) via dangerouslyPasteHTML supaya tag tabel dikenali
    if (initialHtml) {
        quill.clipboard.dangerouslyPasteHTML(0, initialHtml);
    }

    // Sync konten ke hidden input sebelum form dikirim
    function syncContent() {
        document.getElementById('kontenInput').value = quill.root.innerHTML;
    }
    document.getElementById('submitBtn').addEventListener('click', syncContent);
    document.querySelector('form').addEventListener('submit', syncContent);
})();
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
