<?php

namespace app\controllers;

use Flight;

class HistoConnexController
{
    public function showHistorique()
    {
        $period = Flight::request()->query['period'] ?? null;
        $date = Flight::request()->query['date'] ?? date('Y-m-d');

        // Validation
        if ($period && !in_array($period, ['jour', 'mois', 'annee'])) {
            Flight::json(['error' => 'Période invalide'], 400);
            return;
        }

        try {
            $historique = Flight::historiqueModel()->getHistorique($period, $date);
            $stats = Flight::historiqueModel()->getStats($period, $date);

            $page = 'connexion/historique/Histo_Connexion';
            Flight::render('index', [
                'page' => $page,
                'historique' => $historique,
                'stats' => $stats,
                'period' => $period,
                'date' => $date,
                'periodLabels' => [
                    'jour' => 'Jour',
                    'mois' => 'Mois',
                    'annee' => 'Année'
                ]
            ]);
        } catch (\Exception $e) {
            Flight::json([
                'error' => 'Erreur lors de la récupération de l\'historique',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function startConnexion()
    {
        $data = Flight::request()->data;
        $id = uniqid('conn_', true);

        if (Flight::historiqueModel()->addConnexion(
            $id,
            $data['id_client'],
            $data['id_poste'] ?? null
        )) {
            Flight::json(['success' => true, 'id_connexion' => $id]);
        } else {
            Flight::json(['error' => 'Erreur lors de l\'enregistrement'], 500);
        }
    }

    public function endConnexion($id)
    {
        if (Flight::historiqueModel()->updateFinConnexion($id)) {
            Flight::json(['success' => true]);
        } else {
            Flight::json(['error' => 'Erreur lors de la mise à jour'], 500);
        }
    }
}
