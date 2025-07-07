<?php
namespace app\models;
class FactureModel
{
   private $db;

    public function __construct($db) {
        $this->db = $db;
    }

  
    public function getVenteById(int $id_vente): ?array
    {
        $sql = "
            SELECT  v.id_vente,
                    v.date_vente,
                    v.total,
                    c.nom     AS client_nom,
                    c.prenom  AS client_prenom
            FROM        vente          v
            INNER JOIN  vente_draft   vd ON vd.id_vente_draft = v.id_vente_draft
            INNER JOIN  client         c ON c.id_client       = vd.id_client
            WHERE v.id_vente = :id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_vente]);
        $vente = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $vente ?: null;
    }

    /**
     * Renvoie la liste des produits et services de la vente :
     *  [ ['nom'=>..., 'quantite'=>..., 'prix_unitaire'=>...], … ]
     */
    public function getDetailsVente(int $id_vente): array
    {
        $sql = "
            /* Produits */
            SELECT p.nom,
                   vdp.quantite,
                   vdp.prix_unitaire
            FROM vente_draft_produit vdp
            JOIN vente_draft        vd ON vd.id_vente_draft = vdp.id_vente_draft
            JOIN produit             p ON p.id_produit      = vdp.id_produit
            WHERE vd.id_vente_draft = (SELECT id_vente_draft FROM vente WHERE id_vente = :id)

            UNION ALL

            /* Services */
            SELECT s.nom,
                   vds.quantite,
                   vds.prix_unitaire
            FROM vente_draft_service vds
            JOIN vente_draft         vd ON vd.id_vente_draft = vds.id_vente_draft
            JOIN service              s ON s.id_service      = vds.id_service
            WHERE vd.id_vente_draft = (SELECT id_vente_draft FROM vente WHERE id_vente = :id)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_vente]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }
}
