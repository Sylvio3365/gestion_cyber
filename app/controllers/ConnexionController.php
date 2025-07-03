<?php

namespace app\controllers;

use Flight;
use app\models\ConnexionModel;

class ConnexionController
{
    public function __construct() {}

    public function showHisto()
    {
        $historiques = Flight::ConnexionModel()->getHisto();
        $page = 'connexion/historique/index';
        $data = [
            'page' => $page,
            'historiques' => $historiques,
        ];
        Flight::render('index', $data);
    }

    public function payer()
    {
        $id_histo = $_POST['id'];
        $payer =  Flight::ConnexionModel()->payer($id_histo);
        if ($payer) {
            Flight::redirect('/connexion/histo');
        } else {
            Flight::error('Erreur lors de l\'arret du payement.');
        }
    }

    public function arreterarreterConnexion()
    {
        $d_histo = $_POST['id'];
        $stop = Flight::ConnexionModel()->arreterConnexion($d_histo);
        if ($stop) {
            Flight::redirect('/connexion/sansposte');
        } else {
            Flight::error('Erreur lors de l\'arret de la connexion.');
        }
    }

    public function showGestionConnexionCLientSansPoste()
    {
        $clients = Flight::ConnexionModel()->getAllClient();
        $clientConnecter = Flight::ConnexionModel()->getClientConnectee();
        $page = 'connexion/sans_poste/index';
        $data = [
            'page' => $page,
            'clients' => $clients,
            'clientConnecter' => $clientConnecter
        ];
        Flight::render('index', $data);
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
