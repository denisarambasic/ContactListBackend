<?php

use Typeqast\Helpers\Routing;

$routes = new Routing();

/*=== Here we add all our application routes ===*/
$routes->setRoute('GET', '/api/users', 'UserController', 'getAllUsers', []);
$routes->setRoute('POST', '/api/users/create', 'UserController', 'createUser', []);
$routes->setRoute('OPTIONS', '/api/users/create', 'UserController', 'createUser', []);

//Get user by id
$routes->setRoute('GET', '/api/users', 'UserController', 'getUserById', ['id']);
//Update user by id
$routes->setRoute('PATCH', '/api/users', 'UserController', 'updateUserById', ['id']);
//to handle preflight request
$routes->setRoute('OPTIONS', '/api/users', 'UserController', 'updateUserById', ['id']);


/*=== check if clients request match any route ===*/
$routes->callController();