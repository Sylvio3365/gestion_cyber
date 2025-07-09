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
    <!-- CSS personnalisé pour les statistiques -->
    <link rel="stylesheet" href="/assets/css/stat.css">
</head>

<body>
<div class="dashboard-container">
    <div class="card">
        <!-- Entête -->
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-pie me-2"></i>Statistiques des produits vendus
            </h3>
        </div>

        <!-- Corps -->
        <div class="card-body">
            <!-- Formulaire de filtrage -->
            <div class="filter-section">
                <form method="get" action="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                    <div class="row g-3">
                        <!-- Branche -->
                        <div class="col-md-4">
                            <label class="form-label" for="branche">
                                <i class="fas fa-building me-2"></i>Branche
                            </label>
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
                            <label class="form-label" for="date_debut">
                                <i class="fas fa-calendar-alt me-2"></i>Date début
                            </label>
                            <input  type="date"
                                    id="date_debut"
                                    class="form-control"
                                    name="date_debut"
                                    required
                                    value="<?= htmlspecialchars($date_debut ?? '') ?>">
                        </div>

                        <!-- Date fin -->
                        <div class="col-md-3">
                            <label class="form-label" for="date_fin">
                                <i class="fas fa-calendar-check me-2"></i>Date fin
                            </label>
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

            <?php if (!empty($resultats)): ?>
                <!-- Diagramme circulaire -->
                <div class="chart-container">
                    <canvas id="chart" aria-label="Diagramme circulaire des ventes par produit"></canvas>
                </div>

                <!-- Tableau détaillé -->
                <div class="table-responsive mt-4">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th><i class="fas fa-box me-2"></i>Produit</th>
                            <th class="text-end"><i class="fas fa-sort-numeric-down me-2"></i>Quantité vendue</th>
                            <th class="text-end"><i class="fas fa-percentage me-2"></i>Pourcentage</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $total = array_sum(array_column($resultats, 'quantite_total'));
                        foreach ($resultats as $row): 
                            $percentage = round(($row['quantite_total'] / $total) * 100, 2);
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nom_objet']) ?></td>
                                <td class="text-end">
                                    <strong><?= number_format($row['quantite_total'], 0, ',', ' ') ?></strong>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-primary"><?= $percentage ?>%</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Script Chart.js -->
                <script>
                    (function () {
                        const labels = <?= json_encode(array_column($resultats, 'nom_objet')) ?>;
                        const data   = <?= json_encode(array_column($resultats, 'quantite_total')) ?>;

                        /* Couleurs dynamiques : palette HSL */
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
                                cutout: '60%' // Pour un diagramme donut
                            }
                        });
                    })();
                </script>

            <?php elseif (($branche ?? null) && ($date_debut ?? null) && ($date_fin ?? null)): ?>
                <!-- Aucune donnée -->
                <div class="no-data">
                    <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
                    <h4>Aucune donnée disponible</h4>
                    <p>Aucun produit vendu trouvé pour cette période et cette branche.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>