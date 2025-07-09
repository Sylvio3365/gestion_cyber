<?php

namespace app\controllers;

use Flight;
use app\models\ParametreModel;

class ParametreController
{
    private ParametreModel $model;

    public function __construct()
    {
        $this->model = new ParametreModel(Flight::db());
    }

    public function getMdp(): void
    {
        $mdp    = $this->model->getMdp();
        $qrFile = $mdp ? '/assets/img/qr_' . md5($mdp) . '.png' : null;
        // 2. Génère l’URL du QR Code via goqr.me
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($mdp) . '&size=300x300&margin=1';

        Flight::render('index', [
            'mdp'    => $mdp,
            'qrUrl'   => $qrUrl,
            'page' => 'parametre/index'
        ]);
    }

    public function setMdp(): void
    {
        $data = Flight::request()->data->getData();

        if (empty($data['mdp'])) {
            Flight::render('index', [
                'error'  => 'Le paramètre "mdp" est requis',
                'mdp'    => $this->model->getMdp(),
                'qrUrl'  => null,
                'page' => 'parametre/index'
            ]);
            return;
        }

        // 1. Enregistre en base
        $this->model->setMdp($data['mdp']);
        $mdp = $this->model->getMdp();

        // 2. Génère l’URL du QR Code via goqr.me
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($mdp) . '&size=300x300&margin=1';

        // 3. Rend la vue
        Flight::render('index', [
            'message' => 'Mot de passe enregistré et QR-Code généré',
            'mdp'     => $mdp,
            'qrUrl'   => $qrUrl,
            'page' => 'parametre/index',
        ]);
    }
}
