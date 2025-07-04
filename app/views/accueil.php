<?php
// Simule une session (à enlever si tu passes une vraie session depuis le contrôleur)
if (!isset($_SESSION['user'])) {
    echo "<p style='color:red;'>Aucun utilisateur connecté.</p>";
    exit;
}

$user = $_SESSION['user'];
$accountType = strtolower($user['role_name']); // 'Admin' ou 'Employé' (depuis account_type.name)
?>

<link rel="stylesheet" href="/assets/css/accueil.css">

<div class="dashboard-container py-4">
    <div class="welcome-section mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="welcome-title">Bienvenue, <?= htmlspecialchars($user['firstname']) ?> !</h1>
                <p class="welcome-subtitle">Tableau de bord <span class="badge account-type-badge"><?= htmlspecialchars($user['role_name']) ?></span></p>
            </div>
            <div class="col-md-4 text-end">
                <div class="current-date-time">
                    <div class="date"><?php echo date('d M Y'); ?></div>
                    <div class="time" id="currentTime"></div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($accountType === 'admin'): ?>
        <div class="admin-features-container">
            <h2 class="section-title"><i class="bi bi-shield-check me-2"></i></h2>
            
            <div class="row">
                <?php 
                $adminFeatures = [
                    ['url' => '/admin/marque', 'icon' => 'bi-tag', 'title' => 'Marques', 'desc' => 'Gérer les marques de produits'],
                    ['url' => '/admin/categorie', 'icon' => 'bi-clipboard', 'title' => 'Catégories', 'desc' => 'Organiser les produits'],
                    ['url' => '/admin/produit', 'icon' => 'bi-box', 'title' => 'Produits', 'desc' => 'Gérer l\'inventaire'],
                    ['url' => '/admin/service', 'icon' => 'bi bi-printer', 'title' => 'Services', 'desc' => 'Offres de services'],
                    ['url' => '/admin/branche', 'icon' => 'bi-archive', 'title' => 'Branches', 'desc' => 'Sections de l\'entreprise'],
                    ['url' => '/admin/stock', 'icon' => 'bi-boxes', 'title' => 'Stock', 'desc' => 'Gestion d\'inventaire'],
                    ['url' => '/admin/type_mouvement', 'icon' => 'bi-arrow-repeat', 'title' => 'Mouvements', 'desc' => 'Types de transactions'],
                    ['url' => '/benef_form', 'icon' => 'bi-graph-up', 'title' => 'Bénéfices', 'desc' => 'Voir les benefices'],
                ];
                
                foreach ($adminFeatures as $feature): ?>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <a href="<?= $feature['url'] ?>" class="feature-card">
                            <div class="feature-icon">
                                <i class="bi <?= $feature['icon'] ?>"></i>
                            </div>
                            <div class="feature-content">
                                <h3><?= $feature['title'] ?></h3>
                                <p><?= $feature['desc'] ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($accountType === 'vendeur'): ?>
        <div class="employee-features-container">
            <h2 class="section-title"><i class="bi bi-person-badge me-2"></i></h2>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <a href="/interface-client" class="feature-card large">
                        <div class="feature-icon">
                            <i class="bi bi-laptop"></i>
                        </div>
                        <div class="feature-content">
                            <h3>Interface Client</h3>
                            <p>Effectuer des ventes et gérer les achats des clients</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 mb-4">
                    <a href="/panier" class="feature-card large">
                        <div class="feature-icon">
                            <i class="bi bi-cart3"></i>
                        </div>
                        <div class="feature-content">
                            <h3>Mon panier</h3>
                            <p>Voir et gérer les articles dans le panier</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function updateTime() {
    const timeElement = document.getElementById('currentTime');
    const now = new Date();
    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    const seconds = now.getSeconds().toString().padStart(2, '0');
    timeElement.textContent = `${hours}:${minutes}:${seconds}`;
}

setInterval(updateTime, 1000);
updateTime();
</script>