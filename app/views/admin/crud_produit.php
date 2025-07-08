<link rel="stylesheet" href="/assets/css/crud.css">

<div class="container">
    <h1>Gestion des Produits</h1>

    <div class="form-section">
        <h3>Ajouter un nouveau produit</h3>
        <form method="post" action="/admin/produit/add">
            <label>Nom : <input type="text" name="nom" required></label>
            <label>Description : <input type="text" name="description"></label>
            <label>Marque :
                <select name="id_marque" required>
                    <?php foreach ($marques as $m): ?>
                        <option value="<?= $m['id_marque'] ?>"><?= htmlspecialchars($m['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
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
                <th>Marque</th>
                <th>Catégorie</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($produits)): ?>
            <?php foreach ($produits as $p): ?>
                <tr>
                    <td><?= $p['id_produit'] ?></td>
                    <td><?= htmlspecialchars($p['nom']) ?></td>
                    <td><?= htmlspecialchars($p['description']) ?></td>
                    <td><?= htmlspecialchars($p['marque_nom']) ?></td>
                    <td><?= htmlspecialchars($p['categorie_nom']) ?></td>
                    <td>
                        <div class="action-buttons">
                            <form method="post" action="/admin/produit/edit" class="inline-edit" style="display: none;">
                                <input type="hidden" name="id_produit" value="<?= $p['id_produit'] ?>">
                                <input type="text" name="nom" value="<?= htmlspecialchars($p['nom']) ?>" required>
                                <input type="text" name="description" value="<?= htmlspecialchars($p['description']) ?>">
                                <select name="id_marque" required>
                                    <?php foreach ($marques as $m): ?>
                                        <option value="<?= $m['id_marque'] ?>" <?= $p['id_marque'] == $m['id_marque'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($m['nom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="id_categorie" required>
                                    <?php foreach ($categories as $c): ?>
                                        <option value="<?= $c['id_categorie'] ?>" <?= $p['id_categorie'] == $c['id_categorie'] ? 'selected' : '' ?>>
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
                            
                            <form method="post" action="/admin/produit/delete" onsubmit="return confirm('Supprimer ce produit ?');" style="display: inline;">
                                <input type="hidden" name="id_produit" value="<?= $p['id_produit'] ?>">
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
                <td colspan="6">
                    <div class="empty-state">
                        <i class="fas fa-cube"></i>
                        <p>Aucun produit trouvé.</p>
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

