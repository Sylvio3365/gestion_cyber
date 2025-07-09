<?php

namespace app\controllers;

use Flight;
use app\models\ParametreModel;

class ParametreController
{
    private ParametreModel $model;
    private string $ssid;
    private string $securite;

    public function __construct()
    {
        $this->ssid = 'wifi_5g';      // Nom du réseau WiFi
        $this->securite = 'WPA';      // Sécurité : WPA, WEP ou nopass
        $this->model = new ParametreModel(Flight::db());
    }

    public function getMdp(): void
    {
        $mdp = $this->model->getMdp();

        $qrUrl = $this->generateWifiQrCode($mdp);

        Flight::render('index', [
            'mdp'   => $mdp,
            'qrUrl' => $qrUrl,
            'page'  => 'parametre/index'
        ]);
    }

    public function setMdp(): void
    {
        $data = Flight::request()->data->getData();

        if (empty($data['mdp'])) {
            Flight::render('index', [
                'error' => 'Le paramètre "mdp" est requis',
                'mdp'   => $this->model->getMdp(),
                'qrUrl' => null,
                'page'  => 'parametre/index'
            ]);
            return;
        }

        // Enregistrement du mot de passe
        $this->model->setMdp($data['mdp']);
        $mdp = $this->model->getMdp();

        $qrUrl = $this->generateWifiQrCode($mdp);

        Flight::render('index', [
            'message' => 'Mot de passe enregistré et QR Code généré',
            'mdp'     => $mdp,
            'qrUrl'   => $qrUrl,
            'page'    => 'parametre/index',
        ]);
    }

    private function generateWifiQrCode(string $mdp): string
    {
        $wifiString = "WIFI:T:{$this->securite};S:{$this->ssid};P:{$mdp};;";
        return 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($wifiString) . '&size=300x300&margin=1';
    }
}
