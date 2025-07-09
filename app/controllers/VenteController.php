<?php

namespace app\controllers;

use Flight;
use app\models\VenteModel;

class VenteController
{

    public function __construct() {}

    public function afficherBenefice()
{
    $request = Flight::request()->query; // ou ->query si tu utilises method="get"

    $filtreType = $request['filtre_type'] ?? null;
    $model = new VenteModel(Flight::db());

    $resultats = ['total' => 0, 'par_branche' => []]; // Valeur par défaut

    if ($filtreType === 'jour') {
        $date = $request['date'] ?? null;
        $resultats = $model->calculerBenefice($date, null, null);
    } elseif ($filtreType === 'mois') {
        $mois = $request['mois'] ?? null;
        $annee = $request['annee'] ?? null;
        $resultats = $model->calculerBenefice(null, $mois, $annee);
    } elseif ($filtreType === 'annee') {
        $annee = $request['annee'] ?? null;
        $resultats = $model->calculerBenefice(null, null, $annee);
    }

    // Formatage des résultats pour l'affichage
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

    // Passage à la vue
    Flight::render('index', ['benefice' => $beneficeFormate, 'page' => 'Statistique/benefice']);
}


    public function showBenefice()
    {
        Flight::render('index', ['page' => 'Statistique/benefice']);
    }
}
