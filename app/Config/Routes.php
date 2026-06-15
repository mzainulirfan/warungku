<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::processLogin');
$routes->get('/logout', 'AuthController::logout');

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/dashboard', 'DashboardController::index');
    $routes->get('/pos', 'TransactionController::pos');
    $routes->get('/pos/barcode', 'TransactionController::barcode');
    $routes->post('/pos/store', 'TransactionController::store');
    $routes->get('/transaction', 'TransactionController::history');
    $routes->get('/transaction/detail/(:num)', 'TransactionController::detail/$1');
    $routes->get('/report', 'ReportController::index');
    $routes->get('/report/export', 'ReportController::export');

    $routes->group('', ['filter' => 'role:admin'], static function ($routes) {
        $routes->get('/product', 'ProductController::index');
        $routes->get('/product/template', 'ProductController::template');
        $routes->get('/product/export', 'ProductController::export');
        $routes->get('/product/create', 'ProductController::create');
        $routes->get('/product/barcode-preview', 'ProductController::barcodePreview');
        $routes->get('/product/detail/(:num)', 'ProductController::detail/$1');
        $routes->post('/product/store', 'ProductController::store');
        $routes->post('/product/import', 'ProductController::import');
        $routes->get('/product/edit/(:num)', 'ProductController::edit/$1');
        $routes->post('/product/update/(:num)', 'ProductController::update/$1');
        $routes->post('/product/delete/(:num)', 'ProductController::delete/$1');
        $routes->post('/product/toggle/(:num)', 'ProductController::toggle/$1');

        $routes->get('/category', 'CategoryController::index');
        $routes->post('/category/store', 'CategoryController::store');
        $routes->post('/category/update/(:num)', 'CategoryController::update/$1');
        $routes->post('/category/delete/(:num)', 'CategoryController::delete/$1');

        $routes->get('/user', 'UserController::index');
        $routes->get('/user/create', 'UserController::create');
        $routes->post('/user/store', 'UserController::store');
        $routes->get('/user/edit/(:num)', 'UserController::edit/$1');
        $routes->post('/user/update/(:num)', 'UserController::update/$1');
        $routes->post('/user/toggle/(:num)', 'UserController::toggle/$1');

        $routes->get('/setting', 'SettingController::index');
        $routes->post('/setting/update', 'SettingController::update');
    });
});
