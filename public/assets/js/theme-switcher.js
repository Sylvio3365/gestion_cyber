document.addEventListener('DOMContentLoaded', function() {
    const themeToggleBtn = document.getElementById('theme-toggle-btn');
    const themeIcon = themeToggleBtn.querySelector('i');
    
    // AJOUTER LA GESTION DU MENU TOGGLE
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

    // AJOUTER LA FONCTION scrollToSection AU SCOPE GLOBAL
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
    
    // Gestion des sous-menus améliorée
    // Gestion des sous-menus avec style popup
    const submenuToggles = document.querySelectorAll('.submenu-toggle');
    
    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Toggle aria-expanded attribute
            const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', !isExpanded);
            
            // Parent menu item
            const menuItem = toggle.closest('.nav-item');
            
            // Toggle submenu visibility avec effet visuel
            const submenu = toggle.nextElementSibling;
            
            if (isExpanded) {
                // Fermeture
                submenu.style.maxHeight = '0px';
                setTimeout(() => {
                    submenu.classList.remove('show');
                }, 300);
                menuItem.classList.remove('expanded');
            } else {
                // Ouverture
                submenu.classList.add('show');
                submenu.style.maxHeight = submenu.scrollHeight + 'px';
                menuItem.classList.add('expanded');
                
                // Fermer les autres sous-menus
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
    
    // Auto-expand submenu of active item
    const activeSubmenuItem = document.querySelector('.submenu li a.active');
    if (activeSubmenuItem) {
        const parentSubmenu = activeSubmenuItem.closest('.submenu');
        const parentToggle = parentSubmenu.previousElementSibling;
        const parentMenuItem = parentToggle.closest('.nav-item');
        
        parentSubmenu.classList.add('show');
        parentSubmenu.style.maxHeight = parentSubmenu.scrollHeight + 'px';
        parentToggle.setAttribute('aria-expanded', 'true');
        parentMenuItem.classList.add('expanded');
    }
});