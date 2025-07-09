<?php

namespace app\controllers;

use Flight;
use app\models\ConnexionModel;
use app\models\ParametreModel;

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
        if (!isset($_SESSION['user'])) {
            Flight::redirect('/');
        }
        $id_histo = $_POST['id'];
        $payer =  Flight::ConnexionModel()->payer(
            $_SESSION['user']['id_user'],
            $id_histo
        );
        if ($payer) {
            Flight::redirect('/connexion/apayer');
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

    private function generateWifiQrCode(string $mdp): string
    {
        $ssid = 'wifi_5g';      // Nom du réseau WiFi
        $securite = 'WPA'; 
        $wifiString = "WIFI:T:{$securite};S:{$ssid};P:{$mdp};;";
        return 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($wifiString) . '&size=300x300&margin=1';
    }

    public function showGestionConnexionCLientSansPoste()
    {
        $clients = Flight::ConnexionModel()->getAllClient();
        $clientConnecter = Flight::ConnexionModel()->getClientConnectee();
        $page = 'connexion/sans_poste/index';
        $pm = new ParametreModel(Flight::db());
        $mdp = $pm->getMdp();
        $qrUrl = $this->generateWifiQrCode($mdp);
        $data = [
            'page' => $page,
            'clients' => $clients,
            'clientConnecter' => $clientConnecter,
            'qrUrl' => $qrUrl,
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
