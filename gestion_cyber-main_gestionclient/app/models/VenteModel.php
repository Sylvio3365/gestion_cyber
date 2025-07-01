<?php

namespace app\models;

class VenteModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Calcule le bénéfice total (ventes - achats) pour une période donnée
     * 
     * @param string|null $date Format 'YYYY-MM-DD'
     * @param int|null $mois
     * @param int|null $annee
     * @return float
     */
    public function calculerBenefice($date = null, $mois = null, $annee = null) {
        $conditions = [];
        $params = [];

        if ($date) {
            $conditions[] = "DATE(v.date_vente) = :date";
            $params[':date'] = $date;
        } elseif ($mois && $annee) {
            $conditions[] = "MONTH(v.date_vente) = :mois AND YEAR(v.date_vente) = :annee";
            $params[':mois'] = $mois;
            $params[':annee'] = $annee;
        } elseif ($annee) {
            $conditions[] = "YEAR(v.date_vente) = :annee";
            $params[':annee'] = $annee;
        }

        $whereClause = count($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

        // Requête : ventes de produits
        $sql = "
            SELECT vdp.quantite, vdp.prix_unitaire, pa.prix AS prix_achat
            FROM vente v
            JOIN vente_draft vd ON vd.id_vente_draft = v.id_vente_draft
            JOIN vente_draft_produit vdp ON vdp.id_vente_draft = vd.id_vente_draft
            JOIN prix_achat_produit pa ON pa.id_produit = vdp.id_produit
            $whereClause
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $resultats = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $benefice = 0;

        foreach ($resultats as $row) {
            $vente_total = $row['quantite'] * $row['prix_unitaire'];
            $achat_total = $row['quantite'] * $row['prix_achat'];
            $benefice += ($vente_total - $achat_total);
        }

        return $benefice;
    }
}
