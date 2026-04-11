<?php

use App\Models\PengaturanModel;

if (! function_exists('setting')) {
    /**
     * Ambil nilai pengaturan situs berdasarkan key.
     * Hasil di-cache per request menggunakan static variable.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        static $cache = [];

        if (! isset($cache[$key])) {
            $model        = new PengaturanModel();
            $cache[$key]  = $model->getByKey($key, $default);
        }

        return $cache[$key];
    }
}

if (! function_exists('slug_generate')) {
    /**
     * Buat slug URL-safe dari string.
     * Mendukung karakter Latin dan Unicode (bahasa Indonesia).
     */
    function slug_generate(string $string): string
    {
        // Transliterasi karakter khusus
        $string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
        $string = preg_replace('/[\s-]+/', '-', $string);

        return trim($string, '-');
    }
}

if (! function_exists('format_tanggal')) {
    /**
     * Format tanggal ke Bahasa Indonesia.
     * Contoh: 2026-03-24 → 24 Maret 2026
     */
    function format_tanggal(string $date, string $format = 'long'): string
    {
        $bulan = [
            1  => 'Januari', 2  => 'Februari', 3  => 'Maret',
            4  => 'April',   5  => 'Mei',       6  => 'Juni',
            7  => 'Juli',    8  => 'Agustus',   9  => 'September',
            10 => 'Oktober', 11 => 'November',  12 => 'Desember',
        ];

        $hariIndo = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];

        $ts  = strtotime($date);
        $d   = (int) date('j', $ts);
        $m   = (int) date('n', $ts);
        $y   = date('Y', $ts);
        $wd  = date('l', $ts);

        return match ($format) {
            'long'  => $d . ' ' . ($bulan[$m] ?? '') . ' ' . $y,
            'full'  => ($hariIndo[$wd] ?? $wd) . ', ' . $d . ' ' . ($bulan[$m] ?? '') . ' ' . $y,
            'short' => $d . '/' . $m . '/' . $y,
            default => date($format, $ts),
        };
    }
}

if (! function_exists('truncate_text')) {
    /**
     * Potong teks dengan elipsis, tanpa memotong kata.
     */
    function truncate_text(string $text, int $length = 150, string $suffix = '...'): string
    {
        $text = strip_tags($text);

        if (mb_strlen($text) <= $length) {
            return $text;
        }

        $truncated = mb_substr($text, 0, $length);
        $lastSpace = mb_strrpos($truncated, ' ');

        if ($lastSpace !== false) {
            $truncated = mb_substr($truncated, 0, $lastSpace);
        }

        return $truncated . $suffix;
    }
}

if (! function_exists('html_purify')) {
    /**
     * Sanitasi HTML dari Quill editor sebelum disimpan ke database.
     *
     * Strategi:
     *  1. Hapus <script> dan <style> beserta isinya secara keseluruhan.
     *  2. Izinkan hanya tag yang dihasilkan Quill (allowlist).
     *  3. Hapus semua atribut event handler (onclick, onerror, dll.).
     *  4. Blokir href/src dengan nilai javascript: atau data:text.
     */
    function html_purify(string $html): string
    {
        if (empty($html)) {
            return '';
        }

        // 1. Hapus blok <script> dan <style> beserta kontennya
        $html = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $html);
        $html = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $html);

        // 2. Izinkan hanya tag output Quill yang aman
        $allowedTags = '<p><br><strong><b><em><i><u><s>'
                     . '<h1><h2><h3><h4><h5><h6>'
                     . '<ul><ol><li><blockquote><pre><code>'
                     . '<a><img><figure><figcaption>'
                     . '<span><div>'
                     . '<table><thead><tbody><tr><th><td>'
                     . '<iframe>';
        $html = strip_tags($html, $allowedTags);

        // 3. Hapus semua event handler (on*)
        $html = preg_replace('/\s+on[a-zA-Z]+\s*=\s*"[^"]*"/i', '', $html);
        $html = preg_replace('/\s+on[a-zA-Z]+\s*=\s*\'[^\']*\'/i', '', $html);
        $html = preg_replace('/\s+on[a-zA-Z]+\s*=[^\s>]*/i', '', $html);

        // 4. Blokir javascript: dan data:text di href/src
        $html = preg_replace(
            '/(\s(?:href|src|action)\s*=\s*["\'])\s*(?:javascript|data\s*:\s*text)[^"\']*(["\'])/i',
            '$1#$2',
            $html
        );

        return trim($html);
    }
}

if (! function_exists('active_menu')) {
    /**
     * Kembalikan class 'active' jika URL cocok dengan current page.
     */
    function active_menu(string $url): string
    {
        $currentUrl = '/' . ltrim(service('request')->getUri()->getPath(), '/');

        if ($url === '/' && $currentUrl === '/') {
            return 'active';
        }

        if ($url !== '/' && str_starts_with($currentUrl, $url)) {
            return 'active';
        }

        return '';
    }
}
