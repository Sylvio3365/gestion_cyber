document.addEventListener('DOMContentLoaded', function() {
    try {
        // Vérifier si la fonction showNotification existe
        if (typeof window.showNotification !== 'function') {
            console.error("La fonction showNotification n'est pas définie");
            window.showNotification = function(message) {
                alert(message);
            };
        }
        
        // Intercepter tous les formulaires d'ajout au panier
        document.querySelectorAll('form[action="/interface-client/ajouter-panier"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                const isProduit = formData.has('id_produit');
                const itemName = form.closest('.service-item, .supply-item')?.querySelector('h4')?.textContent || 'Article';
                
                fetch('/interface-client/ajouter-panier', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Erreur HTTP: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Notification de succès
                        window.showNotification(`${data.item_name || itemName} ajouté au panier avec succès !`, 'success');
                        
                        // Réinitialiser la quantité à 1
                        form.querySelector('input[name="quantite"]').value = 1;
                    } else {
                        window.showNotification(data.error || 'Erreur lors de l\'ajout au panier', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    window.showNotification('Une erreur est survenue lors de l\'ajout au panier', 'danger');
                });
            });
        });
    } catch (e) {
        console.error("Erreur d'initialisation:", e);
    }
});