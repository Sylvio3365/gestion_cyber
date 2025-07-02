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

        // Récupération des résultats (total + par branche)
        $resultats = $model->calculerBenefice($date, $mois, $annee);

        // Formatage des données pour la vue
        $beneficeFormate = [
            'total' => number_format($resultats['total'], 2, '.', ' ') . ' Ar',
            'branches' => []
        ];

        foreach ($resultats['par_branche'] as $branche => $details) {
            $beneficeFormate['branches'][$branche] = [
                'vente' => number_format($details['vente'], 2, '.', ' ') . ' Ar',
                'achat' => number_format($details['achat'], 2, '.', ' ') . ' Ar',
                'benefice' => number_format($details['benefice'], 2, '.', ' ') . ' Ar'
            ];
        }

        // Passage des données à la vue
        Flight::view()->set('benefice', $beneficeFormate);

        Flight::render('benefice');
    }

    public function showBenefice()
    {
        Flight::render('benefice');
    }
}