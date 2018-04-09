<?php

use Typeqast\Helpers\Routing;

$routes = new Routing();

/*=== Here we add all our application routes ===*/
$routes->setRoute('GET', '/api/users', 'UserController', 'getAllUsers', []);
/*== Get all favorite users ==*/
$routes->setRoute('GET', '/api/users/favorites', 'UserController', 'getAllFavoritesUsers', []);
$routes->setRoute('POST', '/api/users/create', 'UserController', 'createUser', []);
$routes->setRoute('OPTIONS', '/api/users/create', 'UserController', 'createUser', []);

//Get user by id
$routes->setRoute('GET', '/api/users', 'UserController', 'getUserById', ['id']);
//Update user (favorite field) by id
$routes->setRoute('PATCH', '/api/users', 'UserController', 'updateUserById', ['id']);

//Update all fielda (try with put but cannot take the data from the payload)
$routes->setRoute('POST', '/api/users', 'UserController', 'updateUserByIdAll', ['id']);
//delete user
$routes->setRoute('DELETE', '/api/users', 'UserController', 'deleteUser', ['id']);
//to handle preflight request
$routes->setRoute('OPTIONS', '/api/users', 'UserController', 'updateUserById', ['id']);

/*=== check if clients request match any route ===*/
$routes->callController();