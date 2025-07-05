<div class="container mt-5">
    <h2>Gestion des prix par mois</h2>

    <!-- Formulaire de sélection -->
    <form method="get" action="/admin/prix" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="type" class="form-label">Type :</label>
                <select name="type" id="type" class="form-select" required onchange="this.form.submit()">
                    <option disabled <?= !isset($_GET['type']) ? 'selected' : '' ?>>Choisir...</option>
                    <option value="produit" <?= ($_GET['type'] ?? '') === 'produit' ? 'selected' : '' ?>>Produit</option>
                    <option value="service" <?= ($_GET['type'] ?? '') === 'service' ? 'selected' : '' ?>>Service</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="id_item" class="form-label">Produit ou Service :</label>
                <select name="id_item" id="id_item" class="form-select" required onchange="this.form.submit()">
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
            </div>

            <div class="col-md-3">
                <label for="annee" class="form-label">Année :</label>
                <input type="number" name="annee" id="annee" class="form-control"
                    value="<?= $_GET['annee'] ?? date('Y') ?>" onchange="this.form.submit()">
            </div>
        </div>
    </form>

    <!-- Tableau des prix -->
    <?php if (!empty($_GET['type']) && !empty($_GET['id_item']) && !empty($_GET['annee'])): ?>
        <table class="table table-bordered mt-4">
            <thead class="table-light">
                <tr>
                    <th>Mois</th>
                    <th>Prix d'achat</th>
                    <th>Prix de vente</th>
                    <th></th>
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
                                    <input type="number" step="50" name="achat" class="form-control" placeholder="Prix d'achat">
                                <?php endif; ?>
                            </td>

                            <!-- Prix de vente -->
                            <td>
                                <?php if (isset($prixVente[$mois])): ?>
                                    <span class="text-success"><?= number_format($prixVente[$mois], 2) ?> Ar</span>
                                <?php else: ?>
                                    <input type="number" step="50" name="vente" class="form-control" placeholder="Prix de vente">
                                <?php endif; ?>
                            </td>

                            <!-- Action -->
                            <td class="text-center">
                                <?php if (!isset($prixAchat[$mois]) || !isset($prixVente[$mois])): ?>
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-check-circle"></i> Valider
                                    </button>
                                <?php else: ?>
                                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                <?php endif; ?>
                            </td>
                        </form>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>