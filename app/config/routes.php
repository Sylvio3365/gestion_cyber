<?php

use app\controllers\UserController;
use app\controllers\VenteController;
use app\controllers\PanierController;
use app\controllers\AdminController;

use flight\Engine;
use flight\net\Router;
//use Flight;

/** 
 * @var Router $router 
 * @var Engine $app
 */

$UserController = new UserController();
$PanierController = new PanierController();
$VenteController = new VenteController();


$AdminController = new AdminController();

$router->get('/', [$UserController, 'showLoginForm']);
$router->post('/login', [$UserController, 'login']);
$router->get('/logout', [$UserController, 'logout']);
$router->get('/benef_form', [$VenteController, 'showBenefice']);
$router->post('/benefice', [$VenteController, 'afficherBenefice']);


$router->get('/admin/branche', [$AdminController, 'manageBranches']);

$router->post('/admin/branche/add', [$AdminController, 'addBranch']);
$router->post('/admin/branche/edit', [$AdminController, 'editBranch']);
$router->post('/admin/branche/delete', [$AdminController, 'deleteBranch']);

$router->get('/dashboard', function () {
    Flight::render('accueil_(test)');
});
//marques
$router->get('/admin/marque', [$AdminController, 'manageMarques']);
$router->post('/admin/marque/add', [$AdminController, 'addMarque']);
$router->post('/admin/marque/edit', [$AdminController, 'editMarque']);
$router->post('/admin/marque/delete', [$AdminController, 'deleteMarque']);
//categories
$router->get('/admin/categorie', [$AdminController, 'manageCategories']);
$router->post('/admin/categorie/add', [$AdminController, 'addCategorie']);
$router->post('/admin/categorie/edit', [$AdminController, 'editCategorie']);
$router->post('/admin/categorie/delete', [$AdminController, 'deleteCategorie']);

//produits
$router->get('/admin/produit', [$AdminController, 'manageProduits']);
$router->post('/admin/produit/add', [$AdminController, 'addProduit']);
$router->post('/admin/produit/edit', [$AdminController, 'editProduit']);
$router->post('/admin/produit/delete', [$AdminController, 'deleteProduit']);
//services
$router->get('/admin/service', [$AdminController, 'manageServices']);
$router->post('/admin/service/add', [$AdminController, 'addService']);
$router->post('/admin/service/edit', [$AdminController, 'editService']);
$router->post('/admin/service/delete', [$AdminController, 'deleteService']);
//stock
$router->get('/admin/stock', [$AdminController, 'manageStocks']);
$router->post('/admin/stock/add', [$AdminController, 'addStock']);
$router->post('/admin/stock/delete', [$AdminController, 'deleteStock']);
//type_mouvement
$router->get('/admin/type_mouvement', [$AdminController, 'manageTypeMouvements']);
$router->post('/admin/type_mouvement/add', [$AdminController, 'addTypeMouvement']);
$router->post('/admin/type_mouvement/edit', [$AdminController, 'editTypeMouvement']);
$router->post('/admin/type_mouvement/delete', [$AdminController, 'deleteTypeMouvement']);


$router->get('/panier', [$PanierController, 'afficherPanier']);
$router->post('/panier/creer', [$PanierController, 'creerPanier']);
$router->post('/panier/ajouter-produit', [$PanierController, 'ajouterProduit']);
$router->post('/panier/ajouter-service', [$PanierController, 'ajouterService']);
$router->post('/panier/modifier-quantite-produit', [$PanierController, 'modifierQuantiteProduit']);
$router->post('/panier/modifier-quantite-service', [$PanierController, 'modifierQuantiteService']);
$router->post('/panier/supprimer-produit', [$PanierController, 'supprimerProduit']);
$router->post('/panier/supprimer-service', [$PanierController, 'supprimerService']);
$router->post('/panier/ajouter-client', [$PanierController, 'ajouterClient']);
$router->post('/panier/selectionner-client', [$PanierController, 'selectionnerClient']);
$router->post('/panier/valider', [$PanierController, 'validerVente']);
$router->get('/interface-client', [$PanierController, 'interfaceClient']);
$router->post('/interface-client/ajouter-panier', [$PanierController, 'ajouterAuPanierDepuisInterface']);
$router->get('/api/clients', [$PanierController, 'apiClients']);
$router->get('/panier/recapitulatif-json', [$PanierController, 'recapitulatifJson']);