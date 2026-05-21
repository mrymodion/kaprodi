<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

/**
 * Override AuthGroups bawaan Shield dengan 10 role akreditasi.
 * File ini di-load otomatis oleh Shield jika berada di app/Config.
 */
class AuthGroups extends ShieldAuthGroups
{
    public string $defaultGroup = 'mahasiswa';

    public array $groups = [
        'superadmin' => [
            'title'       => 'Super Administrator',
            'description' => 'Akses penuh seluruh sistem, semua PT/prodi, kelola instrumen LAM.',
        ],
        'pimpinan_upps' => [
            'title'       => 'Pimpinan UPPS',
            'description' => 'Dekan / Direktur: akses semua prodi di UPPS, approve LKPS/LED level UPPS.',
        ],
        'kaprodi' => [
            'title'       => 'Kepala Program Studi',
            'description' => 'Owner data prodinya, approve LKPS/LED level prodi.',
        ],
        'sekprodi' => [
            'title'       => 'Sekretaris Program Studi',
            'description' => 'Input harian, draft LKPS/LED.',
        ],
        'gkm' => [
            'title'       => 'Gugus Kendali Mutu (GKM/UPM)',
            'description' => 'SPMI, AMI, kelola survei kepuasan.',
        ],
        'dosen' => [
            'title'       => 'Dosen (DTPR)',
            'description' => 'Input mandiri: penelitian, PkM, publikasi, EWMP.',
        ],
        'tendik' => [
            'title'       => 'Tenaga Kependidikan',
            'description' => 'Input data tendik dan sarpras.',
        ],
        'mahasiswa' => [
            'title'       => 'Mahasiswa / Alumni',
            'description' => 'Isi survei, tracer study.',
        ],
        'mitra' => [
            'title'       => 'Mitra / Pengguna Lulusan',
            'description' => 'Isi survei kepuasan pengguna lulusan (akses via token).',
        ],
        'asesor' => [
            'title'       => 'Asesor',
            'description' => 'Akses baca LKPS/LED/evidence saat asesmen lapangan.',
        ],
        'auditor' => [
            'title'       => 'Auditor Internal',
            'description' => 'Akses audit trail dan AMI.',
        ],
    ];

    public array $permissions = [
        'admin.access'      => 'Akses panel administrasi.',
        'users.manage'      => 'Kelola user dan role.',
        'instrumen.manage'  => 'Kelola instrumen LAM.',
        'prodi.manage'      => 'Kelola data prodi.',
        'dosen.manage'      => 'Kelola data DTPR.',
        'dosen.view_own'    => 'Lihat & edit data diri (dosen).',
        'mahasiswa.manage'  => 'Kelola data mahasiswa.',
        'mahasiswa.view_own'=> 'Lihat data diri (mahasiswa).',
        'kurikulum.manage'  => 'Kelola kurikulum & CPL.',
        'tridharma.manage'  => 'Kelola penelitian, PkM, publikasi.',
        'tridharma.input_own' => 'Input penelitian/PkM milik sendiri (dosen).',
        'kerjasama.manage'  => 'Kelola kerjasama.',
        'spmi.manage'       => 'Kelola SPMI/AMI/PPEPP.',
        'survei.manage'     => 'Kelola survei.',
        'survei.respond'    => 'Mengisi survei.',
        'penilaian.input'   => 'Input skor manual butir LAM.',
        'penilaian.approve' => 'Approve skor butir.',
        'lkps.generate'     => 'Generate LKPS.',
        'led.generate'      => 'Generate LED.',
        'evidence.upload'   => 'Upload bukti dokumen.',
        'evidence.view'     => 'Lihat bukti dokumen.',
        'audit.view'        => 'Lihat audit trail.',
    ];

    public array $matrix = [
        'superadmin' => ['admin.access','users.manage','instrumen.manage','prodi.manage','dosen.manage','mahasiswa.manage','kurikulum.manage','tridharma.manage','kerjasama.manage','spmi.manage','survei.manage','penilaian.input','penilaian.approve','lkps.generate','led.generate','evidence.upload','evidence.view','audit.view'],
        'pimpinan_upps' => ['admin.access','prodi.manage','dosen.manage','penilaian.approve','lkps.generate','led.generate','evidence.view','audit.view'],
        'kaprodi'    => ['admin.access','dosen.manage','mahasiswa.manage','kurikulum.manage','tridharma.manage','kerjasama.manage','spmi.manage','survei.manage','penilaian.input','penilaian.approve','lkps.generate','led.generate','evidence.upload','evidence.view'],
        'sekprodi'   => ['admin.access','dosen.manage','mahasiswa.manage','kurikulum.manage','tridharma.manage','kerjasama.manage','survei.manage','penilaian.input','lkps.generate','led.generate','evidence.upload','evidence.view'],
        'gkm'        => ['admin.access','spmi.manage','survei.manage','penilaian.input','evidence.view','audit.view'],
        'dosen'      => ['admin.access','dosen.view_own','tridharma.input_own','evidence.upload','evidence.view'],
        'tendik'     => ['admin.access','evidence.view'],
        'mahasiswa'  => ['mahasiswa.view_own','survei.respond'],
        'mitra'      => ['survei.respond'],
        'asesor'     => ['admin.access','evidence.view','audit.view','lkps.generate','led.generate'],
        'auditor'    => ['admin.access','audit.view','evidence.view','spmi.manage'],
    ];
}
