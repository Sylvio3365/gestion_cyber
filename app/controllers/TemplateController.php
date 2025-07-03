<?php

namespace app\controllers;

use Flight;

class TemplateController
{

    public function __construct() {}

    public function show()
    {
        Flight::render('index.php');
    }
}
