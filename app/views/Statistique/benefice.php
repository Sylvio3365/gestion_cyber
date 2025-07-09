<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcul du Bénéfice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/benef.css">
</head>

<body>
    <div class="container">
        <h2>Calcul du bénéfice</h2>

        <div class="form-container">
        <form method="get" action="/benefice">
    <div class="form-group mb-3">
        <label for="filtre_type">Filtrer par :</label>
        <select name="filtre_type" id="filtre_type" class="form-control">
            <option value="">-- Sélectionner --</option>
            <option value="jour">Date exacte</option>
            <option value="mois">Mois + année</option>
            <option value="annee">Année uniquement</option>
        </select>
    </div>

    <div id="filtre-jour" class="form-group mb-3" style="display: none;">
        <label for="date">Date exacte :</label>
        <input type="date" name="date" id="date" class="form-control" placeholder="YYYY-MM-DD">
    </div>

    <div id="filtre-mois" class="form-group mb-3" style="display: none;">
        <label for="mois">Mois :</label>
        <input type="number" name="mois" min="1" max="12" id="mois" class="form-control" placeholder="1-12">
    </div>

    <div id="filtre-annee" class="form-group mb-3" style="display: none;">
        <label for="annee">Année :</label>
        <input type="number" name="annee" min="2000" max="2100" id="annee" class="form-control" placeholder="2000-2100">
    </div>

    <input type="submit" value="Calculer" class="btn btn-primary">
</form>

<script>
    const filtreType = document.getElementById('filtre_type');
    const filtreJour = document.getElementById('filtre-jour');
    const filtreMois = document.getElementById('filtre-mois');
    const filtreAnnee = document.getElementById('filtre-annee');

    function afficherChamps() {
        const type = filtreType.value;

        // Réinitialiser tous les champs
        filtreJour.style.display = 'none';
        filtreMois.style.display = 'none';
        filtreAnnee.style.display = 'none';

        if (type === 'jour') {
            filtreJour.style.display = 'block';
        } else if (type === 'mois') {
            filtreMois.style.display = 'block';
            filtreAnnee.style.display = 'block';
        } else if (type === 'annee') {
            filtreAnnee.style.display = 'block';
        }
    }

    filtreType.addEventListener('change', afficherChamps);

    // Pour conserver l'affichage après rechargement si un type a déjà été sélectionné
    window.addEventListener('DOMContentLoaded', afficherChamps);
</script>


        </div>

        <?php if (isset($benefice)): ?>
    <div class="result">
        <h3>Bénéfice global :</h3>
        <p class="result-value"><?php echo $benefice['total']; ?></p>
    </div>

    <div class="charts-container">
        <div class="chart-wrapper">
            <div class="chart-title">Répartition des Bénéfices</div>
            <canvas id="chartBenefice"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

    <script>
        const couleursBenefice = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
            '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
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
                        font: {
                            size: 16
                        }
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
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
    </script>
<?php endif; ?>

    </div>

</body>

</html>