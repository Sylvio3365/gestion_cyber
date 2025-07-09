<?php

namespace app\models;

class StatRecetteModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getStatsParBranche($period, $date)
    {
        switch ($period) {
            case 'jour':
                $condition = "DATE(date_vente) = :date";
                break;
            case 'semaine':
                $condition = "YEARWEEK(date_vente) = YEARWEEK(:date)";
                break;
            case 'mois':
                $condition = "MONTH(date_vente) = MONTH(:date) AND YEAR(date_vente) = YEAR(:date)";
                break;
            case 'annee':
                $condition = "YEAR(date_vente) = YEAR(:date)";
                break;
            default:
                throw new \InvalidArgumentException("Période invalide");
        }

        $sql = "
        SELECT 
            id_branche,
            nom_branche,
            type_vente,
            SUM(quantite) AS total_quantite,
            SUM(total) AS chiffre_affaires
        FROM vue_stats_par_branche
        WHERE $condition
        GROUP BY id_branche, type_vente
        ORDER BY chiffre_affaires DESC
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':date' => $date]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getStatsParBrancheAggregated($period, $date)
    {
        switch ($period) {
            case 'jour':
                $select = "DATE(date_vente) AS periode";
                $where = "DATE(date_vente) = :date";
                break;
            case 'semaine':
                $select = "CONCAT(YEAR(date_vente), '-S', WEEK(date_vente)) AS periode";
                $where = "YEARWEEK(date_vente) = YEARWEEK(:date)";
                break;
            case 'mois':
                $select = "DATE_FORMAT(date_vente, '%Y-%m') AS periode";
                $where = "MONTH(date_vente) = MONTH(:date) AND YEAR(date_vente) = YEAR(:date)";
                break;
            case 'annee':
                $select = "YEAR(date_vente) AS periode";
                $where = "YEAR(date_vente) = YEAR(:date)";
                break;
            default:
                throw new \InvalidArgumentException("Période invalide");
        }

        $sql = "
        SELECT $select, nom_branche, type_vente,
               SUM(quantite) AS total_quantite,
               SUM(total) AS chiffre_affaires
        FROM vue_stats_par_branche
        WHERE $where
        GROUP BY periode, nom_branche, type_vente
        ORDER BY periode ASC
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':date' => $date]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
