<?php

namespace app\controllers;

use Flight;
use app\models\ConnexionModel;

class ConnexionController
{
    public function __construct() {}

    public function showGestionConnexionCLientSansPoste()
    {
        Flight::render('poste/connexion/index.php');
    }
}
