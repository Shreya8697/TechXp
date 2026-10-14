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

$routes->get('api/attendees', 'Api\Attendees::index');
$routes->get('api/attendees/(:num)', 'Api\Attendees::show/$1');
$routes->put('api/attendees/(:num)', 'Api\Attendees::update/$1');
$routes->delete('api/attendees/(:num)', 'Api\Attendees::delete/$1');
$routes->get('admin', 'Admin::index');
$routes->get('admin/attendees/(:num)', 'Admin::attendee/$1');
$routes->get('api/external-activity', 'Api\ExternalApi::activity');
