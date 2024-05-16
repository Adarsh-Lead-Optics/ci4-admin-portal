<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

 $routes->setAutoRoute(true);

// $routes->get('/', 'Home::index');
// $routes->get('/', 'Home::mysession');


$routes->get('/','register::index');
// $routes->get(from:'register', to:'register::userregister');
// $routes->get(from:'user', to:'user::user-login');