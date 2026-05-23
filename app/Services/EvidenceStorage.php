<?php

namespace App\Services;

use CodeIgniter\HTTP\Files\UploadedFile;

/**
 * Simpan berkas bukti PDF ke writable/bukti/ dan kompres bila memungkinkan.
 *
 * Kompresi memakai Ghostscript (binary `gs`/`gswin64c`). Jika Ghostscript
 * tidak terpasang, berkas asli tetap disimpan tanpa kompresi (fallback aman).
 * Path Ghostscript & level kompresi dapat diatur via .env:
 *   pdf.ghostscript   = (mis. C:\\Program Files\\gs\\gs10.03.0\\bin\\gswin64c.exe)
 *   pdf.compressLevel = screen | ebook | printer | prepress   (default: ebook)
 */
class EvidenceStorage
{
    public function dir(): string
    {
        $d = WRITEPATH . 'bukti/';
        if (!is_dir($d)) @mkdir($d, 0775, true);
        return $d;
    }

    /** Simpan + kompres PDF. Return ['ok'=>bool, 'name'=>?, 'error'=>?, 'compressed'=>bool, 'before'=>int, 'after'=>int]. */
    public function storePdf(UploadedFile $file): array
    {
        if (!$file->isValid()) {
            return ['ok' => false, 'error' => 'File tidak valid: ' . $file->getErrorString()];
        }
        $ext = strtolower($file->getClientExtension() ?: $file->getExtension());
        if ($ext !== 'pdf') {
            return ['ok' => false, 'error' => 'Hanya berkas PDF yang diperbolehkan.'];
        }
        $maxMb = (int) env('storage.maxUploadMb', 20);
        if ($file->getSize() > $maxMb * 1024 * 1024) {
            return ['ok' => false, 'error' => "Ukuran berkas melebihi batas {$maxMb} MB."];
        }
        // Verifikasi magic bytes %PDF-
        $fh = @fopen($file->getTempName(), 'rb');
        $head = $fh ? fread($fh, 5) : '';
        if ($fh) fclose($fh);
        if (strncmp($head, '%PDF-', 5) !== 0) {
            return ['ok' => false, 'error' => 'Berkas yang diunggah bukan PDF yang sah.'];
        }

        $name = 'b' . bin2hex(random_bytes(8)) . '.pdf';
        $file->move($this->dir(), $name);
        $dest = $this->dir() . $name;

        $before     = (int) @filesize($dest);
        $compressed = $this->compress($dest);
        clearstatcache(true, $dest);
        $after      = (int) @filesize($dest);

        return ['ok' => true, 'name' => $name, 'compressed' => $compressed, 'before' => $before, 'after' => $after];
    }

    public function delete(?string $name): void
    {
        if (!$name) return;
        $p = $this->dir() . basename($name);
        if (is_file($p)) @unlink($p);
    }

    public function path(string $name): string
    {
        return $this->dir() . basename($name);
    }

    /** Kompres PDF di tempat memakai Ghostscript. Return true jika berhasil mengecilkan. */
    private function compress(string $path): bool
    {
        $gs = $this->gsBinary();
        if (!$gs) return false;

        $level = preg_replace('/[^a-z]/', '', strtolower((string) env('pdf.compressLevel', 'ebook'))) ?: 'ebook';
        if (!in_array($level, ['screen', 'ebook', 'printer', 'prepress'], true)) $level = 'ebook';

        $out = $path . '.cmp';
        $cmd = escapeshellarg($gs)
            . ' -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/' . $level
            . ' -dNOPAUSE -dQUIET -dBATCH -sOutputFile=' . escapeshellarg($out) . ' ' . escapeshellarg($path) . ' 2>&1';

        $output = []; $code = 1;
        @exec($cmd, $output, $code);

        if ($code === 0 && is_file($out) && filesize($out) > 0 && filesize($out) < filesize($path)) {
            @rename($out, $path);
            return true;
        }
        if (is_file($out)) @unlink($out);
        return false;
    }

    /** Cari binary Ghostscript yang tersedia, atau null. */
    private function gsBinary(): ?string
    {
        if (!function_exists('exec')) return null;
        $disabled = array_map('trim', explode(',', (string) ini_get('disable_functions')));
        if (in_array('exec', $disabled, true)) return null;

        $configured = trim((string) env('pdf.ghostscript', ''));
        $cands = $configured !== '' ? [$configured] : ['gswin64c', 'gswin32c', 'gs'];
        foreach ($cands as $c) {
            $o = []; $code = 1;
            @exec(escapeshellarg($c) . ' --version 2>&1', $o, $code);
            if ($code === 0) return $c;
        }
        return null;
    }
}
