<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payement des connexions</title>
    <!-- Bootstrap CSS from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2>Payement des connexions</h2>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>