<link rel="stylesheet" href="/assets/css/crud.css">

<div class="container">
    <h1>Gestion des prix par mois</h1>

    <!-- Section formulaire -->
    <div class="form-section">
        <h3>Sélectionner un élément</h3>
        <form method="get" action="/admin/prix">
            <label>
                Type :
                <select name="type" id="type" required onchange="this.form.submit()">
                    <option disabled <?= !isset($_GET['type']) ? 'selected' : '' ?>>Choisir...</option>
                    <option value="produit" <?= ($_GET['type'] ?? '') === 'produit' ? 'selected' : '' ?>>Produit</option>
                    <option value="service" <?= ($_GET['type'] ?? '') === 'service' ? 'selected' : '' ?>>Service</option>
                </select>
            </label>

            <label>
                Produit ou Service :
                <select name="id_item" id="id_item" required onchange="this.form.submit()">
                    <option disabled <?= !isset($_GET['id_item']) ? 'selected' : '' ?>>Choisir...</option>
                    <?php
                    $type = $_GET['type'] ?? null;
                    $id_item = $_GET['id_item'] ?? null;

                    if ($type === 'produit') {
                        foreach ($produits as $p) {
                            $selected = ($id_item == $p['id_produit']) ? 'selected' : '';
                            echo "<option value='{$p['id_produit']}' $selected>" . htmlspecialchars($p['nom']) . "</option>";
                        }
                    } elseif ($type === 'service') {
                        foreach ($services as $s) {
                            $selected = ($id_item == $s['id_service']) ? 'selected' : '';
                            echo "<option value='{$s['id_service']}' $selected>" . htmlspecialchars($s['nom']) . "</option>";
                        }
                    }
                    ?>
                </select>
            </label>

            <label>
                Année :
                <input type="number" name="annee" id="annee" 
                       value="<?= $_GET['annee'] ?? date('Y') ?>" 
                       onchange="this.form.submit()">
            </label>

            <button type="submit">Rechercher</button>
        </form>
    </div>

    <!-- Tableau des prix -->
    <?php if (!empty($_GET['type']) && !empty($_GET['id_item']) && !empty($_GET['annee'])): ?>
        <table>
            <thead>
                <tr>
                    <th>Mois</th>
                    <th>Prix d'achat</th>
                    <th>Prix de vente</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($mois = 1; $mois <= 12; $mois++): ?>
                    <tr>
                        <td><?= $mois ?></td>
                        <form method="post" action="/admin/prix/valider">
                            <input type="hidden" name="type" value="<?= htmlspecialchars($_GET['type']) ?>">
                            <input type="hidden" name="id_item" value="<?= htmlspecialchars($_GET['id_item']) ?>">
                            <input type="hidden" name="annee" value="<?= htmlspecialchars($_GET['annee']) ?>">
                            <input type="hidden" name="mois" value="<?= $mois ?>">
                            <input type="hidden" name="description" value="Ajout mois <?= $mois ?>">

                            <!-- Prix d'achat -->
                            <td>
                                <?php if (isset($prixAchat[$mois])): ?>
                                    <span class="text-primary"><?= number_format($prixAchat[$mois], 2) ?> Ar</span>
                                <?php else: ?>
                                    <input type="number" step="50" name="achat" placeholder="Prix d'achat">
                                <?php endif; ?>
                            </td>

                            <!-- Prix de vente -->
                            <td>
                                <?php if (isset($prixVente[$mois])): ?>
                                    <span class="text-success"><?= number_format($prixVente[$mois], 2) ?> Ar</span>
                                <?php else: ?>
                                    <input type="number" step="50" name="vente" placeholder="Prix de vente">
                                <?php endif; ?>
                            </td>

                            <!-- Action -->
                            <td>
                                <div class="action-buttons">
                                    <?php if (!isset($prixAchat[$mois]) || !isset($prixVente[$mois])): ?>
                                        <button type="submit" class="btn-icon btn-save" title="Valider">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    <?php else: ?>
                                        <div class="status-badge bon">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </form>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-chart-line"></i>
            <h3>Aucune sélection</h3>
            <p>Veuillez sélectionner un type, un élément et une année pour voir les prix.</p>
        </div>
    <?php endif; ?>
</div>
