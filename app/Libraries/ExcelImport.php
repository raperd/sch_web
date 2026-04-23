<?php

namespace App\Libraries;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * ExcelImport — Utilitas baca/tulis Excel untuk fitur import data admin.
 */
class ExcelImport
{
    /**
     * Baca file Excel yang diupload dan kembalikan array of rows (key = header).
     *
     * @param  string $filePath Path lengkap file sementara
     * @param  int    $headerRow Baris yang berisi header (default 1)
     * @return array  [['kolom' => 'nilai', ...], ...]
     */
    public function readRows(string $filePath, int $headerRow = 1): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray(null, true, true, false);

        if (empty($rows)) {
            return [];
        }

        // Ambil header dari baris yang ditentukan (0-indexed array)
        $headers = array_map(
            fn($h) => trim((string) $h),
            $rows[$headerRow - 1]
        );

        $data = [];
        $totalRows = count($rows);

        for ($i = $headerRow; $i < $totalRows; $i++) {
            $row = $rows[$i];

            // Skip baris kosong
            $allEmpty = true;
            foreach ($row as $cell) {
                if ($cell !== null && trim((string) $cell) !== '') {
                    $allEmpty = false;
                    break;
                }
            }
            if ($allEmpty) {
                continue;
            }

            // Gabungkan header + nilai
            $mapped = [];
            foreach ($headers as $colIdx => $header) {
                if ($header === '') {
                    continue;
                }
                $val = $row[$colIdx] ?? null;
                $mapped[$header] = ($val !== null) ? trim((string) $val) : '';
            }

            $data[] = [
                'row'  => $i + 1, // nomor baris di Excel (1-based)
                'data' => $mapped,
            ];
        }

        return $data;
    }

    /**
     * Buat file template Excel dan stream langsung ke browser.
     *
     * @param  string  $filename   Nama file unduhan (tanpa ekstensi)
     * @param  array   $headers    ['Kolom A', 'Kolom B', ...]
     * @param  array   $example    Baris contoh data [[val1, val2, ...]]
     * @param  array   $notes      Catatan pengisian per kolom ['Kolom A' => 'Harus diisi', ...]
     */
    public function streamTemplate(
        string $filename,
        array  $headers,
        array  $example = [],
        array  $notes   = []
    ): void {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data');

        $colCount = count($headers);

        // ── Baris 1: Header ──────────────────────────────────────────
        foreach ($headers as $i => $header) {
            $col = $i + 1;
            $sheet->setCellValue([$col, 1], $header);
        }

        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colCount);

        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1A5276']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FFFFFF']]],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // ── Baris 2: Contoh data ──────────────────────────────────────
        $exampleRow = $example[0] ?? [];
        foreach ($headers as $i => $header) {
            $val = $exampleRow[$i] ?? '';
            $sheet->setCellValue([$i + 1, 2], $val);
        }

        $sheet->getStyle("A2:{$lastCol}2")->applyFromArray([
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EBF5FB']],
            'font'      => ['color' => ['rgb' => '555555'], 'italic' => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
        ]);

        // ── Baris kosong untuk input (3 – 102) ───────────────────────
        $sheet->getStyle("A3:{$lastCol}102")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'DDDDDD']]],
        ]);

        // ── Sheet catatan ─────────────────────────────────────────────
        if (! empty($notes)) {
            $noteSheet = $spreadsheet->createSheet();
            $noteSheet->setTitle('Panduan');
            $noteSheet->setCellValue('A1', 'Kolom');
            $noteSheet->setCellValue('B1', 'Keterangan');
            $noteSheet->getStyle('A1:B1')->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D5F5E3']],
            ]);
            $r = 2;
            foreach ($notes as $col => $note) {
                $noteSheet->setCellValue('A' . $r, $col);
                $noteSheet->setCellValue('B' . $r, $note);
                $r++;
            }
            $noteSheet->getColumnDimension('A')->setWidth(25);
            $noteSheet->getColumnDimension('B')->setWidth(60);
        }

        // ── Auto width kolom ──────────────────────────────────────────
        foreach (range(1, $colCount) as $col) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        $spreadsheet->setActiveSheetIndex(0);

        // ── Stream download ───────────────────────────────────────────
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
