<?php

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
            // Exemple : on peut stocker l'utilisateur en session
            $_SESSION['user'] = $user;

            Flight::json([
                'status' => 'success',
                'message' => 'Connexion réussie',
                'user' => $user
            ]);
        } else {
            Flight::json([
                'status' => 'error',
                'message' => 'Identifiant ou mot de passe incorrect.'
            ], 401);
        }
    }

    public function showLoginForm()
    {
        // // Vérifier si l'utilisateur est déjà connecté
        // if ($this->isLoggedIn()) {
        //     Flight::redirect('/dashboard');
        // }

        // $data = [
        //     'title' => 'Connexion',
        //     'error' => Flight::request()->query->error ?? null
        // ];
        Flight::render('login_form', null);
    }
}
