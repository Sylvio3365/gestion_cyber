<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des Branches</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
            text-align: center;
        }

        form {
            display: inline;
        }

        .container {
            width: 80%;
            margin: auto;
        }

        .form-section {
            background: #f9f9f9;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Gestion des Stocks</h1>

        <div class="form-section">
            <h3>Ajouter un mouvement de stock</h3>
            <form method="post" action="/admin/stock/add">
                <label>Produit :
                    <select name="id_produit" required>
                        <?php foreach ($produits as $p): ?>
                            <option value="<?= $p['id_produit'] ?>"><?= htmlspecialchars($p['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Type de mouvement :
                    <select name="id_mouvement" required>
                        <?php foreach ($types as $t): ?>
                            <option value="<?= $t['id_mouvement'] ?>"><?= htmlspecialchars($t['type']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Quantité : <input type="number" name="quantite" required></label>
                <button type="submit">Ajouter</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produit</th>
                    <th>Type de Mouvement</th>
                    <th>Quantité</th>
                    <th>Date Mouvement</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($stocks)): ?>
                    <?php foreach ($stocks as $s): ?>
                        <tr>
                            <td><?= $s['id_stock'] ?></td>
                            <td><?= htmlspecialchars($s['produit_nom']) ?></td>
                            <td><?= htmlspecialchars($s['type_mouvement']) ?></td>
                            <td><?= $s['quantite'] ?></td>
                            <td><?= $s['date_mouvement'] ?></td>
                            <td>
                                <!-- Ici on peut autoriser uniquement la suppression d’un mouvement -->
                                <form method="post" action="/admin/stock/delete" onsubmit="return confirm('Supprimer ce mouvement ?');">
                                    <input type="hidden" name="id_stock" value="<?= $s['id_stock'] ?>">
                                    <button type="submit">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Aucun mouvement de stock trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>

</html>