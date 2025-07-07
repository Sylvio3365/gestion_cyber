<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture Vente #<?= $vente['id_vente'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 2rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .facture {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.2rem 1rem rgba(0, 0, 0, 0.1);
        }
        .facture-header {
            border-bottom: 1px solid #ccc;
            margin-bottom: 2rem;
        }
        .facture-title {
            font-size: 2rem;
            font-weight: bold;
        }
        .btn-export {
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="facture">
        <div class="facture-header d-flex justify-content-between align-items-center">
            <div>
                <div class="facture-title">Facture</div>
                <div>Date : <?= htmlspecialchars($vente['date_vente']) ?></div>
                <div>Facture N° : <?= htmlspecialchars($vente['id_vente']) ?></div>
            </div>
            <div>
                <strong>Client :</strong><br>
                <?= htmlspecialchars($vente['client_prenom']) . ' ' . htmlspecialchars($vente['client_nom']) ?>
            </div>
        </div>

        <h5>Détail de la commande</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produit / Service</th>
                    <th class="text-end">Quantité</th>
                    <th class="text-end">Prix unitaire</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): 
                    $totalLigne = $item['quantite'] * $item['prix_unitaire'];
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['nom']) ?></td>
                    <td class="text-end"><?= $item['quantite'] ?></td>
                    <td class="text-end"><?= number_format($item['prix_unitaire'], 2, ',', ' ') ?> Ar</td>
                    <td class="text-end"><?= number_format($totalLigne, 2, ',', ' ') ?> Ar</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total général</th>
                    <th class="text-end"><?= number_format($vente['total'], 2, ',', ' ') ?> Ar</th>
                </tr>
            </tfoot>
        </table>

        <div class="text-end btn-export">
            <a href="/facture/pdf/<?= $vente['id_vente'] ?>" class="btn btn-primary">
                <i class="fas fa-file-pdf me-2"></i> Télécharger en PDF
            </a>
        </div>
    </div>
</body>
</html>
