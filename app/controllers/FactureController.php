<?php
namespace app\controllers;

require_once __DIR__ . '/../../fpdf/fpdf.php'; // ✅ adapte si besoin

use FPDF;
use Flight;

class FactureController
{
    private $model;

    public function __construct()
    {
        $this->model = Flight::factureModel();
    }

    // === Affichage de la facture à l'écran ===
 public function voirFacture($id_user)
{
    $date = $_GET['date'] ?? null;

    if ($date) {
        $ventes = $this->model->getVentesByUserAndDate($id_user, $date);
    } else {
        $ventes = $this->model->getVentesByUser($id_user);
    }

    if (empty($ventes)) {
        Flight::halt(404, "Aucune facture trouvée");
        return;
    }

    // Pour chaque vente, ajouter les détails produits/services
    foreach ($ventes as &$vente) {
        $vente['details'] = $this->model->getDetailsVente($vente['id_vente']);
    }

    Flight::render('Facture/facture_liste.php', [
        'ventes' => $ventes,
        'selected_date' => $date // pour réafficher la date sélectionnée dans le formulaire
    ]);
}







    // === Export PDF ===
 public function genererFacturePDF($id_vente)
{
    require_once(__DIR__ . '/../../fpdf/fpdf.php'); // Assure-toi que fpdf est inclus

    $vente = $this->model->getVenteComplete($id_vente); // données de la vente (client, date, total)
    $details = $this->model->getDetailsVente($id_vente); // produits/services

    if (!$vente) {
        Flight::halt(404, "Facture introuvable");
    }

    $pdf = new \FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // === En-tête ===
    $pdf->SetTextColor(106, 17, 203); // Violet
    $pdf->Cell(0, 10, 'FACTURE', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0, 10, 'Facture #' . $vente['id_vente'], 0, 1);
    $pdf->Cell(0, 10, 'Date : ' . $vente['date_vente'], 0, 1);
    $pdf->Ln(5);

    // === Fournisseur ===
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(95, 10, 'Fournisseur', 0, 0);
    $pdf->Cell(95, 10, 'Client', 0, 1);
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(95, 6, 'I-Cyber', 0, 0);
    $pdf->Cell(95, 6, $vente['client_prenom'] . ' ' . $vente['client_nom'], 0, 1);
    $pdf->Cell(95, 6, 'MB Andoharanofotsy', 0, 0);
    $pdf->Cell(95, 6, 'Email: I-cyber@example.com', 0, 1);
    $pdf->Ln(10);

    // === Tableau des produits/services ===
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(106, 17, 203);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(80, 10, 'Description', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Quantite', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Prix Unitaire', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Total', 1, 1, 'C', true);

    $pdf->SetFont('Arial', '', 11);
    $pdf->SetTextColor(0, 0, 0);
    foreach ($details as $item) {
        $pdf->Cell(80, 8, $item['nom'], 1);
        $pdf->Cell(30, 8, $item['quantite'], 1, 0, 'C');
        $pdf->Cell(40, 8, number_format($item['prix_unitaire'], 0, ',', ' ') . ' Ar', 1, 0, 'R');
        $pdf->Cell(40, 8, number_format($item['quantite'] * $item['prix_unitaire'], 0, ',', ' ') . ' Ar', 1, 1, 'R');
    }

    // === Totaux ===
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(150, 8, 'TOTAL', 1);
    $pdf->Cell(40, 8, number_format($vente['total'], 0, ',', ' ') . ' Ar', 1, 1, 'R');

    // === Footer ===
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->SetTextColor(120, 120, 120);
    $pdf->MultiCell(0, 6, "Merci pour votre confiance.\nPour toute question, contactez-nous à support@votreentreprise.com", 0, 'C');

    $pdf->Output('I', 'facture_' . $id_vente . '.pdf');
}


}
