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

    <!-- CSS de Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="/assets/css/connexion/style_accueil.css">
</head>

<body>

    <div class="main-content">
        <div class="pc-management">
            <!-- Header Section -->
            <div class="pc-management-header mb-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="mb-3 mb-md-0">
                        <h2 class="section-title">Gestion des PC</h2>
                        <p class="section-subtitle">Suivi en temps réel des postes informatiques</p>
                    </div>
                    <div>
                        <?php if ($estNouveauJour) { ?>
                            <form method="get" action="/poste/demarrerSession">
                                <input type="hidden" name="action" value="demarrer">
                                <button type="submit" class="btn btn-success btn-icon-text">
                                    <i class="bi bi-play-fill"></i>
                                    Démarrer
                                </button>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?= htmlspecialchars($messageType === 'error' ? 'danger' : 'success') ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>
            <?php include('info_actuel.php'); ?>
            <?php include('modal_demarrer_session.php'); ?>

            <?php if ($estNouveauJour): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Attention !</strong> Vous devez démarrer une nouvelle session pour commencer la journée.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (!$estNouveauJour): ?>
                <div class="pc-status-section">
                    <div class="row g-3">
                        <?php foreach ($postes as $index => $poste): ?>
                            <?php
                            $etat_class = strtolower(str_replace([' ', 'é', 'è'], ['', 'e', 'e'], $poste['etat_nom']));
                            $status_class = ($poste['etat_nom'] === 'Occupé') ? 'active' : (($poste['etat_nom'] === 'Disponible') ? 'available' : 'maintenance');

                            // Stocker les données nécessaires pour le JavaScript
                            $posteData = [
                                'id_poste' => $poste['id_poste'],
                                'numero_poste' => $poste['numero_poste'],
                                'date_fin' => $poste['date_fin'] ?? null,
                                'etat_nom' => $poste['etat_nom'],
                                'session_expiree' => false // Nouveau champ pour suivre l'état
                            ];
                            ?>
                            <div class="col-xl-3 col-md-4 col-sm-6" data-poste='<?= json_encode($posteData) ?>'>
                                <div class="pc-card <?= $status_class ?>">
                                    <div class="pc-card-header">
                                        <div class="pc-number">
                                            <i class="bi bi-<?= $poste['etat_nom'] === 'Occupé' ? 'wifi' : ($poste['etat_nom'] === 'En maintenance' ? 'tools' : 'laptop') ?>"></i>
                                            <?= htmlspecialchars($poste['numero_poste']) ?>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="status-badge <?= $status_class ?> me-2">
                                                <?= $poste['etat_nom'] === 'Occupé' ? 'Occupé' : htmlspecialchars($poste['etat_nom']) ?>
                                            </span>
                                            <div class="dropdown">
                                                <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="modifier_poste.php?id=<?= $poste['numero_poste'] ?>">
                                                            <i class="bi bi-pencil me-2"></i>Modifier
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="supprimer_poste.php?id=<?= $poste['numero_poste'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce poste ?')">
                                                            <i class="bi bi-trash me-2"></i>Supprimer
                                                        </a>
                                                    </li>
                                                    <?php if ($poste['etat_nom'] !== 'En maintenance') { ?>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <form method="post" action="/poste/mettreEnMaintenance" class="d-inline">
                                                                <input type="hidden" name="poste_id" value="<?= $poste['id_poste'] ?>">
                                                                <button type="submit" class="dropdown-item" style="background: none; border: none; width: 100%; text-align: left;">
                                                                    <i class="bi bi-tools me-2"></i>Mettre en maintenance
                                                                </button>
                                                            </form>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
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
                                                <?php if (!empty($poste['date_fin'])): ?>
                                                    <div class="info-item">
                                                        <i class="bi bi-clock-history"></i>
                                                        <span class="expiration-time" data-expiration="<?= htmlspecialchars($poste['date_fin']) ?>">
                                                            Fin: <?= date('H:i', strtotime($poste['date_fin'])) ?>
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                            <?php elseif ($poste['etat_nom'] === 'En maintenance'): ?>
                                                <div class="info-item text-center w-100">
                                                    <span>En maintenance</span>
                                                </div>
                                                <div class="info-item">
                                                    <i class="bi bi-calendar"></i>
                                                    <span>Depuis: <?= date('H:i', strtotime($poste['date_debut'])) ?></span>
                                                </div>
                                            <?php elseif ($poste['etat_nom'] === 'Disponible'): ?>
                                                <div class="info-item text-center w-100">
                                                    <span>Poste disponible</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="pc-card-footer">
                                        <?php if ($poste['etat_nom'] === 'Occupé'): ?>
                                            <form method="post" action="/poste/arreterSessionPoste" class="w-100">
                                                <input type="hidden" name="action" value="arreter_poste">
                                                <input type="hidden" name="numero_poste" value="<?= $poste['numero_poste'] ?>">
                                                <input type="hidden" name="poste_id" value="<?= $poste['id_poste'] ?>">
                                                <button type="submit" class="btn btn-outline-light w-100" <?= $poste['date_fin'] != null ? 'disabled' : '' ?>>
                                                    <?= $poste['date_fin'] != null ? 'Poste Occupé' : 'Arrêter Session' ?>
                                                </button>
                                            </form>
                                        <?php elseif ($poste['etat_nom'] === 'En maintenance'): ?>
                                            <form method="post" action="/poste/rendreDisponible" class="w-100">
                                                <input type="hidden" name="action" value="marquer_disponible">
                                                <input type="hidden" name="poste_id" value="<?= $poste['id_poste'] ?>">
                                                <button type="submit" class="btn btn-outline-light w-100">
                                                    Marquer disponible
                                                </button>
                                            </form>
                                        <?php elseif ($poste['etat_nom'] === 'Disponible'): ?>
                                            <form method="post" action="" class="w-100">
                                                <input type="hidden" name="action" value="demarrer_poste">
                                                <input type="hidden" name="numero_poste" value="<?= $poste['numero_poste'] ?>">
                                                <input type="hidden" name="poste_id" value="<?= $poste['id_poste'] ?>">
                                                <button type="button" class="btn btn-primary w-100 demarrer-session-btn">
                                                    Démarrer Session
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
    <script src="/assets/js/automatisation.js"></script>

</body>

</html>