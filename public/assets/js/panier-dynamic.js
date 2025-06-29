document.addEventListener('DOMContentLoaded', function() {
    // --- PRODUITS ---
    document.querySelectorAll('form[action="/panier/modifier-quantite-produit"]').forEach(form => {
        form.querySelectorAll('button[name="nouvelle_quantite"]').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const formData = new FormData(form);
                const nouvelleQuantite = parseInt(this.value);
                
                // Si quantité devient 0 ou moins, demander confirmation
                if (nouvelleQuantite <= 0) {
                    if (!confirm("Voulez-vous supprimer cet article du panier ?")) {
                        return;
                    }
                }
                
                formData.set('nouvelle_quantite', nouvelleQuantite);

                fetch('/panier/modifier-quantite-produit', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (nouvelleQuantite <= 0) {
                            // Supprimer la ligne du DOM
                            const row = form.closest('.cart-item-row');
                            if (row) row.remove();
                        } else {
                            // Mettre à jour l'affichage de la quantité
                            const quantitySpan = form.querySelector('span');
                            if (quantitySpan) quantitySpan.textContent = nouvelleQuantite;
                            
                            // Mettre à jour les valeurs des boutons
                            const minusBtn = form.querySelector('button[name="nouvelle_quantite"]:first-of-type');
                            const plusBtn = form.querySelector('button[name="nouvelle_quantite"]:last-of-type');
                            if (minusBtn) minusBtn.value = nouvelleQuantite - 1;
                            if (plusBtn) plusBtn.value = nouvelleQuantite + 1;
                            
                            // Mettre à jour le prix de la ligne
                            const row = form.closest('.cart-item-row');
                            if (row) {
                                const priceElem = row.querySelector('strong');
                                if (priceElem && data.item_price) priceElem.textContent = data.item_price;
                            }
                        }
                        
                        // Mettre à jour les totaux
                        const subtotal = document.getElementById('subtotal');
                        const total = document.getElementById('total');
                        if (subtotal && data.total) subtotal.textContent = data.total;
                        if (total && data.total) total.textContent = data.total;
                        
                        showNotification('Quantité mise à jour', 'success');
                        updatePaymentOrderSummary();
                    } else {
                        showNotification(data.error || 'Erreur lors de la modification', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Une erreur est survenue', 'danger');
                });
            });
        });
    });

    // --- SERVICES ---
    document.querySelectorAll('form[action="/panier/modifier-quantite-service"]').forEach(form => {
        form.querySelectorAll('button[name="nouvelle_quantite"]').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const formData = new FormData(form);
                const nouvelleQuantite = parseInt(this.value);
                
                // Si quantité devient 0 ou moins, demander confirmation
                if (nouvelleQuantite <= 0) {
                    if (!confirm("Voulez-vous supprimer cet article du panier ?")) {
                        return;
                    }
                }
                
                formData.set('nouvelle_quantite', nouvelleQuantite);

                fetch('/panier/modifier-quantite-service', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (nouvelleQuantite <= 0) {
                            // Supprimer la ligne du DOM
                            const row = form.closest('.cart-item-row');
                            if (row) row.remove();
                        } else {
                            // Mettre à jour l'affichage de la quantité
                            const quantitySpan = form.querySelector('span');
                            if (quantitySpan) quantitySpan.textContent = nouvelleQuantite;
                            
                            // Mettre à jour les valeurs des boutons
                            const minusBtn = form.querySelector('button[name="nouvelle_quantite"]:first-of-type');
                            const plusBtn = form.querySelector('button[name="nouvelle_quantite"]:last-of-type');
                            if (minusBtn) minusBtn.value = nouvelleQuantite - 1;
                            if (plusBtn) plusBtn.value = nouvelleQuantite + 1;
                            
                            // Mettre à jour le prix de la ligne
                            const row = form.closest('.cart-item-row');
                            if (row) {
                                const priceElem = row.querySelector('strong');
                                if (priceElem && data.item_price) priceElem.textContent = data.item_price;
                            }
                        }
                        
                        // Mettre à jour les totaux
                        const subtotal = document.getElementById('subtotal');
                        const total = document.getElementById('total');
                        if (subtotal && data.total) subtotal.textContent = data.total;
                        if (total && data.total) total.textContent = data.total;
                        
                        showNotification('Quantité mise à jour', 'success');
                        updatePaymentOrderSummary();
                    } else {
                        showNotification(data.error || 'Erreur lors de la modification', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Une erreur est survenue', 'danger');
                });
            });
        });
    });

    // Intercepter les formulaires de suppression de produits
    document.querySelectorAll('form[action="/panier/supprimer-produit"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            
            fetch('/panier/supprimer-produit', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Supprimer la ligne du DOM
                    const row = form.closest('.cart-item-row');
                    if (row) row.remove();
                    
                    // Mettre à jour les totaux
                    const subtotal = document.getElementById('subtotal');
                    const total = document.getElementById('total');
                    if (subtotal && data.total) subtotal.textContent = data.total;
                    if (total && data.total) total.textContent = data.total;
                    
                    // Notification
                    showNotification('Produit supprimé du panier', 'success');
                    updatePaymentOrderSummary();
                    
                    // Vérifier si le panier est vide
                    if (data.is_empty) {
                        const container = document.getElementById('cart-items-container');
                        if (container) {
                            container.innerHTML = `
                                <div class="empty-cart text-center py-5">
                                    <i class="bi bi-cart-x display-1 text-muted"></i>
                                    <h4 class="mt-3">Votre panier est vide</h4>
                                    <p class="text-muted">Ajoutez des articles depuis l'interface client</p>
                                    <a href="/interface-client" class="btn btn-primary">Commencer mes achats</a>
                                </div>
                            `;
                        }
                        
                        // Désactiver le bouton de validation
                        const validateBtn = document.getElementById('validate-btn');
                        if (validateBtn) validateBtn.setAttribute('disabled', 'disabled');
                    }
                } else {
                    showNotification(data.error || 'Erreur lors de la suppression', 'danger');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showNotification('Une erreur est survenue', 'danger');
            });
        });
    });

    // Intercepter les formulaires de suppression de services
    document.querySelectorAll('form[action="/panier/supprimer-service"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            
            fetch('/panier/supprimer-service', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Supprimer la ligne du DOM
                    const row = form.closest('.cart-item-row');
                    if (row) row.remove();
                    
                    // Mettre à jour les totaux
                    const subtotal = document.getElementById('subtotal');
                    const total = document.getElementById('total');
                    if (subtotal && data.total) subtotal.textContent = data.total;
                    if (total && data.total) total.textContent = data.total;
                    
                    // Notification
                    showNotification('Service supprimé du panier', 'success');
                    updatePaymentOrderSummary();
                    
                    // Vérifier si le panier est vide
                    if (data.is_empty) {
                        const container = document.getElementById('cart-items-container');
                        if (container) {
                            container.innerHTML = `
                                <div class="empty-cart text-center py-5">
                                    <i class="bi bi-cart-x display-1 text-muted"></i>
                                    <h4 class="mt-3">Votre panier est vide</h4>
                                    <p class="text-muted">Ajoutez des articles depuis l'interface client</p>
                                    <a href="/interface-client" class="btn btn-primary">Commencer mes achats</a>
                                </div>
                            `;
                        }
                        
                        // Désactiver le bouton de validation
                        const validateBtn = document.getElementById('validate-btn');
                        if (validateBtn) validateBtn.setAttribute('disabled', 'disabled');
                    }
                } else {
                    showNotification(data.error || 'Erreur lors de la suppression', 'danger');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showNotification('Une erreur est survenue', 'danger');
            });
        });
    });

    // Gestion du paiement - simplification et correction du problème de montant
    const paymentModal = document.getElementById('paymentModal');
    if (paymentModal) {
        paymentModal.addEventListener('show.bs.modal', function() {
            // Récupérer le montant total actuel du résumé de commande
            const totalCommande = document.getElementById('total').textContent;
            
            // Mettre à jour le montant dans le modal de paiement
            document.getElementById('payment-total').textContent = totalCommande;
            
            // Mettre à jour le champ caché avec le montant total non formaté
            const montantTotalNumerique = parseFloat(totalCommande.replace(/[^\d]/g, ''));
            document.getElementById('montant_total').value = montantTotalNumerique;
            
            // Préremplir le champ de montant reçu avec le montant total
            document.getElementById('montant-recu').value = montantTotalNumerique;
        });
    }

    // Gestion de la soumission du formulaire de paiement
    const paymentForm = document.getElementById('payment-form');
    if (paymentForm) {
        paymentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Récupérer les valeurs des montants
            const montantTotal = parseFloat(document.getElementById('montant_total').value);
            const argentDonne = parseFloat(document.getElementById('montant-recu').value);
            
            // Vérifier que le montant reçu est suffisant
            if (argentDonne < montantTotal) {
                showNotification('Le montant reçu est insuffisant', 'danger');
                return;
            }
            
            const formData = new FormData(this);
            
            fetch('/panier/valider', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success || data.id_vente) {
                    // Fermer le modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
                    if (modal) modal.hide();
                    
                    // Notification de succès
                    showNotification('Paiement effectué avec succès !', 'success');
                    
                    // Redirection vers un nouveau panier après un délai
                    setTimeout(() => {
                        window.location.href = '/panier';
                    }, 1500);
                } else {
                    showNotification(data.error || 'Erreur lors du paiement', 'danger');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showNotification('Une erreur est survenue lors du paiement', 'danger');
            });
        });
    }

    function updatePaymentOrderSummary() {
        fetch('/panier/recapitulatif-json')
            .then(res => res.json())
            .then(data => {
                const summaryDiv = document.querySelector('#paymentModal .order-summary > div');
                if (!summaryDiv) return;
                summaryDiv.innerHTML = '';
                data.items.forEach(item => {
                    const line = document.createElement('div');
                    line.className = 'd-flex justify-content-between';
                    line.innerHTML = `<span>${item.nom} (x${item.quantite})</span><span>${item.prix} Ar</span>`;
                    summaryDiv.appendChild(line);
                });
                summaryDiv.innerHTML += '<hr><div class="d-flex justify-content-between fw-bold"><span>Total à payer:</span><span id="payment-total">' + data.total + ' Ar</span></div>';
                document.getElementById('montant_total').value = data.total_numeric;
                document.getElementById('montant-recu').value = data.total_numeric;
                
                // AJOUTER CETTE LIGNE : Maintenir l'id_client sélectionné
                const selectedClientId = document.getElementById('selected-client-id').value;
                document.getElementById('payment-client-id').value = selectedClientId;
            });
    }
});