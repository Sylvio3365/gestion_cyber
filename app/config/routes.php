<?php

use app\controllers\UserController;
use app\controllers\VenteController;
use app\controllers\PanierController;
use app\controllers\AdminController;
use app\controllers\TemplateController;
use app\controllers\StatistiqueController;
use app\controllers\ConnexionController;
use app\controllers\HistoConnexController;
use app\controllers\ParametreController;
use app\controllers\PosteController;
use app\controllers\StatRecetteController;
use app\controllers\FactureController;
use app\controllers\StatController;
use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

$UserController = new UserController();
$PanierController = new PanierController();
$VenteController = new VenteController();
$StatController = new StatistiqueController();
$factureController = new FactureController();
$AdminController = new AdminController();

$router->get('/', [$UserController, 'showLoginForm']);
$router->post('/login', [$UserController, 'login']);
$router->get('/logout', [$UserController, 'logout']);
$router->get('/benef_form', [$VenteController, 'showBenefice']);
$router->get('/benefice', [$VenteController, 'afficherBenefice']);

$router->get('/admin/branche', [$AdminController, 'manageBranches']);

$router->post('/admin/branche/add', [$AdminController, 'addBranch']);
$router->post('/admin/branche/edit', [$AdminController, 'editBranch']);
$router->post('/admin/branche/delete', [$AdminController, 'deleteBranch']);

$router->get('/dashboard', function () {
    $page = 'accueil';
    Flight::render('index', compact('page'));
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

$router->get('/stat', [$StatController, 'topProduitParBranche']);

$TemplateController = new TemplateController();
$router->get('/template', [$TemplateController, 'show']);

// Gestion du trafic de connexion des clients (avec un poste)
$PosteController = new PosteController();
$router->get('/poste/accueil', [$PosteController, 'accueil']);
$router->get('/poste/demarrerSession', [$PosteController, 'demarrerSession']);
$router->post('/poste/demarrerSessionPoste', [$PosteController, 'demarrerSessionPoste']);
$router->post('/poste/arreterSessionPoste', [$PosteController, 'arreterSessionPoste']);
$router->post('/poste/mettreEnMaintenance', [$PosteController, 'mettreEnMaintenance']);
$router->post('/poste/rendreDisponible', [$PosteController, 'rendreDisponible']);
$router->get('/poste/historique', [$PosteController, 'showHistoEtat']);

$ConnexionController = new ConnexionController();
$router->get('/connexion/sansposte', [$ConnexionController, 'showGestionConnexionCLientSansPoste']);
$router->post('/connexion/sansposte/add', [$ConnexionController, 'addClientConecter']);
$router->get('/connexion/apayer', [$ConnexionController, 'showHisto']);
$router->post('/connexion/sansposte/arreter', [$ConnexionController, 'arreterarreterConnexion']);
$router->post('/connexion/payer', [$ConnexionController, 'payer']);

$stat =  new StatRecetteController();
$router->get('/recette/branche', [$stat, 'showStats']);
$router->get('/admin/stats/', [$stat, 'apiStats']);

$histo = new HistoConnexController();
$router->get('/connexion/historique', [$histo, 'showHistorique']);

$router->get('/admin/prix', [$AdminController, 'showCrudPrix']);

$router->post('/admin/prix/valider', [$AdminController, 'validerPrix']);

$parametreController = new ParametreController();
$router->post('/parametre/mdp',[$parametreController,'setMdp']);
$router->get('/parametre/mdp',[$parametreController,'getMdp']);
$router->get('/facture/voir', [$factureController, 'voirFacture']);
$router->get('/facture/pdf/@id', [$factureController, 'genererFacturePDF']);

