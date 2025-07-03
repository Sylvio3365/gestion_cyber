<?php
namespace app\models;

class StatRecetteModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Get sales statistics by type and period
     * 
     * @param string $type (produit, multi, connexion)
     * @param string $period (jour, semaine, mois, annee)
     * @param string $date Reference date
     * @return array
     */
    public function getStats($type, $period, $date) {
        // Determine base query based on type
        switch ($type) {
            case 'produit':
                $query = "
                    SELECT 
                        vdp.id_produit,
                        p.nom AS produit_nom,
                        vdp.prix_unitaire,
                        SUM(vdp.quantite) AS total_quantite,
                        SUM(vdp.quantite * vdp.prix_unitaire) AS chiffre_affaires
                    FROM vente_draft_produit vdp
                    JOIN vente_draft vd ON vdp.id_vente_draft = vd.id_vente_draft
                    JOIN vente v ON vd.id_vente_draft = v.id_vente_draft
                    JOIN produit p ON vdp.id_produit = p.id_produit
                ";
                break;
                
            case 'multi':
                $query = "
                    SELECT
                        s.id_service,
                        s.nom AS service_nom,
                        c.nom AS categorie_nom,
                        SUM(vdp.quantite) AS total_quantite,
                        SUM(vdp.quantite * vdp.prix_unitaire) AS chiffre_affaires
                    FROM vente_draft_service vdp
                    JOIN vente_draft vd ON vdp.id_vente_draft = vd.id_vente_draft
                    JOIN vente v ON vd.id_vente_draft = v.id_vente_draft
                    JOIN service s ON vdp.id_service = s.id_service
                    JOIN categorie c ON s.id_categorie = c.id_categorie
                    JOIN branche b ON b.id_branche = c.id_branche
                    WHERE b.nom != 'Connexion'
                ";
                break;
                
            case 'connexion':
                $query = "
                    SELECT
                        s.id_service,
                        s.nom AS service_nom,
                        c.nom AS categorie_nom,
                        SUM(vdp.quantite) AS total_quantite,
                        SUM(vdp.quantite * vdp.prix_unitaire) AS chiffre_affaires
                    FROM vente_draft_service vdp
                    JOIN vente_draft vd ON vdp.id_vente_draft = vd.id_vente_draft
                    JOIN vente v ON vd.id_vente_draft = v.id_vente_draft
                    JOIN service s ON vdp.id_service = s.id_service
                    JOIN categorie c ON s.id_categorie = c.id_categorie
                    JOIN branche b ON b.id_branche = c.id_branche
                    WHERE b.nom = 'Connexion'
                ";
                break;
                
            default:
                throw new \InvalidArgumentException("Type de statistique invalide");
        }
        
        // Add period condition
        switch ($period) {
            case 'jour':
                $query .= " AND DATE(v.date_vente) = :date";
                $params = [':date' => $date];
                break;
                
            case 'semaine':
                $query .= " AND YEARWEEK(v.date_vente) = YEARWEEK(:date)";
                $params = [':date' => $date];
                break;
                
            case 'mois':
                $query .= " AND MONTH(v.date_vente) = MONTH(:date) AND YEAR(v.date_vente) = YEAR(:date)";
                $params = [':date' => $date];
                break;
                
            case 'annee':
                $query .= " AND YEAR(v.date_vente) = YEAR(:date)";
                $params = [':date' => $date];
                break;
                
            default:
                throw new \InvalidArgumentException("Période invalide");
        }
        
        // Add grouping and ordering
        if ($type === 'produit') {
            $query .= " GROUP BY vdp.id_produit ORDER BY total_quantite DESC";
        } else {
            $query .= " GROUP BY s.id_service ORDER BY total_quantite DESC";
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Get aggregated stats by period
     */
    public function getAggregatedStats($type, $period, $date) {
        // Determine select based on period
        switch ($period) {
            case 'jour':
                $select = "DATE(v.date_vente) AS periode";
                break;
            case 'semaine':
                $select = "CONCAT(YEAR(v.date_vente), '-Semaine ', WEEK(v.date_vente)) AS periode";
                break;
            case 'mois':
                $select = "DATE_FORMAT(v.date_vente, '%Y-%m') AS periode";
                break;
            case 'annee':
                $select = "YEAR(v.date_vente) AS periode";
                break;
            default:
                throw new \InvalidArgumentException("Période invalide");
        }
        
        // Determine base query based on type
        switch ($type) {
            case 'produit':
                $query = "
                    SELECT 
                        $select,
                        SUM(vdp.quantite) AS total_quantite,
                        SUM(vdp.quantite * vdp.prix_unitaire) AS chiffre_affaires
                    FROM vente_draft_produit vdp
                    JOIN vente_draft vd ON vdp.id_vente_draft = vd.id_vente_draft
                    JOIN vente v ON vd.id_vente_draft = v.id_vente_draft
                ";
                break;
                
            case 'multi':
                $query = "
                    SELECT
                        $select,
                        SUM(vdp.quantite) AS total_quantite,
                        SUM(vdp.quantite * vdp.prix_unitaire) AS chiffre_affaires
                    FROM vente_draft_service vdp
                    JOIN vente_draft vd ON vdp.id_vente_draft = vd.id_vente_draft
                    JOIN vente v ON vd.id_vente_draft = v.id_vente_draft
                    JOIN service s ON vdp.id_service = s.id_service
                    JOIN categorie c ON s.id_categorie = c.id_categorie
                    JOIN branche b ON b.id_branche = c.id_branche
                    WHERE b.nom != 'Connexion'
                ";
                break;
                
            case 'connexion':
                $query = "
                    SELECT
                        $select,
                        SUM(vdp.quantite) AS total_quantite,
                        SUM(vdp.quantite * vdp.prix_unitaire) AS chiffre_affaires
                    FROM vente_draft_service vdp
                    JOIN vente_draft vd ON vdp.id_vente_draft = vd.id_vente_draft
                    JOIN vente v ON vd.id_vente_draft = v.id_vente_draft
                    JOIN service s ON vdp.id_service = s.id_service
                    JOIN categorie c ON s.id_categorie = c.id_categorie
                    JOIN branche b ON b.id_branche = c.id_branche
                    WHERE b.nom = 'Connexion'
                ";
                break;
                
            default:
                throw new \InvalidArgumentException("Type de statistique invalide");
        }
        
        // Add period condition
        switch ($period) {
            case 'jour':
                $query .= " AND DATE(v.date_vente) = :date";
                $params = [':date' => $date];
                break;
                
            case 'semaine':
                $query .= " AND YEARWEEK(v.date_vente) = YEARWEEK(:date)";
                $params = [':date' => $date];
                break;
                
            case 'mois':
                $query .= " AND MONTH(v.date_vente) = MONTH(:date) AND YEAR(v.date_vente) = YEAR(:date)";
                $params = [':date' => $date];
                break;
                
            case 'annee':
                $query .= " AND YEAR(v.date_vente) = YEAR(:date)";
                $params = [':date' => $date];
                break;
        }
        
        $query .= " GROUP BY periode";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}