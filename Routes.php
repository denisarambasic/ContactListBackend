<?php

use Typeqast\Helpers\Routing;

$routes = new Routing();

/*=== Here we add all our application routes ===*/
$routes->setRoute('GET', '/api/users', 'UserController', 'getAllUsers', []);
$routes->setRoute('POST', '/api/users/create', 'UserController', 'createUser', []);
$routes->setRoute('OPTIONS', '/api/users/create', 'UserController', 'createUser', []);


/*=== check if clients request match any route ===*/
$routes->callController();