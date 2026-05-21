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
            'mode'    => 'create',
            'user'    => null,
            'groups'  => (new AuthGroups())->groups,
            'prodi'   => Database::connect()->table('prodi')->orderBy('jenjang, nama')->get()->getResultArray(),
            'mapping' => [],
        ]);
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
        $this->saveProdiMapping((int)$user->id, (array)($post['prodi'] ?? []), (array)($post['peran'] ?? []));
        return redirect()->to('/admin/users')->with('success', 'User dibuat.');
    }

    public function edit(int $id)
    {
        if (!function_exists('auth')) return view('auth/install_required');
        $this->guard();
        $user = (new UserModel())->findById($id);
        if (!$user) return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');

        $mapping = Database::connect()->table('user_prodi')->where('user_id', $id)->get()->getResultArray();
        return view('admin/users/form', [
            'mode'    => 'edit',
            'user'    => $user,
            'groups'  => (new AuthGroups())->groups,
            'prodi'   => Database::connect()->table('prodi')->orderBy('jenjang, nama')->get()->getResultArray(),
            'mapping' => $mapping,
            'currentGroups' => $user->getGroups() ?: [],
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

        // Reset mapping
        Database::connect()->table('user_prodi')->where('user_id', $id)->delete();
        $this->saveProdiMapping($id, (array)($post['prodi'] ?? []), (array)($post['peran'] ?? []));
        return redirect()->to('/admin/users')->with('success', 'User diperbarui.');
    }

    public function delete(int $id)
    {
        $this->guard();
        (new UserModel())->delete($id);
        Database::connect()->table('user_prodi')->where('user_id', $id)->delete();
        return redirect()->to('/admin/users')->with('success', 'User dihapus.');
    }

    private function saveProdiMapping(int $userId, array $prodi, array $peran): void
    {
        $db = Database::connect();
        foreach ($prodi as $i => $pid) {
            if (!$pid) continue;
            $db->table('user_prodi')->insert([
                'user_id'    => $userId,
                'prodi_id'   => (int)$pid,
                'peran'      => $peran[$i] ?? 'mahasiswa',
                'is_default' => $i === 0 ? 1 : 0,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    private function guard(): void
    {
        if (!auth()->loggedIn() || !auth()->user()->can('users.manage')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
