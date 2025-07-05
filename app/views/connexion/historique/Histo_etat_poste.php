<div class="container mt-4">
    <h2>Historique des états du poste</h2>

    <div class="card p-3 mb-4">
        <form action="/poste/historique" method="get" class="row g-3">
            <div class="col-md-4">
                <label for="poste_id" class="form-label">Poste :</label>
                <select name="poste_id" id="poste_id" class="form-select" required>
                    <?php foreach ($postes as $poste): ?>
                        <option value="<?= $poste['id_poste'] ?>"
                            <?= $poste['id_poste'] == $selectedPoste ? 'selected' : '' ?>>
                            <?= htmlspecialchars($poste['numero_poste']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="date_debut" class="form-label">Entre :</label>
                <input type="date" name="date_debut" id="date_debut" class="form-control"
                    value="<?= $dateDebut ?>">
            </div>
            <div class="col-md-3">
                <label for="date_fin" class="form-label">Et :</label>
                <input type="date" name="date_fin" id="date_fin" class="form-control"
                    value="<?= $dateFin ?>">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
            </div>
        </form>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Poste</th>
                <th>Date début</th>
                <th>Date fin</th>
                <th>État</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($histo)): ?>
                <?php foreach ($histo as $i => $ligne): ?>
                    <?php
                    // Déterminer la couleur du badge
                    $etat = $ligne['etat_nom'];
                    $badgeClass = match ($etat) {
                        'Disponible' => 'primary',
                        'Occupé' => 'success',
                        'En maintenance' => 'warning',
                        default => 'secondary'
                    };

                    // Ajouter client si état est Occupé
                    $etatAffiche = '<span class="badge bg-' . $badgeClass . '">' . htmlspecialchars($etat) . '</span>';
                    if ($etat === 'Occupé' && !empty($ligne['client_nom_complet'])) {
                        $etatAffiche .= ' <small>(par ' . htmlspecialchars($ligne['client_nom_complet']) . ')</small>';
                    }
                    ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($ligne['numero_poste']) ?></td>
                        <td><?= $ligne['date_debut'] ?></td>
                        <td><?= $ligne['date_fin'] ?? '—' ?></td>
                        <td><?= $etatAffiche ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Aucun historique trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>