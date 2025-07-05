<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des Stocks</title>
    <link rel="stylesheet" href="/assets/css/crud.css">
</head>

<body>

    <div class="container">
        <h1>Gestion des Stocks</h1>

        <div class="form-section">
            <h3>Ajouter stock</h3>
            <form method="post" action="/admin/stock/add">
                <label>Produit :
                    <select name="id_produit" required>
                        <?php foreach ($produits as $p): ?>
                            <option value="<?= $p['id_produit'] ?>"><?= htmlspecialchars($p['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <input type="hidden" name="id_mouvement" value="1">
                <label>Quantité : <input type="number" name="quantite" required></label>
                <button type="submit">Ajouter</button>
            </form>
        </div>

        <h3>Stock actuel par produit</h3>
        <table>
            <thead>
                <tr>
                    <th>ID Produit</th>
                    <th>Nom du Produit</th>
                    <th>Stock Restant</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($stocks)): ?>
                    <?php foreach ($stocks as $s): ?>
                        <tr>
                            <td><?= $s['id_produit'] ?></td>
                            <td><?= htmlspecialchars($s['produit_nom']) ?></td>
                            <td><?= $s['stock_restant'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">
                            <div class="empty-state">
                                <i class="fas fa-boxes"></i>
                                <p>Aucun stock enregistré.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>

</html>