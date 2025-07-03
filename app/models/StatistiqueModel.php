<?php
namespace app\models;

class StatistiqueModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getTopProduitsParBranche($branche, $dateDebut, $dateFin) {
        $sql = "
            SELECT p.nom AS produit, SUM(vdp.quantite) AS quantite_total
            FROM vente_draft vd
            JOIN vente_draft_produit vdp ON vd.id_vente_draft = vdp.id_vente_draft
            JOIN produit p ON p.id_produit = vdp.id_produit
            JOIN categorie c ON c.id_categorie = p.id_categorie
            JOIN branche b ON b.id_branche = c.id_branche
            WHERE b.nom = :branche
              AND vd.date_creation BETWEEN :date_debut AND :date_fin
            GROUP BY p.nom
            ORDER BY quantite_total DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':branche' => $branche,
            ':date_debut' => $dateDebut,
            ':date_fin' => $dateFin
        ]);
        return $stmt->fetchAll();
    }
    public function getTopProduitsGlobal($date_debut, $date_fin)
{
    $sql = "
        SELECT p.nom AS produit, SUM(vdp.quantite) AS quantite_total
        FROM vente_draft_produit vdp
        JOIN produit p ON vdp.id_produit = p.id_produit
        JOIN vente_draft vd ON vd.id_vente_draft = vdp.id_vente_draft
        WHERE vd.date_creation BETWEEN :date_debut AND :date_fin
        GROUP BY p.nom
        ORDER BY quantite_total DESC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        'date_debut' => $date_debut,
        'date_fin' => $date_fin
    ]);

    return $stmt->fetchAll();
}
public function getTousProduitsAvecQuantite($date_debut, $date_fin)
{
    $sql = "
        SELECT p.nom AS produit, 
               IFNULL(SUM(vdp.quantite), 0) AS quantite_total
        FROM produit p
        LEFT JOIN vente_draft_produit vdp ON vdp.id_produit = p.id_produit
        LEFT JOIN vente_draft vd ON vd.id_vente_draft = vdp.id_vente_draft
            AND vd.date_creation BETWEEN :date_debut AND :date_fin
        GROUP BY p.nom
        ORDER BY quantite_total DESC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        'date_debut' => $date_debut,
        'date_fin' => $date_fin
    ]);

    return $stmt->fetchAll();
}


// Dans ton modèle, méthode pour récupérer toutes les branches actives (non supprimées)
public function getAllBranches(): array
{
    $sql = "SELECT id_branche, nom FROM branche WHERE deleted_at IS NULL ORDER BY nom ASC";
    $stmt = $this->db->query($sql);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
}

// Fonction pour récupérer histogramme produits par branche et dates (avec jointures vente + vente_draft)
public function getHistogrammeTousProduitsParBranche(string $branche_id, string $date_debut, string $date_fin): array
{
    if (
        strtotime($date_debut) === false ||
        strtotime($date_fin) === false ||
        strtotime($date_debut) > strtotime($date_fin)
    ) {
        return [];
    }

    $sql = "
        SELECT p.nom AS produit, SUM(vdp.quantite) AS quantite_total
        FROM vente v
        INNER JOIN vente_draft vd ON vd.id_vente_draft = v.id_vente_draft
        INNER JOIN vente_draft_produit vdp ON vdp.id_vente_draft = vd.id_vente_draft
        INNER JOIN produit p ON p.id_produit = vdp.id_produit
        INNER JOIN categorie c ON c.id_categorie = p.id_categorie
        INNER JOIN branche b ON b.id_branche = c.id_branche
        WHERE b.id_branche = :branche_id
          AND vd.date_creation BETWEEN :date_debut AND :date_fin
        GROUP BY p.nom
        ORDER BY quantite_total DESC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':branche_id' => $branche_id,
        ':date_debut' => $date_debut,
        ':date_fin'   => $date_fin,
    ]);

    return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
}




}
