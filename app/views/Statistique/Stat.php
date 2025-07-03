<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques Produits</title>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --accent-color:  #2e59d9;
            --text-color:    #5a5c69;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--secondary-color);
            color: var(--text-color);
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 15px;
        }

        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 2rem;
        }

        .card-header {
            background-color: var(--primary-color);
            color: #fff;
            font-weight: 600;
            padding: 1rem 1.35rem;
            border-bottom: none;
            border-radius: 0.35rem 0.35rem 0 0 !important;
        }

        .card-body {
            padding: 2rem;
        }

        .form-control,
        .form-select {
            border-radius: 0.35rem;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d3e2;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color:     var(--primary-color);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: var(--accent-color);
            border-color:     var(--accent-color);
        }

        .chart-container {
            position: relative;
            height: 450px;
            margin-top: 2rem;
        }

        .filter-section {
            background-color: #fff;
            padding: 1.5rem;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 2rem;
        }

        .no-data {
            text-align: center;
            padding: 3rem;
            background-color: #fff;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }

        .form-label { font-weight: 600; }

        @media (max-width: 768px) {
            .card-body      { padding: 1.5rem; }
            .chart-container{ height: 300px;   }
        }
    </style>
</head>

<body>
<div class="dashboard-container">
    <div class="card">
        <!-- ===== Entête ===== -->
        <div class="card-header d-flex align-items-center">
            <i class="fas fa-chart-pie me-2"></i>
            Statistiques des produits vendus
        </div>

        <!-- ===== Corps ===== -->
        <div class="card-body">

            <!-- === Formulaire de filtrage === -->
            <div class="filter-section">
                <form method="get" action="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                    <div class="row g-3">

                        <!-- Branche -->
                        <div class="col-md-4">
                            <label class="form-label" for="branche">Branche</label>
                            <select id="branche" class="form-select" name="branche" required>
                                <option value="">-- Choisir une branche --</option>
                                <?php foreach ($branches as $b): ?>
                                    <option
                                        value="<?= $b['id_branche'] ?>"
                                        <?= (($branche ?? '') == $b['id_branche']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($b['nom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Date début -->
                        <div class="col-md-3">
                            <label class="form-label" for="date_debut">Date début</label>
                            <input  type="date"
                                    id="date_debut"
                                    class="form-control"
                                    name="date_debut"
                                    required
                                    value="<?= htmlspecialchars($date_debut ?? '') ?>">
                        </div>

                        <!-- Date fin -->
                        <div class="col-md-3">
                            <label class="form-label" for="date_fin">Date fin</label>
                            <input  type="date"
                                    id="date_fin"
                                    class="form-control"
                                    name="date_fin"
                                    required
                                    value="<?= htmlspecialchars($date_fin ?? '') ?>">
                        </div>

                        <!-- Bouton -->
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-2"></i>Filtrer
                            </button>
                        </div>

                    </div>
                </form>
            </div>
            <!-- === /Formulaire === -->

            <?php if (!empty($resultats)): ?>
                <!-- === Diagramme circulaire === -->
                <div class="chart-container">
                    <canvas id="chart" aria-label="Diagramme circulaire des ventes par produit"></canvas>
                </div>

                <!-- === Tableau détaillé === -->
                <div class="table-responsive mt-4">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                        <tr>
                            <th>Produit</th>
                            <th class="text-end">Quantité vendue</th>
                            <th class="text-end">Pourcentage</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $total = array_sum(array_column($resultats, 'quantite_total'));
                        foreach ($resultats as $row): 
                            $percentage = round(($row['quantite_total'] / $total) * 100, 2);
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($row['produit']) ?></td>
                                <td class="text-end"><?= number_format($row['quantite_total'], 0, ',', ' ') ?></td>
                                <td class="text-end"><?= $percentage ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- === Script Chart.js === -->
                <script>
                    (function () {
                        const labels = <?= json_encode(array_column($resultats, 'produit')) ?>;
                        const data   = <?= json_encode(array_column($resultats, 'quantite_total')) ?>;

                        /* Couleurs dynamiques : palette HSL */
                        const colors = labels.map((_, i) =>
                            `hsl(${(i * 50) % 360}, 70%, 55%)`
                        );

                        new Chart(document.getElementById('chart'), {
                            type: 'pie',
                            data: {
                                labels: labels,
                                datasets: [{
                                    data: data,
                                    backgroundColor: colors,
                                    borderColor: '#fff',
                                    borderWidth: 2
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'right',
                                        labels: {
                                            padding: 20,
                                            usePointStyle: true,
                                            pointStyle: 'circle'
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                const label = context.label || '';
                                                const value = context.raw || 0;
                                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                const percentage = Math.round((value / total) * 100);
                                                return `${label}: ${value.toLocaleString()} (${percentage}%)`;
                                            }
                                        }
                                    }
                                },
                                cutout: '60%' // Pour un diagramme donut (vide au centre)
                            }
                        });
                    })();
                </script>

            <?php elseif (($branche ?? null) && ($date_debut ?? null) && ($date_fin ?? null)): ?>
                <!-- === Aucune donnée === -->
                <div class="no-data">
                    <i class="fas fa-exclamation-circle fa-3x text-muted mb-3"></i>
                    <h4>Aucune donnée disponible</h4>
                    <p class="text-muted">
                        Aucun produit vendu trouvé pour cette période et cette branche.
                    </p>
                </div>
            <?php endif; ?>

        </div><!-- /.card-body -->
    </div><!-- /.card -->
</div><!-- /.dashboard-container -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>