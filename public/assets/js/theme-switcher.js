document.addEventListener('DOMContentLoaded', function() {
    const themeToggleBtn = document.getElementById('theme-toggle-btn');
    const themeIcon = themeToggleBtn.querySelector('i');
    
    // GESTION DU MENU TOGGLE
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const contentWrapper = document.getElementById('content-wrapper');

    function toggleSidebar() {
        sidebar.classList.toggle('collapsed');
        contentWrapper.classList.toggle('expanded');
        
        // Sauvegarder l'état dans localStorage
        if (sidebar.classList.contains('collapsed')) {
            localStorage.setItem('sidebar', 'collapsed');
        } else {
            localStorage.setItem('sidebar', 'expanded');
        }
    }
    
    if (menuToggle) {
        menuToggle.addEventListener('click', toggleSidebar);
    }

    // Restaurer l'état du sidebar au chargement
    const savedSidebarState = localStorage.getItem('sidebar');
    if (savedSidebarState === 'collapsed') {
        sidebar.classList.add('collapsed');
        contentWrapper.classList.add('expanded');
    }

    // Fonction pour le scroll
    window.scrollToSection = function(sectionId) {
        const section = document.getElementById(sectionId);
        if (section) {
            section.scrollIntoView({
                behavior: 'smooth'
            });
        }
    };
    
    // Vérifier si l'utilisateur a déjà choisi un thème
    const currentTheme = localStorage.getItem('theme');
    
    if (currentTheme === 'dark') {
        document.body.classList.add('dark-mode');
        themeIcon.classList.remove('bi-moon');
        themeIcon.classList.add('bi-sun');
    } else {
        document.body.classList.remove('dark-mode');
        themeIcon.classList.remove('bi-sun');
        themeIcon.classList.add('bi-moon');
    }
    
    function toggleTheme() {
        if (document.body.classList.contains('dark-mode')) {
            // Passage en mode clair
            document.body.classList.remove('dark-mode');
            localStorage.setItem('theme', 'light');
            themeIcon.classList.remove('bi-sun');
            themeIcon.classList.add('bi-moon');
        } else {
            // Passage en mode sombre
            document.body.classList.add('dark-mode');
            localStorage.setItem('theme', 'dark');
            themeIcon.classList.remove('bi-moon');
            themeIcon.classList.add('bi-sun');
        }
    }
    
    themeToggleBtn.addEventListener('click', toggleTheme);
    
    // Gestion des sous-menus
    const submenuToggles = document.querySelectorAll('.submenu-toggle');
    
    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', !isExpanded);
            
            const menuItem = toggle.closest('.nav-item');
            const submenu = toggle.nextElementSibling;
            
            if (isExpanded) {
                submenu.style.maxHeight = '0px';
                setTimeout(() => {
                    submenu.classList.remove('show');
                }, 300);
                menuItem.classList.remove('expanded');
            } else {
                submenu.classList.add('show');
                submenu.style.maxHeight = submenu.scrollHeight + 'px';
                menuItem.classList.add('expanded');
                
                // Fermer les autres sous-menus (optionnel)
                submenuToggles.forEach(otherToggle => {
                    if (otherToggle !== toggle) {
                        const otherSubmenu = otherToggle.nextElementSibling;
                        const otherMenuItem = otherToggle.closest('.nav-item');
                        
                        otherToggle.setAttribute('aria-expanded', 'false');
                        otherSubmenu.style.maxHeight = '0px';
                        setTimeout(() => {
                            otherSubmenu.classList.remove('show');
                        }, 300);
                        otherMenuItem.classList.remove('expanded');
                    }
                });
            }
        });
    });
});
