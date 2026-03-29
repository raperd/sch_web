<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Optimisasi index database untuk performa query publik dan admin.
 *
 * Strategi:
 *  - Ganti multiple single-column index yang sering dipakai bersama
 *    dengan satu composite index yang lebih efisien.
 *  - Tambahkan index baru untuk tabel yang belum memiliki index relevan.
 *  - InnoDB online DDL (MySQL 8.x): operasi ADD/DROP INDEX tidak
 *    memblokir tabel (ALGORITHM=INPLACE, LOCK=NONE).
 *
 * Nama index lama (hasil forge->addKey('col')) = nama kolom itu sendiri.
 */
class OptimizeIndexes extends Migration
{
    public function up(): void
    {
        $p = $this->db->DBPrefix;

        // ----------------------------------------------------------------
        // artikel
        // Query publik: WHERE status='published' ORDER BY published_at DESC
        // Query featured: WHERE is_featured=1 AND status='published'
        // Ganti 3 single-index (status, is_featured, published_at) → 2 composite
        // ----------------------------------------------------------------
        $this->db->query("
            ALTER TABLE `{$p}artikel`
                DROP INDEX `status`,
                DROP INDEX `is_featured`,
                DROP INDEX `published_at`,
                ADD INDEX `idx_artikel_publik`   (`status`, `published_at`),
                ADD INDEX `idx_artikel_featured` (`is_featured`, `status`)
        ");

        // ----------------------------------------------------------------
        // galeri
        // Query publik: WHERE kategori_id=X ORDER BY urutan
        // Query featured: WHERE is_featured=1 ORDER BY urutan
        // Ganti 3 single-index → 2 composite
        // (kategori_id tetap leading → FK constraint tetap terpenuhi)
        // ----------------------------------------------------------------
        $this->db->query("
            ALTER TABLE `{$p}galeri`
                DROP INDEX `kategori_id`,
                DROP INDEX `is_featured`,
                DROP INDEX `urutan`,
                ADD INDEX `idx_galeri_listing`  (`kategori_id`, `urutan`),
                ADD INDEX `idx_galeri_featured` (`is_featured`, `urutan`)
        ");

        // ----------------------------------------------------------------
        // guru_staf
        // Query direktori: WHERE tipe=X AND is_active=1 ORDER BY urutan
        // Ganti 3 single-index → 1 composite
        // ----------------------------------------------------------------
        $this->db->query("
            ALTER TABLE `{$p}guru_staf`
                DROP INDEX `tipe`,
                DROP INDEX `is_active`,
                DROP INDEX `urutan`,
                ADD INDEX `idx_guru_listing` (`tipe`, `is_active`, `urutan`)
        ");

        // ----------------------------------------------------------------
        // kegiatan
        // Query upcoming: WHERE status='upcoming' ORDER BY tanggal ASC
        // Query featured: WHERE is_featured=1 AND status='upcoming/ongoing'
        // Ganti 3 single-index → 2 composite
        // ----------------------------------------------------------------
        $this->db->query("
            ALTER TABLE `{$p}kegiatan`
                DROP INDEX `tanggal`,
                DROP INDEX `status`,
                DROP INDEX `is_featured`,
                ADD INDEX `idx_kegiatan_listing`  (`status`, `tanggal`),
                ADD INDEX `idx_kegiatan_featured` (`is_featured`, `status`)
        ");

        // ----------------------------------------------------------------
        // prestasi
        // Query listing: WHERE kategori=X AND tahun=Y
        // Query featured: WHERE is_featured=1 AND kategori=X
        // Ganti 3 single-index → 2 composite
        // ----------------------------------------------------------------
        $this->db->query("
            ALTER TABLE `{$p}prestasi`
                DROP INDEX `kategori`,
                DROP INDEX `tahun`,
                DROP INDEX `is_featured`,
                ADD INDEX `idx_prestasi_listing`  (`kategori`, `tahun`),
                ADD INDEX `idx_prestasi_featured` (`is_featured`, `kategori`)
        ");

        // ----------------------------------------------------------------
        // ppdb_konten
        // Query: WHERE is_active=1 ORDER BY tipe, urutan
        // Ganti 3 single-index → 1 composite
        // ----------------------------------------------------------------
        $this->db->query("
            ALTER TABLE `{$p}ppdb_konten`
                DROP INDEX `tipe`,
                DROP INDEX `urutan`,
                DROP INDEX `is_active`,
                ADD INDEX `idx_ppdb_listing` (`is_active`, `tipe`, `urutan`)
        ");

        // ----------------------------------------------------------------
        // ekstrakurikuler
        // Query: WHERE is_active=1 ORDER BY urutan
        // Ganti 2 single-index → 1 composite
        // ----------------------------------------------------------------
        $this->db->query("
            ALTER TABLE `{$p}ekstrakurikuler`
                DROP INDEX `is_active`,
                DROP INDEX `urutan`,
                ADD INDEX `idx_ekskul_listing` (`is_active`, `urutan`)
        ");

        // ----------------------------------------------------------------
        // menu
        // Query: WHERE lokasi='publik' AND is_active=1 ORDER BY urutan
        // Ganti 3 single-index → 1 composite (parent_id FK index dipertahankan)
        // ----------------------------------------------------------------
        $this->db->query("
            ALTER TABLE `{$p}menu`
                DROP INDEX `lokasi`,
                DROP INDEX `urutan`,
                DROP INDEX `is_active`,
                ADD INDEX `idx_menu_listing` (`lokasi`, `is_active`, `urutan`)
        ");

        // ----------------------------------------------------------------
        // quick_links
        // Query: WHERE is_active=1 ORDER BY urutan
        // Ganti 2 single-index → 1 composite
        // ----------------------------------------------------------------
        $this->db->query("
            ALTER TABLE `{$p}quick_links`
                DROP INDEX `urutan`,
                DROP INDEX `is_active`,
                ADD INDEX `idx_quick_links_listing` (`is_active`, `urutan`)
        ");

        // ----------------------------------------------------------------
        // Tabel tanpa index relevan — hanya ADD
        // ----------------------------------------------------------------

        // album_foto: WHERE is_published=1 ORDER BY tanggal DESC
        $this->db->query("
            ALTER TABLE `{$p}album_foto`
                ADD INDEX `idx_album_listing` (`is_published`, `tanggal`)
        ");

        // aplikasi_terkait: WHERE is_active=1 ORDER BY urutan
        $this->db->query("
            ALTER TABLE `{$p}aplikasi_terkait`
                ADD INDEX `idx_aplikasi_listing` (`is_active`, `urutan`)
        ");

        // program_unggulan: WHERE is_active=1 ORDER BY urutan
        $this->db->query("
            ALTER TABLE `{$p}program_unggulan`
                ADD INDEX `idx_program_listing` (`is_active`, `urutan`)
        ");

        // kurikulum_blok: WHERE is_active=1 ORDER BY urutan
        $this->db->query("
            ALTER TABLE `{$p}kurikulum_blok`
                ADD INDEX `idx_kurikulum_listing` (`is_active`, `urutan`)
        ");

        // nilai_sekolah: ORDER BY urutan
        $this->db->query("
            ALTER TABLE `{$p}nilai_sekolah`
                ADD INDEX `idx_nilai_urutan` (`urutan`)
        ");

        // kepala_sekolah: ORDER BY urutan
        $this->db->query("
            ALTER TABLE `{$p}kepala_sekolah`
                ADD INDEX `idx_kepala_urutan` (`urutan`)
        ");
    }

    public function down(): void
    {
        $p = $this->db->DBPrefix;

        // ----------------------------------------------------------------
        // Kembalikan ke single-column index (kondisi sebelum migrasi ini)
        // ----------------------------------------------------------------

        $this->db->query("
            ALTER TABLE `{$p}artikel`
                DROP INDEX `idx_artikel_publik`,
                DROP INDEX `idx_artikel_featured`,
                ADD INDEX `status`       (`status`),
                ADD INDEX `is_featured`  (`is_featured`),
                ADD INDEX `published_at` (`published_at`)
        ");

        $this->db->query("
            ALTER TABLE `{$p}galeri`
                DROP INDEX `idx_galeri_listing`,
                DROP INDEX `idx_galeri_featured`,
                ADD INDEX `kategori_id` (`kategori_id`),
                ADD INDEX `is_featured` (`is_featured`),
                ADD INDEX `urutan`      (`urutan`)
        ");

        $this->db->query("
            ALTER TABLE `{$p}guru_staf`
                DROP INDEX `idx_guru_listing`,
                ADD INDEX `tipe`      (`tipe`),
                ADD INDEX `is_active` (`is_active`),
                ADD INDEX `urutan`    (`urutan`)
        ");

        $this->db->query("
            ALTER TABLE `{$p}kegiatan`
                DROP INDEX `idx_kegiatan_listing`,
                DROP INDEX `idx_kegiatan_featured`,
                ADD INDEX `tanggal`    (`tanggal`),
                ADD INDEX `status`     (`status`),
                ADD INDEX `is_featured`(`is_featured`)
        ");

        $this->db->query("
            ALTER TABLE `{$p}prestasi`
                DROP INDEX `idx_prestasi_listing`,
                DROP INDEX `idx_prestasi_featured`,
                ADD INDEX `kategori`   (`kategori`),
                ADD INDEX `tahun`      (`tahun`),
                ADD INDEX `is_featured`(`is_featured`)
        ");

        $this->db->query("
            ALTER TABLE `{$p}ppdb_konten`
                DROP INDEX `idx_ppdb_listing`,
                ADD INDEX `tipe`      (`tipe`),
                ADD INDEX `urutan`    (`urutan`),
                ADD INDEX `is_active` (`is_active`)
        ");

        $this->db->query("
            ALTER TABLE `{$p}ekstrakurikuler`
                DROP INDEX `idx_ekskul_listing`,
                ADD INDEX `is_active` (`is_active`),
                ADD INDEX `urutan`    (`urutan`)
        ");

        $this->db->query("
            ALTER TABLE `{$p}menu`
                DROP INDEX `idx_menu_listing`,
                ADD INDEX `lokasi`    (`lokasi`),
                ADD INDEX `urutan`    (`urutan`),
                ADD INDEX `is_active` (`is_active`)
        ");

        $this->db->query("
            ALTER TABLE `{$p}quick_links`
                DROP INDEX `idx_quick_links_listing`,
                ADD INDEX `urutan`    (`urutan`),
                ADD INDEX `is_active` (`is_active`)
        ");

        $this->db->query("ALTER TABLE `{$p}album_foto`       DROP INDEX `idx_album_listing`");
        $this->db->query("ALTER TABLE `{$p}aplikasi_terkait` DROP INDEX `idx_aplikasi_listing`");
        $this->db->query("ALTER TABLE `{$p}program_unggulan` DROP INDEX `idx_program_listing`");
        $this->db->query("ALTER TABLE `{$p}kurikulum_blok`   DROP INDEX `idx_kurikulum_listing`");
        $this->db->query("ALTER TABLE `{$p}nilai_sekolah`    DROP INDEX `idx_nilai_urutan`");
        $this->db->query("ALTER TABLE `{$p}kepala_sekolah`   DROP INDEX `idx_kepala_urutan`");
    }
}
