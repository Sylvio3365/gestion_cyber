<link rel="stylesheet" href="/assets/css/crud.css">

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
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($marques)): ?>
            <?php foreach ($marques as $m): ?>
                <tr>
                    <td><?= $m['id_marque'] ?></td>
                    <td><?= htmlspecialchars($m['nom']) ?></td>
                    <td>
                        <div class="action-buttons">
                            <form method="post" action="/admin/marque/edit" class="inline-edit" style="display: none;">
                                <input type="hidden" name="id_marque" value="<?= $m['id_marque'] ?>">
                                <input type="text" name="nom" value="<?= htmlspecialchars($m['nom']) ?>" required>
                                <button type="submit" class="btn-save">
                                    <i class="fas fa-check"></i> Sauvegarder
                                </button>
                            </form>
                            
                            <button class="btn-icon btn-edit" onclick="toggleEdit(this)" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>
                            
                            <form method="post" action="/admin/marque/delete" onsubmit="return confirm('Supprimer cette marque ?');" style="display: inline;">
                                <input type="hidden" name="id_marque" value="<?= $m['id_marque'] ?>">
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
                        <i class="fas fa-trademark"></i>
                        <p>Aucune marque trouvée.</p>
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

