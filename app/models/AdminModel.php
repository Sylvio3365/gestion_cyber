<?php

namespace app\models;

use Flight;


class AdminModel
{

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
    // --- PRIX ACHAT SERVICE ---

    public function getAllPrixAchatServices()
    {
        $sql = "SELECT * FROM prix_achat_service ORDER BY annee, mois";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function insertPrixAchatService($mois, $annee, $prix, $etat, $id_service)
    {
        $sql = "INSERT INTO prix_achat_service (mois, date_modification, annee, prix, etat, id_service)
                VALUES (:mois, NOW(), :annee, :prix, :etat, :id_service)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'mois' => $mois,
            'annee' => $annee,
            'prix' => $prix,
            'etat' => $etat,
            'id_service' => $id_service
        ]);
    }

    // --- PRIX ACHAT PRODUIT ---

    public function getAllPrixAchatProduits()
    {
        $sql = "SELECT * FROM prix_achat_produit ORDER BY annee, mois";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function insertPrixAchatProduit($mois, $annee, $prix, $etat, $id_produit)
    {
        $sql = "INSERT INTO prix_achat_produit (mois, annee, date_modification, prix, etat, id_produit)
                VALUES (:mois, :annee, NOW(), :prix, :etat, :id_produit)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'mois' => $mois,
            'annee' => $annee,
            'prix' => $prix,
            'etat' => $etat,
            'id_produit' => $id_produit
        ]);
    }

    public function getAllPrixServices()
    {
        $sql = "SELECT * FROM prix_service ORDER BY annee DESC, mois ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getAllPrixProduits()
    {
        $sql = "SELECT * FROM prix_produit ORDER BY annee DESC, mois ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function insertPrixService($prix, $mois, $annee, $description, $id_service)
    {
        $sql = "INSERT INTO prix_service (date_modification, prix, mois, annee, description, id_service)
            VALUES (NOW(), :prix, :mois, :annee, :description, :id_service)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'prix' => $prix,
            'mois' => $mois,
            'annee' => $annee,
            'description' => $description,
            'id_service' => $id_service
        ]);
    }

    public function insertPrixProduit($prix, $mois, $annee, $description, $id_produit)
    {
        $sql = "INSERT INTO prix_produit (date_modification, prix, mois, annee, description, id_produit)
            VALUES (NOW(), :prix, :mois, :annee, :description, :id_produit)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'prix' => $prix,
            'mois' => $mois,
            'annee' => $annee,
            'description' => $description,
            'id_produit' => $id_produit
        ]);
    }

    public function getAllBranches()
    {
        $sql = "SELECT * FROM branche WHERE deleted_at IS NULL";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    public function addBranch($nom, $description = null)
    {
        $sql = "INSERT INTO branche (nom, description) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nom, $description]);
    }
    public function updateBranch($id, $nom, $description = null)
    {
        $sql = "UPDATE branche SET nom = ?, description = ? WHERE id_branche = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nom, $description, $id]);
    }
    public function deleteBranch($id)
    {
        $sql = "UPDATE branche SET deleted_at = NOW() WHERE id_branche = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    //marques
    public function getAllMarques()
    {
        $sql = "SELECT * FROM marque WHERE deleted_at IS NULL ORDER BY id_marque DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    public function addMarque($nom)
    {
        $sql = "INSERT INTO marque (nom) VALUES (?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nom]);
    }
    public function updateMarque($id, $nom)
    {
        $sql = "UPDATE marque SET nom = ? WHERE id_marque = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nom, $id]);
    }

    public function deleteMarque($id)
    {
        $sql = "UPDATE marque SET deleted_at = NOW() WHERE id_marque = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    //categorie
    public function getAllCategories()
    {
        $sql = "SELECT c.*, b.nom AS branche_nom
                FROM categorie c
                JOIN branche b ON c.id_branche = b.id_branche";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function addCategorie($nom, $id_branche)
    {
        $sql = "INSERT INTO categorie (nom, id_branche) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nom, $id_branche]);
    }

    public function updateCategorie($id, $nom, $id_branche)
    {
        $sql = "UPDATE categorie SET nom = ?, id_branche = ? WHERE id_categorie = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nom, $id_branche, $id]);
    }

    public function deleteCategorie($id)
    {
        $sql = "DELETE FROM categorie WHERE id_categorie = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }



    //produit
    public function getAllProduits()
    {
        $sql = "SELECT p.*, m.nom AS marque_nom, c.nom AS categorie_nom
                FROM produit p
                JOIN marque m ON p.id_marque = m.id_marque
                JOIN categorie c ON p.id_categorie = c.id_categorie
                WHERE p.deleted_at IS NULL";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function addProduit($nom, $description, $id_marque, $id_categorie)
    {
        $sql = "INSERT INTO produit (nom, description, id_marque, id_categorie) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nom, $description, $id_marque, $id_categorie]);
    }

    public function updateProduit($id, $nom, $description, $id_marque, $id_categorie)
    {
        $sql = "UPDATE produit SET nom = ?, description = ?, id_marque = ?, id_categorie = ? WHERE id_produit = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nom, $description, $id_marque, $id_categorie, $id]);
    }

    public function deleteProduit($id)
    {
        $sql = "UPDATE produit SET deleted_at = NOW() WHERE id_produit = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    //service
    public function getAllServices()
    {
        $sql = "SELECT s.*, c.nom as categorie_nom FROM service s 
                JOIN categorie c ON s.id_categorie = c.id_categorie
                WHERE s.deleted_at IS NULL";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function addService($nom, $description, $id_categorie)
    {
        $sql = "INSERT INTO service (nom, description, id_categorie) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nom, $description, $id_categorie]);
    }

    public function updateService($id, $nom, $description, $id_categorie)
    {
        $sql = "UPDATE service SET nom = ?, description = ?, id_categorie = ? WHERE id_service = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nom, $description, $id_categorie, $id]);
    }

    public function deleteService($id)
    {
        $sql = "UPDATE service SET deleted_at = NOW() WHERE id_service = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getStockRestantParProduit()
    {
        $sql = "SELECT 
                    p.id_produit,
                    p.nom AS produit_nom,
                    SUM(CASE WHEN t.type = 'entrée' THEN s.quantite ELSE 0 END) -
                    SUM(CASE WHEN t.type = 'sortie' THEN s.quantite ELSE 0 END) AS stock_restant
                FROM stock s
                JOIN produit p ON s.id_produit = p.id_produit
                JOIN type_mouvement t ON s.id_mouvement = t.id_mouvement
                GROUP BY p.id_produit, p.nom
                ORDER BY p.nom ASC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    //stock
    public function getAllStocks()
    {
        $sql = "SELECT s.*, p.nom AS produit_nom, t.type AS type_mouvement 
                FROM stock s
                JOIN produit p ON s.id_produit = p.id_produit
                JOIN type_mouvement t ON s.id_mouvement = t.id_mouvement
                ORDER BY s.date_mouvement DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }


    public function getAllTypesMouvement()
    {
        $sql = "SELECT id_mouvement, type FROM type_mouvement";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function addStock($id_produit, $id_mouvement, $quantite)
    {
        $sql = "INSERT INTO stock (id_produit, id_mouvement, quantite, date_mouvement) 
                VALUES (?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_produit, $id_mouvement, $quantite]);
    }

    public function deleteStock($id)
    {
        $sql = "DELETE FROM stock WHERE id_stock = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    //type_mouvement
    public function getAllTypeMouvements()
    {
        $sql = "SELECT * FROM type_mouvement";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function addTypeMouvement($type)
    {
        $sql = "INSERT INTO type_mouvement (type) VALUES (?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$type]);
    }

    public function updateTypeMouvement($id, $type)
    {
        $sql = "UPDATE type_mouvement SET type = ? WHERE id_mouvement = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$type, $id]);
    }

    public function deleteTypeMouvement($id)
    {
        $sql = "DELETE FROM type_mouvement WHERE id_mouvement = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
