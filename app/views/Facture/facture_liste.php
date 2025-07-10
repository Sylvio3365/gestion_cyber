<?php if (empty($ventes)): ?>
    <div class="alert alert-info text-center mt-5">Aucune facture disponible.</div>
<?php else: ?>
    <div class="container my-5">
        <h2 class="text-center mb-4 fw-bold text-primary">Historique des Factures</h2>

        <!-- Formulaire de filtre -->
        <form method="get" class="d-flex justify-content-center align-items-center gap-2 mb-4">
            <label for="filtre_date" class="fw-bold">Filtrer par date :</label>
            <input type="date" id="filtre_date" name="date" class="form-control w-auto"
                value="<?= htmlspecialchars($selected_date ?? '') ?>">
            <button type="submit" class="btn btn-primary">Appliquer</button>
            <?php if (!empty($_GET['date'])): ?>
                <a href="/facture/voir" class="btn btn-secondary">Réinitialiser</a>
            <?php endif; ?>
        </form>

        <?php foreach ($ventes as $vente): ?>
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Facture N° <?= htmlspecialchars($vente['id_vente']) ?></h5>
                    </div>
                    <div>
                        <small>Date : <?= htmlspecialchars($vente['date_vente']) ?></small>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Fournisseur & Client -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary">Fournisseur</h6>
                            <p class="mb-1">e-Cyber</p>
                            <p class="mb-1">MB 156 Andoharanofotsy</p>
                            <p class="mb-1">Tél: +33 1 23 45 67 89</p>
                            <p class="mb-0">Email: ecyber@gmail.com</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary">Client</h6>
                            <p class="mb-1"><strong>Nom :</strong> <?= htmlspecialchars($vente['client_nom']) ?></p>
                            <p class="mb-1"><strong>Prénom :</strong> <?= htmlspecialchars($vente['client_prenom']) ?></p>
                        </div>

                    </div>

                    <!-- Tableau des produits -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>Description</th>
                                    <th>Quantité</th>
                                    <th>Prix Unitaire</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($vente['details'] as $item): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($item['nom']) ?></strong><br>
                                        </td>
                                        <td class="text-center"><?= htmlspecialchars($item['quantite']) ?></td>
                                        <td class="text-end"><?= number_format($item['prix_unitaire'], 0, ',', ' ') ?> Ar</td>
                                        <td class="text-end"><?= number_format($item['quantite'] * $item['prix_unitaire'], 0, ',', ' ') ?> Ar</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Totaux -->
                    <div class="text-end">
                        <h5 class="mt-2 text-primary">TOTAL : <?= number_format($vente['total'], 0, ',', ' ') ?> Ar</h5>
                    </div>

                    <!-- Statut et actions -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            <span class="badge bg-success">PAYÉ</span>
                        </div>
                        <div>
                            <a href="/facture/pdf/<?= $vente['id_vente'] ?>" class="btn btn-outline-danger">
                                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Télécharger PDF
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-muted text-center small">
                    Merci pour votre confiance — Contact : ecyber@gmail.com
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>