<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Connexions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/dark-mode.css">
    <link rel="stylesheet" href="/assets/css/connexion/historique_poste.css">
</head>

<body>
    <div class="container">
        <h1 class="page-title">
            <i class="bi bi-clock-history me-2"></i>
            Historique des Connexions
        </h1>

        <!-- Section des filtres -->
        <div class="filters-section">
            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-funnel me-2 text-primary"></i>
                <h2 class="h5 mb-0">Filtres de recherche</h2>
            </div>
            
            <!-- Filtres de période -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="period-buttons d-flex flex-wrap justify-content-center gap-2">
                        <a href="?period=jour&date=<?= date('Y-m-d') ?>"
                            class="period-btn <?= $period === 'jour' ? 'btn-primary active' : '' ?>">
                            <i class="bi bi-calendar-day me-1"></i>
                            Jour
                        </a>
                        <a href="?period=mois&date=<?= date('Y-m-d') ?>"
                            class="period-btn <?= $period === 'mois' ? 'btn-primary active' : '' ?>">
                            <i class="bi bi-calendar-month me-1"></i>
                            Mois
                        </a>
                        <a href="?period=annee&date=<?= date('Y-m-d') ?>"
                            class="period-btn <?= $period === 'annee' ? 'btn-primary active' : '' ?>">
                            <i class="bi bi-calendar-year me-1"></i>
                            Année
                        </a>
                        <a href="?" class="period-btn btn-info">
                            <i class="bi bi-list-ul me-1"></i>
                            Tous
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sélecteur de date -->
            <div class="row">
                <div class="col-12">
                    <form method="get" class="date-form d-flex align-items-center justify-content-center gap-3">
                        <?php if ($period): ?>
                            <input type="hidden" name="period" value="<?= $period ?>">
                        <?php endif; ?>

                        <div class="d-flex align-items-center">
                            <label for="dateSelect" class="form-label mb-0 me-2">
                                Date:
                            </label>
                            <input type="date" class="form-control" id="dateSelect" name="date"
                                value="<?= $date ?>"
                                <?= $period === 'annee' ? 'pattern="\d{4}"' : '' ?>>
                        </div>
                        
                        <button type="submit" class="btn-apply">
                            <i class="bi bi-search me-1"></i>
                            Appliquer
                        </button>
                    </form>
                </div>
            </div>

            <?php if ($period): ?>
                <div class="filter-alert mt-3">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Filtre actif :</strong> <?= $periodLabels[$period] ?> - <?= $date ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Tableau historique -->
        <div class="table-container">
            <div class="card-header">
                <h2 class="h5">
                    <i class="bi bi-table me-2"></i>
                    Détail des Connexions
                </h2>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($historique)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>
                                        <i class="bi bi-calendar-check me-1"></i>
                                        Date début
                                    </th>
                                    <th>
                                        <i class="bi bi-calendar-x me-1"></i>
                                        Date fin
                                    </th>
                                    <th>
                                        <i class="bi bi-stopwatch me-1"></i>
                                        Durée
                                    </th>
                                    <th>
                                        <i class="bi bi-person me-1"></i>
                                        Client
                                    </th>
                                    <th>
                                        <i class="bi bi-pc-display me-1"></i>
                                        Poste
                                    </th>
                                    <th>
                                        <i class="bi bi-credit-card me-1"></i>
                                        Statut
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historique as $connexion): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-calendar3 me-2 text-primary"></i>
                                                <?= date('d/m/Y H:i:s', strtotime($connexion['date_debut'])) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-calendar3 me-2 text-secondary"></i>
                                                <?= date('d/m/Y H:i:s', strtotime($connexion['date_fin'])) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="duration-indicator">
                                                <i class="bi bi-clock"></i>
                                                <?= $connexion['duree_minutes'] ?> min
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-person-circle me-2 text-info"></i>
                                                <?= htmlspecialchars($connexion['client_nom_complet']) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-display me-2 text-success"></i>
                                                <?= $connexion['poste_nom'] ? htmlspecialchars($connexion['poste_nom']) : 'Aucun poste' ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($connexion['statut'] == 0): ?>
                                                <span class="badge bg-danger status-badge unpaid">
                                                    <i class="bi bi-x-circle me-1"></i>
                                                    Non payé
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-success status-badge paid">
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    Payé
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <p>Aucune connexion trouvée pour les critères sélectionnés.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation d'entrée pour les éléments
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.stat-card, .table-container, .filters-section');
            elements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    element.style.transition = 'all 0.6s ease';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>

</html>