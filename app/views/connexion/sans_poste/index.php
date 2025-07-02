<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion WiFi - Connexion Clients</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
</head>

<body class="bg-light">
    <div class="container-fluid py-4">
        <!-- En-tête -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="text-primary fw-bold mb-0">
                        <i class="fas fa-wifi me-2"></i> Gestion des connexions WiFi
                    </h2>
                    <div class="badge bg-info fs-6 px-3 py-2">
                        <i class="fas fa-users me-1"></i>
                        <?php echo count($clientConnecter); ?> clients connectés
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-primary mb-2">
                            <i class="fas fa-infinity fa-2x"></i>
                        </div>
                        <h6 class="text-muted mb-1">Connexions illimitées</h6>
                        <h4 class="text-primary mb-0">
                            <?php echo count(array_filter($clientConnecter, fn($c) => $c['date_fin'] == null)); ?>
                        </h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-warning mb-2">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <h6 class="text-muted mb-1">Connexions limitées</h6>
                        <h4 class="text-warning mb-0">
                            <?php echo count(array_filter($clientConnecter, fn($c) => $c['date_fin'] != null)); ?>
                        </h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-success mb-2">
                            <i class="fas fa-signal fa-2x"></i>
                        </div>
                        <h6 class="text-muted mb-1">Statut réseau</h6>
                        <h6 class="text-success mb-0">
                            <i class="fas fa-circle me-1"></i>Actif
                        </h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <button class="btn btn-primary btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center"
                            data-bs-toggle="modal" data-bs-target="#addClientModal">
                            <i class="fas fa-plus fa-2x mb-2"></i>
                            <span>Ajouter un client</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des clients connectés -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 d-flex align-items-center">
                            <i class="fas fa-users me-2 text-primary"></i>
                            Clients connectés
                            <span class="ms-auto badge bg-secondary"><?php echo count($clientConnecter); ?></span>
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4" id="clientsContainer">
                            <?php if (empty($clientConnecter)): ?>
                                <div class="col-12">
                                    <div class="text-center py-5">
                                        <i class="fas fa-wifi text-muted" style="font-size: 4rem;"></i>
                                        <h5 class="text-muted mt-3">Aucun client connecté</h5>
                                        <p class="text-muted">Cliquez sur "Ajouter un client" pour commencer</p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php foreach ($clientConnecter as $cn): ?>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                                        <div class="card h-100 border-0 shadow-sm position-relative <?php echo ($cn['date_fin'] == null) ? 'border-start border-primary border-4' : 'border-start border-warning border-4'; ?>"
                                            data-client-id="<?php echo $cn['id_client']; ?>"
                                            data-debut="<?php echo $cn['date_debut']; ?>"
                                            data-fin="<?php echo $cn['date_fin']; ?>">

                                            <!-- Badge de statut -->
                                            <div class="position-absolute top-0 end-0 mt-2 me-2">
                                                <?php if ($cn['date_fin'] == null): ?>
                                                    <span class="badge bg-primary">
                                                        <i class="fas fa-infinity me-1"></i>Illimité
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-clock me-1"></i>Limité
                                                    </span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="card-body text-center pt-4">
                                                <!-- Avatar -->
                                                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle bg-primary text-white fw-bold"
                                                    style="width: 60px; height: 60px; font-size: 1.2rem;">
                                                    <?php echo strtoupper(substr($cn['nom'], 0, 1)) . strtoupper(substr($cn['prenom'], 0, 1)); ?>
                                                </div>

                                                <!-- Nom du client -->
                                                <h6 class="card-title mb-2 fw-semibold">
                                                    <?php echo htmlspecialchars($cn['nom'] . ' ' . $cn['prenom']); ?>
                                                </h6>

                                                <!-- Durée de connexion -->
                                                <div class="mb-3">
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-play-circle me-1"></i>
                                                        Débuté le <?php echo date('d/m/Y à H:i', strtotime($cn['date_debut'])); ?>
                                                    </small>
                                                    <div class="mt-2 p-2 bg-light rounded">
                                                        <span class="duration-text fw-semibold text-primary"></span>
                                                    </div>
                                                </div>

                                                <!-- Actions -->
                                                <div class="mt-auto">
                                                    <?php if ($cn['date_fin'] == null): ?>
                                                        <button class="btn btn-outline-danger btn-sm w-100">
                                                            <i class="fas fa-stop me-1"></i> Arrêter la connexion
                                                        </button>
                                                    <?php else: ?>
                                                        <button class="btn btn-outline-secondary btn-sm w-100" disabled>
                                                            <i class="fas fa-check me-1"></i> Connexion terminée
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include('modal.php') ?>

        <!-- Bootstrap JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            // Fonction pour calculer et afficher la durée
            function updateClientDuration(element, dateDebut, dateFin) {
                const now = new Date();
                const debut = new Date(dateDebut);
                const fin = dateFin ? new Date(dateFin) : null;

                if (!fin) {
                    // Connexion illimitée - afficher le temps écoulé
                    const secondsElapsed = Math.floor((now - debut) / 1000);
                    const hours = Math.floor(secondsElapsed / 3600);
                    const minutes = Math.floor((secondsElapsed % 3600) / 60);
                    const seconds = secondsElapsed % 60;

                    if (hours > 0) {
                        element.innerHTML = `<i class="fas fa-clock me-1"></i>${hours}h ${minutes}m ${seconds}s`;
                    } else {
                        element.innerHTML = `<i class="fas fa-clock me-1"></i>${minutes}m ${seconds}s`;
                    }
                    element.className = element.className.replace(/text-\w+/g, '') + ' text-primary';
                } else {
                    // Connexion limitée - afficher le temps restant ou expiré
                    const secondsRemaining = Math.floor((fin - now) / 1000);

                    if (secondsRemaining > 0) {
                        const hours = Math.floor(secondsRemaining / 3600);
                        const minutes = Math.floor((secondsRemaining % 3600) / 60);
                        const seconds = secondsRemaining % 60;

                        if (hours > 0) {
                            element.innerHTML = `<i class="fas fa-hourglass-half me-1"></i>Reste ${hours}h ${minutes}m ${seconds}s`;
                        } else {
                            element.innerHTML = `<i class="fas fa-hourglass-half me-1"></i>Reste ${minutes}m ${seconds}s`;
                        }
                        element.className = element.className.replace(/text-\w+/g, '') + ' text-warning';
                    } else {
                        element.innerHTML = '<i class="fas fa-times-circle me-1"></i>Connexion expirée';
                        element.className = element.className.replace(/text-\w+/g, '') + ' text-danger';
                    }
                }
            }

            // Initialisation et mise à jour périodique
            document.addEventListener('DOMContentLoaded', function() {
                // Mise à jour des durées dans les cartes
                function updateAllDurations() {
                    // Cartes clients
                    document.querySelectorAll('[data-debut]').forEach(card => {
                        const durationElement = card.querySelector('.duration-text');
                        if (durationElement) {
                            updateClientDuration(
                                durationElement,
                                card.getAttribute('data-debut'),
                                card.getAttribute('data-fin')
                            );
                        }
                    });

                    // Tableau détaillé
                    document.querySelectorAll('.duration-text-table').forEach(element => {
                        updateClientDuration(
                            element,
                            element.getAttribute('data-debut'),
                            element.getAttribute('data-fin')
                        );
                    });
                }

                // Mise à jour initiale
                updateAllDurations();

                // Mise à jour toutes les secondes
                setInterval(updateAllDurations, 1000);
            });

            // Animation au survol des cartes
            document.addEventListener('DOMContentLoaded', function() {
                const cards = document.querySelectorAll('.card');
                cards.forEach(card => {
                    card.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-2px)';
                        this.style.transition = 'transform 0.2s ease';
                    });

                    card.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0)';
                    });
                });
            });
        </script>
</body>

</html>