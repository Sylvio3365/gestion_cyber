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

    <?php if (!empty($_SESSION['benefice_result'])): ?>
        <div class="result">
            Résultat : <?php echo $_SESSION['benefice_result']; ?>
        </div>
        <?php unset($_SESSION['benefice_result']); ?>
    <?php endif; ?>

</body>
</html>
