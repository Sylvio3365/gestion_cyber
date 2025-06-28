<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des PC</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="/assets/css/connexion/style_accueil.css">
</head>

<body>
    <?php
    $sessionActive = false; // Variable pour contrôler l'état de la session

    // Calcul des statistiques
    $stats = [
        'actifs' => count(array_filter($postes, fn($p) => $p['etat_nom'] === 'Occupé')),
        'disponibles' => count(array_filter($postes, fn($p) => $p['etat_nom'] === 'Disponible')),
        'maintenance' => count(array_filter($postes, fn($p) => $p['etat_nom'] === 'En Maintenance')),
        'total' => count($postes)
    ];
    $stats['occupation'] = round(($stats['actifs'] / $stats['total']) * 100);
    if ($estNouveauJour) {
        echo "new";
    } else if (!$estNouveauJour) {
        echo "tsy new";
    }
    ?>

    <div class="main-content">
        <div class="pc-management">
            <!-- Header Section -->
            <div class="pc-management-header mb-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="mb-3 mb-md-0">
                        <h2 class="section-title">Gestion des PC</h2>
                        <p class="section-subtitle">Suivi en temps réel des postes informatiques</p>
                    </div>
                    <div class="session-controls <?= $sessionActive ? 'session-active' : 'session-inactive' ?>">
                        <button class="btn btn-success btn-icon-text" onclick="toggleSession(true)">
                            <i class="bi bi-play-fill"></i>
                            Démarrer Session
                        </button>
                        <button class="btn btn-outline-secondary btn-icon-text" onclick="toggleSession(false)">
                            <i class="bi bi-stop-fill"></i>
                            Arrêter Session
                        </button>
                    </div>
                </div>
            </div>
            <!-- Info Container -->
            <?php include('info_actuel.php'); ?>
            <?php include('stat_poste.php'); ?>

            <!-- PC Status Section -->
            <div class="pc-status-section">
                <div class="section-header mb-4">
                    <h3 class="section-subtitle">État des Postes Informatiques</h3>
                    <p class="text-muted small">Cliquez sur un PC pour gérer sa session</p>
                </div>

                <div class="row g-3">
                    <?php foreach ($postes as $index => $poste): ?>
                        <?php
                        $etat_class = strtolower(str_replace([' ', 'é', 'è'], ['', 'e', 'e'], $poste['etat_nom']));
                        $status_class = ($poste['etat_nom'] === 'Occupé') ? 'active' : (($poste['etat_nom'] === 'Disponible') ? 'available' : 'maintenance');
                        ?>
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <div class="pc-card <?= $status_class ?>">
                                <div class="pc-card-header">
                                    <div class="pc-number">
                                        <i class="bi bi-<?= $poste['etat_nom'] === 'Occupé' ? 'wifi' : ($poste['etat_nom'] === 'En Maintenance' ? 'tools' : 'laptop') ?>"></i>
                                        <?= htmlspecialchars($poste['numero_poste']) ?>
                                    </div>
                                    <span class="status-badge <?= $status_class ?>">
                                        <?= $poste['etat_nom'] === 'Occupé' ? 'Actif' : htmlspecialchars($poste['etat_nom']) ?>
                                    </span>
                                </div>
                                <div class="pc-card-body">
                                    <div class="pc-info">
                                        <?php if ($poste['etat_nom'] === 'Occupé'): ?>
                                            <div class="info-item">
                                                <i class="bi bi-person"></i>
                                                <?php if ($poste['nom_client_occupant'] !== null): ?>
                                                    <span><?= htmlspecialchars($poste['nom_client_occupant']) ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="info-item">
                                                <i class="bi bi-clock"></i>
                                                <span><?= $poste['duree'] !== null ? htmlspecialchars($poste['duree']) : '' ?></span>
                                            </div>
                                        <?php elseif ($poste['etat_nom'] === 'En Maintenance'): ?>
                                            <div class="info-item text-center w-100">
                                                <span>En maintenance</span>
                                            </div>
                                            <div class="info-item">
                                                <i class="bi bi-calendar"></i>
                                                <span>Depuis: <?= date('H:i', strtotime($poste['date_debut'])) ?></span>
                                            </div>
                                        <?php else: ?>
                                            <div class="info-item text-center w-100">
                                                <span>Poste disponible</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($poste['etat_nom'] === 'Occupé' && $poste['prix']): ?>
                                        <div class="pc-price">
                                            <?= htmlspecialchars($poste['prix']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="pc-card-footer">
                                    <?php if ($poste['etat_nom'] === 'Occupé'): ?>
                                        <button class="btn btn-outline-light w-100" onclick="stopSession('<?= $poste['numero_poste'] ?>')">
                                            Arrêter Session
                                        </button>
                                    <?php elseif ($poste['etat_nom'] === 'En Maintenance'): ?>
                                        <button class="btn btn-outline-light w-100" onclick="markAvailable('<?= $poste['numero_poste'] ?>')">
                                            Marquer Disponible
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-primary w-100" onclick="startSession('<?= $poste['numero_poste'] ?>')">
                                            Démarrer Session
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>

</html>