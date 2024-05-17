<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

 $routes->setAutoRoute(true);

// $routes->get('/', 'Home::index');
// $routes->get('/', 'Home::mysession');


$routes->get('/','register::index'); 
// $routes->get('/verify/(:any)', 'Register::verify');
$routes->get('/verify/(:any)', 'Register::activate/$1');
// $routes->get('/verify/(:any)', 'Home::authenticateUserPage/$1');


// $routes->get(from:'user', to:'user::user-login'); 