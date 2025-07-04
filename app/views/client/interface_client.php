<div class="client-interface">
    <!-- Bienvenue Banner -->
    <div class="welcome-banner">
        <h1>Bienvenue au CyBer</h1>
        <p>Choisissez vos services et produits</p>
        <div class="info-badges">
            <div class="info-badge">
                <button class="btn btn-success btn-lg" onclick="scrollToSection('services-section')">
                    <i class="bi bi-printer me-2"></i>
                    Services Bureautiques
                </button>
            </div>
            <div class="info-badge">
                <button class="btn btn-warning btn-lg" onclick="scrollToSection('fournitures-section')">
                    <i class="bi bi-basket me-2"></i>
                    Fournitures
                </button>
            </div>
        </div>
    </div>
    <!-- Services Bureautiques Section -->
    <div class="service-section" id="services-section">
        <div class="service-header">
            <i class="bi bi-printer"></i>
            <h2>Services Bureautiques</h2>
            <p>Impression, scan, photocopie et plus</p>
        </div>
        <div class="services-grid">
            <div class="row">
                <?php foreach ($services as $service): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="service-item">
                            <div class="service-icon purple">
                                <i class="bi bi-printer"></i>
                            </div>
                            <div class="service-info">
                                <h4><?= htmlspecialchars($service['nom']) ?></h4>
                                <p><?= htmlspecialchars($service['description'] ?? '') ?></p>
                                <?php if ($service['prix'] !== null): ?>
                                    <div class="price"><?= number_format($service['prix'], 0, ',', ' ') ?> Ar</div>
                                <?php endif; ?>
                                <div class="unit">par <?= htmlspecialchars($service['unite'] ?? 'unité') ?></div>
                            </div>
                            <form method="post" action="/interface-client/ajouter-panier" class="quantity-control">
                                <input type="hidden" name="id_service" value="<?= $service['id_service'] ?>">
                                <input type="hidden" name="prix_unitaire" value="<?= $service['prix'] ?>">
                                <input type="number" name="quantite" min="1" value="1" class="form-control form-control-sm">
                                <button type="submit" class="btn btn-sm btn-purple">
                                    <i class="bi bi-cart-plus"></i> Ajouter
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Fournitures Section -->
    <div class="service-section" id="fournitures-section">
        <div class="service-header">
            <i class="bi bi-basket"></i>
            <h2>Fournitures Bureautiques</h2>
            <p>Papeterie et accessoires de bureau</p>
        </div>
        <div class="supplies-grid">
            <div class="row">
                <?php foreach ($produits as $produit): ?>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="supply-item">
                            <div class="supply-icon">
                                <i class="bi bi-basket"></i>
                            </div>
                            <div class="supply-info">
                                <h4><?= htmlspecialchars($produit['nom']) ?></h4>
                                <p>Stock: <?= htmlspecialchars($produit['stock'] ?? '-') ?></p>
                                <div class="price"><?= number_format($produit['prix'], 0, ',', ' ') ?> Ar</div>
                                <div class="unit">par <?= htmlspecialchars($produit['unite'] ?? 'pièce') ?></div>
                            </div>
                            <form method="post" action="/interface-client/ajouter-panier" class="quantity-control">
                                <input type="hidden" name="id_produit" value="<?= $produit['id_produit'] ?>">
                                <input type="hidden" name="prix_unitaire" value="<?= $produit['prix'] ?>">
                                <input type="number" name="quantite" min="1" value="1" class="form-control form-control-sm">
                                <button type="submit" class="btn btn-sm btn-orange">Acheter</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>