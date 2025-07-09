<div class="client-interface">
    <!-- Bienvenue Banner -->
    <div class="welcome-banner text-center mb-5">
        <h1>Bienvenue au e-Cyber</h1>
        <p>Choisissez vos services et produits selon votre besoin</p>
    </div>

    <!-- Affichage par branche -->
    <?php foreach ($branches as $branche): ?>
        <div class="service-section mb-5" id="<?= strtolower(str_replace(' ', '-', $branche['nom_branche'])) ?>-section">
            <div class="service-header">
                <h2><i class="bi bi-diagram-3 me-2"></i><?= htmlspecialchars($branche['nom_branche']) ?></h2>
            </div>

            <div class="row">
                <?php foreach ($branche['articles'] as $article): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="item-box p-3 shadow rounded">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi <?= $article['type'] === 'produit' ? 'bi-basket' : 'bi-printer' ?> me-2 fs-4"></i>
                                <h4 class="m-0"><?= htmlspecialchars($article['nom']) ?></h4>
                            </div>
                            <p><?= htmlspecialchars($article['description'] ?? '') ?></p>
                            <div class="price mb-2">
                                <?php if ($article['prix'] === null): ?>
                                    <span class="text-muted">Aucun prix défini</span>
                                <?php else: ?>
                                    <?= number_format($article['prix'], 0, ',', ' ') ?> Ar
                                <?php endif; ?>
                            </div>

                            <?php if ($article['type'] === 'produit'): ?>
                                <div class="mb-2">Stock : <?= htmlspecialchars($article['stock'] ?? '-') ?></div>
                            <?php endif; ?>

                            <form method="post" action="/interface-client/ajouter-panier" class="d-flex gap-2 align-items-center">
                                <?php if ($article['type'] === 'produit'): ?>
                                    <input type="hidden" name="id_produit" value="<?= $article['id'] ?>">
                                <?php else: ?>
                                    <input type="hidden" name="id_service" value="<?= $article['id'] ?>">
                                <?php endif; ?>
                                <input type="hidden" name="prix_unitaire" value="<?= $article['prix'] ?>">
                                <input type="number" name="quantite" min="1" value="1" class="form-control form-control-sm w-25">
                                <button type="submit" class="btn btn-sm <?= $article['type'] === 'produit' ? 'btn-orange' : 'btn-purple' ?>">
                                    <i class="bi bi-cart-plus"></i> Ajouter
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>