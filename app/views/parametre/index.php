    <div class="">
        <div class="card mx-auto p-4" style="max-width: 500px;">
            <h4 class="mb-3 text-center">Paramètres WiFi</h4>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES) ?></div>
            <?php endif; ?>

            <?php if (!empty($message)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($message, ENT_QUOTES) ?></div>
            <?php endif; ?>

            <form action="/parametre/mdp" method="post">
                <div class="mb-3">
                    <input
                        type="text"
                        name="mdp"
                        class="form-control"
                        placeholder="Entrez un nouveau mot de passe"
                        value="<?= isset($_POST['mdp']) ? htmlspecialchars($_POST['mdp'], ENT_QUOTES) : '' ?>">
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Valider</button>
                </div>
            </form>

            <?php if (isset($mdp) && $mdp !== null): ?>
                <div class="mt-3 text-center">
                    <p>Mot de passe actuel : <strong><?= htmlspecialchars($mdp, ENT_QUOTES) ?></strong></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($qrUrl)): ?>
                <hr>
                <div class="qr-code text-center">
                    <h5>QR Code généré</h5>
                    <img src="<?= htmlspecialchars($qrUrl) ?>" alt="QR Code du mot de passe Wi-Fi" class="img-fluid mt-2" style="max-width: 100px;">
                </div>
            <?php endif; ?>
        </div>
    </div>