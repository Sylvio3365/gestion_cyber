<link rel="stylesheet" href="/assets/css/crud.css">

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
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Catégorie</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($services)): ?>
            <?php foreach ($services as $s): ?>
                <tr>
                    <td><?= $s['id_service'] ?></td>
                    <td><?= htmlspecialchars($s['nom']) ?></td>
                    <td><?= htmlspecialchars($s['description']) ?></td>
                    <td><?= htmlspecialchars($s['categorie_nom']) ?></td>
                    <td>
                        <div class="action-buttons">
                            <form method="post" action="/admin/service/edit" class="inline-edit" style="display: none;">
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
                                <button type="submit" class="btn-save">
                                    <i class="fas fa-check"></i> Sauvegarder
                                </button>
                            </form>
                            
                            <button class="btn-icon btn-edit" onclick="toggleEdit(this)" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>
                            
                            <form method="post" action="/admin/service/delete" onsubmit="return confirm('Supprimer ce service ?');" style="display: inline;">
                                <input type="hidden" name="id_service" value="<?= $s['id_service'] ?>">
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
                <td colspan="5">
                    <div class="empty-state">
                        <i class="fas fa-concierge-bell"></i>
                        <p>Aucun service trouvé.</p>
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

