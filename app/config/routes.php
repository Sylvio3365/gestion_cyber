<?php

use app\controllers\UserController;
use app\controllers\VenteController;

use flight\Engine;
use flight\net\Router;
//use Flight;

/** 
 * @var Router $router 
 * @var Engine $app
 */

$UserController = new UserController();
$VenteController = new VenteController();



$router->get('/', [$UserController, 'showLoginForm']);
$router->post('/login', [$UserController, 'login']);
$router->get('/logout', [$UserController, 'logout']);
$router->get('/benef_form', [$VenteController, 'showBenefice']);
$router->post('/benefice', [$VenteController, 'afficherBenefice']);




