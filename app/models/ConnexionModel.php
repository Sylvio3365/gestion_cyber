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

    public function getClientConnectee()
    {
        date_default_timezone_set('Indian/Antananarivo');
        $current_time = date('Y-m-d H:i:s');
        $query = "
        SELECT c.id_client, c.nom, c.prenom, h.date_debut, h.date_fin
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
}
