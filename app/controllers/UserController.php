<?php

namespace app\controllers;

use Flight;
use app\models\UserModel;

class UserController
{
    private $userModel;

    public function __construct() 
    {
        // Initialiser le modèle utilisateur avec la connexion à la base de données
        $this->userModel = new UserModel(Flight::db());
    }

    /**
     * Affiche la page d'accueil/login
     */
    public function home()
    {
        // Vérifier si l'utilisateur est déjà connecté
        if ($this->isLoggedIn()) {
            Flight::redirect('/dashboard');
        }

        $data = ['message' => "Bienvenue, veuillez vous connecter"];
        Flight::render('login_form', $data);
    }

    /**
     * Affiche le formulaire de connexion
     */
    public function showLoginForm()
    {
        // Vérifier si l'utilisateur est déjà connecté
        if ($this->isLoggedIn()) {
            Flight::redirect('/dashboard');
        }

        $data = [
            'title' => 'Connexion',
            'error' => Flight::request()->query->error ?? null
        ];
        Flight::render('login_form', $data);
    }

    /**
     * Traite la tentative de connexion
     */
    public function login()
    {
        try {
            // Récupération des données du formulaire
            $identifier = trim(Flight::request()->data->identifier ?? '');
            $password = Flight::request()->data->password ?? '';

            // Validation des données
            if (empty($identifier) || empty($password)) {
                $this->loginError('Veuillez remplir tous les champs');
                return;
            }

            // Tentative de connexion
            $user = $this->userModel->login($identifier, $password);

            if ($user) {
                // Connexion réussie
                $this->setUserSession($user);
                
                // Redirection vers la page demandée ou dashboard par défaut
                $redirect = Flight::request()->data->redirect ?? '/dashboard';
                Flight::redirect($redirect);
            } else {
                // Échec de la connexion
                $this->loginError('Identifiants incorrects');
            }

        } catch (Exception $e) {
            error_log("Erreur lors de la connexion : " . $e->getMessage());
            $this->loginError('Une erreur est survenue. Veuillez réessayer.');
        }
    }

    /**
     * Déconnexion de l'utilisateur
     */
    public function logout()
    {
        // Démarrer la session si ce n'est pas déjà fait
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Détruire toutes les données de session
        session_destroy();
        
        // Supprimer le cookie de session si il existe
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Redirection vers la page de connexion
        Flight::redirect('/login?message=Déconnexion réussie');
    }

    /**
     * Affiche le tableau de bord (nécessite une connexion)
     */
    public function dashboard()
    {
        // Vérifier si l'utilisateur est connecté
        if (!$this->isLoggedIn()) {
            Flight::redirect('/login');
        }

        $user = $this->getCurrentUser();
        $data = [
            'title' => 'Tableau de bord',
            'user' => $user,
            'welcome_message' => "Bienvenue {$user['firstname']} {$user['name']}"
        ];

        Flight::render('dashboard', $data);
    }

    /**
     * Affiche le profil utilisateur
     */
    public function profile()
    {
        if (!$this->isLoggedIn()) {
            Flight::redirect('/login');
        }

        $user = $this->getCurrentUser();
        $data = [
            'title' => 'Mon Profil',
            'user' => $user
        ];

        Flight::render('profile', $data);
    }

    /**
     * API : Retourne les informations de l'utilisateur connecté (JSON)
     */
    public function getCurrentUserApi()
    {
        if (!$this->isLoggedIn()) {
            Flight::json(['error' => 'Non autorisé'], 401);
            return;
        }

        $user = $this->getCurrentUser();
        Flight::json(['user' => $user]);
    }

    /**
     * Middleware : Vérifier si l'utilisateur est connecté
     */
    public function requireAuth()
    {
        if (!$this->isLoggedIn()) {
            // Pour les requêtes AJAX
            if (Flight::request()->ajax) {
                Flight::json(['error' => 'Session expirée'], 401);
                return;
            }
            
            // Redirection avec l'URL de retour
            $currentUrl = Flight::request()->url;
            Flight::redirect('/login?redirect=' . urlencode($currentUrl));
        }
    }

    /**
     * Vérifier si l'utilisateur est connecté
     * @return bool
     */
    private function isLoggedIn()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['user']) && !empty($_SESSION['user']['id_user']);
    }

    /**
     * Récupérer l'utilisateur actuel
     * @return array|null
     */
    private function getCurrentUser()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return $_SESSION['user'] ?? null;
    }

    /**
     * Définir la session utilisateur
     * @param array $user
     */
    private function setUserSession($user)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['user'] = $user;
        $_SESSION['login_time'] = time();
        
        // Régénérer l'ID de session pour la sécurité
        session_regenerate_id(true);
    }

    /**
     * Gérer les erreurs de connexion
     * @param string $message
     */
    private function loginError($message)
    {
        Flight::redirect('/login?error=' . urlencode($message));
    }

    /**
     * Vérifier les permissions selon le type de compte
     * @param array $allowedTypes - Types de compte autorisés
     * @return bool
     */
    public function hasPermission($allowedTypes = [])
    {
        if (!$this->isLoggedIn()) {
            return false;
        }

        $user = $this->getCurrentUser();
        return in_array($user['account_type_name'], $allowedTypes);
    }

    /**
     * Middleware pour vérifier les permissions
     * @param array $allowedTypes
     */
    public function requirePermission($allowedTypes = [])
    {
        if (!$this->hasPermission($allowedTypes)) {
            if (Flight::request()->ajax) {
                Flight::json(['error' => 'Accès non autorisé'], 403);
                return;
            }
            
            Flight::redirect('/access-denied');
        }
    }
}

