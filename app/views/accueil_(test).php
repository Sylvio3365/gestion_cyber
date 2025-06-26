<?php

// Simule une session (à enlever si tu passes une vraie session depuis le contrôleur)
if (!isset($_SESSION['user'])) {
    echo "<p style='color:red;'>Aucun utilisateur connecté.</p>";
    exit;
}

$user = $_SESSION['user'];
$accountType = strtolower($user['role_name']); // 'Admin' ou 'Employé' (depuis account_type.name)

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard de test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .admin { background-color: #f1f1f1; padding: 10px; margin-bottom: 10px; }
        .employee { background-color: #dff0d8; padding: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>

    <h1>Bienvenue, <?php echo htmlspecialchars($user['firstname']); ?> !</h1>
    <p>Type de compte : <strong><?php echo htmlspecialchars($user['name']); ?></strong></p>

    <?php if ($accountType === 'admin'): ?>
        <div class="admin">
            <h2>Fonctionnalités Admin</h2>
            <ul>
                <li>Gérer les utilisateurs</li>
                <li>Ajouter/modifier/supprimer des produits</li>
                <li>Voir toutes les ventes</li>
                <li>Accès complet aux statistiques</li>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($accountType === 'employé'): ?>
        <div class="employee">
            <h2>Fonctionnalités Vendeur</h2>
            <ul>
                <li>Effectuer un achat pour un client</li>
                <li>Voir la liste des produits disponibles</li>
            </ul>
        </div>
    <?php endif; ?>

    <p><a href="/logout">Se déconnecter</a></p>

</body>
</html>
