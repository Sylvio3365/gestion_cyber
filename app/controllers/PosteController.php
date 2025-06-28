<?php

namespace app\controllers;

use Flight;
use app\models\PosteModel;

class PosteController
{
    public function __construct() {}

    public function accueil()
    {
        $postes = Flight::PosteModel()->getAllPosteAvecEtatActuel();
        $nouveauJour = Flight::PosteModel()->estNouveauJour();
        $data = [
            'postes' => $postes,
            'estNouveauJour' => $nouveauJour
        ];
        Flight::render('poste/accueil.php', $data);
    }
}
