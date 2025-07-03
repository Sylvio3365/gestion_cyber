<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Marques</title>
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
    <h1>Gestion des Marques</h1>

    <div class="form-section">
        <h3>Ajouter une nouvelle marque</h3>
        <form method="post" action="/admin/marque/add">
            <label>Nom : <input type="text" name="nom" required></label>
            <button type="submit">Ajouter</button>
        </form>
    </div>

    <table>
        <thead>
            <tr><th>ID</th><th>Nom</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php if (!empty($marques)): ?>
            <?php foreach ($marques as $m): ?>
                <tr>
                    <td><?= $m['id_marque'] ?></td>
                    <td><?= htmlspecialchars($m['nom']) ?></td>
                    <td>
                        <form method="post" action="/admin/marque/edit">
                            <input type="hidden" name="id_marque" value="<?= $m['id_marque'] ?>">
                            <input type="text" name="nom" value="<?= htmlspecialchars($m['nom']) ?>" required>
                            <button type="submit">Modifier</button>
                        </form>
                        <form method="post" action="/admin/marque/delete" onsubmit="return confirm('Supprimer cette marque ?');">
                            <input type="hidden" name="id_marque" value="<?= $m['id_marque'] ?>">
                            <button type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3">Aucune marque trouvée.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

</div>
</body>
</html>
