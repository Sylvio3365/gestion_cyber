<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques de vente</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container">
        <h1 class="text-center mb-4">Statistiques de vente</h1>

        <!-- Filtres -->
        <div class="filters mb-4">
            <!-- Sélection de la période -->
            <div class="btn-group mb-3 d-flex justify-content-center">
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
            </div>

            <!-- Sélection de la date -->
            <form method="get" class="row g-3 justify-content-center mb-3">
                <input type="hidden" name="period" value="<?= $period ?>">

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

        <h2 class="text-center mb-4">Période: <?= $periodLabels[$period] ?> (<?= $date ?>)</h2>

        <!-- Cartes de statistiques -->
        <div class="row">
            <?php
            // Préparer les données pour chaque type
            $types = [
                'produit' => ['label' => 'Fourniture', 'color' => 'success'],
                'multi' => ['label' => 'Multi Service', 'color' => 'info'],
                'connexion' => ['label' => 'Connexion', 'color' => 'warning']
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
                        'ca' => $ca
                    ];
                    $totalCA += $ca;

                    // Données pour les graphiques
                    $labels[] = $typeData['label'];
                    $dataValues[] = $ca;

                    // Définir les couleurs correspondantes
                    $colorMap = [
                        'success' => 'rgba(40, 167, 69, 0.7)',   // Vert
                        'info' => 'rgba(23, 162, 184, 0.7)',     // Bleu clair
                        'warning' => 'rgba(255, 193, 7, 0.7)'    // Jaune
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
                            <h3 class="card-title text-center"><?= $stat['label'] ?></h3>
                        </div>
                        <div class="card-body text-center">
                            <h4 class="card-text"><?= number_format($stat['ca'], 0, ',', ' ') ?> Ar</h4>
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
            <!-- Barres -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Comparaison par catégorie</h3>
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
                <h3>Récapitulatif</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Chiffre d'affaires</th>
                            <th>Pourcentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allStats as $stat): ?>
                            <tr>
                                <td><?= $stat['label'] ?></td>
                                <td><?= number_format($stat['ca'], 0, ',', ' ') ?> Ar</td>
                                <td>
                                    <?php if ($totalCA > 0): ?>
                                        <?= number_format(($stat['ca'] / $totalCA) * 100, 1) ?>%
                                    <?php else: ?>
                                        0%
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="table-primary fw-bold">
                            <td>TOTAL</td>
                            <td><?= number_format($totalCA, 0, ',', ' ') ?> Ar</td>
                            <td>100%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const periodLinks = document.querySelectorAll('.btn-group a[href*="period="]');
            const dateInput = document.getElementById('dateSelect');
            const periodInput = document.querySelector('input[name="period"]');

            periodLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const url = new URL(this.href);
                    const period = url.searchParams.get('period');

                    // Mise à jour du champ caché period
                    periodInput.value = period;

                    // Adaptation du type de input date
                    if (period === 'annee') {
                        dateInput.type = 'number';
                        dateInput.min = '2000';
                        dateInput.max = '<?= date('Y') + 5 ?>';
                        dateInput.value = '<?= date('Y') ?>';
                    } else {
                        dateInput.type = 'date';
                    }
                });
            });

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