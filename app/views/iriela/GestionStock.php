<div class="container">
    <style>
        /* Same CSS as provided, unchanged */
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        h1 {
            color: #1e3a8a;
            text-align: center;
            margin-bottom: 20px;
        }
        .card {
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #eff6ff;
        }
        .card-body {
            padding: 20px;
        }
        .form-group {
            display: flex;
            align-items: center;
        }
        .form-control {
            border: 1px solid #3b82f6;
            border-radius: 4px;
            padding: 8px;
            margin-right: 10px;
        }
        .btn-primary {
            background-color: #2563eb;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #1e40af;
        }
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .alert-info {
            background-color: #dbeafe;
            border: 1px solid #3b82f6;
            color: #1e3a8a;
        }
        .alert-success {
            background-color: #dcfce7;
            border: 1px solid #22c55e;
            color: #166534;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }
        .table th, .table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #bfdbfe;
        }
        .thead-dark {
            background-color: #1e3a8a;
            color: white;
        }
        .table-striped tbody tr:nth-child(odd) {
            background-color: #f8fafc;
        }
        .table-danger {
            background-color: #fee2e2;
        }
        .table-warning {
            background-color: #fef3c7;
        }
        .badge {
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 0.9em;
        }
        .badge-danger {
            background-color: #dc2626;
            color: white;
        }
        .badge-warning {
            background-color: #d97706;
            color: white;
        }
    </style>

    <h1><?= $isLowStock ? 'Produits en stock faible' : 'Tous les produits en stock' ?></h1>
    
    <div class="card mb-4">
        <div class="card-body">
            <form method="get" class="form-inline">
                <div class="form-group mr-2">
                    <label for="threshold" class="mr-2">Seuil d'alerte:</label>
                    <input type="number" id="threshold" name="threshold" 
                           value="<?= htmlspecialchars($isLowStock ? $threshold : '') ?>" 
                           class="form-control" min="1" step="1" placeholder="Entrer un seuil">
                </div>
                <button type="submit" class="btn btn-primary">Filtrer</button>
            </form>
        </div>
    </div>
    
    <div class="alert alert-info">
        <?= $productCount ?> produit(s) <?= $isLowStock ? "avec un stock ≤ $threshold" : 'au total' ?>
    </div>
    
    <?php if (!empty($products)): ?>
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Produit</th>
                <th>Référence</th>
                <th>Quantité</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr class="<?= $product['quantite'] <= 5 ? 'table-danger' : 'table-warning' ?>">
                <td><?= htmlspecialchars($product['nom']) ?></td>
                <td><?= htmlspecialchars($product['description']) ?></td>
                <td><?= htmlspecialchars($product['quantite']) ?></td>
                <td>
                    <?php if ($product['quantite'] <= 5): ?>
                    <span class="badge badge-danger">Stock très faible</span>
                    <?php else: ?>
                    <span class="badge badge-warning">Stock faible</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div class="alert alert-success">
        <?= $isLowStock ? "Aucun produit en dessous du seuil de $threshold" : 'Aucun produit en stock' ?>
    </div>
    <?php endif; ?>
</div>