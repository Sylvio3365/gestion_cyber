<?php

class ClientModel {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAchatsProduits($id_client) {
        $sql = "
            SELECT 
                c.id_client,
                CONCAT(c.nom, ' ', c.prenom) AS nom_client,
                v.date_vente AS date_mouvement,
                p.nom AS nom_produit,
                vdp.quantite,
                vdp.prix_unitaire,
                v.total
            FROM vente v
            JOIN vente_draft vd ON v.id_vente_draft = vd.id_vente_draft
            JOIN vente_draft_produit vdp ON vd.id_vente_draft = vdp.id_vente_draft
            JOIN produit p ON vdp.id_produit = p.id_produit
            JOIN client c ON vd.id_client = c.id_client
            WHERE vd.id_client = ?
            ORDER BY date_mouvement DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_client]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAchatsServices($id_client) {
        $sql = "
            SELECT 
                c.id_client,
                CONCAT(c.nom, ' ', c.prenom) AS nom_client,
                v.date_vente AS date_mouvement,
                s.nom AS nom_service,
                vds.quantite,
                vds.prix_unitaire,
                v.total
            FROM vente v
            JOIN vente_draft vd ON v.id_vente_draft = vd.id_vente_draft
            JOIN vente_draft_service vds ON vd.id_vente_draft = vds.id_vente_draft
            JOIN service s ON vds.id_service = s.id_service
            JOIN client c ON vd.id_client = c.id_client
            WHERE vd.id_client = ?
            ORDER BY date_mouvement DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_client]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getConnexions($id_client) {
        $sql = "
            SELECT 
                c.id_client,
                CONCAT(c.nom, ' ', c.prenom) AS nom_client,
                hc.date_debut AS date_mouvement,
                hc.date_fin,
                p.numero_poste
            FROM historique_connexion hc
            LEFT JOIN poste p ON hc.id_poste = p.id_poste
            JOIN client c ON hc.id_client = c.id_client
            WHERE hc.id_client = ?
            ORDER BY date_mouvement DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_client]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
