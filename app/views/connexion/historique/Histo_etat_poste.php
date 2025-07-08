<div class="container mt-4">
    <h2 class="mb-4">Historique des états du poste</h2>

    <!-- Filtre dans une card -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <strong>Filtrer</strong>
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
                    <input
                        type="datetime-local"
                        name="date_debut"
                        id="date_debut"
                        class="form-control"
                        value="<?= date('Y-m-d\TH:i', strtotime($dateDebut)) ?>">
                </div>

                <div class="col-md-3">
                    <label for="date_fin" class="form-label">Et :</label>
                    <input
                        type="datetime-local"
                        name="date_fin"
                        id="date_fin"
                        class="form-control"
                        value="<?= date('Y-m-d\TH:i', strtotime($dateFin)) ?>">
                </div>
                <div class="col-md-1 d-grid align-self-end">
                    <button type="submit"
                        class="btn btn-primary py-2 px-2"
                        style="font-size: 1.1rem;">
                        Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau dans une card -->
    <div class="card">
        <div class="card-body p-0">
            <table id="histoTable" class="table table-bordered table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th style="width:5%">#</th>
                        <th style="width:15%">Poste</th>
                        <th style="width:25%">Date début</th>
                        <th style="width:25%">Date fin</th>
                        <th style="width:30%">État</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($histo)): ?>
                        <?php foreach ($histo as $i => $ligne): ?>
                            <?php
                            $etat      = $ligne['etat_nom'];
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
                                <td><?= $ligne['date_debut'] ?></td>
                                <td><?= $ligne['date_fin'] ?? '—' ?></td>
                                <td><?= $etatAffiche ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">Aucun historique trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination Bootstrap (générée par JS) -->
        <div class="card-footer">
            <nav aria-label="Pagination historique postes">
                <ul id="pagination" class="pagination justify-content-center mb-0"></ul>
            </nav>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rowsPerPage = 10;
        const tableBody = document.querySelector('#histoTable tbody');
        const rows = Array.from(tableBody.querySelectorAll('tr'));
        const pageCount = Math.ceil(rows.length / rowsPerPage);
        const pagination = document.getElementById('pagination');
        let currentPage = 1;

        function renderPage(page) {
            currentPage = page;
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            rows.forEach((row, idx) => {
                row.style.display = (idx >= start && idx < end) ? '' : 'none';
            });

            // Reconstruire la pagination
            pagination.innerHTML = '';

            // Bouton Précédent
            const prevLi = document.createElement('li');
            prevLi.className = `page-item${page === 1 ? ' disabled' : ''}`;
            prevLi.innerHTML = `<a class="page-link" href="#">&laquo;</a>`;
            prevLi.addEventListener('click', e => {
                e.preventDefault();
                if (currentPage > 1) renderPage(currentPage - 1);
            });
            pagination.appendChild(prevLi);

            // Numéros de pages
            for (let p = 1; p <= pageCount; p++) {
                const li = document.createElement('li');
                li.className = `page-item${p === page ? ' active' : ''}`;
                li.innerHTML = `<a class="page-link" href="#">${p}</a>`;
                li.addEventListener('click', e => {
                    e.preventDefault();
                    renderPage(p);
                });
                pagination.appendChild(li);
            }

            // Bouton Suivant
            const nextLi = document.createElement('li');
            nextLi.className = `page-item${page === pageCount ? ' disabled' : ''}`;
            nextLi.innerHTML = `<a class="page-link" href="#">&raquo;</a>`;
            nextLi.addEventListener('click', e => {
                e.preventDefault();
                if (currentPage < pageCount) renderPage(currentPage + 1);
            });
            pagination.appendChild(nextLi);
        }

        if (pageCount > 1) {
            renderPage(1);
        }
    });
</script>