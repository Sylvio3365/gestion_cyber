<?php

namespace app\controllers;

use Flight;
use app\models\ConnexionModel;

class ConnexionController
{
    public function __construct() {}

    public function showGestionConnexionCLientSansPoste()
    {
        $clients = Flight::ConnexionModel()->getAllClient();
        $clientConnecter = Flight::ConnexionModel()->getClientConnectee();
        $data = [
            'clients' => $clients,
            'clientConnecter' => $clientConnecter
        ];
        Flight::render('connexion/sans_poste/index.php', $data);
    }

    public function addClientConecter()
    {
        $id_client = $_POST['id_client'];
        $duree = isset($_POST['duree']) && !empty($_POST['duree']) ? $_POST['duree'] : null;
        $add = Flight::ConnexionModel()->addClientConecter($id_client, null, $duree);
        if ($add) {
            Flight::redirect('/connexion/sansposte');
        } else {
            Flight::error('Erreur lors de l\'ajout du client.');
        }
    }
}
