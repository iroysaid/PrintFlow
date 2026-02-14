<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/login', 'Auth::login');
$routes->post('/auth/process', 'Auth::process');
$routes->get('/logout', 'Auth::logout');

// Protected Routes
$routes->group('', ['filter' => 'auth'], function($routes) {
    
    // Cashier Routes
    $routes->get('pos', 'Pos::index');
    $routes->get('pos/history', 'Pos::history');
    $routes->get('pos/printReport', 'Pos::printReport');
    $routes->get('pos/exportExcel', 'Pos::exportExcel');
    $routes->get('pos/searchProduct', 'Pos::searchProduct');
    $routes->get('pos/checkCustomer', 'Pos::checkCustomer');
    $routes->post('pos/saveTransaction', 'Pos::saveTransaction');
    $routes->get('pos/printInvoice/(:num)', 'Pos::printInvoice/$1');
    $routes->get('pos/searchCustomer', 'Pos::searchCustomer');
    $routes->get('pos/getCustomerHistory', 'Pos::getCustomerHistory');
    $routes->get('pos/deleteTransaction/(:num)', 'Pos::deleteTransaction/$1');
    $routes->get('pos/editTransaction/(:num)', 'Pos::editTransaction/$1');
    $routes->post('pos/updateTransaction/(:num)', 'Pos::updateTransaction/$1');
    $routes->get('report/weekend', 'Report::weekend');

    // Admin Routes
    $routes->group('admin', function($routes) {
        $routes->get('dashboard', 'Admin\Dashboard::index');
        $routes->post('dashboard/updateStatus/(:num)', 'Admin\Dashboard::updateStatus/$1');

        $routes->get('promos', 'Admin\Promo::index');
        $routes->post('promo/update/(:num)', 'Admin\Promo::update/$1');

        $routes->get('products', 'Admin\Product::index');
        $routes->post('products/create', 'Admin\Product::create');
        $routes->post('products/update/(:num)', 'Admin\Product::update/$1');
        $routes->get('products/delete/(:num)', 'Admin\Product::delete/$1');
        
        $routes->get('transactions', 'Admin\Transaction::index');
        
        $routes->get('users', 'Admin\User::index');
        $routes->post('users/create', 'Admin\User::create');
    });

    // Production Routes
    $routes->group('production', function($routes) {
        $routes->get('dashboard', 'Production\Dashboard::index');
        $routes->post('updateStatus/(:num)', 'Production\Dashboard::updateStatus/$1');
    });

});

// Debug Route (Outside Auth for easy access test)
$routes->get('debug', 'Debug::index');
