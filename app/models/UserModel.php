<?php

namespace app\models;

use Flight;


class UserModel
{

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Tente de connecter un utilisateur avec email ou username + mot de passe
     */
    public function login($identifiant, $password)
    {
        $sql = "SELECT u.*, a.name AS role_name
        FROM user_app u
        JOIN account_type a ON u.id_account_type = a.id_account_type
        WHERE (u.username = ? OR u.email = ?)
          AND u.deleted_at IS NULL
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

    // fonction de Tafita
    public function getUserById($id)
    {
        $sql = "SELECT u.*, a.name AS 
                FROM user_app u
                JOIN account_type a ON u.id_account_type = a.id_account_type
                WHERE u.id_user_app = ? AND u.deleted_at IS NULL";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getUserById1($id)
    {
        $sql = "SELECT u.*, a.name AS account_type_name
                FROM user_app u
                JOIN account_type a ON u.id_account_type = a.id_account_type
                WHERE u.id_user = ? AND u.deleted_at IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
