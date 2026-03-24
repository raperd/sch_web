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
