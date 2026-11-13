<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Database;
use CodeIgniter\Shield\Entities\User;

class AsesorController extends BaseController
{
    public function index()
    {
        $db = Database::connect();
        
        // Mengambil daftar akun yang memiliki role/grup 'asesor'
        $asesorList = $db->table('users u')
            ->select('u.id, u.username, u.active, u.created_at, ai.secret as email')
            ->join('auth_groups_users agu', 'agu.user_id = u.id')
            ->join('auth_identities ai', 'ai.user_id = u.id AND ai.type = "email_password"')
            ->where('agu.group', 'asesor')
            ->get()->getResultArray();

        return view('admin/asesor/index', ['asesorList' => $asesorList]);
    }

    public function create()
    {
        return view('admin/asesor/form', ['asesor' => null, 'action' => site_url('admin/asesor')]);
    }

    public function store()
    {
        $users = auth()->getProvider();

        $user = new User([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ]);

        try {
            $users->save($user);
            $user = $users->findById($users->getInsertID());
            
            // Tambahkan role asesor
            $user->addGroup('asesor');
            
            if ($this->request->getPost('active') == '1') $user->activate();
            else $user->deactivate();

            return redirect()->to(site_url('admin/asesor'))->with('success', 'Akun Asesor Eksternal berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat akun: ' . $e->getMessage());
        }
    }

    public function edit(int $id)
    {
        $user = auth()->getProvider()->findById($id);
        if (!$user || !$user->inGroup('asesor')) {
            return redirect()->to(site_url('admin/asesor'))->with('error', 'Akun asesor tidak ditemukan.');
        }

        return view('admin/asesor/form', ['asesor' => $user, 'action' => site_url("admin/asesor/{$id}/update")]);
    }

    public function update(int $id)
    {
        $users = auth()->getProvider();
        $user  = $users->findById($id);
        if (!$user || !$user->inGroup('asesor')) return redirect()->to(site_url('admin/asesor'))->with('error', 'Akun asesor tidak ditemukan.');

        $user->fill([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
        ]);

        if ($password = $this->request->getPost('password')) {
            $user->fill(['password' => $password]);
        }

        $users->save($user);
        if ($this->request->getPost('active') == '1') $user->activate();
        else $user->deactivate();

        return redirect()->to(site_url('admin/asesor'))->with('success', 'Akun Asesor Eksternal berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $users = auth()->getProvider();
        if ($user = $users->findById($id)) $users->delete($id, true);
        return redirect()->to(site_url('admin/asesor'))->with('success', 'Akun Asesor berhasil dihapus permanen.');
    }
}