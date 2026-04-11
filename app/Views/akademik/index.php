<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<section class="page-header py-5" style="background: linear-gradient(135deg, var(--bs-primary) 0%, var(--site-secondary) 100%);">
    <div class="container text-center text-white">
        <h1 class="fw-bold mb-2">Akademik</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('/') ?>" class="text-white-50">Beranda</a></li>
                <li class="breadcrumb-item active text-white">Akademik</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Program Unggulan -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge text-bg-primary fs-6 px-3 py-2 mb-3">Keistimewaan Kami</span>
            <h2 class="fw-bold">Program Unggulan</h2>
            <p class="text-muted">Program khusus yang dirancang untuk mengoptimalkan potensi setiap siswa</p>
        </div>
        <div class="row g-4">
            <?php
            // Gunakan data dari DB, fallback ke data statis jika belum ada
            $displayPrograms = !empty($programs) ? array_map(fn($p) => [
                'icon'  => $p['icon'],
                'color' => $p['warna'],
                'title' => $p['judul'],
                'desc'  => $p['deskripsi'] ?? '',
            ], $programs) : [
                ['icon' => 'bi-laptop',     'color' => 'primary',   'title' => 'Literasi Digital',        'desc' => 'Pembekalan keterampilan teknologi informasi dan komunikasi untuk generasi siap era digital.'],
                ['icon' => 'bi-globe2',     'color' => 'success',   'title' => 'Bahasa Inggris Intensif', 'desc' => 'Program penguatan bahasa Inggris dengan pendekatan komunikatif dan interaktif setiap hari.'],
                ['icon' => 'bi-trophy',     'color' => 'warning',   'title' => 'Olimpiade Sains',         'desc' => 'Pembinaan intensif siswa berbakat untuk kompetisi sains tingkat kabupaten, provinsi, dan nasional.'],
                ['icon' => 'bi-heart-pulse','color' => 'danger',    'title' => 'Pendidikan Karakter',     'desc' => 'Pembiasaan nilai-nilai Pancasila, keagamaan, dan budaya lokal yang terintegrasi dalam keseharian sekolah.'],
                ['icon' => 'bi-easel',      'color' => 'info',      'title' => 'Seni & Kreativitas',      'desc' => 'Pengembangan bakat seni rupa, musik, dan pertunjukan melalui kelas dan festival tahunan.'],
                ['icon' => 'bi-people',     'color' => 'secondary', 'title' => 'Kepemimpinan Siswa',      'desc' => 'Pelatihan kepemimpinan melalui OSIS, MPK, dan program pembinaan calon pemimpin muda.'],
            ];
            ?>
            <?php foreach ($displayPrograms as $p): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100 text-center p-4">
                        <div class="rounded-circle bg-<?= esc($p['color']) ?> bg-opacity-10 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:72px;height:72px;">
                            <i class="bi <?= esc($p['icon']) ?> text-<?= esc($p['color']) ?>" style="font-size:2rem;"></i>
                        </div>
                        <h5 class="fw-bold"><?= esc($p['title']) ?></h5>
                        <p class="text-muted small mb-0"><?= esc($p['desc']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Kurikulum Accordion -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge text-bg-primary fs-6 px-3 py-2 mb-3">Kurikulum Merdeka</span>
            <h2 class="fw-bold">Struktur Kurikulum</h2>
            <p class="text-muted">Implementasi Kurikulum Merdeka yang berpusat pada peserta didik</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-9">

                <?php if (!empty($kurikulums)): ?>
                <!-- DYNAMIC: dari database admin -->
                <div class="accordion shadow-sm rounded overflow-hidden" id="kurikulumAccordion">
                    <?php foreach ($kurikulums as $i => $blok): ?>
                        <div class="accordion-item border-0 <?= $i < count($kurikulums) - 1 ? 'border-bottom' : '' ?>">
                            <h2 class="accordion-header">
                                <button class="accordion-button <?= $i > 0 ? 'collapsed' : '' ?> fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#kur<?= $blok['id'] ?>">
                                    <i class="bi bi-journal-bookmark-fill text-primary me-2"></i>
                                    <?= esc($blok['judul']) ?>
                                </button>
                            </h2>
                            <div id="kur<?= $blok['id'] ?>" class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>"
                                 data-bs-parent="#kurikulumAccordion">
                                <div class="accordion-body text-secondary">
                                    <?= $blok['konten'] ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php else: ?>
                <!-- FALLBACK: data statis -->
                <div class="accordion shadow-sm rounded overflow-hidden" id="kurikulumAccordion">

                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-semibold" type="button"
                                data-bs-toggle="collapse" data-bs-target="#kur1">
                                <i class="bi bi-journal-bookmark-fill text-primary me-2"></i>
                                Mata Pelajaran Inti
                            </button>
                        </h2>
                        <div id="kur1" class="accordion-collapse collapse show" data-bs-parent="#kurikulumAccordion">
                            <div class="accordion-body text-secondary">
                                <p>Mata pelajaran inti yang wajib ditempuh oleh seluruh siswa:</p>
                                <div class="row g-2">
                                    <?php foreach (['Pendidikan Agama & Budi Pekerti', 'Pendidikan Pancasila', 'Bahasa Indonesia', 'Matematika', 'IPA & IPS', 'Bahasa Inggris', 'Seni Budaya', 'Pendidikan Jasmani, Olahraga & Kesehatan'] as $mp): ?>
                                        <div class="col-md-6 d-flex align-items-center gap-2">
                                            <i class="bi bi-check-circle-fill text-primary small flex-shrink-0"></i>
                                            <span><?= $mp ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold" type="button"
                                data-bs-toggle="collapse" data-bs-target="#kur2">
                                <i class="bi bi-puzzle text-success me-2"></i>
                                Projek Penguatan Profil Pelajar Pancasila (P5)
                            </button>
                        </h2>
                        <div id="kur2" class="accordion-collapse collapse" data-bs-parent="#kurikulumAccordion">
                            <div class="accordion-body text-secondary">
                                <p>P5 adalah pembelajaran lintas disiplin ilmu untuk mengamati dan memikirkan solusi terhadap permasalahan di lingkungan sekitar. Tema yang diimplementasikan:</p>
                                <ul class="list-unstyled mb-0">
                                    <?php foreach ([
                                        'Gaya Hidup Berkelanjutan — Peduli lingkungan dan pengelolaan sampah',
                                        'Kearifan Lokal — Melestarikan budaya dan tradisi setempat',
                                        'Bhinneka Tunggal Ika — Merayakan keberagaman Indonesia',
                                        'Bangunlah Jiwa & Raganya — Kesehatan fisik dan mental',
                                        'Rekayasa & Teknologi — Inovasi berbasis sains dan teknologi',
                                        'Kewirausahaan — Semangat wirausaha dan kemandirian',
                                    ] as $tema): ?>
                                        <li class="mb-2 d-flex gap-2">
                                            <i class="bi bi-arrow-right-circle text-primary mt-1 flex-shrink-0"></i>
                                            <span><?= $tema ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold" type="button"
                                data-bs-toggle="collapse" data-bs-target="#kur3">
                                <i class="bi bi-clock-history text-warning me-2"></i>
                                Jadwal &amp; Beban Belajar
                            </button>
                        </h2>
                        <div id="kur3" class="accordion-collapse collapse" data-bs-parent="#kurikulumAccordion">
                            <div class="accordion-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm mb-0">
                                        <thead class="table-primary">
                                            <tr><th>Hari</th><th>Jam Masuk</th><th>Jam Keluar</th><th>Keterangan</th></tr>
                                        </thead>
                                        <tbody class="text-secondary">
                                            <tr><td>Senin</td><td>07.00</td><td>14.30</td><td>Upacara Bendera</td></tr>
                                            <tr><td>Selasa – Kamis</td><td>07.00</td><td>14.30</td><td>KBM Reguler</td></tr>
                                            <tr><td>Jumat</td><td>07.00</td><td>11.30</td><td>Jum'at Bersih</td></tr>
                                            <tr><td>Sabtu</td><td>07.00</td><td>12.30</td><td>Ekstrakurikuler</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold" type="button"
                                data-bs-toggle="collapse" data-bs-target="#kur4">
                                <i class="bi bi-graph-up text-info me-2"></i>
                                Penilaian &amp; Evaluasi
                            </button>
                        </h2>
                        <div id="kur4" class="accordion-collapse collapse" data-bs-parent="#kurikulumAccordion">
                            <div class="accordion-body text-secondary">
                                <p>Sistem penilaian Kurikulum Merdeka bersifat holistik dan berkesinambungan:</p>
                                <div class="row g-3">
                                    <?php foreach ([
                                        ['icon' => 'bi-file-earmark-check', 'color' => 'primary', 'title' => 'Penilaian Formatif',  'desc' => 'Tugas harian, presentasi, portofolio, dan observasi selama proses pembelajaran.'],
                                        ['icon' => 'bi-clipboard-data',     'color' => 'success', 'title' => 'Penilaian Sumatif',   'desc' => 'Tes akhir semester dan akhir tahun untuk mengukur pencapaian kompetensi.'],
                                        ['icon' => 'bi-people',             'color' => 'warning', 'title' => 'Penilaian P5',        'desc' => 'Laporan profil yang mendeskripsikan perkembangan Profil Pelajar Pancasila.'],
                                        ['icon' => 'bi-chat-dots',          'color' => 'danger',  'title' => 'Rapor & Refleksi',    'desc' => 'Rapor naratif yang memberikan gambaran menyeluruh perkembangan belajar siswa.'],
                                    ] as $p): ?>
                                        <div class="col-md-6">
                                            <div class="card bg-light border-0 p-3">
                                                <h6 class="fw-bold"><i class="bi <?= $p['icon'] ?> text-<?= $p['color'] ?> me-1"></i><?= $p['title'] ?></h6>
                                                <p class="small text-muted mb-0"><?= $p['desc'] ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold" type="button"
                                data-bs-toggle="collapse" data-bs-target="#kur5">
                                <i class="bi bi-mortarboard text-danger me-2"></i>
                                Prestasi Akademik
                            </button>
                        </h2>
                        <div id="kur5" class="accordion-collapse collapse" data-bs-parent="#kurikulumAccordion">
                            <div class="accordion-body text-secondary">
                                <p>Berbagai pencapaian yang telah diraih siswa-siswi kami:</p>
                                <div class="row g-3">
                                    <?php foreach ([
                                        ['val' => '50+',  'bg' => 'primary', 'label' => 'Medali Olimpiade'],
                                        ['val' => '98%',  'bg' => 'success', 'label' => 'Tingkat Kelulusan'],
                                        ['val' => '30+',  'bg' => 'warning', 'label' => 'Penghargaan Seni'],
                                        ['val' => '15+',  'bg' => 'info',    'label' => 'Juara Olahraga'],
                                    ] as $s): ?>
                                        <div class="col-6 col-md-3 text-center">
                                            <div class="card bg-<?= $s['bg'] ?> <?= in_array($s['bg'], ['warning','info']) ? 'text-dark' : 'text-white' ?> border-0 p-3">
                                                <div class="fs-2 fw-bold"><?= $s['val'] ?></div>
                                                <small><?= $s['label'] ?></small>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div><!-- /accordion static fallback -->
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<!-- CTA Banner -->
<section class="py-5 text-white" style="background: linear-gradient(135deg, var(--bs-primary), var(--site-secondary));">
    <div class="container text-center">
        <h3 class="fw-bold mb-2">Tahun Ajaran <?= esc(setting('tahun_ajaran_aktif') ?? '2024/2025') ?></h3>
        <p class="mb-4 opacity-75">Informasi akademik lengkap tersedia di portal siswa dan orang tua</p>
        <a href="<?= base_url('ppdb') ?>" class="btn btn-light btn-lg fw-semibold px-4 me-2">
            <i class="bi bi-info-circle me-2"></i>Info SPMB
        </a>
        <a href="mailto:<?= esc(setting('email') ?? '') ?>" class="btn btn-outline-light btn-lg fw-semibold px-4">
            <i class="bi bi-envelope me-2"></i>Hubungi Kami
        </a>
    </div>
</section>

<?= $this->endSection() ?>
