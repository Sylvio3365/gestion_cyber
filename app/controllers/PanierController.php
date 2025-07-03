<?php

namespace app\controllers;

use Flight;
use app\models\PanierModel;

class PanierController
{

    public function __construct() {}

    public function afficherPanier()
    {
        if (!isset($_SESSION['user'])) {
            Flight::redirect('/');
            return;
        }
        $panierModel = new PanierModel(Flight::db());
        $id_vente_draft = $_SESSION['panier_id'] ?? null;

        // Initialise le panier avec une structure vide par défaut
        $panier = [
            'produits' => [],
            'services' => []
        ];
        $total = 0;

        if ($id_vente_draft) {
            $panier = $panierModel->getPanier($id_vente_draft);
            $total = $panierModel->calculerTotal($id_vente_draft);
        }

        $produits = $panierModel->getProduits();
        $services = $panierModel->getServices();
        $clients = $panierModel->rechercherClients();
        $types_paiement = $panierModel->getTypesPaiement();

        $page = 'client/panier';
        Flight::render('index', [
            'panier' => $panier,
            'page' => $page,
            'total' => $total,
            'produits' => $produits,
            'services' => $services,
            'clients' => $clients,
            'types_paiement' => $types_paiement,
            'id_vente_draft' => $id_vente_draft
        ]);
    }


    public function creerPanier()
    {
        if (!isset($_SESSION['user'])) {
            Flight::json(['error' => 'Non autorisé'], 401);
            return;
        }
        $panierModel = new PanierModel(Flight::db());
        $id_vente_draft = $panierModel->creerPanier($_SESSION['user']['id_user'], null); // id_client = null
        $_SESSION['panier_id'] = $id_vente_draft;
        Flight::json(['success' => true, 'panier_id' => $id_vente_draft]);
    }


    public function ajouterProduit()
    {
        $request = Flight::request()->data;

        if (!isset($_SESSION['panier_id'])) {
            $this->creerPanier();
        }

        $panierModel = new PanierModel(Flight::db());
        $success = $panierModel->ajouterProduitPanier(
            $_SESSION['panier_id'],
            $request['id_produit'],
            $request['quantite'],
            $request['prix_unitaire']
        );

        if ($success) {
            Flight::redirect('/panier');
        } else {
            Flight::json(['error' => 'Erreur lors de l\'ajout'], 500);
        }
    }


    public function ajouterService()
    {
        $request = Flight::request()->data;

        if (!isset($_SESSION['panier_id'])) {
            $this->creerPanier();
        }

        $panierModel = new PanierModel(Flight::db());
        $success = $panierModel->ajouterServicePanier(
            $_SESSION['panier_id'],
            $request['id_service'],
            $request['quantite'],
            $request['prix_unitaire']
        );

        if ($success) {
            Flight::redirect('/panier');
        } else {
            Flight::json(['error' => 'Erreur lors de l\'ajout'], 500);
        }
    }

    public function modifierQuantiteProduit()
    {
        $request = Flight::request()->data;
        $id_vente_draft_produit = $request['id_vente_draft_produit'];
        $nouvelle_quantite = intval($request['nouvelle_quantite']);

        // Permettre les quantités <= 0 pour suppression
        $panierModel = new PanierModel(Flight::db());
        $success = $panierModel->modifierQuantiteProduit($id_vente_draft_produit, $nouvelle_quantite);

        if ($success) {
            // Récupérer le panier mis à jour
            $id_vente_draft = $_SESSION['panier_id'] ?? null;
            $panier = $panierModel->getPanier($id_vente_draft);
            $total = $panierModel->calculerTotal($id_vente_draft);

            $response = [
                'success' => true,
                'total' => number_format($total, 0, ',', ' ') . ' Ar',
            ];

            // Si l'article n'a pas été supprimé, calculer le prix de la ligne
            if ($nouvelle_quantite > 0) {
                foreach ($panier['produits'] as $item) {
                    if ($item['id_vente_draft_produit'] == $id_vente_draft_produit) {
                        $response['item_price'] = number_format($item['prix_unitaire'] * $item['quantite'], 0, ',', ' ') . ' Ar';
                        break;
                    }
                }
            }

            Flight::json($response);
        } else {
            Flight::json(['error' => 'Erreur lors de la modification de la quantité'], 500);
        }
    }

    public function modifierQuantiteService()
    {
        $request = Flight::request()->data;
        $id_vente_draft_service = $request['id_vente_draft_service'];
        $nouvelle_quantite = intval($request['nouvelle_quantite']);

        // Permettre les quantités <= 0 pour suppression
        $panierModel = new PanierModel(Flight::db());
        $success = $panierModel->modifierQuantiteService($id_vente_draft_service, $nouvelle_quantite);

        if ($success) {
            // Récupérer le panier mis à jour
            $id_vente_draft = $_SESSION['panier_id'] ?? null;
            $panier = $panierModel->getPanier($id_vente_draft);
            $total = $panierModel->calculerTotal($id_vente_draft);

            $response = [
                'success' => true,
                'total' => number_format($total, 0, ',', ' ') . ' Ar',
            ];

            // Si l'article n'a pas été supprimé, calculer le prix de la ligne
            if ($nouvelle_quantite > 0) {
                foreach ($panier['services'] as $item) {
                    if ($item['id_vente_draft_service'] == $id_vente_draft_service) {
                        $response['item_price'] = number_format($item['prix_unitaire'] * $item['quantite'], 0, ',', ' ') . ' Ar';
                        break;
                    }
                }
            }

            Flight::json($response);
        } else {
            Flight::json(['error' => 'Erreur lors de la modification'], 500);
        }
    }

    public function supprimerProduit()
    {
        $request = Flight::request()->data;

        $panierModel = new PanierModel(Flight::db());
        $success = $panierModel->supprimerProduitPanier($request['id_vente_draft_produit']);

        if ($success) {
            $id_vente_draft = $_SESSION['panier_id'] ?? null;
            $panier = $panierModel->getPanier($id_vente_draft);
            $total = $panierModel->calculerTotal($id_vente_draft);

            $is_empty = empty($panier['produits']) && empty($panier['services']);

            Flight::json([
                'success' => true,
                'total' => number_format($total, 0, ',', ' ') . ' Ar',
                'is_empty' => $is_empty
            ]);
        } else {
            Flight::json(['error' => 'Erreur lors de la suppression'], 500);
        }
    }

    public function supprimerService()
    {
        $request = Flight::request()->data;

        $panierModel = new PanierModel(Flight::db());
        $success = $panierModel->supprimerServicePanier($request['id_vente_draft_service']);

        if ($success) {
            $id_vente_draft = $_SESSION['panier_id'] ?? null;
            $panier = $panierModel->getPanier($id_vente_draft);
            $total = $panierModel->calculerTotal($id_vente_draft);

            $is_empty = empty($panier['produits']) && empty($panier['services']);

            Flight::json([
                'success' => true,
                'total' => number_format($total, 0, ',', ' ') . ' Ar',
                'is_empty' => $is_empty
            ]);
        } else {
            Flight::json(['error' => 'Erreur lors de la suppression'], 500);
        }
    }

    public function ajouterClient()
    {
        $request = Flight::request()->data;

        if (empty($request['nom']) || empty($request['prenom'])) {
            Flight::json(['error' => 'Nom et prénom obligatoires'], 400);
            return;
        }

        $panierModel = new PanierModel(Flight::db());
        $id_client = $panierModel->ajouterClient($request['nom'], $request['prenom']);

        if ($id_client) {
            if (isset($_SESSION['panier_id'])) {
                $panierModel->updateClientPanier($_SESSION['panier_id'], $id_client);
            }

            Flight::json([
                'id_client' => $id_client,
                'nom' => $request['nom'],
                'prenom' => $request['prenom']
            ]);
        } else {
            Flight::json(['error' => 'Erreur lors de l\'ajout du client'], 500);
        }
    }

    public function selectionnerClient()
    {
        $request = Flight::request()->data;

        if (isset($_SESSION['panier_id'])) {
            $panierModel = new PanierModel(Flight::db());
            $panierModel->updateClientPanier($_SESSION['panier_id'], $request['id_client']);
        }

        Flight::redirect('/panier');
    }


    // Remplacer la méthode validerVente par cette version simplifiée :
    public function validerVente()
    {
        if (!isset($_SESSION['user']) || !isset($_SESSION['panier_id'])) {
            Flight::json(['error' => 'Session expirée ou panier introuvable'], 400);
            return;
        }

        $request = Flight::request()->data;
        $id_vente_draft = $_SESSION['panier_id'];
        $id_client = $request['id_client'] ?? null;
        $id_type_paiement = $request['id_type_paiement'] ?? null;

        // Convertir les montants en nombres
        $argent_donne = floatval($request['argent_donne']);
        $montant_total = floatval($request['montant_total']);

        // Vérifier si le montant reçu est suffisant
        if ($argent_donne < $montant_total) {
            Flight::json(['error' => 'Le montant reçu est insuffisant'], 400);
            return;
        }

        $panierModel = new PanierModel(Flight::db());

        // AJOUTER CETTE LIGNE : Associer le client au panier avant validation
        if ($id_client) {
            $panierModel->updateClientPanier($id_vente_draft, $id_client);
        }

        $success = $panierModel->validerVente($id_vente_draft, $argent_donne, $id_type_paiement); // <-- ici, pas $montant_total

        if ($success) {
            // Supprimer l'ID du panier de la session
            unset($_SESSION['panier_id']);

            Flight::json(['success' => true, 'id_vente' => $success]);
        } else {
            Flight::json(['error' => 'Erreur lors de la validation de la vente'], 500);
        }
    }

    public function interfaceClient()
    {
        if (!isset($_SESSION['user'])) {
            Flight::redirect('/');
            return;
        }
        $panierModel = new PanierModel(Flight::db());
        $produits = $panierModel->getProduits();
        $services = $panierModel->getServices();
        $panierCount = 0;
        if (isset($_SESSION['panier_id'])) {
            $panier = $panierModel->getPanier($_SESSION['panier_id']);
            $panierCount = (count($panier['produits'] ?? []) + count($panier['services'] ?? []));
        }
        $page = 'client/interface_client';
        Flight::render('index', [
            'page' => $page,
            'produits' => $produits,
            'services' => $services,
            'panierCount' => $panierCount
        ]);
    }

    public function ajouterAuPanierDepuisInterface()
    {
        $request = Flight::request()->data;
        if (!isset($_SESSION['user'])) {
            Flight::json(['error' => 'Utilisateur non connecté'], 401);
            return;
        }

        $success = false;
        $item_name = "";

        if (!isset($_SESSION['panier_id'])) {
            $panierModel = new PanierModel(Flight::db());
            $_SESSION['panier_id'] = $panierModel->creerPanier($_SESSION['user']['id_user'], null);
        }

        $panierModel = new PanierModel(Flight::db());

        if (isset($request['id_produit'])) {
            $success = $panierModel->ajouterProduitPanier(
                $_SESSION['panier_id'],
                $request['id_produit'],
                $request['quantite'],
                $request['prix_unitaire']
            );

            // Récupérer le nom du produit
            $produit = $panierModel->getProduitById($request['id_produit']);
            $item_name = $produit['nom'] ?? 'Produit';
        }

        if (isset($request['id_service'])) {
            $success = $panierModel->ajouterServicePanier(
                $_SESSION['panier_id'],
                $request['id_service'],
                $request['quantite'],
                $request['prix_unitaire']
            );

            // Récupérer le nom du service
            $service = $panierModel->getServiceById($request['id_service']);
            $item_name = $service['nom'] ?? 'Service';
        }

        // Compter les articles dans le panier
        $panier = $panierModel->getPanier($_SESSION['panier_id']);
        $cart_count = count($panier['produits'] ?? []) + count($panier['services'] ?? []);

        Flight::json([
            'success' => $success,
            'item_name' => $item_name,
            'cart_count' => $cart_count
        ]);
    }

    public function apiClients()
    {
        $terme = $_GET['search'] ?? '';
        $panierModel = new PanierModel(Flight::db());
        $clients = $panierModel->rechercherClients($terme);
        Flight::json($clients);
    }

    public function recapitulatifJson()
    {
        $id_vente_draft = $_SESSION['panier_id'] ?? null;
        $panierModel = new PanierModel(Flight::db());
        $panier = $panierModel->getPanier($id_vente_draft);
        $total = $panierModel->calculerTotal($id_vente_draft);
        $items = [];
        foreach (($panier['produits'] ?? []) as $item) {
            $items[] = [
                'nom' => $item['produit_nom'],
                'quantite' => $item['quantite'],
                'prix' => number_format($item['prix_unitaire'] * $item['quantite'], 0, ',', ' ')
            ];
        }
        foreach (($panier['services'] ?? []) as $item) {
            $items[] = [
                'nom' => $item['service_nom'],
                'quantite' => $item['quantite'],
                'prix' => number_format($item['prix_unitaire'] * $item['quantite'], 0, ',', ' ')
            ];
        }
        Flight::json([
            'items' => $items,
            'total' => number_format($total, 0, ',', ' '),
            'total_numeric' => $total
        ]);
    }
}
