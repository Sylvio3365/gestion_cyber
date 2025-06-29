<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier - CyBer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/interface-client.css">
    <link rel="stylesheet" href="/assets/css/panier.css">
    <link rel="stylesheet" href="/assets/css/dark-mode.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <div class="layout">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="logo-container">
                <div class="logo"><span>CB</span></div>
                <div class="brand-name">CyBer</div>
            </div>
            <div class="sidebar-content">
                <ul class="nav flex-column sidebar-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard">
                            <i class="bi bi-speedometer2"></i>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item has-submenu">
                        <a class="nav-link submenu-toggle" href="#">
                            <i class="bi bi-gear"></i>
                            <span class="menu-text">Gestion</span>
                            <i class="bi bi-chevron-down submenu-indicator"></i>
                        </a>
                        <ul class="submenu collapse">
                            <li><a href="#"><i class="bi bi-display"></i> Gestion PC</a></li>
                            <li><a href="#"><i class="bi bi-box-seam"></i> Stock</a></li>
                        </ul>
                    </li>
                    <li class="nav-item has-submenu">
                        <a class="nav-link submenu-toggle" href="#">
                            <i class="bi bi-tools"></i>
                            <span class="menu-text">Services</span>
                            <i class="bi bi-chevron-down submenu-indicator"></i>
                        </a>
                        <ul class="submenu collapse">
                            <li><a href="#"><i class="bi bi-printer"></i> Services</a></li>
                            <li><a href="/interface-client" class="active"><i class="bi bi-window"></i> Interface Client</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-people"></i>
                            <span class="menu-text">Clients</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-graph-up"></i>
                            <span class="menu-text">Statistiques</span>
                        </a>
                    </li>
                </ul>
                <ul class="nav flex-column sidebar-menu sidebar-footer">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-gear"></i>
                            <span class="menu-text">Paramètres</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">
                            <i class="bi bi-box-arrow-right"></i>
                            <span class="menu-text">Déconnexion</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main content -->
        <div class="content-wrapper" id="content-wrapper">
            <!-- Header -->
            <header class="main-header">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="header-left d-flex align-items-center">
                                <button id="menu-toggle" class="hamburger-btn btn btn-link text-dark p-0">
                                    <i class="bi bi-list fs-4"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-auto header-right">
                            <div class="search-container">
                                <input type="text" class="search-input" placeholder="Rechercher...">
                                <i class="bi bi-search"></i>
                            </div>
                            <div class="header-icons">
                                <div class="icon-badge">
                                    <a href="/panier" class="text-decoration-none">
                                        <i class="bi bi-cart3"></i>
                                    </a>
                                </div>
                                <div class="icon-badge">
                                    <i class="bi bi-envelope"></i>
                                    <span class="badge">2</span>
                                </div>
                                <div class="icon-badge">
                                    <i class="bi bi-list-check"></i>
                                    <span class="badge">3</span>
                                </div>
                            </div>
                            <div class="theme-toggle">
                                <button id="theme-toggle-btn" class="btn btn-link text-dark p-0">
                                    <i class="bi bi-moon"></i>
                                </button>
                            </div>
                            <div class="user-profile">
                                <span class="user-name"><?= htmlspecialchars($_SESSION['user']['firstname'] ?? 'Utilisateur') ?></span>
                            </div>
                            <a href="/logout" class="btn btn-outline-primary btn-sm login-btn">Se déconnecter</a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area - Panier -->
            <div class="main-content">
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
                                                <?php foreach($panier['produits'] as $item): ?>
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
                                                                        <button type="submit" name="nouvelle_quantite" value="<?= $item['quantite']-1 ?>" class="btn btn-sm btn-outline-secondary">
                                                                            <i class="bi bi-dash"></i>
                                                                        </button>
                                                                        <span class="mx-2"><?= $item['quantite'] ?></span>
                                                                        <button type="submit" name="nouvelle_quantite" value="<?= $item['quantite']+1 ?>" class="btn btn-sm btn-outline-secondary">
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
                                                <?php foreach($panier['services'] as $item): ?>
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
                                                                        <button type="submit" name="nouvelle_quantite" value="<?= $item['quantite']-1 ?>" class="btn btn-sm btn-outline-secondary">
                                                                            <i class="bi bi-dash"></i>
                                                                        </button>
                                                                        <span class="mx-2"><?= $item['quantite'] ?></span>
                                                                        <button type="submit" name="nouvelle_quantite" value="<?= $item['quantite']+1 ?>" class="btn btn-sm btn-outline-secondary">
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
                                            <!-- Section client à ajouter dans le résumé du panier si elle manque-->
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
                                                <?php foreach($panier['produits'] as $item): ?>
                                                    <div class="d-flex justify-content-between">
                                                        <span><?= htmlspecialchars($item['produit_nom']) ?> (x<?= $item['quantite'] ?>)</span>
                                                        <span><?= number_format($item['prix_unitaire'] * $item['quantite'], 0, ',', ' ') ?> Ar</span>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($panier['services'])): ?>
                                                <?php foreach($panier['services'] as $item): ?>
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

                                    <!-- Champ caché pour le montant total -->
                                    <input type="hidden" name="montant_total" id="montant_total" value="<?= $total ?>">
                                    
                                    <!-- AJOUTER CE CHAMP CACHÉ POUR L'ID CLIENT -->
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
            </div>
        </div>
    </div>
    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script pour les interactions du menu
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const sidebar = document.getElementById('sidebar');
            const contentWrapper = document.getElementById('content-wrapper');
            function toggleSidebar() {
                sidebar.classList.toggle('collapsed');
                contentWrapper.classList.toggle('expanded');
            }
            if (menuToggle) {
                menuToggle.addEventListener('click', toggleSidebar);
            }
        });
        function scrollToSection(sectionId) {
            const section = document.getElementById(sectionId);
            if (section) {
                section.scrollIntoView({ behavior: 'smooth' });
            }
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[action="/panier/valider"]');
            const errorPopup = document.getElementById('error-popup');
            const errorMessage = document.getElementById('error-message');

            form.addEventListener('submit', async function(event) {
                event.preventDefault();

                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.error) {
                    errorMessage.textContent = result.error;
                    errorPopup.classList.remove('d-none');
                } else {
                    window.location.href = '/panier'; // Redirige vers une page de succès
                }
            });
        });
    </script>
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
        // Réinitialise le formulaire et les messages à chaque ouverture
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
            
            // Nettoie les anciens messages
            this.querySelectorAll('.alert').forEach(el => el.remove());
            
            if (result.id_client) {
                // Utiliser le système de notification plutôt qu'une alerte
                showNotification('Client ajouté avec succès !', 'success');
                
                // Remplit le champ recherche client et le champ caché dans le paiement
                document.getElementById('client-search').value = formData.get('nom') + ' ' + formData.get('prenom');
                document.getElementById('selected-client-id').value = result.id_client;
                
                // Ferme le modal après un court délai
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

    // Remplacer le script d'initialisation du modal de paiement par :
    document.getElementById('paymentModal').addEventListener('show.bs.modal', function() {
        // Récupérer le montant total du résumé de commande
        const totalValue = document.getElementById('total').textContent;
        const totalNumeric = parseFloat(totalValue.replace(/[^\d]/g, ''));
        
        // Mettre à jour le montant dans le modal
        document.getElementById('payment-total').textContent = totalValue;
        
        // Mettre à jour le champ caché avec le montant brut
        document.getElementById('montant_total').value = totalNumeric;
        
        // Préremplir le montant reçu avec le même montant
        document.getElementById('montant-recu').value = totalNumeric;
        
        // AJOUTER CETTE LIGNE : Transférer l'id_client sélectionné vers le modal
        const selectedClientId = document.getElementById('selected-client-id').value;
        document.getElementById('payment-client-id').value = selectedClientId;
        
        // Réinitialiser les messages d'erreur
        const errorElements = document.querySelectorAll('.payment-error');
        errorElements.forEach(el => el.remove());
    });
    
    </script>
    <!-- Système de notifications -->
    <script src="/assets/js/notification-system.js"></script>
    <!-- Panier dynamique -->
    <script src="/assets/js/panier-dynamic.js"></script>
    <!-- Script sidebar/menu/theme -->
    <script src="/assets/js/theme-switcher.js"></script>

</body>
</html>