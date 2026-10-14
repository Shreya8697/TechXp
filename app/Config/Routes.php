<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('api/register', 'Api\Register::create');
$routes->get('register', static function () {
    return view('register');
});

$routes->get('login', static function () {
    return view('login');
});

$routes->post('api/login', 'Api\Login::create');

$routes->get('event', 'Event::index');

$routes->get('logout', 'Api\Login::logout');
