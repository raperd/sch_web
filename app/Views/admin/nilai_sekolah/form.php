<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= admin_url('nilai-sekolah') ?>" class="btn btn-sm btn-outline-secondary mb-3">
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

        <form method="post" action="<?= admin_url('nilai-sekolah/' . ($nilai ? 'update/'.$nilai['id'] : 'store')) ?>">
            <?= csrf_field() ?>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Judul Nilai Sekolah <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nama"
                        value="<?= esc(old('nama', $nilai['nama'] ?? '')) ?>" required>
                </div>

                <!-- Icon Picker -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Icon Bootstrap Icons</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white px-3" id="iconPreviewWrap">
                            <i id="iconPreview" class="bi <?= esc(old('icon', $nilai['icon'] ?? 'bi-award')) ?> text-primary fs-5"></i>
                        </span>
                        <input type="text" class="form-control font-monospace" name="icon" id="iconInput"
                            value="<?= esc(old('icon', $nilai['icon'] ?? 'bi-award')) ?>"
                            placeholder="bi-award"
                            autocomplete="off"
                            spellcheck="false">
                        <button type="button" class="btn btn-outline-primary" id="openPickerBtn" title="Pilih dari daftar">
                            <i class="bi bi-grid-3x3-gap"></i>
                        </button>
                    </div>
                    <div class="form-text">Ketik nama icon langsung <em>atau</em> klik <i class="bi bi-grid-3x3-gap"></i> untuk memilih dari daftar.</div>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Urutan</label>
                    <input type="number" class="form-control" name="urutan"
                        value="<?= esc(old('urutan', $nilai['urutan'] ?? ($next_urutan ?? 0))) ?>" min="0">
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

<!-- ─── Icon Picker Modal ──────────────────────────────────────────── -->
<div class="modal fade" id="iconPickerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header pb-2">
                <h5 class="modal-title fw-semibold"><i class="bi bi-grid-3x3-gap me-2"></i>Pilih Icon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Search -->
            <div class="px-3 pt-2 pb-1 border-bottom">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="iconSearch" class="form-control" placeholder="Cari icon... (contoh: heart, star, book)">
                    <button class="btn btn-outline-secondary" type="button" id="iconSearchClear" title="Hapus">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <p class="text-muted small mt-1 mb-0" id="iconSearchInfo"></p>
            </div>

            <div class="modal-body p-3" id="iconPickerBody">
                <?php
                $iconGroups = [
                    'Akademik & Pendidikan' => [
                        'bi-book', 'bi-book-half', 'bi-journal', 'bi-journal-text', 'bi-journal-richtext',
                        'bi-mortarboard', 'bi-mortarboard-fill', 'bi-pencil', 'bi-pencil-fill',
                        'bi-pen', 'bi-pen-fill', 'bi-eraser', 'bi-calculator', 'bi-calculator-fill',
                        'bi-laptop', 'bi-pc-display', 'bi-filetype-pdf', 'bi-file-earmark-text',
                        'bi-clipboard', 'bi-clipboard-check', 'bi-backpack', 'bi-backpack-fill',
                        'bi-lightbulb', 'bi-lightbulb-fill', 'bi-bookmark-check', 'bi-bookmark-star',
                    ],
                    'Karakter & Nilai' => [
                        'bi-heart', 'bi-heart-fill', 'bi-star', 'bi-star-fill',
                        'bi-shield-check', 'bi-shield-fill-check', 'bi-patch-check', 'bi-patch-check-fill',
                        'bi-hand-thumbs-up', 'bi-hand-thumbs-up-fill', 'bi-emoji-smile', 'bi-emoji-heart-eyes',
                        'bi-sun', 'bi-sun-fill', 'bi-moon-stars', 'bi-cloud-sun',
                        'bi-peace', 'bi-flower1', 'bi-flower2', 'bi-flower3',
                        'bi-balloon-heart', 'bi-balloon-heart-fill', 'bi-chat-heart', 'bi-suit-heart-fill',
                    ],
                    'Komunitas & Sosial' => [
                        'bi-people', 'bi-people-fill', 'bi-person', 'bi-person-fill',
                        'bi-person-check', 'bi-person-heart', 'bi-person-raised-hand',
                        'bi-person-workspace', 'bi-person-badge', 'bi-person-badge-fill',
                        'bi-house', 'bi-house-fill', 'bi-house-heart', 'bi-house-heart-fill',
                        'bi-building', 'bi-buildings', 'bi-globe', 'bi-globe2',
                        'bi-chat-dots', 'bi-chat-dots-fill', 'bi-megaphone', 'bi-megaphone-fill',
                        'bi-share', 'bi-share-fill', 'bi-flag', 'bi-flag-fill',
                    ],
                    'Prestasi & Penghargaan' => [
                        'bi-award', 'bi-award-fill', 'bi-trophy', 'bi-trophy-fill',
                        'bi-gem', 'bi-gem-fill', 'bi-medal', 'bi-patch-exclamation',
                        'bi-currency-dollar', 'bi-rocket', 'bi-rocket-fill', 'bi-airplane',
                        'bi-lightning', 'bi-lightning-fill', 'bi-lightning-charge', 'bi-lightning-charge-fill',
                        'bi-fire', 'bi-stars', 'bi-balloon', 'bi-balloon-fill',
                        'bi-send', 'bi-send-fill', 'bi-bullseye', 'bi-crosshair',
                    ],
                    'Inovasi & Teknologi' => [
                        'bi-cpu', 'bi-cpu-fill', 'bi-phone', 'bi-phone-fill',
                        'bi-tablet', 'bi-camera', 'bi-camera-fill', 'bi-broadcast',
                        'bi-wifi', 'bi-router', 'bi-hdd', 'bi-hdd-network',
                        'bi-diagram-3', 'bi-diagram-2', 'bi-graph-up', 'bi-graph-up-arrow',
                        'bi-bar-chart', 'bi-bar-chart-fill', 'bi-pie-chart', 'bi-pie-chart-fill',
                        'bi-gear', 'bi-gear-fill', 'bi-tools', 'bi-wrench',
                    ],
                    'Alam & Lingkungan' => [
                        'bi-tree', 'bi-tree-fill', 'bi-leaf', 'bi-leaf-fill',
                        'bi-cloud', 'bi-cloud-fill', 'bi-droplet', 'bi-droplet-fill',
                        'bi-water', 'bi-wind', 'bi-brightness-high', 'bi-brightness-high-fill',
                        'bi-recycle', 'bi-globe-americas', 'bi-globe-central-south-asia',
                        'bi-hurricane', 'bi-rainbow', 'bi-snow', 'bi-snow2', 'bi-snow3',
                        'bi-thermometer', 'bi-geo-alt', 'bi-geo-alt-fill', 'bi-compass',
                    ],
                    'Umum' => [
                        'bi-check-circle', 'bi-check-circle-fill', 'bi-info-circle', 'bi-info-circle-fill',
                        'bi-exclamation-circle', 'bi-question-circle', 'bi-plus-circle', 'bi-dash-circle',
                        'bi-arrow-up-circle', 'bi-arrow-right-circle', 'bi-activity', 'bi-sliders',
                        'bi-toggles', 'bi-toggles2', 'bi-grid', 'bi-grid-fill',
                        'bi-collection', 'bi-stack', 'bi-layers', 'bi-layers-fill',
                        'bi-box', 'bi-box-fill', 'bi-boxes', 'bi-archive',
                    ],
                ];
                ?>

                <?php foreach ($iconGroups as $group => $icons): ?>
                <div class="icon-group mb-4" data-group="<?= htmlspecialchars($group) ?>">
                    <p class="text-muted small fw-semibold mb-2 border-bottom pb-1"><?= esc($group) ?></p>
                    <div class="row g-1">
                        <?php foreach ($icons as $ic): ?>
                        <div class="col-auto icon-item" data-icon="<?= $ic ?>">
                            <button type="button"
                                class="btn btn-light border icon-picker-btn d-flex flex-column align-items-center justify-content-center"
                                style="width:56px;height:56px;"
                                data-icon="<?= $ic ?>"
                                title="<?= $ic ?>">
                                <i class="bi <?= $ic ?> fs-5"></i>
                                <span class="d-none icon-label"><?= $ic ?></span>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>

                <!-- Empty state saat pencarian tidak ada hasil -->
                <div id="iconSearchEmpty" class="text-center py-5 text-muted d-none">
                    <i class="bi bi-search display-5 opacity-25 d-block mb-2"></i>
                    Tidak ada icon yang cocok.<br>
                    <span class="small">Coba ketik nama icon langsung di kolom input.</span>
                </div>
            </div>

            <div class="modal-footer py-2 justify-content-between">
                <a href="https://icons.getbootstrap.com/" target="_blank" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-box-arrow-up-right me-1"></i>Semua icon Bootstrap Icons
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function () {
    const iconInput   = document.getElementById('iconInput');
    const iconPreview = document.getElementById('iconPreview');

    // ── Live preview saat mengetik manual ─────────────────────────
    iconInput.addEventListener('input', function () {
        const val = this.value.trim();
        // Normalise: pastikan dimulai dengan "bi-"
        const cls = val.startsWith('bi-') ? val : (val ? 'bi-' + val : 'bi-award');
        iconPreview.className = 'bi ' + cls + ' text-primary fs-5';
    });

    // ── Buka modal picker ─────────────────────────────────────────
    const modal     = new bootstrap.Modal(document.getElementById('iconPickerModal'));
    const searchEl  = document.getElementById('iconSearch');
    const clearBtn  = document.getElementById('iconSearchClear');
    const infoEl    = document.getElementById('iconSearchInfo');
    const emptyEl   = document.getElementById('iconSearchEmpty');
    const allItems  = document.querySelectorAll('.icon-item');
    const allGroups = document.querySelectorAll('.icon-group');

    document.getElementById('openPickerBtn').addEventListener('click', () => {
        searchEl.value = '';
        applySearch('');
        modal.show();
        // Focus search setelah modal muncul
        document.getElementById('iconPickerModal').addEventListener('shown.bs.modal', () => {
            searchEl.focus();
        }, { once: true });
    });

    // ── Fungsi pencarian ──────────────────────────────────────────
    function applySearch(q) {
        const term = q.toLowerCase().replace(/^bi-/, '');
        let visible = 0;

        allItems.forEach(item => {
            const iconName = item.dataset.icon.replace('bi-', '');
            const match    = term === '' || iconName.includes(term);
            item.classList.toggle('d-none', !match);
            if (match) visible++;
        });

        // Sembunyikan header grup jika semua isinya hidden
        allGroups.forEach(grp => {
            const anyVisible = grp.querySelector('.icon-item:not(.d-none)');
            grp.classList.toggle('d-none', !anyVisible);
        });

        emptyEl.classList.toggle('d-none', visible > 0);
        infoEl.textContent = term
            ? (visible > 0 ? `${visible} icon ditemukan` : '')
            : '';
    }

    searchEl.addEventListener('input', () => applySearch(searchEl.value));
    clearBtn.addEventListener('click', () => {
        searchEl.value = '';
        applySearch('');
        searchEl.focus();
    });

    // ── Pilih icon dari grid ──────────────────────────────────────
    document.querySelectorAll('.icon-picker-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const chosen = this.dataset.icon;
            iconInput.value = chosen;
            iconPreview.className = 'bi ' + chosen + ' text-primary fs-5';

            // Highlight tombol yang dipilih sebentar
            this.classList.add('btn-primary', 'text-white');
            this.classList.remove('btn-light');
            setTimeout(() => {
                this.classList.remove('btn-primary', 'text-white');
                this.classList.add('btn-light');
            }, 300);

            modal.hide();
        });
    });
})();
</script>
<?= $this->endSection() ?>
