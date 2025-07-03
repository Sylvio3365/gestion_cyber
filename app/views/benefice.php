<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Calcul du Bénéfice</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 30px; 
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        form { 
            margin-bottom: 30px; 
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input[type="date"], input[type="number"] {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 200px;
        }
        input[type="submit"] { 
            padding: 10px 20px; 
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background: #0056b3;
        }
        .result { 
            font-weight: bold; 
            margin: 20px 0; 
            color: green;
            background: #e8f5e8;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        .charts-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 30px;
        }
        .chart-wrapper {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .chart-title {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            font-size: 18px;
            font-weight: bold;
        }
        canvas { 
            max-width: 100%; 
            height: 400px !important;
        }
        @media (max-width: 768px) {
            .charts-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📊 Calcul du bénéfice - Diagrammes en Camembert</h2>
    <p>Remplis un des champs suivants pour effectuer le calcul :</p>

    <form method="post" action="/benefice">
        <div class="form-group">
            <label for="date">Date exacte (YYYY-MM-DD) :</label>
            <input type="date" name="date" id="date">
        </div>

        <div class="form-group">
            <label for="mois">Mois :</label>
            <input type="number" name="mois" min="1" max="12" id="mois">
        </div>

        <div class="form-group">
            <label for="annee">Année :</label>
            <input type="number" name="annee" min="2000" max="2100" id="annee">
        </div>

        <input type="submit" value="📈 Calculer">
    </form>

    <?php if (isset($benefice)): ?>
    <div class="result">
        <h3>💰 Bénéfice global :</h3>
        <p style="font-size: 24px; margin: 10px 0;"><?php echo $benefice['total']; ?></p>
    </div>

    <div class="charts-container">
        <div class="chart-wrapper">
            <div class="chart-title">🎯 Répartition des Bénéfices</div>
            <canvas id="chartBenefice"></canvas>
        </div>
        
        <div class="chart-wrapper">
            <div class="chart-title">📈 Comparaison Ventes vs Achats</div>
            <canvas id="chartVentesAchats"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

    <script>
        const couleursBenefice = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
            '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
        ];
        
        const couleursVentesAchats = [
            '#28a745', '#dc3545'
        ];

        const branches = <?php echo json_encode(array_keys($benefice['branches'])); ?>;
        const benefices = <?php 
            $benefices = [];
            foreach ($benefice['branches'] as $branche => $details) {
                $beneficeValue = str_replace([' ', 'Ar'], '', $details['benefice']);
                $benefices[] = (float)$beneficeValue;
            }
            echo json_encode($benefices);
        ?>;
        
        const ventes = <?php 
            $ventes = [];
            foreach ($benefice['branches'] as $branche => $details) {
                $venteValue = str_replace([' ', 'Ar'], '', $details['vente']);
                $ventes[] = (float)$venteValue;
            }
            echo json_encode($ventes);
        ?>;
        
        const achats = <?php 
            $achats = [];
            foreach ($benefice['branches'] as $branche => $details) {
                $achatValue = str_replace([' ', 'Ar'], '', $details['achat']);
                $achats[] = (float)$achatValue;
            }
            echo json_encode($achats);
        ?>;

        const totalVentes = ventes.reduce((a, b) => a + b, 0);
        const totalAchats = achats.reduce((a, b) => a + b, 0);

        const ctxBenefice = document.getElementById('chartBenefice').getContext('2d');
        const chartBenefice = new Chart(ctxBenefice, {
            type: 'pie',
            data: {
                labels: branches,
                datasets: [{
                    label: 'Bénéfices (Ar)',
                    data: benefices,
                    backgroundColor: couleursBenefice,
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Répartition des bénéfices par branche',
                        font: { size: 16 }
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value.toLocaleString('fr-FR')} Ar (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        const ctxVentesAchats = document.getElementById('chartVentesAchats').getContext('2d');
        const chartVentesAchats = new Chart(ctxVentesAchats, {
            type: 'doughnut',
            data: {
                labels: ['Ventes Totales', 'Achats Totaux'],
                datasets: [{
                    data: [totalVentes, totalAchats],
                    backgroundColor: couleursVentesAchats,
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Répartition globale Ventes vs Achats',
                        font: { size: 16 }
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value.toLocaleString('fr-FR')} Ar (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    </script>
<?php endif; ?>

</div>

</body>
</html>