<?php

require_once 'models/ClientModel.php';

class ClientController {


    public function mouvementsParBranche($id) {
        $clientModel = Flight::clientModel();

        $produits = $clientModel->getAchatsProduits($id);
        $services = $clientModel->getAchatsServices($id);
        $connexions = $clientModel->getConnexions($id);

        Flight::json([
            'status' => 'success',
            'data' => [
                'produits' => $produits,
                'services' => $services,
                'connexions' => $connexions
            ]
        ]);
    }
}
