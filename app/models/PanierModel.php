<?php

namespace app\models;

class PanierModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function creerPanier($id_user, $id_client = null)
    {
        $sql = "INSERT INTO vente_draft (date_creation, id_user, id_client) VALUES (NOW(), ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_user, $id_client]);
        return $this->db->lastInsertId();
    }

    public function updateClientPanier($id_vente_draft, $id_client)
    {
        $sql = "UPDATE vente_draft SET id_client = ? WHERE id_vente_draft = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_client, $id_vente_draft]);
    }

    public function ajouterProduitPanier($id_vente_draft, $id_produit, $quantite, $prix_unitaire)
    {
        $sql = "SELECT id_vente_draft_produit, quantite FROM vente_draft_produit 
                WHERE id_vente_draft = ? AND id_produit = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_vente_draft, $id_produit]);
        $existing = $stmt->fetch();

        if ($existing) {
            $nouvelle_quantite = $existing['quantite'] + $quantite;
            $sql = "UPDATE vente_draft_produit SET quantite = ? WHERE id_vente_draft_produit = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$nouvelle_quantite, $existing['id_vente_draft_produit']]);
        } else {
            $sql = "INSERT INTO vente_draft_produit (quantite, prix_unitaire, id_vente_draft, id_produit) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$quantite, $prix_unitaire, $id_vente_draft, $id_produit]);
        }
    }

    public function ajouterServicePanier($id_vente_draft, $id_service, $quantite, $prix_unitaire)
    {
        $sql = "SELECT id_vente_draft_service, quantite FROM vente_draft_service 
                WHERE id_vente_draft = ? AND id_service = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_vente_draft, $id_service]);
        $existing = $stmt->fetch();

        if ($existing) {
            $nouvelle_quantite = $existing['quantite'] + $quantite;
            $sql = "UPDATE vente_draft_service SET quantite = ? WHERE id_vente_draft_service = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$nouvelle_quantite, $existing['id_vente_draft_service']]);
        } else {
            $sql = "INSERT INTO vente_draft_service (quantite, prix_unitaire, id_service, id_vente_draft) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$quantite, $prix_unitaire, $id_service, $id_vente_draft]);
        }
    }

    public function getPanier($id_vente_draft)
    {
        $sql_produits = "SELECT vdp.*, p.nom as produit_nom, p.id_produit
                        FROM vente_draft_produit vdp
                        JOIN produit p ON vdp.id_produit = p.id_produit
                        WHERE vdp.id_vente_draft = ?";
        $stmt = $this->db->prepare($sql_produits);
        $stmt->execute([$id_vente_draft]);
        $produits = $stmt->fetchAll();

        $sql_services = "SELECT vds.*, s.nom as service_nom, s.id_service
                        FROM vente_draft_service vds
                        JOIN service s ON vds.id_service = s.id_service
                        WHERE vds.id_vente_draft = ?";
        $stmt = $this->db->prepare($sql_services);
        $stmt->execute([$id_vente_draft]);
        $services = $stmt->fetchAll();

        return [
            'produits' => $produits,
            'services' => $services
        ];
    }

    public function modifierQuantiteProduit($id_vente_draft_produit, $nouvelle_quantite)
    {
        if ($nouvelle_quantite <= 0) {
            return $this->supprimerProduitPanier($id_vente_draft_produit);
        }

        $sql = "UPDATE vente_draft_produit SET quantite = ? WHERE id_vente_draft_produit = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nouvelle_quantite, $id_vente_draft_produit]);
    }

    public function modifierQuantiteService($id_vente_draft_service, $nouvelle_quantite)
    {
        if ($nouvelle_quantite <= 0) {
            return $this->supprimerServicePanier($id_vente_draft_service);
        }

        $sql = "UPDATE vente_draft_service SET quantite = ? WHERE id_vente_draft_service = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nouvelle_quantite, $id_vente_draft_service]);
    }

    public function supprimerProduitPanier($id_vente_draft_produit)
    {
        $sql = "DELETE FROM vente_draft_produit WHERE id_vente_draft_produit = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_vente_draft_produit]);
    }

    public function supprimerServicePanier($id_vente_draft_service)
    {
        $sql = "DELETE FROM vente_draft_service WHERE id_vente_draft_service = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_vente_draft_service]);
    }

    public function calculerTotal($id_vente_draft)
    {
        $sql_produits = "SELECT SUM(quantite * prix_unitaire) as total_produits 
                        FROM vente_draft_produit WHERE id_vente_draft = ?";
        $stmt = $this->db->prepare($sql_produits);
        $stmt->execute([$id_vente_draft]);
        $total_produits = $stmt->fetchColumn() ?: 0;

        $sql_services = "SELECT SUM(quantite * prix_unitaire) as total_services 
                        FROM vente_draft_service WHERE id_vente_draft = ?";
        $stmt = $this->db->prepare($sql_services);
        $stmt->execute([$id_vente_draft]);
        $total_services = $stmt->fetchColumn() ?: 0;

        return $total_produits + $total_services;
    }

    public function validerVente($id_vente_draft, $argent_donne, $id_type_paiement)
    {
        $total = $this->calculerTotal($id_vente_draft);

        // Vérification si l'argent donné est suffisant
        if ($argent_donne < $total) {
            throw new \Exception("L'argent donné est insuffisant. Total à payer : $total Ar, argent donné : $argent_donne Ar.");
        }

        // Créer la vente
        $sql = "INSERT INTO vente (date_vente, total, argent_donner, id_type_de_payement, id_vente_draft) 
                VALUES (NOW(), ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$total, $argent_donne, $id_type_paiement, $id_vente_draft]);
        $id_vente = $this->db->lastInsertId();

        // Gérer le stock pour les produits
        $this->gererStockProduits($id_vente_draft);

        // Gérer le stock pour les services (consommation de produits)
        $this->gererStockServices($id_vente_draft);

        return $id_vente;
    }

    private function gererStockProduits($id_vente_draft)
    {
        $sql = "SELECT id_produit, quantite FROM vente_draft_produit WHERE id_vente_draft = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_vente_draft]);
        $produits = $stmt->fetchAll();

        $id_mouvement_sortie = 2;

        foreach ($produits as $produit) {
            $sql_stock = "INSERT INTO stock (quantite, date_mouvement, id_produit, id_mouvement) 
                         VALUES (?, NOW(), ?, ?)";
            $stmt_stock = $this->db->prepare($sql_stock);
            $stmt_stock->execute([-abs($produit['quantite']), $produit['id_produit'], $id_mouvement_sortie]);
        }
    }

    private function gererStockServices($id_vente_draft)
    {
        $sql = "SELECT id_service, quantite FROM vente_draft_service WHERE id_vente_draft = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_vente_draft]);
        $services = $stmt->fetchAll();

        $id_mouvement_sortie = 2;

        foreach ($services as $service) {
            $produits_consommes = $this->getProduitConsommeParService($service['id_service']);

            foreach ($produits_consommes as $produit_consomme) {
                $quantite_consommee = $service['quantite'] * $produit_consomme['quantite_par_service'];

                $sql_stock = "INSERT INTO stock (quantite, date_mouvement, id_produit, id_mouvement) 
                             VALUES (?, NOW(), ?, ?)";
                $stmt_stock = $this->db->prepare($sql_stock);
                $stmt_stock->execute([-abs($quantite_consommee), $produit_consomme['id_produit'], $id_mouvement_sortie]);
            }
        }
    }

    public function getProduitConsommeParService($id_service)
    {
        $sql = "SELECT sp.id_produit, sp.quantite_par_service, p.nom
                FROM service_produit sp
                JOIN produit p ON sp.id_produit = p.id_produit
                WHERE sp.id_service = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_service]);
        return $stmt->fetchAll();
    }

    public function getProduits()
    {
        $mois = date('n');
        $annee = date('Y');

        $sql = "SELECT p.*, 
                COALESCE(
                    (SELECT pp1.prix FROM prix_produit pp1 
                        WHERE pp1.id_produit = p.id_produit 
                        AND pp1.mois = ? 
                        AND pp1.annee = ?
                        ORDER BY pp1.date_modification DESC LIMIT 1
                    ),
                    (SELECT pp2.prix FROM prix_produit pp2 
                        WHERE pp2.id_produit = p.id_produit 
                        ORDER BY pp2.date_modification DESC LIMIT 1
                    )
                ) AS prix,
                (SELECT SUM(s.quantite) FROM stock s WHERE s.id_produit = p.id_produit) AS stock
            FROM produit p
            WHERE p.deleted_at IS NULL
            ORDER BY p.nom";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$mois, $annee]);
        return $stmt->fetchAll();
    }

    public function getServices()
    {
        $mois = date('n');
        $annee = date('Y');

        $sql = "SELECT s.*, 
                COALESCE(
                    (SELECT ps1.prix FROM prix_service ps1 
                        WHERE ps1.id_service = s.id_service 
                        AND ps1.mois = ? 
                        AND ps1.annee = ?
                        ORDER BY ps1.date_modification DESC LIMIT 1
                    ),
                    (SELECT ps2.prix FROM prix_service ps2 
                        WHERE ps2.id_service = s.id_service 
                        ORDER BY ps2.date_modification DESC LIMIT 1
                    )
                ) AS prix
            FROM service s
            WHERE s.deleted_at IS NULL 
            AND s.nom != 'connexion'
            ORDER BY s.nom";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$mois, $annee]);
        return $stmt->fetchAll();
    }

    public function getProduitsEtServicesParBranche()
    {
        $sql = "SELECT * FROM vue_produits_services_branche ORDER BY nom_branche, type, nom";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }


    public function getTypesPaiement()
    {
        $sql = "SELECT * FROM type_de_payement";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getTypePaiementById($id)
    {
        $sql = "SELECT * FROM type_de_payement WHERE id_type_de_payement = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public function getClientById($id)
    {
        $sql = "SELECT * FROM client WHERE id_client = ? AND deleted_at IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function ajouterClient($nom, $prenom)
    {
        $sql = "INSERT INTO client (nom, prenom, added_at) VALUES (?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nom, $prenom]);
        return $this->db->lastInsertId();
    }

    public function rechercherClients($terme = '')
    {
        $sql = "SELECT id_client, nom, prenom FROM client 
                WHERE (nom LIKE ? OR prenom LIKE ?) AND deleted_at IS NULL 
                ORDER BY nom, prenom LIMIT 10";
        $stmt = $this->db->prepare($sql);
        $terme_like = '%' . $terme . '%';
        $stmt->execute([$terme_like, $terme_like]);
        return $stmt->fetchAll();
    }

    public function getProduitById($id_produit)
    {
        $sql = "SELECT * FROM produit WHERE id_produit = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_produit]);
        return $stmt->fetch();
    }

    public function getServiceById($id_service)
    {
        $sql = "SELECT * FROM service WHERE id_service = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_service]);
        return $stmt->fetch();
    }
}
