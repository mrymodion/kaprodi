<?php

namespace App\Services;

/**
 * Klien WhatsApp Gateway Fonnte (https://fonnte.com).
 * Token device disimpan di .env sebagai fonnte.token.
 */
class FonnteClient
{
    public function enabled(): bool
    {
        return trim((string) env('fonnte.token', '')) !== '';
    }

    /** Kirim pesan WA. Return ['ok'=>bool, 'error'=>?string]. */
    public function send(string $target, string $message): array
    {
        $token = trim((string) env('fonnte.token', ''));
        if ($token === '') {
            return ['ok' => false, 'error' => 'fonnte.token belum dikonfigurasi di .env.'];
        }
        $target = $this->normalizePhone($target);
        if ($target === '') {
            return ['ok' => false, 'error' => 'Nomor WhatsApp kosong / tidak valid.'];
        }

        try {
            $res = \Config\Services::curlrequest()->request('POST', 'https://api.fonnte.com/send', [
                'headers'     => ['Authorization' => $token],
                'form_params' => ['target' => $target, 'message' => $message],
                'timeout'     => (int) env('fonnte.timeout', 30),
                'http_errors' => false,
            ]);
            $body = json_decode((string) $res->getBody(), true) ?: [];
            $status = $body['status'] ?? null;
            if ($status === true || $status === 'true' || $status === 1) {
                return ['ok' => true, 'error' => null];
            }
            $reason = $body['reason'] ?? ($body['detail'] ?? ('Gagal (HTTP ' . $res->getStatusCode() . ')'));
            return ['ok' => false, 'error' => (string) $reason];
        } catch (\Throwable $e) {
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }

    /** Normalkan nomor ke format 62xxxx. */
    public function normalizePhone(string $p): string
    {
        $p = preg_replace('/[^0-9]/', '', $p);
        if ($p === '') return '';
        if (str_starts_with($p, '0')) {
            return '62' . substr($p, 1);
        }
        if (!str_starts_with($p, '62')) {
            return '62' . $p;
        }
        return $p;
    }
}
