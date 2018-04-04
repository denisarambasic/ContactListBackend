<?php

use Typeqast\Helpers\Routing;

$routes = new Routing();

/*=== Here we add all our application routes ===*/
$routes->setRoute('GET', '/api/users', 'UserController', 'getAllUsers', []);


/*=== check if clients request match any route ===*/
$routes->callController();