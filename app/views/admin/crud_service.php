<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Services</title>
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
    <h1>Gestion des Services</h1>

    <div class="form-section">
        <h3>Ajouter un nouveau service</h3>
        <form method="post" action="/admin/service/add">
            <label>Nom : <input type="text" name="nom" required></label>
            <label>Description : <input type="text" name="description"></label>
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
                <th>ID</th><th>Nom</th><th>Description</th><th>Catégorie</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($services as $s): ?>
            <tr>
                <td><?= $s['id_service'] ?></td>
                <td><?= htmlspecialchars($s['nom']) ?></td>
                <td><?= htmlspecialchars($s['description']) ?></td>
                <td><?= htmlspecialchars($s['categorie_nom']) ?></td>
                <td>
                    <form method="post" action="/admin/service/edit">
                        <input type="hidden" name="id_service" value="<?= $s['id_service'] ?>">
                        <input type="text" name="nom" value="<?= htmlspecialchars($s['nom']) ?>" required>
                        <input type="text" name="description" value="<?= htmlspecialchars($s['description']) ?>">
                        <select name="id_categorie" required>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= $c['id_categorie'] ?>" <?= $c['id_categorie']==$s['id_categorie'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit">Modifier</button>
                    </form>
                    <form method="post" action="/admin/service/delete" onsubmit="return confirm('Supprimer ce service ?');">
                        <input type="hidden" name="id_service" value="<?= $s['id_service'] ?>">
                        <button type="submit">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <p><a href="/dashboard">Retour au Dashboard</a></p>
</div>

</body>
</html>
