<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<section class="page-header py-5" style="background: linear-gradient(135deg, #1a5276 0%, #2e86c1 100%); margin-top: var(--nav-height);">
    <div class="container text-center text-white">
        <span class="badge bg-warning text-dark fs-6 px-3 py-2 mb-3 fw-bold">
            <i class="bi bi-bell-fill me-1"></i>Pendaftaran Dibuka
        </span>
        <h1 class="fw-bold mb-2">PPDB <?= esc(setting('tahun_ajaran_aktif') ?? '') ?></h1>
        <p class="fs-5 opacity-75 mb-3">Penerimaan Peserta Didik Baru</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('/') ?>" class="text-white-50">Beranda</a></li>
                <li class="breadcrumb-item active text-white">PPDB</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Tombol Daftar Utama -->
<?php $ppdbLink = setting('ppdb_link_external'); ?>
<?php if ($ppdbLink): ?>
    <section class="py-4 bg-warning">
        <div class="container text-center">
            <div class="d-flex align-items-center justify-content-center gap-3 flex-wrap">
                <div>
                    <h5 class="fw-bold mb-0 text-dark">Siap mendaftar?</h5>
                    <p class="mb-0 text-dark opacity-75 small">Pendaftaran dilakukan melalui portal resmi Dinas Pendidikan</p>
                </div>
                <a href="<?= esc($ppdbLink) ?>" target="_blank" rel="noopener"
                    class="btn btn-dark btn-lg fw-bold px-5 shadow">
                    <i class="bi bi-box-arrow-up-right me-2"></i>Daftar Sekarang
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Info Blok (jika ada) -->
<?php if (!empty($info)): ?>
    <section class="py-4 bg-info bg-opacity-10">
        <div class="container">
            <?php foreach ($info as $blok): ?>
                <div class="alert alert-info border-start border-4 border-info mb-2">
                    <?php if (!empty($blok['judul_blok'])): ?>
                        <h6 class="fw-bold mb-1"><?= esc($blok['judul_blok']) ?></h6>
                    <?php endif; ?>
                    <div class="text-secondary"><?= $blok['konten'] ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<!-- Alur Pendaftaran -->
<?php if (!empty($alur)): ?>
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge text-bg-primary fs-6 px-3 py-2 mb-3">Langkah demi Langkah</span>
                <h2 class="fw-bold">Alur Pendaftaran</h2>
                <p class="text-muted">Ikuti tahapan berikut untuk menyelesaikan proses pendaftaran</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="position-relative">
                        <!-- Timeline line -->
                        <div class="position-absolute d-none d-md-block bg-primary bg-opacity-25"
                            style="width:3px;left:28px;top:0;bottom:0;"></div>
                        <div class="d-flex flex-column gap-4">
                            <?php foreach ($alur as $i => $a): ?>
                                <div class="d-flex gap-4 align-items-start position-relative">
                                    <!-- Step number -->
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0 shadow"
                                        style="width:56px;height:56px;font-size:1.2rem;z-index:1;">
                                        <?= $i + 1 ?>
                                    </div>
                                    <!-- Content -->
                                    <div class="card border-0 shadow-sm flex-grow-1">
                                        <div class="card-body p-4">
                                            <?php if (!empty($a['judul_blok'])): ?>
                                                <h5 class="fw-bold mb-2 text-primary"><?= esc($a['judul_blok']) ?></h5>
                                            <?php endif; ?>
                                            <div class="text-secondary lh-lg"><?= $a['konten'] ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Jadwal -->
<?php if (!empty($jadwal)): ?>
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge text-bg-warning text-dark fs-6 px-3 py-2 mb-3">Kalender PPDB</span>
                <h2 class="fw-bold">Jadwal Penerimaan</h2>
                <p class="text-muted">Pastikan Anda tidak melewati setiap tahapan pendaftaran</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <?php foreach ($jadwal as $idx => $j): ?>
                                <div class="d-flex gap-3 p-4 <?= $idx < count($jadwal) - 1 ? 'border-bottom' : '' ?> align-items-start">
                                    <div class="text-warning flex-shrink-0 mt-1">
                                        <i class="bi bi-calendar-check-fill fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <?php if (!empty($j['judul_blok'])): ?>
                                            <h6 class="fw-bold mb-1"><?= esc($j['judul_blok']) ?></h6>
                                        <?php endif; ?>
                                        <div class="text-secondary lh-lg"><?= $j['konten'] ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Persyaratan -->
<?php if (!empty($persyaratan)): ?>
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge text-bg-primary fs-6 px-3 py-2 mb-3">Yang Perlu Disiapkan</span>
                <h2 class="fw-bold">Persyaratan Pendaftaran</h2>
                <p class="text-muted">Siapkan dokumen-dokumen berikut sebelum mendaftar</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="accordion shadow-sm rounded overflow-hidden" id="persyaratanAccordion">
                        <?php foreach ($persyaratan as $idx => $p): ?>
                            <div class="accordion-item border-0 <?= $idx < count($persyaratan) - 1 ? 'border-bottom' : '' ?>">
                                <h2 class="accordion-header">
                                    <button class="accordion-button <?= $idx > 0 ? 'collapsed' : '' ?> fw-semibold"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#pers<?= $idx ?>">
                                        <i class="bi bi-file-earmark-check text-primary me-2"></i>
                                        <?= esc($p['judul_blok'] ?: 'Persyaratan ' . ($idx + 1)) ?>
                                    </button>
                                </h2>
                                <div id="pers<?= $idx ?>" class="accordion-collapse collapse <?= $idx === 0 ? 'show' : '' ?>"
                                    data-bs-parent="#persyaratanAccordion">
                                    <div class="accordion-body text-secondary lh-lg">
                                        <?= $p['konten'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- FAQ -->
<?php if (!empty($faq)): ?>
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge text-bg-info text-dark fs-6 px-3 py-2 mb-3">Tanya Jawab</span>
                <h2 class="fw-bold">Pertanyaan Umum (FAQ)</h2>
                <p class="text-muted">Pertanyaan yang sering ditanyakan calon peserta didik baru</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="accordion shadow-sm rounded overflow-hidden" id="faqAccordion">
                        <?php foreach ($faq as $idx => $f): ?>
                            <div class="accordion-item border-0 <?= $idx < count($faq) - 1 ? 'border-bottom' : '' ?>">
                                <h2 class="accordion-header">
                                    <button class="accordion-button <?= $idx > 0 ? 'collapsed' : '' ?> fw-semibold"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#faq<?= $idx ?>">
                                        <i class="bi bi-question-circle text-info me-2"></i>
                                        <?= esc($f['judul_blok'] ?: 'Pertanyaan ' . ($idx + 1)) ?>
                                    </button>
                                </h2>
                                <div id="faq<?= $idx ?>" class="accordion-collapse collapse <?= $idx === 0 ? 'show' : '' ?>"
                                    data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-secondary lh-lg">
                                        <?= $f['konten'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- CTA Bottom -->
<section class="py-5 text-white" style="background: linear-gradient(135deg, var(--bs-primary), var(--bs-secondary));">
    <div class="container text-center">
        <h3 class="fw-bold mb-2">Masih ada pertanyaan?</h3>
        <p class="mb-4 opacity-75">Hubungi kami langsung melalui telepon atau email untuk informasi lebih lanjut</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <?php $telp = setting('telepon'); ?>
            <?php if ($telp): ?>
                <a href="tel:<?= esc($telp) ?>" class="btn btn-light btn-lg fw-semibold px-4">
                    <i class="bi bi-telephone me-2"></i><?= esc($telp) ?>
                </a>
            <?php endif; ?>
            <?php $email = setting('email'); ?>
            <?php if ($email): ?>
                <a href="mailto:<?= esc($email) ?>" class="btn btn-outline-light btn-lg fw-semibold px-4">
                    <i class="bi bi-envelope me-2"></i><?= esc($email) ?>
                </a>
            <?php endif; ?>
            <?php if ($ppdbLink): ?>
                <a href="<?= esc($ppdbLink) ?>" target="_blank" rel="noopener"
                    class="btn btn-warning btn-lg fw-bold px-4 text-dark">
                    <i class="bi bi-box-arrow-up-right me-2"></i>Portal PPDB
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
