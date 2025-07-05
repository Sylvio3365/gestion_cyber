<?php

namespace app\models;

use Flight;

class ConnexionModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getClientConnectee()
    {
        date_default_timezone_set('Indian/Antananarivo');
        $current_time = date('Y-m-d H:i:s');
        $query = "
            SELECT h.id_historique_connection, c.id_client, c.nom, c.prenom, h.date_debut, h.date_fin
            FROM client c
            JOIN historique_connexion h ON c.id_client = h.id_client
            WHERE h.id_poste IS NULL 
            AND h.statut = 0 
            AND (h.date_fin IS NULL OR h.date_fin > :current_time)
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([':current_time' => $current_time]);
            $clients = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $clients;
        } catch (\PDOException $e) {
            Flight::error($e);
            return [];
        }
    }


    public function getAllClient()
    {
        try {
            $query = "SELECT id_client, nom, prenom, added_at 
                      FROM client 
                      WHERE deleted_at IS NULL
                      ORDER BY nom, prenom";

            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $clients = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $clients;
        } catch (\PDOException $e) {
            Flight::error($e);
            return [];
        }
    }

    public function getHisto()
    {
        $query = "
        SELECT 
            h.id_historique_connection, 
            c.id_client, 
            c.nom, 
            c.prenom, 
            h.date_debut, 
            h.date_fin, 
            h.id_poste
        FROM client c
        JOIN historique_connexion h ON c.id_client = h.id_client
        WHERE h.statut = 0 
        AND h.date_fin IS NOT NULL
        AND DATE(h.date_debut) = CURDATE()
    ";

        $tarif_par_15min = $this->getPrixServiceActuel();
        if ($tarif_par_15min === false) {
            return 'Erreur lors de la récupération du prix.';
        }

        $tarif_par_minute = $tarif_par_15min / 15;

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $resultats_bruts = $stmt->fetchAll();

        $resultats_final = [];

        foreach ($resultats_bruts as $connexion) {
            
            $date_debut = new \DateTime($connexion['date_debut']);
            $date_fin = new \DateTime($connexion['date_fin']);

            $interval = $date_debut->diff($date_fin);
            $duree_minutes = ($interval->h * 60) + $interval->i;

            $connexion['duree_minutes'] = $duree_minutes;
            $connexion['montant_a_payer'] = round($duree_minutes * $tarif_par_minute, 2);

            $resultats_final[] = $connexion;
        }

        return $resultats_final;
    }

    public function creerVente($id_user, $id_client, $id_service, $quantite, $prix_unitaire)
    {
        if ($id_user == null) {
            $id_user = 2; // L'ID de l'utilisateur (vous pouvez ajuster cette valeur)
        }
        try {
            // 1. Créer un enregistrement dans la table vente_draft
            $date_creation = date('Y-m-d H:i:s');
            $query = "INSERT INTO vente_draft (date_creation, id_user, id_client)
                  VALUES (:date_creation, :id_user, :id_client)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':date_creation' => $date_creation,
                ':id_user' => $id_user,
                ':id_client' => $id_client
            ]);

            // Récupérer l'ID de la vente_draft
            // Requête pour obtenir le dernier ID inséré
            $query_id = "SELECT id_vente_draft FROM vente_draft 
                     WHERE date_creation = :date_creation AND id_user = :id_user AND id_client = :id_client
                     ORDER BY id_vente_draft DESC LIMIT 1";
            $stmt_id = $this->db->prepare($query_id);
            $stmt_id->execute([
                ':date_creation' => $date_creation,
                ':id_user' => $id_user,
                ':id_client' => $id_client
            ]);
            $id_vente_draft = $stmt_id->fetchColumn();  // Récupère l'ID de la vente_draft insérée

            // 2. Insérer un service dans la table vente_draft_service
            $query_service = "INSERT INTO vente_draft_service (quantite, prix_unitaire, id_service, id_vente_draft)
                          VALUES (:quantite, :prix_unitaire, :id_service, :id_vente_draft)";
            $stmt_service = $this->db->prepare($query_service);
            $stmt_service->execute([
                ':quantite' => $quantite,
                ':prix_unitaire' => $prix_unitaire,
                ':id_service' => $id_service,
                ':id_vente_draft' => $id_vente_draft
            ]);

            // 3. Calculer le total et finaliser la vente
            $total = $quantite * $prix_unitaire;
            $argent_donner = $total;

            $query_vente = "INSERT INTO vente (date_vente, total, argent_donner, id_vente_draft,id_type_de_payement)
                VALUES (NOW(), :total, :argent_donner, :id_vente_draft, :id_type_de_payement)";  // Utilisation de NOW() pour la date
            $stmt_vente = $this->db->prepare($query_vente);
            $stmt_vente->execute([
                ':id_type_de_payement' => 1,
                ':total' => $total,
                ':argent_donner' => $argent_donner,
                ':id_vente_draft' => $id_vente_draft
            ]);
            return true;
        } catch (\PDOException $e) {
            Flight::error($e);  // Gérer l'erreur
            return false;
        }
    }

    public function addClientConecter($id_client, $id_poste = null, $duree = null)
    {
        date_default_timezone_set('Indian/Antananarivo');
        $date_debut = date('Y-m-d H:i:s');
        if ($duree !== null) {
            $date_fin = date('Y-m-d H:i:s', strtotime($date_debut . ' + ' . $duree . ' minutes'));
        } else {
            $date_fin = null;
        }
        try {
            $query = "INSERT INTO historique_connexion (date_debut, date_fin, id_client, id_poste)
                  VALUES (:date_debut, :date_fin, :id_client, :id_poste)";
            $this->db->prepare($query)->execute([
                ':date_debut' => $date_debut,
                ':date_fin' => $date_fin,
                ':id_client' => $id_client,
                ':id_poste' => $id_poste
            ]);
            return true;
        } catch (\PDOException $e) {
            Flight::error($e);
            return false;
        }
    }

    public function payer($id_user, $idhistoriqueconn)
    {
        try {
            $query_update = "UPDATE historique_connexion SET statut = 1 WHERE id_historique_connection = :idhistoriqueconn";
            $stmt_update = $this->db->prepare($query_update);
            $stmt_update->execute([':idhistoriqueconn' => $idhistoriqueconn]);
            // 1. Récupérer l'historique de la connexion pour obtenir date_debut et date_fin
            $query = "SELECT id_client, date_debut, date_fin FROM historique_connexion WHERE id_historique_connection = :idhistoriqueconn AND date_fin IS NOT NULL";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':idhistoriqueconn' => $idhistoriqueconn]);

            $connexion = $stmt->fetch();

            if (!$connexion) {
                return false;  // Si aucune connexion n'est trouvée ou si la date_fin est NULL
            }

            $date_debut = new \DateTime($connexion['date_debut']);
            $date_fin = new \DateTime($connexion['date_fin']);


            $interval = $date_debut->diff($date_fin);
            $duree_minutes = ($interval->h * 60) + $interval->i;  // Convertir heures en minutes et ajouter les minutes

            // // Récupérer le prix du service actuel
            $prix_service = $this->getPrixServiceActuel() / 15;

            if ($prix_service === false) {
                return 'Erreur lors de la récupération du prix.';
            }

            // // Calculer le montant à payer
            $montant_a_payer = $duree_minutes * ($prix_service);  // Le prix par minute est récupéré dynamiquement

            // // Appeler la fonction de création de la vente
            $ventes = $this->creerVente($id_user, $connexion['id_client'], $this->getServiceConnexion(), $duree_minutes, $prix_service);

            if ($ventes) {
                return true;
            }
        } catch (\PDOException $e) {
            Flight::error($e);  // Gérer l'erreur
            return 'Erreur lors du calcul du paiement.';
        }
    }



    public function getServiceConnexion()
    {
        try {
            // Requête SQL pour récupérer l'ID du service dont le nom est 'connexion'
            $query = "SELECT id_service FROM service WHERE nom = 'connexion' LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            // Récupérer l'ID du service
            $service = $stmt->fetch(\PDO::FETCH_ASSOC);

            // Si un service est trouvé, retourner l'ID, sinon retourner false
            if ($service) {
                return $service['id_service'];
            } else {
                return false;  // Aucun service trouvé avec le nom 'connexion'
            }
        } catch (\PDOException $e) {
            Flight::error($e);  // Gérer l'erreur
            return false;  // Retourner false en cas d'erreur
        }
    }

    public function getPrixServiceActuel()
    {
        try {
            // Définir la date actuelle et en extraire le mois et l'année
            date_default_timezone_set('Indian/Antananarivo');
            $currentDate = date('Y-m-d');
            $currentMonth = date('m');  // Mois actuel
            $currentYear = date('Y');   // Année actuelle

            // Préparer la requête pour récupérer le prix du service pour le mois et l'année actuels
            $query = "
            SELECT ps.prix
            FROM prix_service ps
            JOIN service s ON ps.id_service = s.id_service
            WHERE ps.mois = :mois
            AND ps.annee = :annee
            AND s.nom = 'connexion'
            ORDER BY ps.date_modification DESC
            LIMIT 1
            ";

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':mois' => $currentMonth,
                ':annee' => $currentYear
            ]);

            // Récupérer le prix
            $prix = $stmt->fetch(\PDO::FETCH_ASSOC);

            // Si un prix est trouvé, retourner le prix, sinon retourner un message d'erreur
            if ($prix) {
                return $prix['prix'];  // Retourner le prix trouvé
            } else {
                return 'Aucun prix trouvé pour ce mois et cette année.';
            }
        } catch (\PDOException $e) {
            Flight::error($e);
            return 'Erreur lors de la récupération du prix.';
        }
    }

    public function arreterConnexion($idhistoriqueconn)
    {
        try {
            date_default_timezone_set('Indian/Antananarivo');
            $date_fin = date('Y-m-d H:i:s');
            $query = "UPDATE historique_connexion
                  SET date_fin = :date_fin
                  WHERE id_historique_connection = :idhistoriqueconn
                    AND date_fin IS NULL";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':date_fin' => $date_fin,
                ':idhistoriqueconn' => $idhistoriqueconn
            ]);
            return true;  // Retourner true si la mise à jour a réussi
        } catch (\PDOException $e) {
            Flight::error($e);  // En cas d'erreur, gérer l'exception
            return false;
        }
    }
}
