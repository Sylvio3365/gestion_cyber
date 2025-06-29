// Système de notification simplifié compatible avec Bootstrap 5
window.showNotification = function(message, type = 'success') {
    // Créer l'élément toast
    const toastEl = document.createElement('div');
    const id = 'toast-' + Date.now();
    toastEl.id = id;
    toastEl.className = `toast align-items-center text-white bg-${type} border-0 position-fixed bottom-0 end-0 m-3`;
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');
    toastEl.style.zIndex = '9999';
    
    // Contenu du toast
    toastEl.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    // Ajouter au document
    document.body.appendChild(toastEl);
    
    // Initialiser et montrer le toast
    const toast = new bootstrap.Toast(toastEl, {delay: 3000});
    toast.show();
    
    // Nettoyer après fermeture
    toastEl.addEventListener('hidden.bs.toast', function() {
        toastEl.remove();
    });
};

// Fonction d'aide pour débogage - afficher une erreur dans la console et comme toast
window.showError = function(message, error) {
    console.error(message, error);
    showNotification(message, 'danger');
};