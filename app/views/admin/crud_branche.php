<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Branches</title>
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
    <h1>Gestion des Branches</h1>

    <div class="form-section">
        <h3>Ajouter une nouvelle branche</h3>
        <form method="post" action="/admin/branche/add">
            <label>Nom : <input type="text" name="nom" required></label>
            <label>Description : <input type="text" name="description"></label>
            <button type="submit">Ajouter</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($branches)): ?>
            <?php foreach ($branches as $b): ?>
                <tr>
                    <td><?= $b['id_branche'] ?></td>
                    <td><?= htmlspecialchars($b['nom']) ?></td>
                    <td><?= htmlspecialchars($b['description']) ?></td>
                    <td>
                        <form method="post" action="/admin/branche/edit">
                            <input type="hidden" name="id_branche" value="<?= $b['id_branche'] ?>">
                            <input type="text" name="nom" value="<?= htmlspecialchars($b['nom']) ?>" required>
                            <input type="text" name="description" value="<?= htmlspecialchars($b['description']) ?>">
                            <button type="submit">Modifier</button>
                        </form>

                        <form method="post" action="/admin/branche/delete" onsubmit="return confirm('Supprimer cette branche ?');">
                            <input type="hidden" name="id_branche" value="<?= $b['id_branche'] ?>">
                            <button type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4">Aucune branche trouvée.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <p><a href="/dashboard">Retour au Dashboard</a></p>
</div>

</body>
</html>
