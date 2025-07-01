<?php

namespace app\controllers;

use Flight;
use app\models\VenteModel;

class VenteController {

    public function __construct() {}

    public function afficherBenefice() {
        $request = Flight::request()->data;

        $date = $request['date'] ?? null;
        $mois = $request['mois'] ?? null;
        $annee = $request['annee'] ?? null;

        $model = new VenteModel(Flight::db());
        $benefice = $model->calculerBenefice($date, $mois, $annee);
        $_SESSION['benefice_result'] = round($benefice, 2) . ' Ar';

        Flight::render('benefice');
    }

    public function showBenefice()
    {
        Flight::render('benefice', null);
    }
}
