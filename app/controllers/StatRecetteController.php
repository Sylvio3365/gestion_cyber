<?php

namespace app\controllers;

use Flight;
use app\models\StatRecetteModel;

class StatRecetteController
{

    public function showStats()
    {
        $period = Flight::request()->query['period'] ?? 'jour';
        $date = Flight::request()->query['date'] ?? date('Y-m-d');

        if (!in_array($period, ['jour', 'semaine', 'mois', 'annee'])) {
            Flight::json(['error' => 'Période invalide'], 400);
            return;
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            Flight::json(['error' => 'Format de date invalide'], 400);
            return;
        }

        try {
            $stats = Flight::statModel()->getStatsParBranche($period, $date);
            $aggregated = Flight::statModel()->getStatsParBrancheAggregated($period, $date);

            $page = 'Statistique/StatRecette';
            Flight::render('index', [
                'page' => $page,
                'period' => $period,
                'date' => $date,
                'stats' => $stats,
                'aggregated' => $aggregated,
                'periodLabels' => [
                    'jour' => 'Jour',
                    'semaine' => 'Semaine',
                    'mois' => 'Mois',
                    'annee' => 'Année'
                ]
            ]);
        } catch (\Exception $e) {
            Flight::json([
                'error' => 'Erreur lors de la récupération des statistiques',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function apiStats()
    {
        $period = Flight::request()->query['period'] ?? 'jour';
        $date = Flight::request()->query['date'] ?? date('Y-m-d');

        if (!in_array($period, ['jour', 'semaine', 'mois', 'annee'])) {
            Flight::json(['error' => 'Période invalide'], 400);
            return;
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            Flight::json(['error' => 'Format de date invalide'], 400);
            return;
        }

        try {
            $stats = Flight::statModel()->getStatsParBranche($period, $date);
            $aggregated = Flight::statModel()->getStatsParBrancheAggregated($period, $date);

            Flight::json([
                'success' => true,
                'period' => $period,
                'date' => $date,
                'data' => $stats,
                'aggregated' => $aggregated
            ]);
        } catch (\Exception $e) {
            Flight::json([
                'error' => 'Erreur lors de la récupération des statistiques',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function redirectToHome()
    {
        // Utilisez une URL relative au routeur
        Flight::redirect('statistique');
    }
}
