<?php

namespace app\models;

class ParametreModel
{
    private $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function getMdp()
    {
        $sql = 'SELECT mdp
                  FROM parametre_wifi
                 ORDER BY date DESC
                 LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $mdp = $stmt->fetchColumn();
        return $mdp !== false ? $mdp : null;
    }

    public function setMdp($mdp)
    {
        $sql = 'INSERT INTO parametre_wifi (mdp, date) VALUES (:mdp, NOW())';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':mdp' => $mdp
        ]);
    }
}
