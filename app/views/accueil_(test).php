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
        ul { list-style: none; padding: 0; }
        li { margin-bottom: 5px; }
        a { text-decoration: none; color: #007BFF; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <h1>Bienvenue, <?php echo htmlspecialchars($user['firstname']); ?> !</h1>
    <p>Type de compte : <strong><?php echo htmlspecialchars($user['name']); ?></strong></p>

    <?php if ($accountType === 'admin'): ?>
        <div class="admin">
            <h2>Fonctionnalités Admin</h2>
            <ul>
                <li><a href="/admin/marque">Gérer les marques</a></li>
                <li><a href="/admin/categorie">Gérer les categories</a></li>
                <li><a href="/admin/produit">Gérer les produits</a></li>
                <li><a href="/admin/service">Gérer les services</a></li>
                <li><a href="/admin/branche">Gérer les branches</a></li>
                <li><a href="/admin/stock">Gérer le stock</a></li>
                <li><a href="/admin/type_mouvement">Gérer les types de mouvement</a></li>
                <li><a href="/admin/vente">Voir les ventes</a></li>
                <li><a href="/admin/connexion">Historique des connexions</a></li>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($accountType === 'employé'): ?>
        <h2>Produits Disponibles</h2>

<?php if (!empty($produits)): ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Nom Produit</th>
                <th>Description</th>
                <th>Marque</th>
                <th>Catégorie</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produits as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['nom']) ?></td>
                    <td><?= htmlspecialchars($p['description']) ?></td>
                    <td>
                        <?php
                        // Trouver le nom de la marque correspondante
                        foreach ($marques as $m) {
                            if ($m['id_marque'] == $p['id_marque']) {
                                echo htmlspecialchars($m['nom']);
                                break;
                            }
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        // Trouver le nom de la catégorie correspondante
                        foreach ($categories as $c) {
                            if ($c['id_categorie'] == $p['id_categorie']) {
                                echo htmlspecialchars($c['nom']);
                                break;
                            }
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucun produit disponible.</p>
<?php endif; ?>

    <?php endif; ?>

    <p><a href="/logout">Se déconnecter</a></p>
    <p><a href="/benef_form">Benefice</a></p>


</body>
</html>
