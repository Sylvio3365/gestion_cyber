document.addEventListener('DOMContentLoaded', function() {
    
    // Fonction pour définir l'élément actif dans la navigation
    function setActiveNavigation() {
        const currentPath = window.location.pathname;
        const currentQuery = window.location.search;
        const fullPath = currentPath + currentQuery;
        
        // Supprimer toutes les classes active existantes
        document.querySelectorAll('.nav-link, .sidebar-menu a, .submenu a').forEach(link => {
            link.classList.remove('active');
            const parentItem = link.closest('.nav-item');
            if (parentItem) {
                parentItem.classList.remove('active');
            }
        });
        
        // Définir les règles de correspondance pour les pages
        const navigationRules = {
            // Dashboard/Accueil
            '/dashboard': ['/', '/dashboard', '/accueil'],
            
            // Gestion - Pages CRUD
            '/admin/branche': ['/admin/branche'],
            '/admin/categorie': ['/admin/categorie'],
            '/admin/marque': ['/admin/marque'],
            '/admin/produit': ['/admin/produit'],
            '/admin/service': ['/admin/service'],
            '/admin/stock': ['/admin/stock'],
            '/admin/prix': ['/admin/prix'],
            
            // Bureautique et services
            '/interface-client': ['/interface-client'],
            
            // Connexion
            '/poste/accueil': ['/poste/accueil'],
            '/poste/historique': ['/poste/historique'],
            '/connexion/sansposte': ['/connexion/sansposte'],
            '/connexion/apayer': ['/connexion/apayer'],
            '/connexion/historique': ['/connexion/historique'],
            
            // Clients
            '/clients': ['/clients'],
            
            // Statistiques
            '/recette/branche': ['/recette/branche'],
            '/stat': ['/stat'],
            '/benef_form': ['/benef_form', '/benefice'],
            
            // Panier
            '/panier': ['/panier'],
            
            // Paramètres
            '/parametres': ['/parametres']
        };
        
        // Trouver la correspondance
        let matchedRule = null;
        
        for (const [rulePath, patterns] of Object.entries(navigationRules)) {
            for (const pattern of patterns) {
                if (currentPath === pattern || currentPath.startsWith(pattern + '/')) {
                    matchedRule = rulePath;
                    break;
                }
            }
            if (matchedRule) break;
        }
        
        // Définir l'élément actif basé sur la correspondance
        const targetPath = matchedRule || currentPath;
        
        // Chercher le lien correspondant dans la navigation
        const navLinks = document.querySelectorAll('.sidebar-menu .nav-link, .submenu a');
        
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (!href) return;
            
            // Correspondance exacte ou début de chemin
            if (href === targetPath || (targetPath !== '/' && href !== '/' && targetPath.startsWith(href))) {
                setLinkActive(link);
            }
        });
        
        // Gestion spéciale pour le dashboard (racine)
        if (currentPath === '/' || currentPath === '/dashboard') {
            const dashboardLink = document.querySelector('a[href="/dashboard"]');
            if (dashboardLink) {
                setLinkActive(dashboardLink);
            }
        }
    }
    
    // Fonction pour définir un lien comme actif
    function setLinkActive(link) {
        link.classList.add('active');
        
        // Marquer l'élément parent comme actif
        const parentItem = link.closest('.nav-item');
        if (parentItem) {
            parentItem.classList.add('active');
        }
        
        // Ouvrir le sous-menu parent si c'est un élément de sous-menu
        const submenu = link.closest('.submenu');
        if (submenu) {
            submenu.classList.add('show');
            submenu.style.maxHeight = submenu.scrollHeight + 'px';
            
            const parentToggle = submenu.previousElementSibling;
            if (parentToggle && parentToggle.classList.contains('submenu-toggle')) {
                parentToggle.setAttribute('aria-expanded', 'true');
                const parentNavItem = parentToggle.closest('.nav-item');
                if (parentNavItem) {
                    parentNavItem.classList.add('active', 'expanded');
                }
            }
        }
    }
    
    // Définir l'état actif au chargement de la page
    setActiveNavigation();
    
    // Mettre à jour lors de la navigation (pour les SPA)
    window.addEventListener('popstate', setActiveNavigation);
    
    // Fonction pour marquer manuellement un élément comme actif (utile pour AJAX)
    window.setActiveNav = function(selector) {
        document.querySelectorAll('.nav-link, .sidebar-menu a, .submenu a').forEach(link => {
            link.classList.remove('active');
            const parentItem = link.closest('.nav-item');
            if (parentItem) {
                parentItem.classList.remove('active');
            }
        });
        
        const targetLink = document.querySelector(selector);
        if (targetLink) {
            setLinkActive(targetLink);
        }
    };
});