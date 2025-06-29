<div class="modal fade" id="demarrerSessionModal" tabindex="-1" aria-labelledby="demarrerSessionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="demarrerSessionModalLabel">Démarrer une session (Poste <span id="modalPosteNumeroDisplay"></span> - ID: <span id="modalPosteIdDisplay"></span>)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="/poste/demarrerSessionPoste">
                <div class="modal-body">
                    <input type="hidden" name="action" value="demarrer_poste">
                    <input type="hidden" name="numero_poste" id="modalNumeroPoste" value="">
                    <input type="hidden" name="poste_id" id="modalPosteId" value="">

                    <div class="mb-3">
                        <label for="clientSelect" class="form-label">Client</label>
                        <select class="form-select select2-with-search" id="clientSelect" name="client_id" required>
                            <option value="" selected disabled>Sélectionnez un client</option>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?= $client['id_client'] ?>">
                                    <?= htmlspecialchars($client['nom'] . ' ' . $client['prenom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="toggleDuree" name="duree_option">
                            <label class="form-check-label" for="toggleDuree">Durée spécifique</label>
                        </div>
                    </div>

                    <div class="mb-3" id="dureeContainer" style="display: none;">
                        <label for="dureeMinutes" class="form-label">Durée (en minutes)</label>
                        <input type="number" class="form-control" id="dureeMinutes" name="duree_minutes" min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Démarrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de l'affichage du champ durée
        const toggleDuree = document.getElementById('toggleDuree');
        const dureeContainer = document.getElementById('dureeContainer');

        if (toggleDuree && dureeContainer) {
            toggleDuree.addEventListener('change', function() {
                dureeContainer.style.display = this.checked ? 'block' : 'none';
                if (!this.checked) {
                    document.getElementById('dureeMinutes').value = '';
                }
            });
        }

        // Gestion du clic sur les boutons "Démarrer Session"
        document.querySelectorAll('.demarrer-session-btn').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');
                const numeroPoste = form.querySelector('input[name="numero_poste"]').value;
                const posteId = form.querySelector('input[name="poste_id"]')?.value;

                document.getElementById('modalNumeroPoste').value = numeroPoste;
                if (posteId) {
                    document.getElementById('modalPosteId').value = posteId;
                }

                document.getElementById('modalPosteNumeroDisplay').textContent = numeroPoste;
                document.getElementById('modalPosteIdDisplay').textContent = posteId || 'N/A';

                const modal = new bootstrap.Modal(document.getElementById('demarrerSessionModal'));
                modal.show();

                // Initialisation de Select2 après l'affichage complet de la modal
                $('#demarrerSessionModal').on('shown.bs.modal', function() {
                    $('#clientSelect').select2({
                        theme: 'bootstrap-5',
                        placeholder: 'Sélectionnez un client',
                        dropdownParent: $('#demarrerSessionModal'),
                        width: '100%',
                        language: {
                            noResults: function() {
                                return "Aucun résultat trouvé";
                            }
                        }
                    });
                });

                // Nettoyage quand la modal est fermée
                $('#demarrerSessionModal').on('hidden.bs.modal', function() {
                    $('#clientSelect').select2('destroy');
                });
            });
        });
    });
</script>

<!-- jQuery (nécessaire pour Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- JS de Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>