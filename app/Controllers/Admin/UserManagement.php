<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use Config\Database;
use Config\AuthGroups;

/**
 * Manajemen user untuk Super Admin.
 * Permission: users.manage
 */
class UserManagement extends BaseController
{
    public function index()
    {
        if (!function_exists('auth')) return view('auth/install_required');
        $this->guard();

        $users = (new UserModel())->orderBy('id')->findAll();
        $db = Database::connect();

        // Annotate prodi & group
        foreach ($users as $u) {
            $u->groups_str = implode(', ', $u->getGroups() ?: []);
            $rows = $db->table('user_prodi up')
                ->select('p.nama, p.jenjang, up.peran')
                ->join('prodi p', 'p.id = up.prodi_id')
                ->where('up.user_id', $u->id)->get()->getResultArray();
            $u->prodi_str = implode('; ', array_map(fn($r) => $r['nama'].' ('.$r['jenjang'].') - '.$r['peran'], $rows));
        }

        return view('admin/users/index', ['users' => $users]);
    }

    public function create()
    {
        if (!function_exists('auth')) return view('auth/install_required');
        $this->guard();
        return view('admin/users/form', [
            'mode'          => 'create',
            'user'          => null,
            'groups'        => (new AuthGroups())->groups,
            'prodi'         => Database::connect()->table('prodi')->orderBy('jenjang, nama')->get()->getResultArray(),
            'mapping'       => [],
            'dosenAll'      => $this->dosenAll(),
            'linkedDosenId' => 0,
        ]);
    }

    /** Daftar dosen aktif untuk penautan (id, prodi, nama+gelar, nidn). */
    private function dosenAll(): array
    {
        $rows = Database::connect()->table('dosen')
            ->select('id, prodi_id, nama, gelar_depan, gelar_belakang, nidn')
            ->where('aktif', 1)->orderBy('prodi_id, nama')->get()->getResultArray();
        foreach ($rows as &$r) {
            $r['label'] = trim(($r['gelar_depan'] ? $r['gelar_depan'] . ' ' : '') . $r['nama'] . ($r['gelar_belakang'] ? ', ' . $r['gelar_belakang'] : ''))
                . ($r['nidn'] ? ' — NIDN ' . $r['nidn'] : '');
        }
        return $rows;
    }

    public function store()
    {
        $this->guard();
        $post = $this->request->getPost();
        $um = new UserModel();

        $user = new User([
            'username' => $post['username'],
            'email'    => $post['email'],
            'password' => $post['password'],
        ]);
        $um->save($user);
        $user = $um->findById($um->getInsertID());

        foreach ((array)($post['groups'] ?? []) as $g) {
            $user->addGroup($g);
        }
        $this->saveProdiMapping((int)$user->id, (array)($post['prodi'] ?? []), (array)($post['peran'] ?? []), (array)($post['dosen'] ?? []));
        return redirect()->to('/admin/users')->with('success', 'User dibuat.');
    }

    public function edit(int $id)
    {
        if (!function_exists('auth')) return view('auth/install_required');
        $this->guard();
        $user = (new UserModel())->findById($id);
        if (!$user) return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');

        $db = Database::connect();
        $mapping = $db->table('user_prodi')->where('user_id', $id)->get()->getResultArray();
        $linked  = $db->table('dosen')->select('id')->where('user_id', $id)->get()->getRowArray();
        return view('admin/users/form', [
            'mode'          => 'edit',
            'user'          => $user,
            'groups'        => (new AuthGroups())->groups,
            'prodi'         => $db->table('prodi')->orderBy('jenjang, nama')->get()->getResultArray(),
            'mapping'       => $mapping,
            'currentGroups' => $user->getGroups() ?: [],
            'dosenAll'      => $this->dosenAll(),
            'linkedDosenId' => (int) ($linked['id'] ?? 0),
        ]);
    }

    public function update(int $id)
    {
        $this->guard();
        $um = new UserModel();
        $user = $um->findById($id);
        if (!$user) return redirect()->to('/admin/users');

        $post = $this->request->getPost();
        $user->fill([
            'username' => $post['username'],
            'email'    => $post['email'],
        ]);
        if (!empty($post['password'])) $user->password = $post['password'];
        $um->save($user);

        // Reset groups
        foreach ($user->getGroups() ?: [] as $g) $user->removeGroup($g);
        foreach ((array)($post['groups'] ?? []) as $g) $user->addGroup($g);

        // Reset mapping & tautan dosen (akan ditata ulang dari input form)
        $db = Database::connect();
        $db->table('user_prodi')->where('user_id', $id)->delete();
        $db->table('dosen')->where('user_id', $id)->update(['user_id' => null]);
        $this->saveProdiMapping($id, (array)($post['prodi'] ?? []), (array)($post['peran'] ?? []), (array)($post['dosen'] ?? []));
        return redirect()->to('/admin/users')->with('success', 'User diperbarui.');
    }

    public function delete(int $id)
    {
        $this->guard();
        (new UserModel())->delete($id);
        Database::connect()->table('user_prodi')->where('user_id', $id)->delete();
        return redirect()->to('/admin/users')->with('success', 'User dihapus.');
    }

    private function saveProdiMapping(int $userId, array $prodi, array $peran, array $dosen = []): void
    {
        $db = Database::connect();
        foreach ($prodi as $i => $pid) {
            if (!$pid) continue;
            $pid   = (int) $pid;
            $prn   = $peran[$i] ?? 'mahasiswa';
            $db->table('user_prodi')->insert([
                'user_id'    => $userId,
                'prodi_id'   => $pid,
                'peran'      => $prn,
                'is_default' => $i === 0 ? 1 : 0,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            // Penautan dosen DTPR (opsional): hubungkan akun ke record dosen,
            // dan bila peran kaprodi, tetapkan sebagai penandatangan prodi.
            $did = (int) ($dosen[$i] ?? 0);
            if (!$did) continue;
            $d = $db->table('dosen')->select('id, prodi_id')->where('id', $did)->get()->getRowArray();
            if (!$d || (int) $d['prodi_id'] !== $pid) continue; // dosen harus milik prodi baris ini

            $db->table('dosen')->where('id', $did)->update(['user_id' => $userId]);
            if ($prn === 'kaprodi') {
                $db->table('prodi')->where('id', $pid)->update(['kaprodi_dosen_id' => $did]);
            }
        }
    }

    private function guard(): void
    {
        if (!auth()->loggedIn() || !auth()->user()->can('users.manage')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
