<?php

// ... après vérification des infos client

namespace app\controllers;

use Flight;
use app\models\UserModel;


class UserController {


    public function __construct() {}

    public function login() {
        
        $request = Flight::request()->data;

        // Vérifie que les champs requis sont présents
        if (empty($request['identifiant']) || empty($request['password'])) {
            Flight::json([
                'status' => 'error',
                'message' => 'Veuillez fournir un identifiant (email ou username) et un mot de passe.'
            ], 400);
            return;
        }
        $model = new UserModel(Flight::db());


        $identifiant = $request['identifiant'];
        $password = $request['password'];

        $user = $model->login($identifiant, $password);

        if ($user) {
            $_SESSION['user'] = $user;

             Flight::redirect('/dashboard');
        } else {
            Flight::json([
                'status' => 'error',
                'message' => 'Identifiant ou mot de passe incorrect.'
            ], 401);
        }
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        // Détruit toutes les données de session
        session_unset();
        session_destroy();
    
        // Redirige vers la page de login
        Flight::render('login_form');

    }
    

    public function showLoginForm()
    {
        Flight::render('login_form', null);
    }
    

    public function home()
    {
        $data = ['message' => "Hello world"];
        Flight::render('template', $data);
    }
}
