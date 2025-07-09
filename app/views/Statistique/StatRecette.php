<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Statistiques de vente par branche</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container py-4">
        <!-- En-tête principale -->
        <div class="card shadow mb-4">
            <div class="card-body text-center">
                <h1 class="mb-0"><i class="fas fa-chart-line me-2"></i>Statistiques de vente</h1>
            </div>
        </div>

        <!-- Filtres -->
        <div class="card shadow mb-4">
            <div class="card-header"><i class="fas fa-filter me-2"></i>Filtres</div>
            <div class="card-body">
                <div class="btn-group mb-3">
                    <?php foreach (['jour' => 'Jour', 'mois' => 'Mois', 'annee' => 'Année'] as $key => $label): ?>
                        <a href="?period=<?= $key ?>&date=<?= date('Y-m-d') ?>"
                            class="btn btn-<?= $period === $key ? 'primary' : 'secondary' ?>">
                            <?= $label ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                <div class="text-center">
                    <form method="get" class="row g-2 justify-content-center align-items-center">
                        <input type="hidden" name="period" value="<?= $period ?>">
                        <div class="col-auto">
                            <input type="date" name="date" class="form-control" value="<?= $date ?>">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-outline-primary">
                                <i class="fas fa-search me-1"></i>Appliquer
                            </button>
                        </div>
                    </form>
                    <div class="mt-2">
                        <strong><i class="fas fa-calendar-check me-1"></i>Période :</strong>
                        <?= $periodLabels[$period] ?> (<?= $date ?>)
                    </div>
                </div>

            </div>
        </div>

        <?php
        // Traitement des données
        $grouped = [];
        $totalCA = 0;
        $labels = [];
        $dataValues = [];
        $backgroundColors = [];
        $borderColors = [];

        $colors = ['primary', 'success', 'info', 'warning', 'danger', 'secondary'];
        $colorBg = ['rgba(0,123,255,0.7)', 'rgba(40,167,69,0.7)', 'rgba(23,162,184,0.7)', 'rgba(255,193,7,0.7)', 'rgba(220,53,69,0.7)', 'rgba(108,117,125,0.7)'];
        $colorBorder = ['rgba(0,123,255,1)', 'rgba(40,167,69,1)', 'rgba(23,162,184,1)', 'rgba(255,193,7,1)', 'rgba(220,53,69,1)', 'rgba(108,117,125,1)'];

        $i = 0;
        foreach ($stats as $row) {
            $branche = $row['nom_branche'];
            if (!isset($grouped[$branche])) {
                $grouped[$branche] = [
                    'nom_branche' => $branche,
                    'ca' => 0,
                    'quantite' => 0,
                    'color' => $colors[$i % count($colors)],
                    'bg' => $colorBg[$i % count($colorBg)],
                    'border' => $colorBorder[$i % count($colorBorder)],
                ];
                $i++;
            }
            $grouped[$branche]['ca'] += $row['chiffre_affaires'];
            $grouped[$branche]['quantite'] += $row['total_quantite'];
            $totalCA += $row['chiffre_affaires'];
        }

        foreach ($grouped as $g) {
            $labels[] = $g['nom_branche'];
            $dataValues[] = $g['ca'];
            $backgroundColors[] = $g['bg'];
            $borderColors[] = $g['border'];
        }

        usort($grouped, fn($a, $b) => $b['ca'] <=> $a['ca']);
        ?>

        <!-- Cartes par branche -->
        <div class="row">
            <?php foreach ($grouped as $stat): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-<?= $stat['color'] ?> shadow h-100">
                        <div class="card-header bg-<?= $stat['color'] ?> text-white text-center">
                            <i class="fas fa-store me-1"></i><?= $stat['nom_branche'] ?>
                        </div>
                        <div class="card-body text-center">
                            <h4 class="mb-2"><?= number_format($stat['ca'], 0, ',', ' ') ?> Ar</h4>
                            <?php if ($totalCA > 0): ?>
                                <div class="progress">
                                    <div class="progress-bar bg-<?= $stat['color'] ?>"
                                        style="width: <?= ($stat['ca'] / $totalCA) * 100 ?>%">
                                        <?= number_format(($stat['ca'] / $totalCA) * 100, 1) ?>%
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Graphique -->
        <div class="card mt-4 shadow">
            <div class="card-header">
                <i class="fas fa-chart-bar me-2"></i>Chiffre d'affaires par branche
            </div>
            <div class="card-body">
                <canvas id="barChart" height="100"></canvas>
            </div>
        </div>

        <!-- Tableau -->
        <div class="card mt-4 shadow">
            <div class="card-header">
                <i class="fas fa-table me-2"></i>Tableau récapitulatif
            </div>
            <div class="card-body table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Branche</th>
                            <th>Chiffre d'affaires</th>
                            <th>Pourcentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($grouped as $stat): ?>
                            <tr>
                                <td><i class="fas fa-store me-1"></i><?= $stat['nom_branche'] ?></td>
                                <td><strong><?= number_format($stat['ca'], 0, ',', ' ') ?> Ar</strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress me-2" style="width: 100px;">
                                            <div class="progress-bar bg-<?= $stat['color'] ?>"
                                                style="width: <?= $totalCA ? ($stat['ca'] / $totalCA) * 100 : 0 ?>%">
                                            </div>
                                        </div>
                                        <span class="badge bg-<?= $stat['color'] ?>">
                                            <?= number_format(($stat['ca'] / $totalCA) * 100, 1) ?>%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="table-primary fw-bold">
                            <td>Total</td>
                            <td><?= number_format($totalCA, 0, ',', ' ') ?> Ar</td>
                            <td><span class="badge bg-primary">100%</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('barChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($labels) ?>,
                    datasets: [{
                        label: 'Chiffre d\'affaires (Ar)',
                        data: <?= json_encode($dataValues) ?>,
                        backgroundColor: <?= json_encode($backgroundColors) ?>,
                        borderColor: <?= json_encode($borderColors) ?>,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => value.toLocaleString() + ' Ar'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: ctx => ctx.parsed.y.toLocaleString() + ' Ar'
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>