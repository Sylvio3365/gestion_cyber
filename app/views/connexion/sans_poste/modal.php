<!-- Modal pour ajouter un client -->
<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addClientModalLabel"><i class="fas fa-user-plus me-2"></i> Ajouter un client WiFi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="wifiForm" method="POST" action="/connexion/sansposte/add">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="clientSelect" class="form-label">Sélectionner un client</label>
                        <select class="form-select select2-with-search" id="clientSelect" required name="id_client">
                            <option value="" selected disabled>Rechercher un client...</option>
                            <?php foreach ($clients as $c): ?>
                                <option value="<?php echo $c['id_client']; ?>"><?php echo $c['nom'] . ' ' . $c['prenom']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="toggleDuree" name="duree_option">
                            <label class="form-check-label" for="toggleDuree">Définir une durée spécifique</label>
                        </div>
                    </div>

                    <div class="mb-3" id="dureeContainer" style="display: none;">
                        <label for="dureeMinutes" class="form-label">Durée de connexion (minutes)</label>
                        <input type="number" class="form-control" name="duree" id="dureeMinutes" min="15" placeholder="30">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plug me-2"></i>Connecter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript pour gérer la visibilité du champ durée -->
<script>
    // Lorsque le formulaire est chargé, ajouter l'écouteur pour l'option durée
    document.addEventListener('DOMContentLoaded', function() {
        const toggleDuree = document.getElementById('toggleDuree');
        const dureeContainer = document.getElementById('dureeContainer');

        toggleDuree.addEventListener('change', function() {
            // Si la case est cochée, afficher le champ pour la durée
            if (this.checked) {
                dureeContainer.style.display = 'block';
            } else {
                dureeContainer.style.display = 'none';
            }
        });
    });
</script>