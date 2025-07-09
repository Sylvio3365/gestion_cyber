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

    public function voirFacture()
    {
        $date = $_GET['date'] ?? null;

        if ($date && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $ventes = $this->model->getVentesByUserAndDate(null, $date); // user ignoré
        } else {
            $ventes = $this->model->getVentesByUser(null);
        }

        if (empty($ventes)) {
            Flight::render(404, "Aucune facture trouvée");
            return;
        }

        foreach ($ventes as &$vente) {
            $vente['details'] = $this->model->getDetailsVente($vente['id_vente']);
        }

        Flight::render('index', [
            'page' => 'Facture/facture_liste',
            'ventes' => $ventes,
            'selected_date' => $date
        ]);
    }

    public function genererFacturePDF($id_vente)
    {
        require_once(__DIR__ . '/../../fpdf/fpdf.php'); // Assure-toi que fpdf est inclus

        $vente = $this->model->getVenteComplete($id_vente);
        $details = $this->model->getDetailsVente($id_vente);

        if (!$vente) {
            Flight::halt(404, "Facture introuvable");
        }

        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // === En-tête ===
        $pdf->SetTextColor(0, 123, 255); // Bleu clair
        $pdf->Cell(0, 10, 'FACTURE', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 10, 'Facture #' . $vente['id_vente'], 0, 1);
        $pdf->Cell(0, 10, 'Date : ' . $vente['date_vente'], 0, 1);
        $pdf->Ln(5);

        // === Fournisseur / Client ===
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(95, 10, 'Fournisseur', 0, 0);
        $pdf->Cell(95, 10, 'Client', 0, 1);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(95, 6, 'e-Cyber', 0, 0);
        $pdf->Cell(95, 6, $vente['client_prenom'] . ' ' . $vente['client_nom'], 0, 1);
        $pdf->Cell(95, 6, 'MB Andoharanofotsy', 0, 0);
        $pdf->Cell(95, 6, 'Email: ecyber@example.com', 0, 1);
        $pdf->Ln(10);

        // === Tableau des articles ===
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(0, 123, 255); // Bleu clair
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
        $pdf->SetFillColor(220, 230, 241); // Bleu très pâle pour total
        $pdf->SetTextColor(0);
        $pdf->Cell(150, 8, 'TOTAL', 1, 0, 'R', true);
        $pdf->Cell(40, 8, number_format($vente['total'], 0, ',', ' ') . ' Ar', 1, 1, 'R', true);

        // === Footer ===
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->SetTextColor(120, 120, 120);
        $pdf->MultiCell(0, 6, "Merci pour votre confiance.\nPour toute question, contactez-nous a ecyber@example.com", 0, 'C');

        $pdf->Output('I', 'facture_' . $id_vente . '.pdf');
    }
}
