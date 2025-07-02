<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Calcul du Bénéfice</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        form { margin-bottom: 20px; }
        input[type="submit"] { padding: 6px 12px; }
        .result { font-weight: bold; margin-top: 20px; color: green; }
        .branche-result { margin-top: 10px; color: navy; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>

    <h2>Calcul du bénéfice</h2>
    <p>Remplis un des champs suivants pour effectuer le calcul :</p>

    <form method="post" action="/benefice">
        <label for="date">Date exacte (YYYY-MM-DD) :</label><br>
        <input type="date" name="date" id="date"><br><br>

        <label for="mois">Mois :</label><br>
        <input type="number" name="mois" min="1" max="12" id="mois"><br><br>

        <label for="annee">Année :</label><br>
        <input type="number" name="annee" min="2000" max="2100" id="annee"><br><br>

        <input type="submit" value="Calculer">
    </form>

    <?php if (isset($benefice)): ?>
        <div class="result">
            <h3>Bénéfice global :</h3>
            <p><?php echo $benefice['total']; ?></p>
        </div>

        <div class="branche-result">
            <h3>Détail du bénéfice par branche :</h3>
            <table>
                <thead>
                    <tr>
                        <th>Branche</th>
                        <th>Ventes</th>
                        <th>Achats</th>
                        <th>Bénéfice</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($benefice['branches'] as $branche => $details): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($branche); ?></td>
                        <td><?php echo $details['vente']; ?></td>
                        <td><?php echo $details['achat']; ?></td>
                        <td><?php echo $details['benefice']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</body>
</html>