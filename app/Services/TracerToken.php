<?php

namespace App\Services;

/**
 * Token tertanda untuk link publik tracer study (tanpa kolom DB tambahan).
 * Token = "{mahasiswaId}-{hmac}". Hanya bisa dibuat/diverifikasi oleh server.
 */
class TracerToken
{
    public static function make(int $mahasiswaId, string $purpose = ''): string
    {
        $sig = substr(hash_hmac('sha256', $purpose . ':' . $mahasiswaId, self::secret()), 0, 24);
        return $mahasiswaId . '-' . $sig;
    }

    public static function verify(string $token, string $purpose = ''): ?int
    {
        $parts = explode('-', $token, 2);
        if (count($parts) !== 2 || !ctype_digit($parts[0])) {
            return null;
        }
        $id = (int) $parts[0];
        return hash_equals(self::make($id, $purpose), $token) ? $id : null;
    }

    private static function secret(): string
    {
        $key = (string) env('encryption.key', '');
        return $key !== '' ? $key : 'kaprodi-tracer-' . (string) env('app.baseURL', 'local');
    }
}
