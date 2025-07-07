<?php
namespace app\controllers;
require_once 'fpdf/fpdf.php';
use Flight;

class FactureController
{
    private $model;

    public function __construct() {
        $this->model = Flight::FactureModel();
    }

    public function genererFacturePDF($id_vente)
    {
        // --- 1. Vérification de l'ID
        if (!$id_vente || !is_numeric($id_vente)) {
            Flight::halt(400, "ID de vente invalide");
        }

        // --- 2. Récupérer les infos de la vente
        $stmt = $this->db->prepare("
            SELECT v.date_vente, v.total, c.nom AS client_nom, c.prenom AS client_prenom
            FROM vente v
            JOIN vente_draft vd ON v.id_vente_draft = vd.id_vente_draft
            JOIN client c ON vd.id_client = c.id_client
            WHERE v.id_vente = ?
        ");
        $stmt->execute([$id_vente]);
        $vente = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$vente) {
            Flight::halt(404, "Vente introuvable");
        }

        // --- 3. Produits + services
        $stmt = $this->db->prepare("
            SELECT p.nom AS nom, vdp.quantite, vdp.prix_unitaire
            FROM vente_draft_produit vdp
            JOIN produit p ON vdp.id_produit = p.id_produit
            WHERE vdp.id_vente_draft = (
                SELECT id_vente_draft FROM vente WHERE id_vente = ?
            )
            UNION ALL
            SELECT s.nom AS nom, vds.quantite, vds.prix_unitaire
            FROM vente_draft_service vds
            JOIN service s ON vds.id_service = s.id_service
            WHERE vds.id_vente_draft = (
                SELECT id_vente_draft FROM vente WHERE id_vente = ?
            )
        ");
        $stmt->execute([$id_vente, $id_vente]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // --- 4. Génération du PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);

        // En-tête
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Facture', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Client : ' . $vente['client_prenom'] . ' ' . $vente['client_nom'], 0, 1);
        $pdf->Cell(0, 10, 'Date : ' . $vente['date_vente'], 0, 1);
        $pdf->Ln(10);

        // Tableau
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(80, 10, 'Produit/Service', 1);
        $pdf->Cell(30, 10, 'Quantité', 1);
        $pdf->Cell(40, 10, 'Prix Unitaire', 1);
        $pdf->Cell(40, 10, 'Total', 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        foreach ($items as $item) {
            $totalLigne = $item['quantite'] * $item['prix_unitaire'];
            $pdf->Cell(80, 10, $item['nom'], 1);
            $pdf->Cell(30, 10, $item['quantite'], 1, 0, 'C');
            $pdf->Cell(40, 10, number_format($item['prix_unitaire'], 2), 1, 0, 'R');
            $pdf->Cell(40, 10, number_format($totalLigne, 2), 1, 0, 'R');
            $pdf->Ln();
        }

        // Total
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(150, 10, 'Total général', 1);
        $pdf->Cell(40, 10, number_format($vente['total'], 2), 1, 0, 'R');

        // --- 5. Envoi du PDF au navigateur
        $nomFichier = 'facture_' . $id_vente . '.pdf';
        $pdf->Output('I', $nomFichier);
    }
    public function voirFacture($id_vente)
{
    $vente = $this->getVenteById($id_vente); // récupère infos client/vente
    $items = $this->getDetailsVente($id_vente); // récupère produits/services

    if (!$vente) {
        Flight::halt(404, "Facture introuvable");
    }

    Flight::render('facture_view.php', [
        'vente' => $vente,
        'items' => $items,
    ]);
}

}
