<?php

namespace App\Services;

/**
 * Ekstraksi teks dari PDF (untuk auto-isi data dari sertifikat).
 * Memakai `pdftotext` (poppler-utils) bila tersedia. Bila tidak ada,
 * mengembalikan null (pemanggil menyiapkan fallback).
 */
class PdfTextExtractor
{
    public function available(): bool
    {
        if (!function_exists('exec')) return false;
        $disabled = array_map('trim', explode(',', (string) ini_get('disable_functions')));
        if (in_array('exec', $disabled, true)) return false;
        $bin = $this->binary();
        return $bin !== null;
    }

    /** Ekstrak teks dari beberapa halaman pertama. Return teks atau null bila gagal. */
    public function extract(string $path, int $maxPages = 3): ?string
    {
        if (!is_file($path) || !$this->available()) return null;
        $bin = $this->binary();
        $cmd = escapeshellarg($bin) . ' -layout -enc UTF-8 -f 1 -l ' . (int) $maxPages
            . ' ' . escapeshellarg($path) . ' - 2>/dev/null';
        $out = []; $code = 1;
        @exec($cmd, $out, $code);
        if ($code !== 0) return null;
        $text = trim(implode("\n", $out));
        return $text !== '' ? $text : null;
    }

    private function binary(): ?string
    {
        $configured = trim((string) env('pdf.pdftotext', ''));
        $cands = $configured !== '' ? [$configured] : ['pdftotext'];
        foreach ($cands as $c) {
            $o = []; $code = 1;
            @exec(escapeshellarg($c) . ' -v 2>&1', $o, $code);
            // pdftotext -v keluar dengan code 0 atau 99 tergantung versi; cek output mengandung "pdftotext"
            if ($code === 0 || stripos(implode(' ', $o), 'pdftotext') !== false) return $c;
        }
        return null;
    }
}
