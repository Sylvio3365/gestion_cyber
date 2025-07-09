<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Types de Mouvement</title>
    <link rel="stylesheet" href="/assets/css/crud.css">
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
                        <div class="action-buttons">
                            <form method="post" action="/admin/type_mouvement/edit" class="inline-edit" style="display: none;">
                                <input type="hidden" name="id_mouvement" value="<?= $t['id_mouvement'] ?>">
                                <input type="text" name="type" value="<?= htmlspecialchars($t['type']) ?>" required>
                                <button type="submit" class="btn-save">
                                    <i class="fas fa-check"></i> Sauvegarder
                                </button>
                            </form>
                            
                            <button class="btn-icon btn-edit" onclick="toggleEdit(this)" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>
                            
                            <form method="post" action="/admin/type_mouvement/delete" onsubmit="return confirm('Supprimer ce type ?');" style="display: inline;">
                                <input type="hidden" name="id_mouvement" value="<?= $t['id_mouvement'] ?>">
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
                <td colspan="3">
                    <div class="empty-state">
                        <i class="fas fa-exchange-alt"></i>
                        <p>Aucun type trouvé.</p>
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
