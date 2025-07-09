<?php

namespace app\controllers;

// Ajuster le chemin vers votre librairie QRcode
require_once __DIR__ . '/phpqrcode/qrlib.php';

use Flight;
use app\models\ParametreModel;
use QRcode;

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

        Flight::render('parametre/index', [
            'mdp'    => $mdp,
            'qrFile' => $qrFile,
        ]);
    }

    public function setMdp(): void
    {
        $data = Flight::request()->data->getData();

        if (empty($data['mdp'])) {
            Flight::render('parametre/index', [
                'error'  => 'Le paramètre "mdp" est requis',
                'mdp'    => $this->model->getMdp(),
                'qrFile' => null,
            ]);
            return;
        }

        // Enregistrement en base
        $this->model->setMdp($data['mdp']);
        $mdp = $this->model->getMdp();

        // Nom de fichier
        $filename = 'qr_' . md5($mdp) . '.png';

        // Chemin absolu vers public/assets/img
        $publicImgDir = realpath(__DIR__ . '/../../public/assets/img');
        if ($publicImgDir === false) {
            // fallback si realpath échoue
            $publicImgDir = dirname(__DIR__, 2) . '/public/assets/img';
        }

        // Création du dossier si nécessaire
        if (!is_dir($publicImgDir)) {
            mkdir($publicImgDir, 0755, true);
        }

        // Génération du QR-code
        $filePath = $publicImgDir . DIRECTORY_SEPARATOR . $filename;
        QRcode::png($mdp, $filePath, QR_ECLEVEL_H, 8, 2);

        // Chemin relatif pour l’affichage
        $qrFile = '/assets/img/' . $filename;

        Flight::render('parametre/index', [
            'message' => 'Mot de passe enregistré et QR-code généré',
            'mdp'     => $mdp,
            'qrFile'  => $qrFile,
        ]);
    }
}
