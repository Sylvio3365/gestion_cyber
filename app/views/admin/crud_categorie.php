<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Catégories</title>
    <link rel="stylesheet" href="/assets/css/crud.css">

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
                        <div class="action-buttons">
                            <form method="post" action="/admin/categorie/edit" class="inline-edit" style="display: none;">
                                <input type="hidden" name="id_categorie" value="<?= $c['id_categorie'] ?>">
                                <input type="text" name="nom" value="<?= htmlspecialchars($c['nom']) ?>" required>
                                <select name="id_branche" required>
                                    <?php foreach ($branches as $b): ?>
                                        <option value="<?= $b['id_branche'] ?>" <?= $b['id_branche'] == $c['id_branche'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($b['nom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn-save">
                                    <i class="fas fa-check"></i> Sauvegarder
                                </button>
                            </form>
                            
                            <button class="btn-icon btn-edit" onclick="toggleEdit(this)" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>
                            
                            <form method="post" action="/admin/categorie/delete" onsubmit="return confirm('Supprimer cette catégorie ?');" style="display: inline;">
                                <input type="hidden" name="id_categorie" value="<?= $c['id_categorie'] ?>">
                                <button type="submit" class="btn-icon btn-delete" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">
                    <div class="empty-state">
                        <i class="fas fa-tags"></i>
                        <p>Aucune catégorie trouvée.</p>
                    </div>
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

</div>

<script>
function toggleEdit(button) {
    const row = button.closest('tr');
    const form = row.querySelector('.inline-edit');
    const isEditing = form.style.display !== 'none';
    
    if (isEditing) {
        form.style.display = 'none';
        button.innerHTML = '<i class="fas fa-edit"></i>';
        button.title = 'Modifier';
    } else {
        form.style.display = 'flex';
        button.innerHTML = '<i class="fas fa-times"></i>';
        button.title = 'Annuler';
    }
}
</script>

</body>
</html>
