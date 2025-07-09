<div class="cart-page">
    <!-- Header Section -->
    <div class="cart-header">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1><i class="bi bi-cart3 me-3"></i>Mon Panier</h1>
                <p class="text-muted">Gérez vos articles et finalisez votre commande</p>
            </div>
            <div>
                <a href="/interface-client" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i>Continuer mes achats
                </a>
            </div>
        </div>
    </div>

    <!-- Cart Content -->
    <div class="cart-content">
        <div class="row">
            <!-- Articles du panier -->
            <div class="col-lg-8">
                <div class="cart-items-section">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Articles dans votre panier</h5>
                        </div>
                        <div class="card-body" id="cart-items-container">
                            <?php if (!$id_vente_draft || (empty($panier['produits']) && empty($panier['services']))): ?>
                                <div class="empty-cart text-center py-5">
                                    <i class="bi bi-cart-x display-1 text-muted"></i>
                                    <h4 class="mt-3">Votre panier est vide</h4>
                                    <p class="text-muted">Ajoutez des articles depuis l'interface client</p>
                                    <a href="/interface-client" class="btn btn-primary">Commencer mes achats</a>
                                </div>
                            <?php else: ?>
                                <?php foreach ($panier['produits'] as $item): ?>
                                    <div class="cart-item-row border-bottom py-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-circle-fill text-primary me-3"></i>
                                                    <div>
                                                        <h6 class="mb-1"><?= htmlspecialchars($item['produit_nom']) ?></h6>
                                                        <small class="text-muted"><?= number_format($item['prix_unitaire'], 0, ',', ' ') ?> Ar l'unité</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="quantity-controls d-flex align-items-center">
                                                    <form method="post" action="/panier/modifier-quantite-produit" class="d-flex">
                                                        <input type="hidden" name="id_vente_draft_produit" value="<?= $item['id_vente_draft_produit'] ?>">
                                                        <button type="submit" name="nouvelle_quantite" value="<?= $item['quantite'] - 1 ?>" class="btn btn-sm btn-outline-secondary">
                                                            <i class="bi bi-dash"></i>
                                                        </button>
                                                        <span class="mx-2"><?= $item['quantite'] ?></span>
                                                        <button type="submit" name="nouvelle_quantite" value="<?= $item['quantite'] + 1 ?>" class="btn btn-sm btn-outline-secondary">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <strong><?= number_format($item['prix_unitaire'] * $item['quantite'], 0, ',', ' ') ?> Ar</strong>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <form method="post" action="/panier/supprimer-produit">
                                                    <input type="hidden" name="id_vente_draft_produit" value="<?= $item['id_vente_draft_produit'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <?php foreach ($panier['services'] as $item): ?>
                                    <div class="cart-item-row border-bottom py-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-circle-fill text-success me-3"></i>
                                                    <div>
                                                        <h6 class="mb-1"><?= htmlspecialchars($item['service_nom']) ?></h6>
                                                        <small class="text-muted"><?= number_format($item['prix_unitaire'], 0, ',', ' ') ?> Ar l'unité</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="quantity-controls d-flex align-items-center">
                                                    <form method="post" action="/panier/modifier-quantite-service" class="d-flex">
                                                        <input type="hidden" name="id_vente_draft_service" value="<?= $item['id_vente_draft_service'] ?>">
                                                        <button type="submit" name="nouvelle_quantite" value="<?= $item['quantite'] - 1 ?>" class="btn btn-sm btn-outline-secondary">
                                                            <i class="bi bi-dash"></i>
                                                        </button>
                                                        <span class="mx-2"><?= $item['quantite'] ?></span>
                                                        <button type="submit" name="nouvelle_quantite" value="<?= $item['quantite'] + 1 ?>" class="btn btn-sm btn-outline-secondary">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <strong><?= number_format($item['prix_unitaire'] * $item['quantite'], 0, ',', ' ') ?> Ar</strong>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <form method="post" action="/panier/supprimer-service">
                                                    <input type="hidden" name="id_vente_draft_service" value="<?= $item['id_vente_draft_service'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Résumé de la commande -->
            <div class="col-lg-4">
                <div class="cart-summary-section">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Résumé de la commande</h5>
                        </div>
                        <div class="card-body">
                            <div class="summary-item d-flex justify-content-between">
                                <span>Sous-total:</span>
                                <span id="subtotal"><?= number_format($total, 0, ',', ' ') ?> Ar</span>
                            </div>
                            <hr>
                            <div class="summary-item d-flex justify-content-between fw-bold">
                                <span>Total:</span>
                                <span id="total"><?= number_format($total, 0, ',', ' ') ?> Ar</span>
                            </div>
                            <!-- Section client -->
                            <div class="client-section mt-3 mb-3">
                                <h6>Client</h6>
                                <div class="input-group mb-2">
                                    <input type="text" id="client-search" class="form-control" placeholder="Rechercher un client...">
                                    <button class="btn btn-outline-secondary" type="button" onclick="showNewClientModal()">
                                        <i class="bi bi-person-plus"></i>
                                    </button>
                                </div>
                                <div id="client-list" class="list-group position-absolute w-100 shadow-sm" style="display: none; z-index: 1000;"></div>
                                <input type="hidden" id="selected-client-id" name="id_client" value="">
                            </div>
                            <div class="d-grid gap-2 mt-4">
                                <button class="btn btn-success btn-lg" id="validate-btn" onclick="showPaymentModal()" <?= (!$id_vente_draft || (empty($panier['produits']) && empty($panier['services']))) ? 'disabled' : '' ?>>
                                    <i class="bi bi-check-circle me-2"></i>Valider la commande
                                </button>
                            </div>
                            <div id="error-popup" class="alert alert-danger d-none mt-3" role="alert">
                                <span id="error-message"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de paiement -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel"><i class="bi bi-credit-card me-2"></i>Finaliser le paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="payment-form" method="post">
                <div class="modal-body">
                    <div class="order-summary mb-4">
                        <h6>Résumé de votre commande</h6>
                        <div>
                            <?php if (!empty($panier['produits'])): ?>
                                <?php foreach ($panier['produits'] as $item): ?>
                                    <div class="d-flex justify-content-between">
                                        <span><?= htmlspecialchars($item['produit_nom']) ?> (x<?= $item['quantite'] ?>)</span>
                                        <span><?= number_format($item['prix_unitaire'] * $item['quantite'], 0, ',', ' ') ?> Ar</span>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <?php if (!empty($panier['services'])): ?>
                                <?php foreach ($panier['services'] as $item): ?>
                                    <div class="d-flex justify-content-between">
                                        <span><?= htmlspecialchars($item['service_nom']) ?> (x<?= $item['quantite'] ?>)</span>
                                        <span><?= number_format($item['prix_unitaire'] * $item['quantite'], 0, ',', ' ') ?> Ar</span>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total à payer:</span>
                                <span id="payment-total"><?= number_format($total, 0, ',', ' ') ?> Ar</span>
                            </div>
                        </div>
                    </div>

                    <!-- Mode de paiement -->
                    <div class="form-group mb-3">
                        <label for="id_type_paiement" class="form-label">Mode de paiement</label>
                        <select name="id_type_paiement" id="id_type_paiement" class="form-select" required>
                            <option value="">Sélectionnez</option>
                            <?php foreach ($types_paiement as $type): ?>
                                <option value="<?= $type['id_type_de_payement'] ?>">
                                    <?= htmlspecialchars($type['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Montant reçu -->
                    <div class="form-group mb-3">
                        <label for="montant-recu" class="form-label">Montant reçu</label>
                        <div class="input-group">
                            <input type="number" name="argent_donne" id="montant-recu" class="form-control" required>
                            <span class="input-group-text">Ar</span>
                        </div>
                    </div>

                    <!-- Champs cachés -->
                    <input type="hidden" name="montant_total" id="montant_total" value="<?= $total ?>">
                    <input type="hidden" name="id_client" id="payment-client-id" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-2"></i>Valider le paiement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal ajout client -->
<div class="modal fade" id="newClientModal" tabindex="-1" aria-labelledby="newClientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="new-client-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="newClientModalLabel">Ajouter un nouveau client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" class="form-control" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prénom</label>
                        <input type="text" class="form-control" name="prenom" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS spécifique pour le panier -->
<link rel="stylesheet" href="/assets/css/panier.css">

<script>
    function showPaymentModal() {
        const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
        modal.show();
    }

    // Recherche client dynamique
    document.getElementById('client-search').addEventListener('input', async function() {
        const terme = this.value.trim();
        const list = document.getElementById('client-list');
        const hiddenInput = document.getElementById('selected-client-id');
        hiddenInput.value = '';
        list.innerHTML = '';
        list.style.display = 'none';
        if (terme.length < 2) return;

        try {
            const res = await fetch('/api/clients?search=' + encodeURIComponent(terme));
            if (!res.ok) throw new Error('Erreur API');
            const clients = await res.json();
            if (clients.length) {
                list.style.display = 'block';
                clients.forEach(client => {
                    const item = document.createElement('button');
                    item.type = 'button';
                    item.className = 'list-group-item list-group-item-action';
                    item.textContent = client.nom + ' ' + client.prenom;
                    item.onclick = () => {
                        document.getElementById('client-search').value = client.nom + ' ' + client.prenom;
                        hiddenInput.value = client.id_client;
                        list.style.display = 'none';
                    };
                    list.appendChild(item);
                });
            } else {
                list.style.display = 'block';
                const item = document.createElement('div');
                item.className = 'list-group-item text-muted';
                item.textContent = 'Aucun client trouvé';
                list.appendChild(item);
            }
        } catch (e) {
            list.style.display = 'block';
            const item = document.createElement('div');
            item.className = 'list-group-item text-danger';
            item.textContent = 'Erreur lors de la recherche';
            list.appendChild(item);
        }
    });

    // Affichage du modal ajout client
    function showNewClientModal() {
        const form = document.getElementById('new-client-form');
        form.reset();
        form.querySelectorAll('.alert').forEach(el => el.remove());
        const modal = new bootstrap.Modal(document.getElementById('newClientModal'));
        modal.show();
    }

    // Ajout client via AJAX
    document.getElementById('new-client-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        try {
            const res = await fetch('/panier/ajouter-client', {
                method: 'POST',
                body: formData
            });

            if (!res.ok) {
                throw new Error('Erreur serveur');
            }

            const result = await res.json();
            this.querySelectorAll('.alert').forEach(el => el.remove());

            if (result.id_client) {
                showNotification('Client ajouté avec succès !', 'success');
                document.getElementById('client-search').value = formData.get('nom') + ' ' + formData.get('prenom');
                document.getElementById('selected-client-id').value = result.id_client;

                setTimeout(() => {
                    bootstrap.Modal.getInstance(document.getElementById('newClientModal')).hide();
                    this.reset();
                }, 1000);
            } else {
                showNotification(result.error || 'Erreur lors de l\'ajout du client', 'danger');
            }
        } catch (error) {
            console.error('Erreur:', error);
            showNotification('Une erreur est survenue lors de l\'ajout du client', 'danger');
        }
    });

    // Initialisation du modal de paiement
    document.getElementById('paymentModal').addEventListener('show.bs.modal', function() {
        const totalValue = document.getElementById('total').textContent;
        const totalNumeric = parseFloat(totalValue.replace(/[^\d]/g, ''));

        document.getElementById('payment-total').textContent = totalValue;
        document.getElementById('montant_total').value = totalNumeric;
        document.getElementById('montant-recu').value = totalNumeric;

        const selectedClientId = document.getElementById('selected-client-id').value;
        document.getElementById('payment-client-id').value = selectedClientId;

        const errorElements = document.querySelectorAll('.payment-error');
        errorElements.forEach(el => el.remove());
    });
</script>

<!-- Scripts spécifiques au panier -->
<script src="/assets/js/panier-dynamic.js"></script>