<?php
namespace app\controllers;

use Flight;

class StatController {
    private $model;

    public function __construct() {
        $this->model = Flight::statistiqueModel();
    }

public function topProduitParBranche()
{
    $branche_id  = Flight::request()->query->branche ?? null;
    $date_debut  = Flight::request()->query->date_debut ?? null;
    $date_fin    = Flight::request()->query->date_fin ?? null;

    // Récupération branches (toujours)
    $branches = $this->model->getAllBranches();

    // Résultats par défaut vides
    $resultats = [];

    // Validation et récupération données
    if (
        $branche_id &&
        $date_debut && $date_fin &&
        strtotime($date_debut) !== false &&
        strtotime($date_fin) !== false &&
        strtotime($date_debut) <= strtotime($date_fin)
    ) {
        $resultats = $this->model->getHistogrammeTousProduitsParBranche($branche_id, $date_debut, $date_fin);
    }

    // Passage des variables vers la vue (toujours défini)
    Flight::render('Statistique/Stat', [
        'branches'   => $branches,
        'resultats'  => $resultats,
        'branche'    => $branche_id,
        'date_debut' => $date_debut,
        'date_fin'   => $date_fin,
    ]);
}





}
