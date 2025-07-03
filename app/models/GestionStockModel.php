<?php
namespace app\models;

class GestionStockModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Get products with stock level below or equal to threshold
     * 
     * @param int $threshold Stock threshold
     * @return array
     */
    public function getLowStockProducts($threshold = 10) {
        $sql = "SELECT * FROM stock s JOIN produit p ON s.id_produit = p.id_produit WHERE quantite <= :threshold ORDER BY quantite ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':threshold' => $threshold]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
   public function getStockProducts() {
        $sql = "SELECT * FROM stock s JOIN produit p ON s.id_produit = p.id_produit ORDER BY quantite ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(); // No parameters to bind
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
