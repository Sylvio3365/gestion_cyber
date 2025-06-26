<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Catégories</title>
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
    <h1>Gestion des Catégories</h1>

    <div class="form-section">
        <h3>Ajouter une nouvelle catégorie</h3>
        <form method="post" action="/admin/categorie/add">
            <label>Nom : <input type="text" name="nom" required></label>
            <label>Branche :
                <select name="id_branche" required>
                    <?php foreach ($branches as $b): ?>
                        <option value="<?= $b['id_branche'] ?>"><?= htmlspecialchars($b['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button type="submit">Ajouter</button>
        </form>
    </div>

    <table>
        <thead>
            <tr><th>ID</th><th>Nom</th><th>Branche</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $c): ?>
                <tr>
                    <td><?= $c['id_categorie'] ?></td>
                    <td><?= htmlspecialchars($c['nom']) ?></td>
                    <td><?= htmlspecialchars($c['branche_nom']) ?></td>
                    <td>
                        <form method="post" action="/admin/categorie/edit">
                            <input type="hidden" name="id_categorie" value="<?= $c['id_categorie'] ?>">
                            <input type="text" name="nom" value="<?= htmlspecialchars($c['nom']) ?>" required>
                            <select name="id_branche" required>
                                <?php foreach ($branches as $b): ?>
                                    <option value="<?= $b['id_branche'] ?>" <?= $b['id_branche'] == $c['id_branche'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($b['nom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit">Modifier</button>
                        </form>
                        <form method="post" action="/admin/categorie/delete" onsubmit="return confirm('Supprimer cette catégorie ?');">
                            <input type="hidden" name="id_categorie" value="<?= $c['id_categorie'] ?>">
                            <button type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4">Aucune catégorie trouvée.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <p><a href="/dashboard">Retour au Dashboard</a></p>
</div>
</body>
</html>
