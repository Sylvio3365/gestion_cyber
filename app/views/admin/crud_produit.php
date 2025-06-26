<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Produits</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: 20px auto; }
        th, td { border: 1px solid #333; padding: 8px; text-align: center; }
        form { display: inline; }
        .container { width: 80%; margin: auto; }
        .form-section { background: #f9f9f9; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; }
    </style>
</head>
<body>

<div class="container">
    <h1>Gestion des Produits</h1>

    <div class="form-section">
        <h3>Ajouter un nouveau produit</h3>
        <form method="post" action="/admin/produit/add">
            <label>Nom :
                <input type="text" name="nom" required>
            </label>
            <label>Description :
                <input type="text" name="description">
            </label>
            <label>Marque :
                <select name="id_marque" required>
                    <?php foreach ($marques as $m): ?>
                        <option value="<?= $m['id_marque'] ?>"><?= htmlspecialchars($m['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Catégorie :
                <select name="id_categorie" required>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['id_categorie'] ?>"><?= htmlspecialchars($c['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button type="submit">Ajouter</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Marque</th>
                <th>Catégorie</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($produits)): ?>
            <?php foreach ($produits as $p): ?>
                <tr>
                    <td><?= $p['id_produit'] ?></td>
                    <td><?= htmlspecialchars($p['nom']) ?></td>
                    <td><?= htmlspecialchars($p['description']) ?></td>
                    <td><?= htmlspecialchars($p['marque_nom']) ?></td>
                    <td><?= htmlspecialchars($p['categorie_nom']) ?></td>
                    <td>
                        <form method="post" action="/admin/produit/edit">
                            <input type="hidden" name="id_produit" value="<?= $p['id_produit'] ?>">
                            <input type="text" name="nom" value="<?= htmlspecialchars($p['nom']) ?>" required>
                            <input type="text" name="description" value="<?= htmlspecialchars($p['description']) ?>">
                            <select name="id_marque" required>
                                <?php foreach ($marques as $m): ?>
                                    <option value="<?= $m['id_marque'] ?>" <?= $p['id_marque'] == $m['id_marque'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($m['nom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <select name="id_categorie" required>
                                <?php foreach ($categories as $c): ?>
                                    <option value="<?= $c['id_categorie'] ?>" <?= $p['id_categorie'] == $c['id_categorie'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['nom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit">Modifier</button>
                        </form>

                        <form method="post" action="/admin/produit/delete" onsubmit="return confirm('Supprimer ce produit ?');">
                            <input type="hidden" name="id_produit" value="<?= $p['id_produit'] ?>">
                            <button type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6">Aucun produit trouvé.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <p><a href="/dashboard">Retour au Dashboard</a></p>
</div>

</body>
</html>
