<?php

namespace app\models;

use Flight;

class UserModel {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Fonction de connexion qui accepte soit le nom d'utilisateur soit l'email
     * @param string $identifier - Nom d'utilisateur ou email
     * @param string $password - Mot de passe
     * @return array|false - Retourne les données de l'utilisateur si succès, false sinon
     */
    public function login($identifier, $password) {
        try {
            // Requête pour chercher l'utilisateur par nom d'utilisateur ou email
            // On vérifie aussi que l'utilisateur n'est pas supprimé (deleted_at IS NULL)
            $sql = "SELECT u.*, at.name as account_type_name 
                    FROM user_app u 
                    LEFT JOIN account_type at ON u.id_account_type = at.id_account_type 
                    WHERE (u.username = :identifier OR u.email = :identifier) 
                    AND u.deleted_at IS NULL 
                    AND at.deleted_at IS NULL";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':identifier', $identifier, PDO::PARAM_STR);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Si l'utilisateur n'existe pas
            if (!$user) {
                return false;
            }
            
            // Vérification du mot de passe
            if (password_verify($password, $user['password'])) {
                // Suppression du mot de passe des données retournées pour la sécurité
                unset($user['password']);
                
                // Optionnel : Mettre à jour la dernière connexion
                $this->updateLastLogin($user['id_user']);
                
                return $user;
            }
            
            return false;
            
        } catch (PDOException $e) {
            // Log de l'erreur (adaptez selon votre système de logging)
            error_log("Erreur lors de la connexion : " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Met à jour la dernière connexion de l'utilisateur
     * @param int $userId - ID de l'utilisateur
     * @return bool
     */
    private function updateLastLogin($userId) {
        try {
            // Si vous avez une colonne last_login dans votre table user_app
            // Sinon, vous pouvez ignorer cette fonction ou l'adapter selon vos besoins
            $sql = "UPDATE user_app SET last_login = NOW() WHERE id_user = :user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour de la dernière connexion : " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Fonction utilitaire pour hasher un mot de passe
     * @param string $password - Mot de passe en clair
     * @return string - Mot de passe hashé
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Validation de l'email
     * @param string $email
     * @return bool
     */
    private function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Fonction pour créer un utilisateur (bonus)
     * @param array $userData - Données de l'utilisateur
     * @return int|false - ID de l'utilisateur créé ou false
     */
    public function createUser($userData) {
        try {
            $sql = "INSERT INTO user_app (name, username, firstname, email, password, id_account_type) 
                    VALUES (:name, :username, :firstname, :email, :password, :account_type)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':name', $userData['name']);
            $stmt->bindParam(':username', $userData['username']);
            $stmt->bindParam(':firstname', $userData['firstname']);
            $stmt->bindParam(':email', $userData['email']);
            $stmt->bindParam(':password', self::hashPassword($userData['password']));
            $stmt->bindParam(':account_type', $userData['account_type'], PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            
            return false;
            
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de l'utilisateur : " . $e->getMessage());
            return false;
        }
    }
}

