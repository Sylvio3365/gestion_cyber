<?php

namespace app\controllers;

use Flight;

class UserController
{

    public function __construct() {}

    public function home()
    {
        $data = ['message' => "Hello world"];
        Flight::render('index', $data);
    }
}
