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
     * @return array ['total' => float, 'par_branche' => array]
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

        // Bénéfice des produits
        $sqlProduits = "
    SELECT 
        b.nom AS branche,
        SUM(vdp.quantite * vdp.prix_unitaire) AS total_vente,
        (
            SELECT COALESCE(SUM(s.quantite * pa.prix), 0)
            FROM stock s
            JOIN produit p2 ON s.id_produit = p2.id_produit
            JOIN prix_achat_produit pa ON pa.id_produit = p2.id_produit
            WHERE 
                s.id_mouvement = (SELECT id_mouvement FROM type_mouvement WHERE type = 'entrée' LIMIT 1)
                AND MONTH(s.date_mouvement) = MONTH(v.date_vente)
                AND YEAR(s.date_mouvement) = YEAR(v.date_vente)
                AND p2.id_categorie = c.id_categorie
        ) AS total_achat
    FROM vente v
    JOIN vente_draft vd ON vd.id_vente_draft = v.id_vente_draft
    JOIN vente_draft_produit vdp ON vdp.id_vente_draft = vd.id_vente_draft
    JOIN produit p ON p.id_produit = vdp.id_produit
    JOIN categorie c ON c.id_categorie = p.id_categorie
    JOIN branche b ON b.id_branche = c.id_branche
    $whereClause
    GROUP BY b.nom
";


        // Bénéfice des services
        $sqlServices = "
    SELECT 
        b.nom AS branche,
        SUM(vds.quantite * vds.prix_unitaire) AS total_vente,
        (
            SELECT COALESCE(SUM(sp.quantite_par_service * s.quantite * pap.prix), 0)
            FROM service_produit sp
            JOIN produit pr ON sp.id_produit = pr.id_produit
            JOIN stock s ON s.id_produit = pr.id_produit
            JOIN prix_achat_produit pap ON pap.id_produit = pr.id_produit
            WHERE 
                sp.id_service = vds.id_service
                AND s.id_mouvement = (SELECT id_mouvement FROM type_mouvement WHERE type = 'entrée' LIMIT 1)
                AND MONTH(s.date_mouvement) = MONTH(v.date_vente)
                AND YEAR(s.date_mouvement) = YEAR(v.date_vente)
        ) AS total_achat
    FROM vente v
    JOIN vente_draft vd ON vd.id_vente_draft = v.id_vente_draft
    JOIN vente_draft_service vds ON vds.id_vente_draft = vd.id_vente_draft
    JOIN service s ON s.id_service = vds.id_service
    JOIN categorie c ON c.id_categorie = s.id_categorie
    JOIN branche b ON b.id_branche = c.id_branche
    $whereClause
    GROUP BY b.nom
";


        // Exécution des requêtes
        $beneficesProduits = $this->executerRequeteBenefice($sqlProduits, $params);
        $beneficesServices = $this->executerRequeteBenefice($sqlServices, $params);

        // Fusion des résultats
        $benefices = $this->fusionnerBenefices($beneficesProduits, $beneficesServices);

        // Calcul du total
        $total = array_sum(array_column($benefices, 'benefice'));

        return [
            'total' => $total,
            'par_branche' => $benefices
        ];
    }

    private function executerRequeteBenefice($sql, $params) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $resultats = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $benefices = [];
        foreach ($resultats as $row) {
            $benefice = floatval($row['total_vente']) - floatval($row['total_achat']);
            $benefices[] = [
                'branche' => $row['branche'],
                'vente' => floatval($row['total_vente']),
                'achat' => floatval($row['total_achat']),
                'benefice' => $benefice
            ];
        }

        return $benefices;
    }

    private function fusionnerBenefices($produits, $services) {
        $branches = [];

        // Traitement des produits
        foreach ($produits as $produit) {
            $branche = $produit['branche'];
            if (!isset($branches[$branche])) {
                $branches[$branche] = [
                    'vente' => 0,
                    'achat' => 0,
                    'benefice' => 0
                ];
            }
            $branches[$branche]['vente'] += $produit['vente'];
            $branches[$branche]['achat'] += $produit['achat'];
            $branches[$branche]['benefice'] += $produit['benefice'];
        }

        // Traitement des services
        foreach ($services as $service) {
            $branche = $service['branche'];
            if (!isset($branches[$branche])) {
                $branches[$branche] = [
                    'vente' => 0,
                    'achat' => 0,
                    'benefice' => 0
                ];
            }
            $branches[$branche]['vente'] += $service['vente'];
            $branches[$branche]['achat'] += $service['achat'];
            $branches[$branche]['benefice'] += $service['benefice'];
        }

        return $branches;
    }
}