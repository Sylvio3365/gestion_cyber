<?php

namespace app\controllers;

use Flight;
use app\models\AdminModel;

class AdminController
{
    public function manageBranches()
    {
        $branches = Flight::adminModel()->getAllBranches();
        $page = 'admin/crud_branche';  // Notez qu'on ne met pas /views/ ici, Flight gère ça pour vous
        Flight::render('index', ['branches' => $branches, 'page' => $page]);
    }

    public function addBranch()
    {
        $request = Flight::request()->data;

        if (empty($request['nom'])) {
            Flight::json(['error' => 'Le nom est obligatoire'], 400);
            return;
        }

        $success = Flight::adminModel()->addBranch(
            $request['nom'],
            $request['description'] ?? null
        );

        if ($success) {
            Flight::redirect('/admin/branche');
        } else {
            Flight::json(['error' => 'Erreur lors de l\'ajout'], 500);
        }
    }
    public function editBranch()
    {
        $request = Flight::request()->data;

        if (empty($request['id_branche']) || empty($request['nom'])) {
            Flight::json(['error' => 'ID et nom requis'], 400);
            return;
        }

        $success = Flight::adminModel()->updateBranch(
            $request['id_branche'],
            $request['nom'],
            $request['description'] ?? null
        );

        if ($success) {
            Flight::redirect('/admin/branche');
        } else {
            Flight::json(['error' => 'Erreur lors de la modification'], 500);
        }
    }
    public function deleteBranch()
    {
        $id = Flight::request()->data['id_branche'] ?? null;

        if (!$id) {
            Flight::json(['error' => 'ID requis'], 400);
            return;
        }

        $success = Flight::adminModel()->deleteBranch($id);

        if ($success) {
            Flight::redirect('/admin/branche');
        } else {
            Flight::json(['error' => 'Erreur lors de la suppression'], 500);
        }
    }
    //marque
    public function manageMarques()
    {
        $page = 'admin/crud_marque';  // Notez qu'on ne met pas /views/ ici, Flight gère ça pour vous
        $marques = Flight::adminModel()->getAllMarques();
        Flight::render('index', ['marques' => $marques, 'page' => $page]);
    }
    public function addMarque()
    {
        $request = Flight::request()->data;

        if (empty($request['nom'])) {
            Flight::json(['error' => 'Le nom est obligatoire'], 400);
            return;
        }

        $success = Flight::adminModel()->addMarque($request['nom']);

        if ($success) {
            Flight::redirect('/admin/marque');
        } else {
            Flight::json(['error' => 'Erreur lors de l\'ajout'], 500);
        }
    }
    public function editMarque()
    {
        $request = Flight::request()->data;

        if (empty($request['id_marque']) || empty($request['nom'])) {
            Flight::json(['error' => 'ID et nom requis'], 400);
            return;
        }

        $success = Flight::adminModel()->updateMarque($request['id_marque'], $request['nom']);

        if ($success) {
            Flight::redirect('/admin/marque');
        } else {
            Flight::json(['error' => 'Erreur lors de la modification'], 500);
        }
    }

    public function deleteMarque()
    {
        $id = Flight::request()->data['id_marque'] ?? null;

        if (!$id) {
            Flight::json(['error' => 'ID requis'], 400);
            return;
        }

        $success = Flight::adminModel()->deleteMarque($id);

        if ($success) {
            Flight::redirect('/admin/marque');
        } else {
            Flight::json(['error' => 'Erreur lors de la suppression'], 500);
        }
    }
    //categorie
    public function manageCategories()
    {
        $categories = Flight::adminModel()->getAllCategories();
        $branches = Flight::adminModel()->getAllBranches(); // pour liste déroulante dans le formulaire
        $page = 'admin/crud_categorie';
        Flight::render('index', [
            'categories' => $categories,
            'branches' => $branches,
            'page' => $page
        ]);
    }

    public function addCategorie()
    {
        $request = Flight::request()->data;

        if (empty($request['nom']) || empty($request['id_branche'])) {
            Flight::json(['error' => 'Nom et branche requis'], 400);
            return;
        }

        $success = Flight::adminModel()->addCategorie($request['nom'], $request['id_branche']);

        if ($success) {
            Flight::redirect('/admin/categorie');
        } else {
            Flight::json(['error' => 'Erreur lors de l\'ajout'], 500);
        }
    }

    public function editCategorie()
    {
        $request = Flight::request()->data;

        if (empty($request['id_categorie']) || empty($request['nom']) || empty($request['id_branche'])) {
            Flight::json(['error' => 'ID, nom et branche requis'], 400);
            return;
        }

        $success = Flight::adminModel()->updateCategorie(
            $request['id_categorie'],
            $request['nom'],
            $request['id_branche']
        );

        if ($success) {
            Flight::redirect('/admin/categorie');
        } else {
            Flight::json(['error' => 'Erreur lors de la modification'], 500);
        }
    }

    public function deleteCategorie()
    {
        $id = Flight::request()->data['id_categorie'] ?? null;

        if (!$id) {
            Flight::json(['error' => 'ID requis'], 400);
            return;
        }

        $success = Flight::adminModel()->deleteCategorie($id);

        if ($success) {
            Flight::redirect('/admin/categorie');
        } else {
            Flight::json(['error' => 'Erreur lors de la suppression'], 500);
        }
    }

    //produit
    public function manageProduits()
    {
        $produits = Flight::adminModel()->getAllProduits();
        $marques = Flight::adminModel()->getAllMarques();
        $categories = Flight::adminModel()->getAllCategories();

        Flight::render('index', [
            'produits' => $produits,
            'marques' => $marques,
            'categories' => $categories,
            'page' => 'admin/crud_produit'
        ]);
    }

    public function addProduit()
    {
        $request = Flight::request()->data;

        if (empty($request['nom']) || empty($request['id_marque']) || empty($request['id_categorie'])) {
            Flight::json(['error' => 'Nom, marque et catégorie requis'], 400);
            return;
        }

        $success = Flight::adminModel()->addProduit(
            $request['nom'],
            $request['description'] ?? null,
            $request['id_marque'],
            $request['id_categorie']
        );

        if ($success) {
            Flight::redirect('/admin/produit');
        } else {
            Flight::json(['error' => 'Erreur lors de l\'ajout'], 500);
        }
    }

    public function editProduit()
    {
        $request = Flight::request()->data;

        if (empty($request['id_produit']) || empty($request['nom']) || empty($request['id_marque']) || empty($request['id_categorie'])) {
            Flight::json(['error' => 'Tous les champs sont requis'], 400);
            return;
        }

        $success = Flight::adminModel()->updateProduit(
            $request['id_produit'],
            $request['nom'],
            $request['description'] ?? null,
            $request['id_marque'],
            $request['id_categorie']
        );

        if ($success) {
            Flight::redirect('/admin/produit');
        } else {
            Flight::json(['error' => 'Erreur lors de la modification'], 500);
        }
    }

    public function deleteProduit()
    {
        $id = Flight::request()->data['id_produit'] ?? null;

        if (!$id) {
            Flight::json(['error' => 'ID requis'], 400);
            return;
        }

        $success = Flight::adminModel()->deleteProduit($id);

        if ($success) {
            Flight::redirect('/admin/produit');
        } else {
            Flight::json(['error' => 'Erreur lors de la suppression'], 500);
        }
    }

    //service
    public function manageServices()
    {
        $services = Flight::adminModel()->getAllServices();
        $categories = Flight::adminModel()->getAllCategories();

        Flight::render('index', ['services' => $services, 'categories' => $categories, 'page' => 'admin/crud_service']);
    }

    public function addService()
    {
        $request = Flight::request()->data;
        if (empty($request['nom']) || empty($request['id_categorie'])) {
            Flight::json(['error' => 'Nom et catégorie obligatoires'], 400);
            return;
        }
        $success = Flight::adminModel()->addService($request['nom'], $request['description'] ?? null, $request['id_categorie']);
        if ($success) Flight::redirect('/admin/service');
        else Flight::json(['error' => 'Erreur lors de l\'ajout'], 500);
    }

    public function editService()
    {
        $request = Flight::request()->data;
        if (empty($request['id_service']) || empty($request['nom']) || empty($request['id_categorie'])) {
            Flight::json(['error' => 'ID, nom et catégorie obligatoires'], 400);
            return;
        }
        $success = Flight::adminModel()->updateService($request['id_service'], $request['nom'], $request['description'] ?? null, $request['id_categorie']);
        if ($success) Flight::redirect('/admin/service');
        else Flight::json(['error' => 'Erreur lors de la modification'], 500);
    }

    public function deleteService()
    {
        $id = Flight::request()->data['id_service'] ?? null;
        if (!$id) {
            Flight::json(['error' => 'ID requis'], 400);
            return;
        }
        $success = Flight::adminModel()->deleteService($id);
        if ($success) Flight::redirect('/admin/service');
        else Flight::json(['error' => 'Erreur lors de la suppression'], 500);
    }
    public function manageStocks()
    {
        $stocks = Flight::adminModel()->getAllStocks();
        $produits = Flight::adminModel()->getAllProduits();
        $types = Flight::adminModel()->getAllTypesMouvement();

        Flight::render('index', [
            'stocks' => $stocks,
            'produits' => $produits,
            'types' => $types
        ]);
    }

    public function addStock()
    {
        $request = Flight::request()->data;

        if (empty($request['id_produit']) || empty($request['id_mouvement']) || empty($request['quantite'])) {
            Flight::json(['error' => 'Tous les champs sont requis'], 400);
            return;
        }

        $success = Flight::adminModel()->addStock(
            $request['id_produit'],
            $request['id_mouvement'],
            $request['quantite']
        );

        if ($success) {
            Flight::redirect('/admin/stock');
        } else {
            Flight::json(['error' => 'Erreur lors de l\'ajout'], 500);
        }
    }

    public function deleteStock()
    {
        $id = Flight::request()->data['id_stock'] ?? null;

        if (!$id) {
            Flight::json(['error' => 'ID requis'], 400);
            return;
        }

        $success = Flight::adminModel()->deleteStock($id);

        if ($success) {
            Flight::redirect('/admin/stock');
        } else {
            Flight::json(['error' => 'Erreur lors de la suppression'], 500);
        }
    }
    //type_mouvement
    public function manageTypeMouvements()
    {
        $types = Flight::adminModel()->getAllTypeMouvements();
        Flight::render('admin/crud_type_mouvement', ['types' => $types]);
    }

    public function addTypeMouvement()
    {
        $request = Flight::request()->data;

        if (empty($request['type'])) {
            Flight::json(['error' => 'Le type est requis'], 400);
            return;
        }

        $success = Flight::adminModel()->addTypeMouvement($request['type']);

        if ($success) {
            Flight::redirect('/admin/type_mouvement');
        } else {
            Flight::json(['error' => 'Erreur lors de l\'ajout'], 500);
        }
    }

    public function editTypeMouvement()
    {
        $request = Flight::request()->data;

        if (empty($request['id_mouvement']) || empty($request['type'])) {
            Flight::json(['error' => 'ID et type requis'], 400);
            return;
        }

        $success = Flight::adminModel()->updateTypeMouvement($request['id_mouvement'], $request['type']);

        if ($success) {
            Flight::redirect('/admin/type_mouvement');
        } else {
            Flight::json(['error' => 'Erreur lors de la modification'], 500);
        }
    }

    public function deleteTypeMouvement()
    {
        $id = Flight::request()->data['id_mouvement'] ?? null;

        if (!$id) {
            Flight::json(['error' => 'ID requis'], 400);
            return;
        }

        $success = Flight::adminModel()->deleteTypeMouvement($id);

        if ($success) {
            Flight::redirect('/admin/type_mouvement');
        } else {
            Flight::json(['error' => 'Erreur lors de la suppression'], 500);
        }
    }
}
