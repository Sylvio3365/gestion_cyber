<?php
// Simule une session (à enlever si tu passes une vraie session depuis le contrôleur)
if (!isset($_SESSION['user'])) {
    echo "<p style='color:red;'>Aucun utilisateur connecté.</p>";
    exit;
}

$user = $_SESSION['user'];
$accountType = strtolower($user['role_name']); // 'Admin' ou 'Employé' (depuis account_type.name)
?>

<div class="dashboard-content">
    <h1>Bienvenue, <?= htmlspecialchars($user['firstname']) ?> !</h1>
    <p>Type de compte : <strong><?= htmlspecialchars($user['role_name']) ?></strong></p>
    <?php if ($accountType === 'admin'): ?>
        <div class="admin">
            <h2><i class="bi bi-shield-check me-2"></i>Fonctionnalités Admin</h2>
            <ul class="dashboard-links">
                <li><a href="/admin/marque"><i class="bi bi-tag me-2"></i>Gérer les marques</a></li>
                <li><a href="/admin/categorie"><i class="bi bi-clipboard me-2"></i>Gérer les catégories</a></li>
                <li><a href="/admin/produit"><i class="bi bi-box me-2"></i>Gérer les produits</a></li>
                <li><a href="/admin/service"><i class="bi bi-wrench me-2"></i>Gérer les services</a></li>
                <li><a href="/admin/branche"><i class="bi bi-archive me-2"></i>Gérer les branches</a></li>
                <li><a href="/admin/stock"><i class="bi bi-boxes me-2"></i>Gérer le stock</a></li>
                <li><a href="/admin/type_mouvement"><i class="bi bi-arrow-repeat me-2"></i>Gérer les types de mouvement</a></li>
            </ul>
        </div>
        <div class="mt-4">
        <a href="/benef_form" class="btn btn-outline-primary">
            <i class="bi bi-graph-up me-2"></i>Voir les bénéfices
        </a>
    </div>
    <?php endif; ?>
    <?php if ($accountType === 'vendeur'): ?>
        <div class="employee">
            <h2><i class="bi bi-person-badge me-2"></i>Fonctionnalités Vendeur</h2>
            <ul class="dashboard-links">
                <li><a href="/interface-client"><i class="bi bi-laptop me-2"></i>Interface client</a></li>
                <li><a href="/panier"><i class="bi bi-cart3 me-2"></i>Panier</a></li>
            </ul>
        </div>
    <?php endif; ?>
</div>

