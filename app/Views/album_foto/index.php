<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<section class="page-header py-5" style="background: linear-gradient(135deg, var(--bs-primary) 0%, var(--site-secondary) 100%);">
    <div class="container text-center text-white">
        <h1 class="fw-bold mb-2">Album Foto</h1>
        <p class="opacity-75 mb-3">Kumpulan momen dan kegiatan sekolah kami di Google Foto</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('/') ?>" class="text-white-50">Beranda</a></li>
                <li class="breadcrumb-item active text-white">Album Foto</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Album Grid -->
<section class="py-5">
    <div class="container">

        <?php if (!empty($albums)): ?>
            <div class="row g-4">
                <?php foreach ($albums as $a): ?>
                    <div class="col-sm-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100 album-card">
                            <!-- Cover persegi 1:1 -->
                            <div class="album-cover-wrap position-relative overflow-hidden"
                                style="aspect-ratio:16/9;background:#e9ecef;">
                                <?php if (!empty($a['cover_foto'])): ?>
                                    <img src="<?= base_url('uploads/album_foto/' . esc($a['cover_foto'])) ?>"
                                        style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transition:.3s;"
                                        class="album-cover-img"
                                        alt="<?= esc($a['judul']) ?>">
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center w-100 h-100"
                                        style="position:absolute;inset:0;">
                                        <i class="bi bi-images text-muted" style="font-size:4rem;opacity:.4;"></i>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-1"><?= esc($a['judul']) ?></h5>
                                <?php if (!empty($a['tanggal'])): ?>
                                    <small class="text-muted d-block mb-2">
                                        <i class="bi bi-calendar3 me-1"></i><?= format_tanggal($a['tanggal'], 'long') ?>
                                    </small>
                                <?php endif; ?>
                                <?php if (!empty($a['deskripsi'])): ?>
                                    <p class="small text-muted mb-0"><?= esc(truncate_text($a['deskripsi'], 100)) ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="card-footer bg-white border-top-0 p-3 pt-0">
                                <a href="<?= esc($a['link_google_foto']) ?>" target="_blank" rel="noopener noreferrer"
                                    class="btn btn-primary w-100">
                                    <i class="bi bi-google me-2"></i>Buka di Google Foto
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-images display-3 d-block mb-3 opacity-25"></i>
                <p class="mb-0">Album foto belum tersedia.</p>
            </div>
        <?php endif; ?>

        <!-- Info note -->
        <div class="text-center mt-5 pt-3 border-top">
            <p class="text-muted small mb-0">
                <i class="bi bi-google me-1"></i>Album foto kami dikelola melalui Google Foto
            </p>
        </div>

    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.album-card { transition: transform .25s, box-shadow .25s; }
.album-card:hover { transform: translateY(-4px); box-shadow: 0 .75rem 1.5rem rgba(0,0,0,.12) !important; }
.album-card:hover .album-cover-img { transform: scale(1.05); }
</style>
<?= $this->endSection() ?>
