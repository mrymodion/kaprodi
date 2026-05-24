<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public
$routes->get('login', 'Auth\Login::index');
$routes->post('login', 'Auth\Login::attempt');
$routes->get('logout', 'Auth\Login::logout');

// Form publik tracer study (diisi alumni via link WA, tanpa login)
$routes->get('tracer/isi/(:segment)',  'Tracer\PublicTracerController::form/$1');
$routes->post('tracer/isi/(:segment)', 'Tracer\PublicTracerController::submit/$1');

// Form publik survei kepuasan pengguna lulusan (diisi atasan/pengguna, tanpa login)
$routes->get('kepuasan/isi/(:segment)',  'Tracer\PublicKepuasanController::form/$1');
$routes->post('kepuasan/isi/(:segment)', 'Tracer\PublicKepuasanController::submit/$1');

// Routes yang butuh autentikasi (filter 'session' & 'prodiScope' diaktifkan di Config/Filters.php)
$routes->group('', ['filter' => ['session', 'prodiScope']], function ($routes) {
    $routes->get('/', 'Dashboard::index');

    // Area self-service dosen ("Profil Saya")
    $routes->group('akun', function ($routes) {
        $routes->get('/',          'Akun\AkunController::index');
        $routes->get('profil',     'Akun\AkunController::profil');
        $routes->post('profil',    'Akun\AkunController::saveProfil');
        $routes->get('password',   'Akun\AkunController::password');
        $routes->post('password',  'Akun\AkunController::savePassword');

        // Publikasi milik dosen
        $routes->get('publikasi',                  'Akun\KaryaController::publikasi');
        $routes->get('publikasi/cari',             'Akun\KaryaController::publikasiCari');
        $routes->post('publikasi/cari/search',     'Akun\KaryaController::publikasiSearch');
        $routes->post('publikasi/cari/import',     'Akun\KaryaController::publikasiImport');
        $routes->get('publikasi/baru',             'Akun\KaryaController::publikasiCreate');
        $routes->post('publikasi/baru',            'Akun\KaryaController::publikasiStore');
        $routes->get('publikasi/(:num)/edit',      'Akun\KaryaController::publikasiEdit/$1');
        $routes->post('publikasi/(:num)/update',   'Akun\KaryaController::publikasiUpdate/$1');
        $routes->post('publikasi/(:num)/delete',   'Akun\KaryaController::publikasiDelete/$1');
        $routes->post('publikasi/(:num)/klaim',    'Akun\KaryaController::publikasiKlaim/$1');

        // Penelitian milik dosen (sebagai ketua)
        $routes->get('penelitian',                 'Akun\KaryaController::penelitian');
        $routes->get('penelitian/baru',            'Akun\KaryaController::penelitianCreate');
        $routes->post('penelitian/baru',           'Akun\KaryaController::penelitianStore');
        $routes->get('penelitian/(:num)/edit',     'Akun\KaryaController::penelitianEdit/$1');
        $routes->post('penelitian/(:num)/update',  'Akun\KaryaController::penelitianUpdate/$1');
        $routes->post('penelitian/(:num)/delete',  'Akun\KaryaController::penelitianDelete/$1');

        // PkM milik dosen (sebagai ketua)
        $routes->get('pkm',                        'Akun\KaryaController::pkm');
        $routes->get('pkm/baru',                   'Akun\KaryaController::pkmCreate');
        $routes->post('pkm/baru',                  'Akun\KaryaController::pkmStore');
        $routes->get('pkm/(:num)/edit',            'Akun\KaryaController::pkmEdit/$1');
        $routes->post('pkm/(:num)/update',         'Akun\KaryaController::pkmUpdate/$1');
        $routes->post('pkm/(:num)/delete',         'Akun\KaryaController::pkmDelete/$1');

        // EWMP milik dosen
        $routes->get('ewmp',                       'Akun\DtprController::ewmp');
        $routes->post('ewmp',                      'Akun\DtprController::ewmpStore');

        // Pengembangan diri (LKPS 3.A.3)
        $routes->get('pengembangan',               'Akun\DtprController::pengembangan');
        $routes->post('pengembangan',              'Akun\DtprController::pengembanganStore');
        $routes->post('pengembangan/(:num)/delete','Akun\DtprController::pengembanganDelete/$1');

        // HKI milik dosen (3.C.3 / 4.C.3)
        $routes->get('hki',                        'Akun\DtprController::hki');
        $routes->post('hki/baru',                  'Akun\DtprController::hkiStore');
        $routes->post('hki/(:num)/klaim',          'Akun\DtprController::hkiKlaim/$1');
        $routes->post('hki/(:num)/delete',         'Akun\DtprController::hkiDelete/$1');

        // Rekognisi dosen (C3)
        $routes->get('rekognisi',                  'Akun\DtprController::rekognisi');
        $routes->post('rekognisi/baru',            'Akun\DtprController::rekognisiStore');
        $routes->post('rekognisi/(:num)/delete',   'Akun\DtprController::rekognisiDelete/$1');
    });

    // Pemilih prodi aktif (khusus superadmin)
    $routes->get('prodi/switch/(:num)', 'ProdiSwitchController::switch/$1');

    // Bantuan draf Groq AI (vmts, tupoksi, dampak_pkm, manfaat_kerjasama, ringkasan)
    $routes->post('ai/draft/(:segment)', 'AiController::draft/$1');

    // Manajemen Prodi
    $routes->group('prodi', ['filter' => 'permission:prodi.manage'], function ($routes) {
        $routes->get('/',              'ProdiController::index');
        $routes->get('create',         'ProdiController::create');
        $routes->post('store',         'ProdiController::store');
        $routes->get('(:num)/edit',    'ProdiController::edit/$1');
        $routes->post('(:num)/update', 'ProdiController::update/$1');
        $routes->post('(:num)/delete', 'ProdiController::delete/$1');
    });

    // Instrumen LAM (lihat struktur + pemetaan prodi)
    $routes->group('instrumen', ['filter' => 'permission:instrumen.manage'], function ($routes) {
        $routes->get('/',       'InstrumenController::index');
        $routes->post('assign', 'InstrumenController::assign');
    });

    // VMTS (Visi Misi Tujuan Strategi)
    $routes->group('vmts', ['filter' => 'permission:prodi.manage'], function ($routes) {
        $routes->get('/',      'VmtsController::index');
        $routes->post('save',  'VmtsController::save');
    });

    // Sarana & Prasarana
    $routes->group('sarpras', ['filter' => 'permission:prodi.manage'], function ($routes) {
        $routes->get('/',              'SarprasController::index');
        $routes->get('create',         'SarprasController::create');
        $routes->post('store',         'SarprasController::store');
        $routes->get('(:num)/edit',    'SarprasController::edit/$1');
        $routes->post('(:num)/update', 'SarprasController::update/$1');
        $routes->post('(:num)/delete', 'SarprasController::delete/$1');
    });

    // Keuangan
    $routes->group('keuangan', ['filter' => 'permission:prodi.manage'], function ($routes) {
        $routes->get('/',              'KeuanganController::index');
        $routes->get('create',         'KeuanganController::create');
        $routes->post('store',         'KeuanganController::store');
        $routes->get('(:num)/edit',    'KeuanganController::edit/$1');
        $routes->post('(:num)/update', 'KeuanganController::update/$1');
        $routes->post('(:num)/delete', 'KeuanganController::delete/$1');
    });

    // Tenaga Kependidikan
    $routes->group('tendik', ['filter' => 'permission:prodi.manage'], function ($routes) {
        $routes->get('/',              'TendikController::index');
        $routes->get('create',         'TendikController::create');
        $routes->post('store',         'TendikController::store');
        $routes->get('(:num)/edit',    'TendikController::edit/$1');
        $routes->post('(:num)/update', 'TendikController::update/$1');
        $routes->post('(:num)/delete', 'TendikController::delete/$1');
    });

    // Penyaji berkas bukti PDF (borang & tridharma)
    $routes->get('eviden/file/(:segment)', 'EvidenceController::lihat/$1');

    // Dokumen eviden (PDF) untuk dosen & tendik
    $routes->post('dokumen/(:segment)/(:num)/upload', 'DokumenController::upload/$1/$2');
    $routes->get('dokumen/(:num)/lihat',              'DokumenController::lihat/$1');
    $routes->post('dokumen/(:num)/delete',            'DokumenController::delete/$1');

    // Borang LKPS pelengkap (generik): tata-pamong, spmi, fleksibilitas, rekognisi, pengembangan-dtpr, tata-kelola
    $routes->group('borang', ['filter' => 'permission:prodi.manage'], function ($routes) {
        $routes->get('(:segment)',                'BorangController::index/$1');
        $routes->get('(:segment)/create',         'BorangController::create/$1');
        $routes->post('(:segment)/store',         'BorangController::store/$1');
        $routes->get('(:segment)/(:num)/edit',    'BorangController::edit/$1/$2');
        $routes->post('(:segment)/(:num)/update', 'BorangController::update/$1/$2');
        $routes->post('(:segment)/(:num)/delete', 'BorangController::delete/$1/$2');
    });

    // Admin
    $routes->group('admin', function ($routes) {
        $routes->get('users',              'Admin\UserManagement::index');
        $routes->get('users/create',       'Admin\UserManagement::create');
        $routes->post('users',             'Admin\UserManagement::store');
        $routes->get('users/(:num)/edit',  'Admin\UserManagement::edit/$1');
        $routes->post('users/(:num)/update','Admin\UserManagement::update/$1');
        $routes->post('users/(:num)/delete','Admin\UserManagement::delete/$1');
    });

    // Kurikulum OBE + CPL
    $routes->group('kurikulum', ['filter' => 'permission:kurikulum.manage'], function ($routes) {
        $routes->get('/',                                              'Kurikulum\KurikulumController::index');
        $routes->get('create',                                         'Kurikulum\KurikulumController::create');
        $routes->post('store',                                         'Kurikulum\KurikulumController::store');
        $routes->get('(:num)',                                         'Kurikulum\KurikulumController::show/$1');
        $routes->get('(:num)/edit',                                    'Kurikulum\KurikulumController::edit/$1');
        $routes->post('(:num)/update',                                 'Kurikulum\KurikulumController::update/$1');
        $routes->post('(:num)/delete',                                 'Kurikulum\KurikulumController::delete/$1');
        // CPL
        $routes->get('(:num)/cpl/import',                              'Kurikulum\KurikulumController::cplImportForm/$1');
        $routes->get('(:num)/cpl/import/template',                     'Kurikulum\KurikulumController::cplImportTemplate/$1');
        $routes->post('(:num)/cpl/import',                             'Kurikulum\KurikulumController::cplImportStore/$1');
        $routes->get('(:num)/cpl/create',                              'Kurikulum\KurikulumController::cplCreate/$1');
        $routes->post('(:num)/cpl/store',                              'Kurikulum\KurikulumController::cplStore/$1');
        $routes->get('(:num)/cpl/(:num)/edit',                         'Kurikulum\KurikulumController::cplEdit/$1/$2');
        $routes->post('(:num)/cpl/(:num)/update',                      'Kurikulum\KurikulumController::cplUpdate/$1/$2');
        $routes->post('(:num)/cpl/(:num)/delete',                      'Kurikulum\KurikulumController::cplDelete/$1/$2');
        // Mata Kuliah
        $routes->get('(:num)/mk/import',                               'Kurikulum\KurikulumController::mkImportForm/$1');
        $routes->get('(:num)/mk/import/template',                      'Kurikulum\KurikulumController::mkImportTemplate/$1');
        $routes->post('(:num)/mk/import',                              'Kurikulum\KurikulumController::mkImportStore/$1');
        $routes->get('(:num)/mk/create',                               'Kurikulum\KurikulumController::mkCreate/$1');
        $routes->post('(:num)/mk/store',                               'Kurikulum\KurikulumController::mkStore/$1');
        $routes->get('(:num)/mk/(:num)/edit',                          'Kurikulum\KurikulumController::mkEdit/$1/$2');
        $routes->post('(:num)/mk/(:num)/update',                       'Kurikulum\KurikulumController::mkUpdate/$1/$2');
        $routes->post('(:num)/mk/(:num)/delete',                       'Kurikulum\KurikulumController::mkDelete/$1/$2');
        // Peta CPL-MK
        $routes->post('(:num)/peta/save',                              'Kurikulum\KurikulumController::petaSave/$1');
        // Ketercapaian CPL
        $routes->get('(:num)/ketercapaian',                            'Kurikulum\KurikulumController::ketercapaianForm/$1');
        $routes->post('(:num)/ketercapaian/save',                      'Kurikulum\KurikulumController::ketercapaianSave/$1');
    });

    // Mahasiswa + Tracer Study
    $routes->group('mahasiswa', ['filter' => 'permission:mahasiswa.manage'], function ($routes) {
        $routes->get('/',                          'Mahasiswa\MahasiswaController::index');
        $routes->get('create',                     'Mahasiswa\MahasiswaController::create');
        $routes->post('store',                     'Mahasiswa\MahasiswaController::store');
        $routes->get('import',                     'Mahasiswa\MahasiswaController::importForm');
        $routes->get('import/template',            'Mahasiswa\MahasiswaController::importTemplate');
        $routes->post('import',                    'Mahasiswa\MahasiswaController::importStore');
        $routes->get('(:num)',                     'Mahasiswa\MahasiswaController::show/$1');
        $routes->get('(:num)/edit',               'Mahasiswa\MahasiswaController::edit/$1');
        $routes->post('(:num)/update',            'Mahasiswa\MahasiswaController::update/$1');
        $routes->post('(:num)/delete',            'Mahasiswa\MahasiswaController::delete/$1');
        $routes->get('tracer',                     'Mahasiswa\MahasiswaController::tracerIndex');
        $routes->get('tracer/laporan',             'Mahasiswa\MahasiswaController::tracerReport');
        $routes->post('tracer/kirim-semua',        'Mahasiswa\MahasiswaController::tracerKirimSemua');
        $routes->get('tracer/(:num)',              'Mahasiswa\MahasiswaController::tracerForm/$1');
        $routes->post('tracer/(:num)/store',       'Mahasiswa\MahasiswaController::tracerStore/$1');
        $routes->post('tracer/(:num)/kirim',       'Mahasiswa\MahasiswaController::tracerKirim/$1');
        // Survei Kepuasan Pengguna Lulusan (2.B.6)
        $routes->get('kepuasan',                   'Mahasiswa\MahasiswaController::kepuasanIndex');
        $routes->post('kepuasan/kirim-semua',      'Mahasiswa\MahasiswaController::kepuasanKirimSemua');
        $routes->get('kepuasan/(:num)',            'Mahasiswa\MahasiswaController::kepuasanForm/$1');
        $routes->post('kepuasan/(:num)/store',     'Mahasiswa\MahasiswaController::kepuasanStore/$1');
        $routes->post('kepuasan/(:num)/kirim',     'Mahasiswa\MahasiswaController::kepuasanKirim/$1');
    });

    // LKPS Generator
    $routes->group('lkps', ['filter' => 'permission:lkps.generate'], function ($routes) {
        $routes->get('/',                   'Lkps\LkpsController::index');
        $routes->post('generate-all',       'Lkps\LkpsController::generateAll');
        $routes->post('generate/(:any)',    'Lkps\LkpsController::generateSingle/$1');
        $routes->get('download/(:any)',     'Lkps\LkpsController::download/$1');
    });

    // Dashboard Kesiapan Akreditasi
    $routes->get('kesiapan', 'KesiapanController::index', ['filter' => 'permission:penilaian.input']);

    // Penilaian LAM
    $routes->group('penilaian', ['filter' => 'permission:penilaian.input'], function ($routes) {
        $routes->get('/',                    'Penilaian\PenilaianController::index');        $routes->get('kriteria/(:num)',      'Penilaian\PenilaianController::kriteria/$1');
        $routes->post('skor/(:num)/save',    'Penilaian\PenilaianController::saveSkor/$1');
        $routes->post('catatan-ai/(:num)',   'Penilaian\PenilaianController::catatanAi/$1');
        $routes->get('bukti/(:segment)',     'Penilaian\PenilaianController::bukti/$1');
        $routes->post('run-auto',            'Penilaian\PenilaianController::runAuto');
    });

    // Penelitian (Kriteria C3)
    $routes->group('penelitian', ['filter' => 'permission:tridharma.manage'], function ($routes) {
        $routes->get('/',              'Tridharma\PenelitianController::index');
        $routes->get('create',         'Tridharma\PenelitianController::create');
        $routes->post('store',         'Tridharma\PenelitianController::store');
        $routes->get('(:num)/edit',    'Tridharma\PenelitianController::edit/$1');
        $routes->post('(:num)/update', 'Tridharma\PenelitianController::update/$1');
        $routes->post('(:num)/delete', 'Tridharma\PenelitianController::delete/$1');
    });

    // PkM (Kriteria C4)
    $routes->group('pkm', ['filter' => 'permission:tridharma.manage'], function ($routes) {
        $routes->get('/',              'Tridharma\PkmController::index');
        $routes->get('create',         'Tridharma\PkmController::create');
        $routes->post('store',         'Tridharma\PkmController::store');
        $routes->get('(:num)/edit',    'Tridharma\PkmController::edit/$1');
        $routes->post('(:num)/update', 'Tridharma\PkmController::update/$1');
        $routes->post('(:num)/delete', 'Tridharma\PkmController::delete/$1');
    });

    // Publikasi & HKI (Tabel 3.C.2, 3.C.3, 4.C.2, 4.C.3)
    $routes->group('publikasi', ['filter' => 'permission:tridharma.manage'], function ($routes) {
        $routes->get('/',                   'Tridharma\PublikasiController::index');
        $routes->get('cari',                'Tridharma\PublikasiController::cariPublikasi');
        $routes->post('cari/search',        'Tridharma\PublikasiController::searchPublikasi');
        $routes->post('cari/import',        'Tridharma\PublikasiController::importPublikasi');
        $routes->post('sitasi/update',      'Tridharma\PublikasiController::updateSitasi');
        $routes->get('create',              'Tridharma\PublikasiController::createPublikasi');
        $routes->post('store',              'Tridharma\PublikasiController::storePublikasi');
        $routes->get('(:num)/edit',         'Tridharma\PublikasiController::editPublikasi/$1');
        $routes->post('(:num)/update',      'Tridharma\PublikasiController::updatePublikasi/$1');
        $routes->post('(:num)/delete',      'Tridharma\PublikasiController::deletePublikasi/$1');
        $routes->get('(:num)/pemilik',      'Tridharma\PublikasiController::pemilikPublikasi/$1');
        $routes->post('(:num)/pemilik',     'Tridharma\PublikasiController::simpanPemilik/$1');
        $routes->get('hki/create',          'Tridharma\PublikasiController::createHki');
        $routes->post('hki/store',          'Tridharma\PublikasiController::storeHki');
        $routes->get('hki/(:num)/edit',     'Tridharma\PublikasiController::editHki/$1');
        $routes->post('hki/(:num)/update',  'Tridharma\PublikasiController::updateHki/$1');
        $routes->post('hki/(:num)/delete',  'Tridharma\PublikasiController::deleteHki/$1');
    });

    // Kerjasama (Tabel 3.C.1 / 4.C.1)
    $routes->group('kerjasama', ['filter' => 'permission:kerjasama.manage'], function ($routes) {
        $routes->get('/',              'Tridharma\KerjasamaController::index');
        $routes->get('create',         'Tridharma\KerjasamaController::create');
        $routes->post('store',         'Tridharma\KerjasamaController::store');
        $routes->get('(:num)/edit',    'Tridharma\KerjasamaController::edit/$1');
        $routes->post('(:num)/update', 'Tridharma\KerjasamaController::update/$1');
        $routes->post('(:num)/delete', 'Tridharma\KerjasamaController::delete/$1');
    });

    // LED Generator
    $routes->group('led', ['filter' => 'permission:led.generate'], function ($routes) {
        $routes->get('/',                       'Led\LedController::index');
        $routes->post('generate',               'Led\LedController::generate');
        $routes->post('narasi/(:num)/save',     'Led\LedController::saveNarasi/$1');
        $routes->post('groq/(:num)',            'Led\LedController::groqGenerate/$1');
        $routes->get('download/(:any)',         'Led\LedController::download/$1');
    });

    // DTPR — Dosen
    $routes->group('dosen', ['filter' => 'permission:dosen.manage'], function ($routes) {
        $routes->get('/',                        'Dosen\DosenController::index');
        $routes->get('create',                   'Dosen\DosenController::create');
        $routes->post('store',                   'Dosen\DosenController::store');
        $routes->get('import',                   'Dosen\DosenController::importForm');
        $routes->get('import/template',          'Dosen\DosenController::importTemplate');
        $routes->post('import',                  'Dosen\DosenController::importStore');
        $routes->post('metrik/update',           'Dosen\DosenController::updateMetrik');
        $routes->post('(:num)/akun',             'Dosen\DosenController::buatAkun/$1');
        $routes->post('(:num)/akun/reset',       'Dosen\DosenController::resetAkun/$1');
        $routes->post('(:num)/akun/tautkan',     'Dosen\DosenController::tautkanAkun/$1');
        $routes->get('ewmp/import',              'Dosen\DosenController::ewmpImportForm');
        $routes->get('ewmp/import/template',     'Dosen\DosenController::ewmpImportTemplate');
        $routes->post('ewmp/import',             'Dosen\DosenController::ewmpImportStore');
        $routes->get('(:num)',                   'Dosen\DosenController::show/$1');
        $routes->get('(:num)/edit',              'Dosen\DosenController::edit/$1');
        $routes->post('(:num)/update',           'Dosen\DosenController::update/$1');
        $routes->post('(:num)/delete',           'Dosen\DosenController::delete/$1');
        $routes->get('ewmp',                     'Dosen\DosenController::ewmpIndex');
        $routes->get('ewmp/(:num)',              'Dosen\DosenController::ewmpForm/$1');
        $routes->post('ewmp/(:num)/store',       'Dosen\DosenController::ewmpStore/$1');
    });
});
