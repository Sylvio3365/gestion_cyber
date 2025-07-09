<div class="container my-5">
    <div class="card mx-auto p-4 shadow rounded-4" style="max-width: 500px;">
        <h4 class="mb-4 text-center">Paramètres WiFi</h4>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($message)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form action="/parametre/mdp" method="post">
            <div class="mb-3">
                <label for="mdp" class="form-label">Nouveau mot de passe</label>
                <input
                    type="text"
                    name="mdp"
                    id="mdp"
                    class="form-control"
                    placeholder="Entrez un nouveau mot de passe"
                    value="<?= isset($_POST['mdp']) ? htmlspecialchars($_POST['mdp'], ENT_QUOTES, 'UTF-8') : '' ?>"
                    required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Valider</button>
            </div>
        </form>

        <?php if (isset($mdp) && $mdp !== null): ?>
            <div class="mt-4 text-center">
                <p class="mb-0">Mot de passe actuel :</p>
                <strong><?= htmlspecialchars($mdp, ENT_QUOTES, 'UTF-8') ?></strong>
            </div>
        <?php endif; ?>

        <?php if (!empty($qrUrl)): ?>
            <hr>
            <div class="qr-code text-center mt-3">
                <h5 class="mb-2">QR Code</h5>
                <img
                    src="<?= htmlspecialchars($qrUrl, ENT_QUOTES, 'UTF-8') ?>"
                    alt="QR Code du mot de passe Wi-Fi"
                    class="img-fluid"
                    style="max-width: 200px;">
            </div>
        <?php endif; ?>
    </div>
</div>