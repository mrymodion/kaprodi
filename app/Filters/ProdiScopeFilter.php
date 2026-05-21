<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;

/**
 * Inject "active_prodi_id" ke session.
 * Optimasi: query DB hanya pada request pertama tiap login session;
 * status superadmin & prodi default di-cache di session.
 */
class ProdiScopeFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!function_exists('auth')) {
            return;
        }
        $session = session();

        // Sudah pernah resolved untuk session ini — skip semua DB call
        if ($session->has('scope_resolved')) {
            return;
        }

        $user = auth()->user();
        if (!$user) return;

        // Tandai sudah resolved supaya request berikutnya skip filter ini total
        $session->set('scope_resolved', true);

        if ($user->inGroup('superadmin')) {
            $session->set('is_superadmin', true);
            return;
        }
        $session->set('is_superadmin', false);

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
