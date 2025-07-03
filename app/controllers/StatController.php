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
    $branche_id = Flight::request()->query->branche ?? null;
    $date_debut = Flight::request()->query->date_debut ?? null;
    $date_fin = Flight::request()->query->date_fin ?? null;

    $branches = $this->model->getAllBranches();
    $resultats = [];

    // Valide que la date est correcte et dans le bon ordre
    if ($branche_id && $date_debut && $date_fin && strtotime($date_debut) !== false && strtotime($date_fin) !== false && strtotime($date_debut) <= strtotime($date_fin)) {
        $resultats = $this->model->getHistogrammeTousProduitsParBranche($branche_id, $date_debut, $date_fin);
    }

    Flight::render('Stat', [
        'branches' => $branches,
        'resultats' => $resultats,
        'branche' => $branche_id,
        'date_debut' => $date_debut,
        'date_fin' => $date_fin
    ]);
}



}
