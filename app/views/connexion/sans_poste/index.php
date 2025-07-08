<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">

<div class="container-fluid py-4">
        <!-- En-tête -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="text-primary fw-bold mb-0">
                        <i class="fas fa-wifi me-2"></i> Gestion des connexions WiFi
                    </h2>

                </div>
            </div>
        </div>

        <!-- Liste des clients connectés -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 d-flex align-items-center">
                                <i class="fas fa-users me-2 text-primary"></i>
                                <?php echo count($clientConnecter); ?> clients connectés
                            </h5>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addClientModal">
                                <i class="fas fa-plus fa-2x mb-2"></i>
                                <span class="fs-6">Ajouter</span>
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="row g-4" id="clientsContainer">
                            <?php
                            // Pagination
                            $clientsPerPage = 8;  // Afficher 6 clients par page
                            $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                            $totalClients = count($clientConnecter);
                            $totalPages = ceil($totalClients / $clientsPerPage);
                            $startIndex = ($currentPage - 1) * $clientsPerPage;
                            $clientsToShow = array_slice($clientConnecter, $startIndex, $clientsPerPage);

                            if (empty($clientsToShow)): ?>
                                <div class="col-12">
                                    <div class="text-center py-5">
                                        <i class="fas fa-wifi text-muted" style="font-size: 4rem;"></i>
                                        <h5 class="text-muted mt-3">Aucun client connecté</h5>
                                        <p class="text-muted">Cliquez sur "Ajouter un client" pour commencer</p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php foreach ($clientsToShow as $cn): ?>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                                        <div class="card h-100 border-0 shadow-sm position-relative <?php echo ($cn['date_fin'] == null) ? 'border-start border-primary border-4' : 'border-start border-warning border-4'; ?>"
                                            data-client-id="<?php echo $cn['id_client']; ?>"
                                            data-debut="<?php echo $cn['date_debut']; ?>"
                                            data-fin="<?php echo $cn['date_fin']; ?>">

                                            <!-- Badge de statut -->
                                            <div class="position-absolute top-0 end-0 mt-2 me-2">
                                                <?php if ($cn['date_fin'] == null): ?>
                                                    <span class="badge bg-primary">
                                                        <i class="fas fa-infinity me-1"></i>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-clock me-1"></i>
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
                                                    <?php if ($cn['date_fin'] == null) { ?>
                                                        <form action="/connexion/sansposte/arreter" method="post">
                                                            <button class="btn btn-outline-danger btn-sm w-100">
                                                                <input type="hidden" name="id" value="<?php echo $cn['id_historique_connection']; ?>">
                                                                <i class="fas fa-stop me-1"></i> Arrêter la connexion
                                                            </button>
                                                        </form>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <!-- Pagination -->
                        <br>
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <?php if ($currentPage > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>" aria-label="Précédent">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php for ($page = 1; $page <= $totalPages; $page++): ?>
                                    <li class="page-item <?php echo $page == $currentPage ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($currentPage < $totalPages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>" aria-label="Suivant">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <?php include('modal.php') ?>


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

                        // Si la connexion est expirée, actualiser la page après 2 secondes
                        setTimeout(() => {
                            location.reload(); // Recharge la page
                        }, 2000);
                    }
                }
            }

            // Fonction pour vérifier l'état des connexions et actualiser la page si nécessaire
            function checkConnectionStatus() {
                const clients = document.querySelectorAll('.card');

                clients.forEach(card => {
                    const dateFin = card.getAttribute('data-fin');
                    const durationElement = card.querySelector('.duration-text');

                    if (dateFin) {
                        const now = new Date();
                        const fin = new Date(dateFin);
                        if (fin <= now) {
                            // Si la connexion est terminée, actualiser la page
                            setTimeout(() => {
                                location.reload(); // Recharge la page
                            }, 2000);
                        }
                    }
                });
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

                    // Vérifier l'état des connexions et actualiser si nécessaire
                    checkConnectionStatus();
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
