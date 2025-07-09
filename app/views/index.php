<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-Cyber</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/interface-client.css">
    <link rel="stylesheet" href="/assets/css/dark-mode.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Bibliothèque d'animation (optionnelle pour effet de l'icône panier) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</head>

<body>
    <div class="layout">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="logo-container">
                <div class="logo"><span>e-C</span></div>
                <div class="brand-name">e-Cyber</div>
            </div>
            <div class="sidebar-content">
                <ul class="nav flex-column sidebar-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard">
                            <i class="bi bi-house-door"></i>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </li>

                    <?php if (isset($_SESSION['u']) && $_SESSION['u']['account_type_name'] == 'admin') : ?>
                        <li class="nav-item has-submenu">
                            <a class="nav-link submenu-toggle" href="#">
                                <i class="bi bi-gear"></i>
                                <span class="menu-text">Gestion</span>
                                <i class="bi bi-chevron-down submenu-indicator"></i>
                            </a>
                            <ul class="submenu collapse">
                                <li><a href="/admin/branche"><i class="bi bi-archive"></i> Branche</a></li>
                                <li><a href="/admin/categorie"><i class="bi bi-clipboard"></i> Catégorie</a></li>
                                <li><a href="/admin/marque"><i class="bi bi-tag"></i> Marque</a></li>
                                <li><a href="/admin/produit"><i class="bi bi-box"></i> Produit</a></li>
                                <li><a href="/admin/service"><i class="bi bi-wrench"></i> Service</a></li>
                                <li><a href="/admin/stock"><i class="bi bi-box"></i> Stock</a></li>
                                <li><a href="/admin/prix"><i class="bi bi-currency-dollar"></i> Prix</a></li>
                                <li><a href="/admin/type_mouvement"><i class="bi bi-arrow-repeat"></i> Mouvement</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>


                    <li class="nav-item">
                        <a class="nav-link" href="/interface-client">
                            <i class="bi bi-laptop"></i>
                            <span class="menu-text">Bureautique et services</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/facture/voir">
                            <i class="bi bi-receipt"></i>
                            <span class="menu-text">Factures & Historique des ventes</span>
                        </a>
                    </li>

                    <li class="nav-item has-submenu">
                        <a class="nav-link submenu-toggle" href="#">
                            <i class="bi bi-link"></i>
                            <span class="menu-text">Connexion</span>
                            <i class="bi bi-chevron-down submenu-indicator"></i>
                        </a>
                        <ul class="submenu collapse">
                            <li><a href="/poste/accueil"><i class="bi bi-display"></i> Avec poste</a></li>
                            <li><a href="/poste/historique"><i class="bi bi-activity"></i> Historique états poste</a></li>
                            <li><a href="/connexion/sansposte"><i class="bi bi-person-lines-fill"></i> Sans poste</a></li>
                            <li><a href="/connexion/apayer"><i class="bi bi-credit-card"></i> Payer</a></li>
                            <li><a href="/connexion/historique"><i class="bi bi-clock-history"></i> Historique</a></li>
                        </ul>

                    </li>
                    <?php if (isset($_SESSION['u']) && $_SESSION['u']['account_type_name'] == 'admin') { ?>
                        <li class="nav-item has-submenu">
                            <a class="nav-link submenu-toggle" href="#">
                                <i class="bi bi-bar-chart-line"></i> <!-- Corrected to represent Statistics -->
                                <span class="menu-text">Statistique</span>
                                <i class="bi bi-chevron-down submenu-indicator"></i>
                            </a>
                            <ul class="submenu collapse">
                                <li><a href="/recette/branche"><i class="bi bi-cash-stack"></i>Recette par branche</a></li> <!-- Updated to cash-stack -->
                                <li><a href="/stat"><i class="bi bi-pie-chart"></i>Vente</a></li> <!-- Updated to pie-chart -->
                                <li><a href="/benef_form"><i class="bi bi-graph-up-arrow"></i> Bénéfice</a></li>
                            </ul>
                        </li>
                    <?php } ?>
                </ul>
                <ul class="nav flex-column sidebar-menu sidebar-footer">
                    <?php if (isset($_SESSION['u']) && $_SESSION['u']['account_type_name'] == 'admin') { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/parametre/mdp">
                                <i class="bi bi-gear"></i>
                                <span class="menu-text">Paramètres</span>
                            </a>
                        </li>
                    <?php } ?>
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
                                <!-- <div class="icon-badge">
                                    <i class="bi bi-envelope"></i>
                                    <span class="badge">2</span>
                                </div>
                                <div class="icon-badge">
                                    <i class="bi bi-list-check"></i>
                                    <span class="badge">3</span>
                                </div> -->
                            </div>
                            <div class="theme-toggle">
                                <button id="theme-toggle-btn" class="btn btn-link text-dark p-0">
                                    <i class="bi bi-moon"></i>
                                </button>
                            </div>
                            <div class="user-profile">
                                <span class="user-name"><?= htmlspecialchars($_SESSION['user']['firstname'] ?? 'Utilisateur') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area - Interface Client -->
            <div class="main-content">
                <?php
                if (isset($page)) {
                    include($page . '.php');  // Inclut le fichier 'crud_branche.php' depuis 'admin'
                }
                ?>
            </div>

        </div>
    </div>

    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Script sidebar/menu/theme -->
    <script src="/assets/js/theme-switcher.js"></script>
    <!-- Navigation active -->
    <script src="/assets/js/active-navigation.js"></script>
    <!-- Système de notifications -->
    <script src="/assets/js/notification-system.js"></script>
    <!-- Interface client dynamique -->
    <script src="/assets/js/interface-client-dynamic.js"></script>
    <script>
        // Script pour les interactions du menu
        document.addEventListener('DOMContentLoaded', function() {
            // SUPPRIMER CE BLOC - il est déjà géré par theme-switcher.js
            // const menuToggle = document.getElementById('menu-toggle');
            // const sidebar = document.getElementById('sidebar');
            // const contentWrapper = document.getElementById('content-wrapper');

            // function toggleSidebar() {
            //     sidebar.classList.toggle('collapsed');
            //     contentWrapper.classList.toggle('expanded');
            // }
            // if (menuToggle) {
            //     menuToggle.addEventListener('click', toggleSidebar);
            // }
        });

        function scrollToSection(sectionId) {
            const section = document.getElementById(sectionId);
            if (section) {
                section.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }
    </script>
</body>

</html>