<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement des connexions</title>
    <!-- Bootstrap CSS from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- CSS personnalisé -->
    <link rel="stylesheet" href="/assets/css/connexion/apayer.css">
</head>

<body>
    <div class="payment-container">
        <!-- En-tête de page -->
        <div class="page-header">
            <h1 class="page-title">
                <div class="icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                Paiement des connexions
            </h1>
            <p class="page-subtitle">
                Gestion et confirmation des paiements en attente
            </p>
        </div>

        <!-- Conteneur des paiements -->
        <div class="payments-container">
            <div class="payments-header">
                <h2 class="payments-title">
                    <i class="fas fa-list-check"></i>
                    Liste des paiements à confirmer
                </h2>
            </div>

            <div class="payments-table-container">
                <?php if (!empty($historiques)) : ?>
                    <table class="payments-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-user me-2"></i>Client</th>
                                <th><i class="fas fa-calendar-start me-2"></i>Début</th>
                                <th><i class="fas fa-calendar-check me-2"></i>Fin</th>
                                <th><i class="fas fa-tag me-2"></i>Type</th>
                                <th><i class="fas fa-clock me-2"></i>Durée</th>
                                <th><i class="fas fa-coins me-2"></i>Montant</th>
                                <th><i class="fas fa-cog me-2"></i>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historiques as $histoire) : ?>
                                <tr>
                                    <td>
                                        <div class="client-info">
                                            <div class="client-avatar">
                                                <?= strtoupper(substr($histoire['prenom'], 0, 1) . substr($histoire['nom'], 0, 1)) ?>
                                            </div>
                                            <div class="client-details">
                                                <div class="client-name">
                                                    <?= htmlspecialchars($histoire['nom']) ?>
                                                </div>
                                                <div class="client-secondary">
                                                    <?= htmlspecialchars($histoire['prenom']) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="duration-display">
                                            <i class="fas fa-play"></i>
                                            <?= htmlspecialchars($histoire['date_debut']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($histoire['date_fin']) : ?>
                                            <div class="duration-display">
                                                <i class="fas fa-stop"></i>
                                                <?= htmlspecialchars($histoire['date_fin']) ?>
                                            </div>
                                        <?php else : ?>
                                            <div class="duration-ongoing">
                                                <div class="pulse"></div>
                                                En cours
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($histoire['id_poste']) : ?>
                                            <span class="type-badge with-poste">
                                                <i class="fas fa-desktop"></i>
                                                Avec poste
                                            </span>
                                        <?php else : ?>
                                            <span class="type-badge without-poste">
                                                <i class="fas fa-wifi"></i>
                                                Sans poste
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($histoire['duree_minutes']) : ?>
                                            <div class="duration-display">
                                                <i class="fas fa-hourglass-half"></i>
                                                <?= $histoire['duree_minutes'] ?> min
                                            </div>
                                        <?php else : ?>
                                            <span class="amount-pending">---</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (isset($histoire['montant_a_payer'])) : ?>
                                            <div class="amount-display">
                                                <i class="fas fa-money-bill"></i>
                                                <?= number_format($histoire['montant_a_payer'], 0, ',', ' ') ?> Ar
                                            </div>
                                        <?php else : ?>
                                            <span class="amount-pending">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="payment-action">
                                            <form action="/connexion/payer" method="post">
                                                <input type="hidden" name="id" value="<?= $histoire['id_historique_connection'] ?>">
                                                <button type="submit" class="payment-btn">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    Confirmer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <h3>Aucun paiement en attente</h3>
                        <p>Toutes les connexions ont été payées ou aucune session n'est disponible.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>