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
});
