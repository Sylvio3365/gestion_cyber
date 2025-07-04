<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Branches</title>
    <link rel="stylesheet" href="/assets/css/crud.css">
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
                        <div class="action-buttons">
                            <form method="post" action="/admin/branche/edit" class="inline-edit" style="display: none;">
                                <input type="hidden" name="id_branche" value="<?= $b['id_branche'] ?>">
                                <input type="text" name="nom" value="<?= htmlspecialchars($b['nom']) ?>" required>
                                <input type="text" name="description" value="<?= htmlspecialchars($b['description']) ?>">
                                <button type="submit" class="btn-save">
                                    <i class="fas fa-check"></i> Sauvegarder
                                </button>
                            </form>
                            
                            <button class="btn-icon btn-edit" onclick="toggleEdit(this)" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>
                            
                            <form method="post" action="/admin/branche/delete" onsubmit="return confirm('Supprimer cette branche ?');" style="display: inline;">
                                <input type="hidden" name="id_branche" value="<?= $b['id_branche'] ?>">
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
                        <i class="fas fa-folder-open"></i>
                        <p>Aucune branche trouvée.</p>
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
