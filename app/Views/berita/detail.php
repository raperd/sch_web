<?= $this->extend('layouts/public') ?>

<?= $this->section('styles') ?>
<style>
.artikel-konten img { max-width: 100%; height: auto; border-radius: .5rem; margin: 1rem 0; }
.artikel-konten p { margin-bottom: 1.25rem; line-height: 1.85; }
.artikel-konten h2, .artikel-konten h3 { margin-top: 2rem; margin-bottom: 1rem; font-weight: 700; }
.artikel-konten blockquote { border-left: 4px solid var(--bs-primary); padding-left: 1rem; color: #555; font-style: italic; margin: 1.5rem 0; }
.artikel-konten ul, .artikel-konten ol { margin-bottom: 1.25rem; padding-left: 1.5rem; }
.artikel-konten li { margin-bottom: .4rem; line-height: 1.7; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div style="margin-top: var(--nav-height);"></div>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('berita') ?>">Berita &amp; Artikel</a></li>
                        <li class="breadcrumb-item active text-truncate" style="max-width:200px;"><?= esc($artikel['judul']) ?></li>
                    </ol>
                </nav>

                <!-- Kategori & Meta -->
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <?php if (!empty($artikel['nama_kategori'])): ?>
                        <span class="badge text-bg-primary"><?= esc($artikel['nama_kategori']) ?></span>
                    <?php endif; ?>
                    <?php if ($artikel['is_featured']): ?>
                        <span class="badge text-bg-warning"><i class="bi bi-star-fill me-1"></i>Pilihan</span>
                    <?php endif; ?>
                </div>

                <!-- Judul -->
                <h1 class="fw-bold mb-3 lh-base"><?= esc($artikel['judul']) ?></h1>

                <!-- Meta -->
                <div class="d-flex flex-wrap gap-3 text-muted small mb-4 pb-3 border-bottom">
                    <span><i class="bi bi-calendar3 me-1"></i><?= format_tanggal($artikel['published_at'] ?? $artikel['created_at'], 'full') ?></span>
                    <span><i class="bi bi-eye me-1"></i><?= number_format($artikel['view_count'] ?? 0) ?> kali dibaca</span>
                    <?php if (!empty($artikel['tags'])): ?>
                        <?php foreach (explode(',', $artikel['tags']) as $tag): ?>
                            <span class="badge text-bg-light border">#<?= esc(trim($tag)) ?></span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Thumbnail -->
                <?php if (!empty($artikel['thumbnail'])): ?>
                    <img src="<?= base_url('uploads/artikel/' . esc($artikel['thumbnail'])) ?>"
                        class="img-fluid rounded-3 mb-4 w-100"
                        style="max-height:450px;object-fit:cover;"
                        alt="<?= esc($artikel['judul']) ?>">
                <?php endif; ?>

                <!-- Ringkasan -->
                <?php if (!empty($artikel['ringkasan'])): ?>
                    <div class="alert border-start border-4 border-primary bg-primary bg-opacity-5 mb-4 py-3">
                        <p class="mb-0 fst-italic text-secondary"><?= esc($artikel['ringkasan']) ?></p>
                    </div>
                <?php endif; ?>

                <!-- Konten Utama -->
                <div class="artikel-konten fs-6 lh-lg text-secondary">
                    <?= $artikel['konten'] ?>
                </div>

                <!-- Share -->
                <div class="d-flex align-items-center gap-3 mt-5 pt-4 border-top flex-wrap">
                    <span class="fw-semibold text-dark">Bagikan:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(current_url()) ?>"
                        target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-facebook me-1"></i>Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?= urlencode(current_url()) ?>&text=<?= urlencode($artikel['judul']) ?>"
                        target="_blank" rel="noopener" class="btn btn-sm btn-outline-dark">
                        <i class="bi bi-twitter-x me-1"></i>X / Twitter
                    </a>
                    <a href="https://api.whatsapp.com/send?text=<?= urlencode($artikel['judul'] . ' ' . current_url()) ?>"
                        target="_blank" rel="noopener" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-whatsapp me-1"></i>WhatsApp
                    </a>
                    <button class="btn btn-sm btn-outline-secondary ms-auto" id="copyLinkBtn"
                        data-url="<?= current_url() ?>">
                        <i class="bi bi-link-45deg me-1"></i>Salin Link
                    </button>
                </div>

                <!-- Back -->
                <div class="mt-4">
                    <a href="<?= base_url('berita') ?>" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali ke Berita
                    </a>
                </div>

            </div><!-- /col-lg-8 -->
        </div><!-- /row -->

        <!-- Artikel Terkait -->
        <?php if (!empty($terkait)): ?>
            <div class="row justify-content-center mt-5 pt-3">
                <div class="col-lg-8">
                    <h5 class="fw-bold mb-4 border-bottom pb-2">Artikel Terkait</h5>
                    <div class="row g-4">
                        <?php foreach ($terkait as $t): ?>
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm h-100 news-card">
                                    <?php if (!empty($t['thumbnail'])): ?>
                                        <img src="<?= base_url('uploads/artikel/' . esc($t['thumbnail'])) ?>"
                                            class="card-img-top" style="height:150px;object-fit:cover;"
                                            alt="<?= esc($t['judul']) ?>">
                                    <?php else: ?>
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="height:150px;">
                                            <i class="bi bi-newspaper text-muted fs-2"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <h6 class="fw-bold">
                                            <a href="<?= base_url('berita/' . esc($t['slug'])) ?>"
                                                class="text-decoration-none text-dark stretched-link">
                                                <?= esc(truncate_text($t['judul'], 70)) ?>
                                            </a>
                                        </h6>
                                        <small class="text-muted"><?= format_tanggal($t['published_at'] ?? $t['created_at'], 'short') ?></small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('copyLinkBtn')?.addEventListener('click', function() {
    navigator.clipboard.writeText(this.dataset.url).then(() => {
        this.innerHTML = '<i class="bi bi-check2 me-1"></i>Tersalin!';
        setTimeout(() => { this.innerHTML = '<i class="bi bi-link-45deg me-1"></i>Salin Link'; }, 2000);
    });
});
</script>
<?= $this->endSection() ?>
