<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberCafé Pro - Interface Client</title>
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
                        <a class="nav-link" href="/stat">
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

            <!-- Main Content Area - Interface Client -->
            <div class="main-content">
                <div class="client-interface">
                    <!-- Bienvenue Banner -->
                    <div class="welcome-banner">
                        <h1>Bienvenue au CyBer</h1>
                        <p>Choisissez vos services et produits</p>
                        <div class="info-badges">
                            <div class="info-badge">
                                <button class="btn btn-primary btn-lg" onclick="scrollToSection('pc-section')">
                                    <i class="bi bi-display me-2"></i>
                                    Accès PC & Internet
                                </button>
                            </div>
                            <div class="info-badge">
                                <button class="btn btn-success btn-lg" onclick="scrollToSection('services-section')">
                                    <i class="bi bi-printer me-2"></i>
                                    Services Bureautiques
                                </button>
                            </div>
                            <div class="info-badge">
                                <button class="btn btn-warning btn-lg" onclick="scrollToSection('fournitures-section')">
                                    <i class="bi bi-basket me-2"></i>
                                    Fournitures
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Internet Access Section -->
                    <div class="service-section" id="pc-section">
                        <div class="service-header">
                            <i class="bi bi-display"></i>
                            <h2>Accès Internet & PC</h2>
                            <p>Choisissez votre poste de travail</p>
                        </div>
                        <!-- ... tu peux automatiser ici si tu as les PC en BDD ... -->
                        <!-- ... sinon laisse statique comme dans le HTML ... -->
                    </div>

                    <!-- Services Bureautiques Section -->
                    <div class="service-section" id="services-section">
                        <div class="service-header">
                            <i class="bi bi-printer"></i>
                            <h2>Services Bureautiques</h2>
                            <p>Impression, scan, photocopie et plus</p>
                        </div>
                        <div class="services-grid">
                            <div class="row">
                                <?php foreach ($services as $service): ?>
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="service-item">
                                        <div class="service-icon purple">
                                            <i class="bi bi-printer"></i>
                                        </div>
                                        <div class="service-info">
                                            <h4><?= htmlspecialchars($service['nom']) ?></h4>
                                            <p><?= htmlspecialchars($service['description'] ?? '') ?></p>
                                            <div class="price"><?= number_format($service['prix'], 0, ',', ' ') ?> Ar</div>
                                            <div class="unit">par <?= htmlspecialchars($service['unite'] ?? 'unité') ?></div>
                                        </div>
                                        <form method="post" action="/interface-client/ajouter-panier" class="quantity-control">
                                            <input type="hidden" name="id_service" value="<?= $service['id_service'] ?>">
                                            <input type="hidden" name="prix_unitaire" value="<?= $service['prix'] ?>">
                                            <input type="number" name="quantite" min="1" value="1" class="form-control form-control-sm">
                                            <button type="submit" class="btn btn-sm btn-purple">
                                                <i class="bi bi-cart-plus"></i> Ajouter
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Fournitures Section -->
                    <div class="service-section" id="fournitures-section">
                        <div class="service-header">
                            <i class="bi bi-basket"></i>
                            <h2>Fournitures Bureautiques</h2>
                            <p>Papeterie et accessoires de bureau</p>
                        </div>
                        <div class="supplies-grid">
                            <div class="row">
                                <?php foreach ($produits as $produit): ?>
                                <div class="col-lg-3 col-md-6 mb-4">
                                    <div class="supply-item">
                                        <div class="supply-icon">
                                            <i class="bi bi-basket"></i>
                                        </div>
                                        <div class="supply-info">
                                            <h4><?= htmlspecialchars($produit['nom']) ?></h4>
                                            <p>Stock: <?= htmlspecialchars($produit['stock'] ?? '-') ?></p>
                                            <div class="price"><?= number_format($produit['prix'], 0, ',', ' ') ?> Ar</div>
                                            <div class="unit">par <?= htmlspecialchars($produit['unite'] ?? 'pièce') ?></div>
                                        </div>
                                        <form method="post" action="/interface-client/ajouter-panier" class="quantity-control">
                                            <input type="hidden" name="id_produit" value="<?= $produit['id_produit'] ?>">
                                            <input type="hidden" name="prix_unitaire" value="<?= $produit['prix'] ?>">
                                            <input type="number" name="quantite" min="1" value="1" class="form-control form-control-sm">
                                            <button type="submit" class="btn btn-sm btn-orange">Acheter</button>
                                        </form>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Script sidebar/menu/theme -->
    <script src="/assets/js/theme-switcher.js"></script>
    <!-- Système de notifications -->
    <script src="/assets/js/notification-system.js"></script>
    <!-- Interface client dynamique -->
    <script src="/assets/js/interface-client-dynamic.js"></script>
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
</body>
</html>