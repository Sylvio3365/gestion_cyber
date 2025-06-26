<?php

use app\controllers\UserController;
use flight\Engine;
use flight\net\Router;
//use Flight;

/** 
 * @var Router $router 
 * @var Engine $app
 */

$UserController = new UserController();


$router->get('/', [$UserController, 'showLoginForm']);
$router->post('/login', [$UserController, 'login']);
$router->get('/logout', [$UserController, 'logout']);



