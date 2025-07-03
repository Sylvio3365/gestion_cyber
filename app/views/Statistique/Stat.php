<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques Produits</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --accent-color: #2e59d9;
            --text-color: #5a5c69;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fc;
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
            color: white;
            font-weight: 600;
            padding: 1rem 1.35rem;
            border-bottom: none;
            border-radius: 0.35rem 0.35rem 0 0 !important;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .form-control, .form-select {
            border-radius: 0.35rem;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d3e2;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
        }
        
        .btn-primary:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }
        
        .chart-container {
            position: relative;
            height: 400px;
            margin-top: 2rem;
        }
        
        .filter-section {
            background-color: white;
            padding: 1.5rem;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 2rem;
        }
        
        .no-data {
            text-align: center;
            padding: 3rem;
            background-color: white;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }
        
        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .filter-row {
            margin-bottom: 1.5rem;
        }
        
        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem;
            }
            
            .chart-container {
                height: 300px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-chart-bar me-2"></i>
                    Statistiques des produits vendus
                </div>
            </div>
            
            <div class="card-body">
                <div class="filter-section">
                    <form method="get" action="/Stat">
                        <div class="row filter-row">
                            <div class="col-md-4">
                                <label class="form-label">Branche</label>
                                <select class="form-select" name="branche" required>
                                    <option value="">-- Choisir une branche --</option>
                                    <?php foreach ($branches as $b): ?>
                                        <option value="<?= $b['id_branche'] ?>" <?= ($branche == $b['id_branche']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($b['nom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Date début</label>
                                <input type="date" class="form-control" name="date_debut" required value="<?= htmlspecialchars($date_debut ?? '') ?>">
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Date fin</label>
                                <input type="date" class="form-control" name="date_fin" required value="<?= htmlspecialchars($date_fin ?? '') ?>">
                            </div>
                            
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-2"></i>Filtrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <?php if (!empty($resultats)): ?>
                    <div class="chart-container">
                        <canvas id="chart"></canvas>
                    </div>
                    
                    <div class="table-responsive mt-4">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Produit</th>
                                    <th class="text-end">Quantité vendue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($resultats as $row): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['produit']) ?></td>
                                        <td class="text-end"><?= number_format($row['quantite_total'], 0, ',', ' ') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <script>
                        const ctx = document.getElementById('chart').getContext('2d');
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: <?= json_encode(array_column($resultats, 'produit')) ?>,
                                datasets: [{
                                    label: 'Quantité vendue',
                                    data: <?= json_encode(array_column($resultats, 'quantite_total')) ?>,
                                    backgroundColor: 'rgba(78, 115, 223, 0.8)',
                                    borderColor: 'rgba(78, 115, 223, 1)',
                                    borderWidth: 1,
                                    borderRadius: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'top',
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return context.parsed.y.toLocaleString();
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function(value) {
                                                return value.toLocaleString();
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    </script>
                <?php elseif ($branche && $date_debut && $date_fin): ?>
                    <div class="no-data">
                        <i class="fas fa-exclamation-circle fa-3x text-muted mb-3"></i>
                        <h4>Aucune donnée disponible</h4>
                        <p class="text-muted">Aucun produit vendu trouvé pour cette période et cette branche.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>