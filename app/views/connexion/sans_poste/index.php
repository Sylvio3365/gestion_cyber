<div class="container-fluid py-4">
    <!-- QR Code WiFi -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="d-flex align-items-center mb-3 mb-md-0">
                <div>
                    <h5 class="fw-bold text-primary mb-1">Accès WiFi</h5>
                    <p class="text-muted mb-0 small">Scannez le code QR pour accéder rapidement au réseau WiFi.</p>
                </div>
            </div>
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#qrModal">
                <i class="fas fa-qrcode me-1"></i> Afficher le QR Code
            </button>
        </div>
    </div>

    <!-- Carte principale -->
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-white">
            <h5 class="mb-0 text-primary">
                <i class="fas fa-users me-2"></i><?= count($clientConnecter) ?> clients connectés
            </h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addClientModal">
                <i class="fas fa-user-plus me-1"></i> Ajouter un client
            </button>
        </div>

        <div class="card-body p-4">
            <div class="row g-4" id="clientsContainer">
                <?php
                $clientsPerPage = 8;
                $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $totalClients = count($clientConnecter);
                $totalPages = ceil($totalClients / $clientsPerPage);
                $startIndex = ($currentPage - 1) * $clientsPerPage;
                $clientsToShow = array_slice($clientConnecter, $startIndex, $clientsPerPage);

                if (empty($clientsToShow)): ?>
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-user-slash text-muted" style="font-size: 4rem;"></i>
                        <h5 class="text-muted mt-3">Aucun client connecté</h5>
                        <p class="text-muted">Cliquez sur "Ajouter un client" pour commencer</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($clientsToShow as $cn): ?>
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="card h-100 shadow-sm border-start border-4 <?= $cn['date_fin'] == null ? 'border-primary' : 'border-warning' ?>"
                                data-client-id="<?= $cn['id_client'] ?>"
                                data-debut="<?= $cn['date_debut'] ?>"
                                data-fin="<?= $cn['date_fin'] ?>">

                                <!-- Badge -->
                                <div class="position-absolute top-0 end-0 mt-2 me-2">
                                    <span class="badge <?= $cn['date_fin'] == null ? 'bg-primary' : 'bg-warning' ?>">
                                        <i class="fas <?= $cn['date_fin'] == null ? 'fa-wifi' : 'fa-clock' ?> me-1"></i>
                                    </span>
                                </div>

                                <!-- Corps de la carte -->
                                <div class="card-body text-center pt-4">
                                    <div class="mx-auto mb-3 rounded-circle bg-primary text-white fw-bold d-flex align-items-center justify-content-center"
                                        style="width: 60px; height: 60px; font-size: 1.2rem;">
                                        <?= strtoupper(substr($cn['nom'], 0, 1)) . strtoupper(substr($cn['prenom'], 0, 1)) ?>
                                    </div>

                                    <h6 class="fw-semibold"><?= htmlspecialchars($cn['nom'] . ' ' . $cn['prenom']) ?></h6>

                                    <small class="text-muted d-block mb-2">
                                        <i class="fas fa-play-circle me-1"></i>
                                        Débuté le <?= date('d/m/Y à H:i', strtotime($cn['date_debut'])) ?>
                                    </small>

                                    <div class="bg-light p-2 rounded">
                                        <span class="duration-text fw-semibold text-primary"></span>
                                    </div>

                                    <?php if ($cn['date_fin'] == null): ?>
                                        <form action="/connexion/sansposte/arreter" method="post" class="mt-3">
                                            <input type="hidden" name="id" value="<?= $cn['id_historique_connection'] ?>">
                                            <button class="btn btn-outline-danger btn-sm w-100">
                                                <i class="fas fa-stop-circle me-1"></i> Arrêter
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($currentPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $currentPage - 1 ?>">&laquo;</a>
                        </li>
                    <?php endif; ?>
                    <?php for ($page = 1; $page <= $totalPages; $page++): ?>
                        <li class="page-item <?= $page == $currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $page ?>"><?= $page ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($currentPage < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $currentPage + 1 ?>">&raquo;</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>

    <?php include('modal.php') ?>
</div>

<!-- Modal QR Code WiFi -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow rounded-4">
            <div class="modal-header">
                <h5 class="modal-title text-primary" id="qrModalLabel">
                    <i class="fas fa-qrcode me-2"></i>QR Code WiFi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body text-center">
                <img src="<?php echo $qrUrl ?>" alt="QR Code WiFi" class="img-fluid mb-2">
                <p class="text-muted small">Scannez ce code pour vous connecter automatiquement au WiFi</p>
            </div>
        </div>
    </div>
</div>

<!-- JS de durée et animation -->
<script>
    function updateClientDuration(element, dateDebut, dateFin) {
        const now = new Date();
        const debut = new Date(dateDebut);
        const fin = dateFin ? new Date(dateFin) : null;

        if (!fin) {
            const s = Math.floor((now - debut) / 1000);
            const h = Math.floor(s / 3600),
                m = Math.floor((s % 3600) / 60),
                sec = s % 60;
            element.innerHTML = `<i class="fas fa-clock me-1"></i>${h > 0 ? h + 'h ' : ''}${m}m ${sec}s`;
            element.className = 'duration-text text-primary';
        } else {
            const s = Math.floor((fin - now) / 1000);
            if (s > 0) {
                const h = Math.floor(s / 3600),
                    m = Math.floor((s % 3600) / 60),
                    sec = s % 60;
                element.innerHTML = `<i class="fas fa-hourglass-half me-1"></i>Reste ${h > 0 ? h + 'h ' : ''}${m}m ${sec}s`;
                element.className = 'duration-text text-warning';
            } else {
                element.innerHTML = '<i class="fas fa-times-circle me-1"></i>Connexion expirée';
                element.className = 'duration-text text-danger';
                setTimeout(() => location.reload(), 2000);
            }
        }
    }

    function updateAllDurations() {
        document.querySelectorAll('[data-debut]').forEach(card => {
            const el = card.querySelector('.duration-text');
            updateClientDuration(el, card.dataset.debut, card.dataset.fin);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateAllDurations();
        setInterval(updateAllDurations, 1000);

        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', () => card.style.transform = 'translateY(-2px)');
            card.addEventListener('mouseleave', () => card.style.transform = 'translateY(0)');
        });
    });
</script>