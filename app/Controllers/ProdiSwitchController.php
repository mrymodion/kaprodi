<?php

namespace App\Controllers;

use App\Models\ProdiModel;

class ProdiSwitchController extends BaseController
{
    public function switch(int $id)
    {
        $user = auth()->user();
        if (!$user || !$user->inGroup('superadmin')) {
            return redirect()->back()->with('error', 'Hanya superadmin yang dapat berganti prodi.');
        }

        $prodi = (new ProdiModel())->find($id);
        if (!$prodi) {
            return redirect()->back()->with('error', 'Prodi tidak ditemukan.');
        }

        session()->set('active_prodi_id', (int) $id);

        return redirect()->back()->with('message', 'Prodi aktif: ' . $prodi['nama']);
    }
}
