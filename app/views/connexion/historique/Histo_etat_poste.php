<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des États des Postes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/dark-mode.css">
    <link rel="stylesheet" href="/assets/css/connexion/historique_poste.css">
</head>

<body>
    <div class="container">
        <h1 class="page-title">
            <i class="bi bi-clock-history me-2"></i>
            Historique des États des Postes
        </h1>

        <!-- Section des filtres -->
        <div class="filters-section">
            <div class="d-flex align-items-center mb-3">
                <i class="bi bi-funnel me-2 text-primary"></i>
                <h2 class="h5 mb-0">Filtres de recherche</h2>
            </div>
            
            <form action="/poste/historique" method="get">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="poste_id" class="form-label">
                            <i class="bi bi-pc-display me-1"></i>
                            Poste :
                        </label>
                        <select name="poste_id" id="poste_id" class="form-control" required>
                            <?php foreach ($postes as $poste): ?>
                                <option value="<?= $poste['id_poste'] ?>"
                                    <?= $poste['id_poste'] == $selectedPoste ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($poste['numero_poste']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="date_debut" class="form-label">
                            <i class="bi bi-calendar-check me-1"></i>
                            Date début :
                        </label>
                        <input type="datetime-local" 
                               name="date_debut" 
                               id="date_debut" 
                               class="form-control"
                               value="<?= date('Y-m-d\TH:i', strtotime($dateDebut)) ?>">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="date_fin" class="form-label">
                            <i class="bi bi-calendar-x me-1"></i>
                            Date fin :
                        </label>
                        <input type="datetime-local" 
                               name="date_fin" 
                               id="date_fin" 
                               class="form-control"
                               value="<?= date('Y-m-d\TH:i', strtotime($dateFin)) ?>">
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn-apply w-100">
                            <i class="bi bi-search me-1"></i>
                            Filtrer
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Tableau historique -->
        <div class="table-container">
            <div class="card-header">
                <h2 class="h5">
                    <i class="bi bi-table me-2"></i>
                    Détail des États du Poste
                </h2>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($histo)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>
                                        <i class="bi bi-hash me-1"></i>
                                        #
                                    </th>
                                    <th>
                                        <i class="bi bi-pc-display me-1"></i>
                                        Poste
                                    </th>
                                    <th>
                                        <i class="bi bi-calendar-check me-1"></i>
                                        Date début
                                    </th>
                                    <th>
                                        <i class="bi bi-calendar-x me-1"></i>
                                        Date fin
                                    </th>
                                    <th>
                                        <i class="bi bi-diagram-3 me-1"></i>
                                        État
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($histo as $i => $ligne): ?>
                                    <?php
                                    // Déterminer la couleur du badge
                                    $etat = $ligne['etat_nom'];
                                    $badgeClass = match ($etat) {
                                        'Disponible' => 'success',
                                        'Occupé' => 'primary',
                                        'En maintenance' => 'warning',
                                        default => 'secondary'
                                    };

                                    // Icône selon l'état
                                    $iconeEtat = match ($etat) {
                                        'Disponible' => 'bi-check-circle',
                                        'Occupé' => 'bi-person-fill',
                                        'En maintenance' => 'bi-tools',
                                        default => 'bi-question-circle'
                                    };

                                    // Ajouter client si état est Occupé
                                    $etatAffiche = '<span class="badge bg-' . $badgeClass . ' status-badge"><i class="' . $iconeEtat . ' me-1"></i>' . htmlspecialchars($etat) . '</span>';
                                    if ($etat === 'Occupé' && !empty($ligne['client_nom_complet'])) {
                                        $etatAffiche .= '<div class="mt-1"><small class="text-muted"><i class="bi bi-person me-1"></i>par ' . htmlspecialchars($ligne['client_nom_complet']) . '</small></div>';
                                    }
                                    ?>
                                    <tr>
                                        <td>
                                            <span class="fw-semibold text-primary"><?= $i + 1 ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-display me-2 text-info"></i>
                                                <span class="fw-medium"><?= htmlspecialchars($ligne['numero_poste']) ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-calendar3 me-2 text-success"></i>
                                                <span><?= date('d/m/Y H:i:s', strtotime($ligne['date_debut'])) ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if ($ligne['date_fin']): ?>
                                                    <i class="bi bi-calendar3 me-2 text-secondary"></i>
                                                    <span><?= date('d/m/Y H:i:s', strtotime($ligne['date_fin'])) ?></span>
                                                <?php else: ?>
                                                    <i class="bi bi-infinity me-2 text-warning"></i>
                                                    <span class="text-muted fw-medium">En cours</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td><?= $etatAffiche ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <h5>Aucun historique trouvé</h5>
                        <p>Aucun état enregistré pour les critères sélectionnés.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation d'entrée pour les éléments
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.stat-card, .table-container, .filters-section');
            elements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    element.style.transition = 'all 0.6s ease';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Validation du formulaire
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const dateDebut = document.getElementById('date_debut').value;
                const dateFin = document.getElementById('date_fin').value;
                
                if (dateDebut && dateFin && dateDebut > dateFin) {
                    e.preventDefault();
                    alert('La date de début ne peut pas être postérieure à la date de fin.');
                    return false;
                }
            });

            // Amélioration des interactions avec les lignes du tableau
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.01)';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>

</html>