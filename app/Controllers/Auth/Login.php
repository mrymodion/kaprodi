<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Validation\ValidationRules;

/**
 * Login custom dengan tampilan UTD.
 * Kalau Shield tidak tersedia, controller men-render pesan instalasi.
 */
class Login extends BaseController
{
    public function index()
    {
        if (!function_exists('auth')) {
            return view('auth/install_required');
        }
        if (auth()->loggedIn()) {
            return redirect()->to($this->homeFor());
        }
        return view('auth/login');
    }

    /** Tujuan setelah login: dosen biasa (tanpa hak kelola) diarahkan ke area Profil Saya. */
    private function homeFor(): string
    {
        $u = auth()->user();
        if ($u && $u->inGroup('dosen') && !$u->can('dosen.manage')) {
            return '/akun';
        }
        return '/';
    }

    public function attempt()
    {
        if (!function_exists('auth')) {
            return redirect()->to('/login');
        }
        // Shield melempar LogicException kalau ada session user aktif saat attempt().
        // Pastikan sesi lama dibersihkan sebelum login ulang.
        if (auth()->loggedIn()) {
            auth()->logout();
        }
        $rules = (new ValidationRules())->getLoginRules();
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $credentials = [
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ];
        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();
        $result = $authenticator->attempt($credentials);
        if (!$result->isOK()) {
            return redirect()->back()->withInput()->with('error', $result->reason());
        }
        return redirect()->to($this->homeFor());
    }

    public function logout()
    {
        if (function_exists('auth')) {
            auth()->logout();
        }
        session()->destroy(); // bersihkan scope_resolved, active_prodi_id, dll
        return redirect()->to('/login');
    }
}
