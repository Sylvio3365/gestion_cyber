
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Paramètres WiFi</title>
    <style>
        .error {
            color: #c00;
            margin: 1em 0;
        }

        .message {
            color: #060;
            margin: 1em 0;
        }

        form {
            margin: 1em 0;
        }

        input[type="text"] {
            padding: 0.5em;
            width: 250px;
        }

        button {
            padding: 0.5em 1em;
        }

        .qr-code {
            margin: 1em 0;
        }
    </style>
</head>

<body>
    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error, ENT_QUOTES) ?></div>
    <?php endif; ?>

    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message, ENT_QUOTES) ?></div>
    <?php endif; ?>

    <form action="/parametre/mdp" method="post">
        <input
            type="text"
            name="mdp"
            placeholder="Entrez un nouveau mot de passe"
            value="<?= isset($_POST['mdp']) ? htmlspecialchars($_POST['mdp'], ENT_QUOTES) : '' ?>" />
        <button type="submit">Valider</button>
    </form>

    <?php if (isset($mdp) && $mdp !== null): ?>
        <div>
            <p>Mot de passe actuel : <strong><?= htmlspecialchars($mdp, ENT_QUOTES) ?></strong></p>
        </div>
    <?php endif; ?>

    <?php if (!empty($qrFile)): ?>
        <div class="qr-code">
            <p>QR Code du mot de passe :</p>
            <img src="<?= htmlspecialchars($qrFile, ENT_QUOTES) ?>" alt="QR Code du mot de passe" />
        </div>
    <?php endif; ?>
</body>

</html>