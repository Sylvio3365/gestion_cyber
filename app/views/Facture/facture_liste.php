<?php if (empty($ventes)): ?>
    <div class="alert alert-info text-center mt-5">Aucune facture disponible.</div>
<?php else: ?>
    <style>
        /* Styles de base pour le PDF */
        .invoice-pdf-container {
            width: 210mm; /* Largeur A4 */
            margin: 0 auto;
            padding: 15mm; /* Marges intérieures */
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            background: #ffffff;
            color: #333;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .invoice-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            border-radius: 8px 8px 0 0;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .invoice-title {
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }
        
        .invoice-number {
            opacity: 0.9;
            font-size: 0.9rem;
        }
        
        /* Sections principales */
        .invoice-section {
            margin-bottom: 1.5rem;
        }
        
        /* Informations client/fournisseur */
        .info-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
        }
        
        .info-box {
            width: 48%;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .info-title {
            font-weight: 600;
            color: #6a11cb;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }
        
        /* Tableau des articles */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }
        
        .invoice-table thead th {
            background: #6a11cb;
            color: white;
            padding: 0.75rem;
            text-align: left;
        }
        
        .invoice-table tbody td {
            padding: 0.75rem;
            border-bottom: 1px solid #e9ecef;
            vertical-align: top;
        }
        
        .invoice-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        /* Totaux */
        .total-container {
            width: 50%;
            margin-left: auto;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
        }
        
        .grand-total {
            font-weight: bold;
            font-size: 1.1rem;
            border-top: 2px solid #6a11cb;
            margin-top: 0.5rem;
            padding-top: 0.5rem;
        }
        
        /* Pied de page */
        .invoice-footer {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
            text-align: center;
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        /* Boutons d'action (visible seulement à l'écran) */
        .action-buttons {
            display: none;
        }
        
        @media screen {
            .invoice-pdf-container {
                margin: 2rem auto;
                box-shadow: 0 0 20px rgba(0,0,0,0.1);
            }
            
            .action-buttons {
                display: flex;
                justify-content: center;
                gap: 1rem;
                margin-top: 2rem;
            }
        }
    </style>
    
    <div class="container my-5">
        <center><h2 class="mb-4 fw-bold text-primary text-center">Historique des Factures</h2></center>
        
        <?php foreach ($ventes as $vente): ?>
            <div class="d-flex justify-content-center">
                <div class="invoice-pdf-container">
                    <!-- En-tête -->
                    <div class="invoice-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="invoice-title">FACTURE</h1>
                                <div class="invoice-number">N° <?= htmlspecialchars($vente['id_vente']) ?></div>
                            </div>
                            <div class="text-end">
                                <div>Date</div>
                                <div class="fw-bold"><?= htmlspecialchars($vente['date_vente']) ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informations -->
                    <div class="info-container">
                        <div class="info-box">
                            <div class="info-title">Fournisseur</div>
                            <p class="mb-1 fw-bold">I-Cyber</p>
                            <p class="mb-1">MB 156 Andoharanofotsy</p>
                            <p class="mb-1">Tél: +33 1 23 45 67 89</p>
                            <p class="mb-0">Email: @votreentreprise.com</p>
                        </div>
                        
                        <div class="info-box">
                            <div class="info-title">Client</div>
                            <p class="mb-1 fw-bold"><?= htmlspecialchars($vente['client_prenom']) . ' ' . htmlspecialchars($vente['client_nom']) ?></p>
                            <p class="mb-1">Adresse du client</p>
                            <p class="mb-1">Ville, Code postal</p>
                            <p class="mb-0">Email: client@example.com</p>
                        </div>
                    </div>
                    
                    <!-- Tableau des articles -->
                    <table class="invoice-table">
                        <thead>
                            <tr>
                                <th width="50%">Description</th>
                                <th width="15%">Quantité</th>
                                <th width="15%">Prix Unitaire</th>
                                <th width="20%">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vente['details'] as $item): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($item['nom']) ?></strong>
                                        <div class="text-muted small">Description du produit/service</div>
                                    </td>
                                    <td><?= htmlspecialchars($item['quantite']) ?></td>
                                    <td><?= number_format($item['prix_unitaire'], 0, ',', ' ') ?> Ar</td>
                                    <td><?= number_format($item['prix_unitaire'] * $item['quantite'], 0, ',', ' ') ?> Ar</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <!-- Totaux -->
                    <div class="total-container">
                        <div class="total-row">
                            <span>Sous-total:</span>
                            <span><?= number_format($vente['total'], 0, ',', ' ') ?> Ar</span>
                        </div>
                        <div class="total-row">
                            <span>Remise:</span>
                            <span>0 Ar</span>
                        </div>
                        <div class="total-row">
                            <span>Taxe:</span>
                            <span>0 Ar</span>
                        </div>
                        <div class="total-row grand-total">
                            <span>TOTAL:</span>
                            <span><?= number_format($vente['total'], 0, ',', ' ') ?> Ar</span>
                        </div>
                    </div>
                    
                    <!-- Statut -->
                    <div class="d-flex justify-content-between mt-4">
                        <div>
                            <strong>Méthode de paiement:</strong>
                            <div>Carte Bancaire</div>
                        </div>
                        <div class="text-end">
                            <strong>Statut:</strong>
                            <div class="badge bg-success p-2">PAYÉ</div>
                        </div>
                    </div>
                    
                    <!-- Pied de page -->
                    <div class="invoice-footer">
                        <p class="mb-1">Merci pour votre confiance.</p>
                        <p class="mb-0">Pour toute question, contactez-nous à I-Cyber@gmail.com</p>
                    </div>
                </div>
            </div>
            
            <!-- Boutons d'action (visible seulement à l'écran) -->
            <div class="action-buttons">
                <a href="/facture/pdf/<?= $vente['id_vente'] ?>" class="btn btn-danger btn-lg">
    <i class="bi bi-file-earmark-pdf-fill me-2"></i> Télécharger PDF
</a>

            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>