<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public
$routes->get('login', 'Auth\Login::index');
$routes->post('login', 'Auth\Login::attempt');
$routes->get('logout', 'Auth\Login::logout');

// Routes yang butuh autentikasi (filter 'session' & 'prodiScope' diaktifkan di Config/Filters.php)
$routes->group('', ['filter' => ['session', 'prodiScope']], function ($routes) {
    $routes->get('/', 'Dashboard::index');

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
        $routes->get('(:num)/cpl/create',                              'Kurikulum\KurikulumController::cplCreate/$1');
        $routes->post('(:num)/cpl/store',                              'Kurikulum\KurikulumController::cplStore/$1');
        $routes->get('(:num)/cpl/(:num)/edit',                         'Kurikulum\KurikulumController::cplEdit/$1/$2');
        $routes->post('(:num)/cpl/(:num)/update',                      'Kurikulum\KurikulumController::cplUpdate/$1/$2');
        $routes->post('(:num)/cpl/(:num)/delete',                      'Kurikulum\KurikulumController::cplDelete/$1/$2');
        // Mata Kuliah
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
        $routes->get('(:num)',                     'Mahasiswa\MahasiswaController::show/$1');
        $routes->get('(:num)/edit',               'Mahasiswa\MahasiswaController::edit/$1');
        $routes->post('(:num)/update',            'Mahasiswa\MahasiswaController::update/$1');
        $routes->post('(:num)/delete',            'Mahasiswa\MahasiswaController::delete/$1');
        $routes->get('tracer',                     'Mahasiswa\MahasiswaController::tracerIndex');
        $routes->get('tracer/(:num)',              'Mahasiswa\MahasiswaController::tracerForm/$1');
        $routes->post('tracer/(:num)/store',       'Mahasiswa\MahasiswaController::tracerStore/$1');
    });

    // DTPR — Dosen
    $routes->group('dosen', ['filter' => 'permission:dosen.manage'], function ($routes) {
        $routes->get('/',                        'Dosen\DosenController::index');
        $routes->get('create',                   'Dosen\DosenController::create');
        $routes->post('store',                   'Dosen\DosenController::store');
        $routes->get('(:num)',                   'Dosen\DosenController::show/$1');
        $routes->get('(:num)/edit',              'Dosen\DosenController::edit/$1');
        $routes->post('(:num)/update',           'Dosen\DosenController::update/$1');
        $routes->post('(:num)/delete',           'Dosen\DosenController::delete/$1');
        $routes->get('ewmp',                     'Dosen\DosenController::ewmpIndex');
        $routes->get('ewmp/(:num)',              'Dosen\DosenController::ewmpForm/$1');
        $routes->post('ewmp/(:num)/store',       'Dosen\DosenController::ewmpStore/$1');
    });
});
