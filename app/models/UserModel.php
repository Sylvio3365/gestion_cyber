<?php

namespace app\models;

use Flight;


class UserModel {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Tente de connecter un utilisateur avec email ou username + mot de passe
     */
    public function login($identifiant, $password)
    {
        $sql = "SELECT * FROM user_app 
                WHERE (username = ? OR email = ?) 
                AND deleted_at IS NULL 
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $identifiant);
        $stmt->bindValue(2, $identifiant);
        $stmt->execute();

        $result = $stmt->fetchAll(); // tableau de lignes

        if (!empty($result)) {
            $user = $result[0]; // on prend la première ligne

            if ($user['password'] === $password) {
                unset($user['password']);
                return $user;
            }
            
        }

        return false; // Échec d’authentification
    }

}


