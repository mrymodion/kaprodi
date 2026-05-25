<?php

namespace App\Services;

/**
 * Ekstraksi teks dari PDF (untuk auto-isi data dari sertifikat).
 *
 * Dua jalur (urut prioritas):
 *  1. `pdftotext` (poppler-utils) bila binary tersedia — cepat & akurat.
 *  2. Library PHP `smalot/pdfparser` bila terpasang via composer — tanpa
 *     dependensi sistem, sebagai fallback.
 * Bila keduanya tidak tersedia / gagal, mengembalikan null.
 */
class PdfTextExtractor
{
    public function available(): bool
    {
        return $this->binary() !== null || class_exists(\Smalot\PdfParser\Parser::class);
    }

    /** Ekstrak teks dari beberapa halaman pertama. Return teks atau null bila gagal. */
    public function extract(string $path, int $maxPages = 3): ?string
    {
        if (!is_file($path)) return null;

        $text = $this->extractWithPdftotext($path, $maxPages);
        if ($text !== null && mb_strlen($text) >= 20) return $text;

        $php = $this->extractWithPhp($path);
        if ($php !== null && mb_strlen($php) >= 20) return $php;

        return $text ?? $php; // kembalikan apa pun yang sempat didapat (mungkin pendek)
    }

    private function extractWithPdftotext(string $path, int $maxPages): ?string
    {
        $bin = $this->binary();
        if ($bin === null) return null;
        $cmd = escapeshellarg($bin) . ' -layout -enc UTF-8 -f 1 -l ' . max(1, $maxPages)
            . ' ' . escapeshellarg($path) . ' - 2>/dev/null';
        $out = []; $code = 1;
        @exec($cmd, $out, $code);
        if ($code !== 0) return null;
        $text = trim(implode("\n", $out));
        return $text !== '' ? $text : null;
    }

    private function extractWithPhp(string $path): ?string
    {
        if (!class_exists(\Smalot\PdfParser\Parser::class)) return null;
        try {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf    = $parser->parseFile($path);
            $text   = trim($pdf->getText());
            return $text !== '' ? $text : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /** Lokasi binary pdftotext yang valid, atau null (deteksi via `command -v`). */
    private function binary(): ?string
    {
        if (!function_exists('exec')) return null;
        $disabled = array_map('trim', explode(',', (string) ini_get('disable_functions')));
        if (in_array('exec', $disabled, true)) return null;

        $configured = trim((string) env('pdf.pdftotext', ''));
        $cands = $configured !== '' ? [$configured] : ['pdftotext'];
        foreach ($cands as $c) {
            $o = []; $code = 1;
            // `command -v` mengembalikan 0 hanya bila binary benar-benar ada.
            @exec('command -v ' . escapeshellarg($c) . ' 2>/dev/null', $o, $code);
            if ($code === 0 && !empty($o)) return $c;
        }
        return null;
    }
}
