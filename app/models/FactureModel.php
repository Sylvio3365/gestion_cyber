<?php
namespace app\models;

use PDO;

class FactureModel
{
    private PDO $pdo;
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

 public function getVentesByUser($id_user)
{
    $sql = "
        SELECT v.id_vente, v.date_vente, v.total,
               c.nom AS client_nom, c.prenom AS client_prenom
        FROM vente v
        JOIN vente_draft vd ON v.id_vente_draft = vd.id_vente_draft
        JOIN client c ON vd.id_client = c.id_client
        WHERE c.id_client = :id_user
        ORDER BY v.date_vente DESC
    ";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['id_user' => $id_user]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



 public function getVenteComplete($id_vente)
{
    $sql = "
        SELECT v.*, c.nom AS client_nom, c.prenom AS client_prenom
        FROM vente v
        JOIN vente_draft vd ON vd.id_vente_draft = v.id_vente_draft
        JOIN client c ON c.id_client = vd.id_client
        WHERE v.id_vente = :id
    ";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['id' => $id_vente]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function getDetailsVente($id_vente)
{
    $sql = "
        SELECT p.nom, vdp.quantite, vdp.prix_unitaire
        FROM vente v
        JOIN vente_draft vd ON v.id_vente_draft = vd.id_vente_draft
        JOIN vente_draft_produit vdp ON vdp.id_vente_draft = vd.id_vente_draft
        JOIN produit p ON p.id_produit = vdp.id_produit
        WHERE v.id_vente = :id

        UNION ALL

        SELECT s.nom, vds.quantite, vds.prix_unitaire
        FROM vente v
        JOIN vente_draft vd ON v.id_vente_draft = vd.id_vente_draft
        JOIN vente_draft_service vds ON vds.id_vente_draft = vd.id_vente_draft
        JOIN service s ON s.id_service = vds.id_service
        WHERE v.id_vente = :id
    ";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['id' => $id_vente]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
