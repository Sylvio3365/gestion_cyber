<div class="container mt-4">

    <!-- Carte pour le formulaire de filtre -->
    <div class="card mb-4">
        <div class="card-header">
            <h2 class="h5 mb-0">Filtrer l’historique des postes</h2>
        </div>
        <div class="card-body">
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
                    <input type="datetime-local" name="date_debut" id="date_debut" class="form-control"
                        value="<?= date('Y-m-d\TH:i', strtotime($dateDebut)) ?>">
                </div>
                <div class="col-md-3">
                    <label for="date_fin" class="form-label">Et :</label>
                    <input type="datetime-local" name="date_fin" id="date_fin" class="form-control"
                        value="<?= date('Y-m-d\TH:i', strtotime($dateFin)) ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Carte pour les résultats -->
    <div class="card">
        <div class="card-header">
            <h2 class="h5 mb-0">Résultat de l'historique</h2>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light text-center">
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
                            $etat = $ligne['etat_nom'];
                            $badgeClass = match ($etat) {
                                'Disponible'     => 'primary',
                                'Occupé'         => 'success',
                                'En maintenance' => 'warning',
                                default          => 'secondary'
                            };

                            $etatAffiche = '<span class="badge bg-' . $badgeClass . '">' . htmlspecialchars($etat) . '</span>';
                            if ($etat === 'Occupé' && !empty($ligne['client_nom_complet'])) {
                                $etatAffiche .= ' <small>(par ' . htmlspecialchars($ligne['client_nom_complet']) . ')</small>';
                            }
                            ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($ligne['numero_poste']) ?></td>
                                <td><?= htmlspecialchars($ligne['date_debut']) ?></td>
                                <td><?= $ligne['date_fin'] ? htmlspecialchars($ligne['date_fin']) : '—' ?></td>
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
    </div>
</div>