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
}
