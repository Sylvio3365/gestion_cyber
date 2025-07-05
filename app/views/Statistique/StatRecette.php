<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques de vente</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- CSS personnalisé -->
    <link rel="stylesheet" href="/assets/css/stat.css">
</head>

<body>
    <div class="container">
        <h1><i class="fas fa-chart-line me-3"></i>Statistiques de vente</h1>

        <!-- Filtres -->
        <div class="filters">
            <!-- Sélection de la période -->
            <div class="btn-group d-flex justify-content-center">
                <a href="?period=jour&date=<?= date('Y-m-d') ?>"
                    class="btn btn-<?= $period === 'jour' ? 'primary' : 'secondary' ?>">
                    <i class="fas fa-calendar-day me-2"></i>Jour
                </a>
                <a href="?period=mois&date=<?= date('Y-m-d') ?>"
                    class="btn btn-<?= $period === 'mois' ? 'primary' : 'secondary' ?>">
                    <i class="fas fa-calendar-alt me-2"></i>Mois
                </a>
                <a href="?period=annee&date=<?= date('Y-m-d') ?>"
                    class="btn btn-<?= $period === 'annee' ? 'primary' : 'secondary' ?>">
                    <i class="fas fa-calendar me-2"></i>Année
                </a>
            </div>

            <!-- Sélection de la date -->
            <form method="get" class="row g-3 justify-content-center">
                <input type="hidden" name="period" value="<?= $period ?>">
                <div class="col-auto">
                    <label for="dateSelect" class="form-label">
                        <i class="fas fa-calendar-check me-2"></i>Date:
                    </label>
                </div>
                <div class="col-auto">
                    <input type="date" class="form-control" id="dateSelect" name="date"
                        value="<?= $date ?>"
                        <?= $period === 'annee' ? 'pattern="\d{4}"' : '' ?>>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Appliquer
                    </button>
                </div>
            </form>
        </div>

        <h2><i class="fas fa-calendar-check me-2"></i>Période: <?= $periodLabels[$period] ?> (<?= $date ?>)</h2>

        <!-- Cartes de statistiques -->
        <div class="row">
            <?php
            // Préparer les données pour chaque type
            $types = [
                'produit' => ['label' => 'Fourniture', 'color' => 'success', 'icon' => 'fas fa-box'],
                'multi' => ['label' => 'Multi Service', 'color' => 'info', 'icon' => 'fas fa-cogs'],
                'connexion' => ['label' => 'Connexion', 'color' => 'warning', 'icon' => 'fas fa-wifi']
            ];

            // Récupérer les données pour chaque type et calculer le total
            $allStats = [];
            $totalCA = 0;
            $labels = [];
            $dataValues = [];
            $backgroundColors = [];

            foreach ($types as $typeKey => $typeData) {
                try {
                    $stats = Flight::statModel()->getAggregatedStats($typeKey, $period, $date);
                    $ca = $stats[0]['chiffre_affaires'] ?? 0;
                    $allStats[] = [
                        'type' => $typeKey,
                        'label' => $typeData['label'],
                        'color' => $typeData['color'],
                        'icon' => $typeData['icon'],
                        'ca' => $ca
                    ];
                    $totalCA += $ca;

                    // Données pour les graphiques
                    $labels[] = $typeData['label'];
                    $dataValues[] = $ca;

                    // Définir les couleurs correspondantes
                    $colorMap = [
                        'success' => 'rgba(40, 167, 69, 0.7)',
                        'info' => 'rgba(23, 162, 184, 0.7)',
                        'warning' => 'rgba(255, 193, 7, 0.7)'
                    ];
                    $backgroundColors[] = $colorMap[$typeData['color']];
                } catch (Exception $e) {
                    // Ignorer les erreurs pour l'affichage
                }
            }

            // Trier par chiffre d'affaires (du plus bas au plus haut)
            usort($allStats, function ($a, $b) {
                return $a['ca'] <=> $b['ca'];
            });

            // Afficher chaque carte
            foreach ($allStats as $stat):
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card border-<?= $stat['color'] ?>">
                        <div class="card-header bg-<?= $stat['color'] ?> text-white">
                            <h3 class="card-title text-center">
                                <i class="<?= $stat['icon'] ?> me-2"></i><?= $stat['label'] ?>
                            </h3>
                        </div>
                        <div class="card-body text-center">
                            <h4 class="card-text">
                                <i class="fas fa-coins me-2"></i><?= number_format($stat['ca'], 0, ',', ' ') ?> Ar
                            </h4>
                            <?php if ($totalCA > 0): ?>
                                <div class="progress mt-3">
                                    <div class="progress-bar bg-<?= $stat['color'] ?>"
                                        role="progressbar"
                                        style="width: <?= ($stat['ca'] / $totalCA) * 100 ?>%"
                                        aria-valuenow="<?= ($stat['ca'] / $totalCA) * 100 ?>"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                        <?= number_format(($stat['ca'] / $totalCA) * 100, 1) ?>%
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Graphiques -->
        <div class="row mt-4">
            <div class="col-md-12"> <!-- élargi de 6 à 12 colonnes -->
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-chart-bar me-2"></i>Comparaison par catégorie</h3>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Tableau récapitulatif -->
        <div class="card mt-4">
            <div class="card-header">
                <h3><i class="fas fa-table me-2"></i>Récapitulatif</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th><i class="fas fa-tag me-2"></i>Type</th>
                                <th><i class="fas fa-money-bill-wave me-2"></i>Chiffre d'affaires</th>
                                <th><i class="fas fa-percentage me-2"></i>Pourcentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allStats as $stat): ?>
                                <tr>
                                    <td>
                                        <i class="<?= $stat['icon'] ?> me-2"></i><?= $stat['label'] ?>
                                    </td>
                                    <td>
                                        <strong><?= number_format($stat['ca'], 0, ',', ' ') ?> Ar</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress me-2" style="width: 100px;">
                                                <div class="progress-bar bg-<?= $stat['color'] ?>"
                                                    role="progressbar"
                                                    style="width: <?= $totalCA > 0 ? ($stat['ca'] / $totalCA) * 100 : 0 ?>%">
                                                </div>
                                            </div>
                                            <span class="badge bg-<?= $stat['color'] ?>">
                                                <?php if ($totalCA > 0): ?>
                                                    <?= number_format(($stat['ca'] / $totalCA) * 100, 1) ?>%
                                                <?php else: ?>
                                                    0%
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="table-primary fw-bold">
                                <td>
                                    <i class="fas fa-calculator me-2"></i><strong>TOTAL</strong>
                                </td>
                                <td>
                                    <strong><?= number_format($totalCA, 0, ',', ' ') ?> Ar</strong>
                                </td>
                                <td>
                                    <span class="badge bg-primary">100%</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script Chart.js inchangé
        document.addEventListener('DOMContentLoaded', function() {
            const labels = <?= json_encode($labels) ?>;
            const dataValues = <?= json_encode($dataValues) ?>;
            const backgroundColors = [
                'rgba(40, 167, 69, 0.7)',
                'rgba(23, 162, 184, 0.7)',
                'rgba(255, 193, 7, 0.7)'
            ];
            const borderColors = [
                'rgba(40, 167, 69, 1)',
                'rgba(23, 162, 184, 1)',
                'rgba(255, 193, 7, 1)'
            ];

            const barCtx = document.getElementById('barChart').getContext('2d');
            const barChart = new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Chiffre d\'affaires (Ar)',
                        data: dataValues,
                        backgroundColor: backgroundColors,
                        borderColor: borderColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value + ' Ar';
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y.toFixed(0) + ' Ar';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>