<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Types de Mouvement</title>
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
    <h1>Gestion des Types de Mouvement</h1>

    <div class="form-section">
        <h3>Ajouter un type</h3>
        <form method="post" action="/admin/type_mouvement/add">
            <label>Type : <input type="text" name="type" required></label>
            <button type="submit">Ajouter</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($types)): ?>
            <?php foreach ($types as $t): ?>
                <tr>
                    <td><?= $t['id_mouvement'] ?></td>
                    <td><?= htmlspecialchars($t['type']) ?></td>
                    <td>
                        <form method="post" action="/admin/type_mouvement/edit">
                            <input type="hidden" name="id_mouvement" value="<?= $t['id_mouvement'] ?>">
                            <input type="text" name="type" value="<?= htmlspecialchars($t['type']) ?>" required>
                            <button type="submit">Modifier</button>
                        </form>
                        <form method="post" action="/admin/type_mouvement/delete" onsubmit="return confirm('Supprimer ce type ?');">
                            <input type="hidden" name="id_mouvement" value="<?= $t['id_mouvement'] ?>">
                            <button type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3">Aucun type trouvé.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <p><a href="/dashboard">Retour au Dashboard</a></p>
</div>

</body>
</html>
