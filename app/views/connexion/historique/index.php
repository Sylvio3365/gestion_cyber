<div class="container mt-4">
    <h2 class="mb-4">Paiement des connexions</h2>

    <!-- Tableau historique des connexions -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mt-3">
                    <thead class="table-light">
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Date Début</th>
                            <th>Date Fin</th>
                            <th>Type</th>
                            <th>Durée (min)</th>
                            <th>Montant à payer</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($historiques)) : ?>
                            <?php foreach ($historiques as $histoire) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($histoire['nom']) ?></td>
                                    <td><?= htmlspecialchars($histoire['prenom']) ?></td>
                                    <td><?= htmlspecialchars($histoire['date_debut']) ?></td>
                                    <td><?= $histoire['date_fin'] ? htmlspecialchars($histoire['date_fin']) : 'En cours' ?></td>
                                    <td><?= $histoire['id_poste'] ? 'Avec poste' : 'Sans poste' ?></td>
                                    <td><?= $histoire['duree_minutes'] ?? '-' ?></td>
                                    <td>
                                        <?= isset($histoire['montant_a_payer'])
                                            ? number_format($histoire['montant_a_payer'], 0, ',', ' ') . ' Ar'
                                            : '-' ?>
                                    </td>
                                    <td>
                                        <form action="/connexion/payer" method="post">
                                            <input type="hidden" name="id" value="<?= $histoire['id_historique_connection'] ?>">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                Confirmer le paiement
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="8" class="text-center">Aucune donnée trouvée</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>