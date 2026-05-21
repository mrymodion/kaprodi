<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;

/**
 * Inject "active_prodi_id" ke session berdasarkan user login.
 * Aturan:
 * - Superadmin: prodi_id null (akses semua), bisa pilih prodi via switcher.
 * - User lain: ambil prodi default dari tabel user_prodi.
 * Modul CRUD selanjutnya WAJIB menyaring query berdasarkan session('active_prodi_id')
 * kecuali user superadmin.
 */
class ProdiScopeFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!function_exists('auth')) {
            return; // Shield belum diinstall, skip
        }
        $user = auth()->user();
        if (!$user) return;

        $session = session();

        // Superadmin: bebas, hanya isi jika belum di-set
        if ($user->inGroup('superadmin')) {
            $session->set('is_superadmin', true);
            return;
        }
        $session->set('is_superadmin', false);

        if ($session->has('active_prodi_id')) return;

        $db = Database::connect();
        $row = $db->table('user_prodi')
            ->where(['user_id' => $user->id])
            ->orderBy('is_default', 'DESC')
            ->limit(1)
            ->get()->getRowArray();

        if ($row) {
            $session->set('active_prodi_id', (int)$row['prodi_id']);
            $session->set('active_peran', $row['peran']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
