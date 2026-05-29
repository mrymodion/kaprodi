<?php

namespace App\Services;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Output\QRGdImagePNG;
use chillerlan\QRCode\Common\EccLevel;

/**
 * Token validasi & QR code untuk Surat Tugas Mengajar.
 * Stateless: token = HMAC(dosen_id:ta_id, secret). Secret per-instalasi
 * disimpan di file WRITEPATH (tidak ikut repo, stabil antar request).
 */
class SuratValidator
{
    private function secret(): string
    {
        $path = WRITEPATH . 'surat_secret.key';
        if (is_file($path) && is_readable($path)) {
            $k = trim((string) file_get_contents($path));
            if ($k !== '') return $k;
        }
        $k = bin2hex(random_bytes(32));
        @file_put_contents($path, $k);
        return $k;
    }

    public function token(int $dosenId, int $taId): string
    {
        return substr(hash_hmac('sha256', $dosenId . ':' . $taId, $this->secret()), 0, 20);
    }

    public function url(int $dosenId, int $taId): string
    {
        return site_url('validasi/surat') . '?d=' . $dosenId . '&t=' . $taId . '&k=' . $this->token($dosenId, $taId);
    }

    public function verify(int $dosenId, int $taId, string $token): bool
    {
        return $token !== '' && hash_equals($this->token($dosenId, $taId), $token);
    }

    /** Token & URL visitasi eviden (prodi-level), dipakai untuk berbagi link ke asesor. */
    public function visitasiToken(int $prodiId): string
    {
        return substr(hash_hmac('sha256', 'visitasi:' . $prodiId, $this->secret()), 0, 20);
    }
    public function visitasiUrl(int $prodiId): string
    {
        return site_url('visitasi/eviden') . '?p=' . $prodiId . '&k=' . $this->visitasiToken($prodiId);
    }
    public function verifyVisitasi(int $prodiId, string $token): bool
    {
        return $token !== '' && hash_equals($this->visitasiToken($prodiId), $token);
    }

    /** QR berisi URL validasi sebagai PNG data URI (aman untuk dompdf). */
    public function qrDataUri(int $dosenId, int $taId): ?string
    {
        try {
            $opt = new QROptions([
                'outputInterface'  => QRGdImagePNG::class,
                'eccLevel'         => EccLevel::M,
                'scale'            => 4,
                'outputBase64'     => true,
                'imageTransparent' => false,
                'quietzoneSize'    => 2,
            ]);
            return (new QRCode($opt))->render($this->url($dosenId, $taId));
        } catch (\Throwable $e) {
            return null;
        }
    }
}
