<div class="container mt-4">
        <h1 class="mb-4">Historique des Connexions</h1>

        <!-- Filtres -->
        <div class="card mb-4">
            <div class="card-header">
                <h2 class="h5">Filtres</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="btn-group mb-3">
                            <a href="?period=jour&date=<?= date('Y-m-d') ?>"
                                class="btn btn-<?= $period === 'jour' ? 'primary' : 'secondary' ?>">
                                Jour
                            </a>
                            <a href="?period=mois&date=<?= date('Y-m-d') ?>"
                                class="btn btn-<?= $period === 'mois' ? 'primary' : 'secondary' ?>">
                                Mois
                            </a>
                            <a href="?period=annee&date=<?= date('Y-m-d') ?>"
                                class="btn btn-<?= $period === 'annee' ? 'primary' : 'secondary' ?>">
                                Année
                            </a>
                            <a href="?" class="btn btn-info">
                                Tous
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <form method="get" class="row g-3">
                            <?php if ($period): ?>
                                <input type="hidden" name="period" value="<?= $period ?>">
                            <?php endif; ?>

                            <div class="col-auto">
                                <label for="dateSelect" class="col-form-label">Date:</label>
                            </div>
                            <div class="col-auto">
                                <input type="date" class="form-control" id="dateSelect" name="date"
                                    value="<?= $date ?>"
                                    <?= $period === 'annee' ? 'pattern="\d{4}"' : '' ?>>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">Appliquer</button>
                            </div>
                        </form>
                    </div>
                </div>

                <?php if ($period): ?>
                    <div class="alert alert-info">
                        Filtre actif : <?= $periodLabels[$period] ?> - <?= $date ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tableau historique -->
        <div class="card">
            <div class="card-header">
                <h2 class="h5">Détail des Connexions</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date début</th>
                                <th>Date fin</th>
                                <th>Durée (minutes)</th>
                                <th>Client</th>
                                <th>Poste</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historique as $connexion): ?>
                                <tr>
                                    <td><?= date('d/m/Y H:i:s', strtotime($connexion['date_debut'])) ?></td>
                                    <td><?= date('d/m/Y H:i:s', strtotime($connexion['date_fin'])) ?></td>
                                    <td><?= $connexion['duree_minutes'] ?> minutes</td>
                                    <td><?= htmlspecialchars($connexion['client_nom_complet']) ?></td>
                                    <td><?= $connexion['poste_nom'] ? htmlspecialchars($connexion['poste_nom']) : 'Aucun poste' ?></td>
                                    <td>
                                        <?php if ($connexion['statut'] == 0): ?>
                                            <span class="badge bg-danger">Non payé</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Payé</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
