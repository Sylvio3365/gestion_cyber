<?php

namespace app\controllers;

use Flight;
use app\models\PosteModel;

class PosteController
{
    public function __construct() {}

    public function accueil()
    {
        // Récupérer les messages depuis l'URL
        $message = Flight::request()->query['message'] ?? null;
        $messageType = Flight::request()->query['type'] ?? null;

        $postes = Flight::PosteModel()->getAllPosteAvecEtatActuel();
        $nouveauJour = Flight::PosteModel()->estNouveauJour();
        $clients = Flight::ConnexionModel()->getAllClient();

        $data = [
            'estNouveauJour' => $nouveauJour,
            'postes' => $postes,
            'clients' => $clients,
            'message' => $message,
            'messageType' => $messageType
        ];

        Flight::render('connexion/avec_poste/accueil.php', $data);
    }

    private function redirectWithMessage($url, $message, $type = 'success')
    {
        $query = http_build_query(['message' => $message, 'type' => $type]);
        Flight::redirect($url . (strpos($url, '?') === false ? '?' : '&') . $query);
    }

    public function demarrerSession()
    {
        try {
            $demarrer = Flight::PosteModel()->demarrerSession();
            if ($demarrer) {
                $this->redirectWithMessage('/poste/accueil', 'Session démarrée avec succès pour tous les postes');
            }
        } catch (\Exception $e) {
            $this->redirectWithMessage('/poste/accueil', 'Erreur: ' . $e->getMessage(), 'error');
        }
    }

    public function demarrerSessionPoste()
    {
        $request = Flight::request();

        // Validation des données
        if (empty($request->data->poste_id) || empty($request->data->client_id)) {
            $this->redirectWithMessage('/poste/accueil', 'Tous les champs sont obligatoires', 'error');
            return;
        }

        try {
            $duree_minutes = !empty($request->data->duree_minutes) ? (int)$request->data->duree_minutes : null;

            $success = Flight::PosteModel()->demarrerSessionPoste(
                $request->data->poste_id,
                $request->data->client_id,
                $duree_minutes
            );

            if ($success) {
                $this->redirectWithMessage('/poste/accueil', 'Session démarrée avec succès');
            }
        } catch (\Exception $e) {
            $this->redirectWithMessage('/poste/accueil', 'Erreur: ' . $e->getMessage(), 'error');
        }
    }

    public function arreterSessionPoste()
    {
        $request = Flight::request();

        if (empty($request->data->poste_id)) {
            $this->redirectWithMessage('/poste/accueil', 'ID du poste manquant', 'error');
            return;
        }

        try {
            $success = Flight::PosteModel()->arreterSessionPoste($request->data->poste_id);

            if ($success) {
                $this->redirectWithMessage('/poste/accueil', 'Session arrêtée avec succès');
            }
        } catch (\Exception $e) {
            $this->redirectWithMessage('/poste/accueil', 'Erreur: ' . $e->getMessage(), 'error');
        }
    }

    public function mettreEnMaintenance()
    {
        $request = Flight::request();

        if (empty($request->data->poste_id)) {
            $this->redirectWithMessage('/poste/accueil', 'ID du poste manquant', 'error');
            return;
        }

        try {
            $raison = $request->data->raison ?? null;
            $success = Flight::PosteModel()->mettreEnMaintenance(
                $request->data->poste_id,
                $raison
            );

            if ($success) {
                $this->redirectWithMessage('/poste/accueil', 'Poste mis en maintenance avec succès');
            }
        } catch (\Exception $e) {
            $this->redirectWithMessage('/poste/accueil', 'Erreur: ' . $e->getMessage(), 'error');
        }
    }

    public function rendreDisponible()
    {
        $request = Flight::request();

        if (empty($request->data->poste_id)) {
            $this->redirectWithMessage('/poste/accueil', 'ID du poste manquant', 'error');
            return;
        }

        try {
            $success = Flight::PosteModel()->rendreDisponible($request->data->poste_id);

            if ($success) {
                $this->redirectWithMessage('/poste/accueil', 'Poste marqué comme disponible avec succès');
            }
        } catch (\Exception $e) {
            $this->redirectWithMessage('/poste/accueil', 'Erreur: ' . $e->getMessage(), 'error');
        }
    }
}
