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

$router->get('/', [$UserController, 'home']);
