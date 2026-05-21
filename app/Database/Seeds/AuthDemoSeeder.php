<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

/**
 * Seeder demo users untuk testing role.
 * WAJIB dijalankan SETELAH `php spark shield:setup` & `php spark migrate`.
 *
 * Akun yang dibuat (password default: "rahasia123"):
 *   superadmin / superadmin@utd.ac.id   -> role superadmin
 *   kaprodi    / kaprodi@utd.ac.id      -> role kaprodi (prodi MI D3 57401)
 *   dosen      / dosen@utd.ac.id        -> role dosen (prodi MI D3 57401)
 *   mahasiswa  / mahasiswa@utd.ac.id    -> role mahasiswa
 *   gkm        / gkm@utd.ac.id          -> role gkm
 *   asesor     / asesor@utd.ac.id       -> role asesor
 */
class AuthDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (!class_exists(UserModel::class)) {
            echo "Shield belum ter-install. Jalankan: composer require codeigniter4/shield && php spark shield:setup\n";
            return;
        }
        $users = new UserModel();
        $prodiMiD3 = $this->db->table('prodi')->where('kode_pddikti', '57401')->get()->getRowArray();
        $prodiId = $prodiMiD3 ? (int) $prodiMiD3['id'] : null;

        $accounts = [
            ['superadmin', 'superadmin@utd.ac.id', 'Super Administrator',     'superadmin', null],
            ['kaprodi',    'kaprodi@utd.ac.id',    'Kaprodi MI D3',           'kaprodi',    $prodiId],
            ['sekprodi',   'sekprodi@utd.ac.id',   'Sekretaris Prodi MI D3',  'sekprodi',   $prodiId],
            ['gkm',        'gkm@utd.ac.id',        'GKM UTD',                 'gkm',        $prodiId],
            ['dosen',      'dosen@utd.ac.id',      'Dosen DTPR MI D3',        'dosen',      $prodiId],
            ['tendik',     'tendik@utd.ac.id',     'Tendik UTD',              'tendik',     $prodiId],
            ['mahasiswa',  'mahasiswa@utd.ac.id',  'Mahasiswa Demo',          'mahasiswa',  $prodiId],
            ['asesor',     'asesor@utd.ac.id',     'Asesor Demo',             'asesor',     null],
            ['auditor',    'auditor@utd.ac.id',    'Auditor Internal',        'auditor',    null],
        ];

        foreach ($accounts as [$uname, $email, $nama, $role, $pid]) {
            if ($users->findByCredentials(['email' => $email])) continue;
            $user = new User([
                'username' => $uname,
                'email'    => $email,
                'password' => 'rahasia123',
            ]);
            $users->save($user);
            $user = $users->findById($users->getInsertID());
            $user->addGroup($role);

            if ($pid) {
                $this->db->table('user_prodi')->insert([
                    'user_id'    => $user->id,
                    'prodi_id'   => $pid,
                    'peran'      => $role,
                    'is_default' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        echo "Demo users dibuat. Password semua: rahasia123\n";
    }
}
