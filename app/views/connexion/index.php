<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion WiFi - Connexion Clients</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
    <style>
        /* Style pour la liste des clients connectés */
        .connected-clients-container {
            display: flex;
            overflow-x: auto;
            padding: 15px 0;
            gap: 15px;
            align-items: center;
            scrollbar-width: thin;
        }

        .client-card {
            flex: 0 0 auto;
            width: 120px;
            border-radius: 10px;
            padding: 15px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
        }

        .client-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .client-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #0d6efd;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
        }

        .client-name {
            font-weight: 500;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .client-duration {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .disconnect-btn {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #dc3545;
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .client-card:hover .disconnect-btn {
            opacity: 1;
        }

        .add-client-btn {
            width: 120px;
            height: 140px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .add-client-btn:hover {
            border-color: #0d6efd;
            background: rgba(13, 110, 253, 0.05);
        }

        .add-client-btn i {
            font-size: 24px;
            color: #0d6efd;
            margin-bottom: 10px;
        }

        .no-clients {
            text-align: center;
            padding: 30px;
            color: #6c757d;
        }

        /* Custom scrollbar */
        .connected-clients-container::-webkit-scrollbar {
            height: 6px;
        }

        .connected-clients-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .connected-clients-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        .connected-clients-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-wifi me-2"></i> Gestion des connexions WiFi</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClientModal">
                        <i class="fas fa-plus me-2"></i>Ajouter un client
                    </button>
                </div>

                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-users me-2"></i> Clients connectés</h5>
                    </div>
                    <div class="card-body p-0">
                        <div id="noClientsMessage" class="no-clients">
                            <i class="fas fa-user-slash fa-3x mb-3"></i>
                            <p>Aucun client connecté actuellement</p>
                        </div>
                        <div id="clientsContainer" class="connected-clients-container" style="display: none;">
                            <!-- Les clients seront ajoutés ici dynamiquement -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour ajouter un client -->
    <div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClientModalLabel"><i class="fas fa-user-plus me-2"></i> Ajouter un client WiFi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="wifiForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="clientSelect" class="form-label">Sélectionner un client</label>
                            <select class="form-select select2-with-search" id="clientSelect" required>
                                <option value="" selected disabled>Rechercher un client...</option>
                                <option value="1">Jean Dupont</option>
                                <option value="2">Marie Martin</option>
                                <option value="3">Pierre Durand</option>
                                <option value="4">Sophie Lambert</option>
                                <option value="5">Thomas Moreau</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="toggleDuree" name="duree_option">
                                <label class="form-check-label" for="toggleDuree">Définir une durée spécifique</label>
                            </div>
                        </div>

                        <div class="mb-3" id="dureeContainer" style="display: none;">
                            <label for="dureeMinutes" class="form-label">Durée de connexion (minutes)</label>
                            <input type="number" class="form-control" id="dureeMinutes" min="1" placeholder="30">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plug me-2"></i>Connecter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialisation de Select2
            $('#clientSelect').select2({
                theme: 'bootstrap-5',
                placeholder: 'Rechercher un client...',
                dropdownParent: $('#addClientModal'),
                width: '100%',
                language: {
                    noResults: function() {
                        return "Aucun client trouvé";
                    }
                }
            });

            // Gestion de l'affichage du champ durée
            const toggleDuree = document.getElementById('toggleDuree');
            const dureeContainer = document.getElementById('dureeContainer');

            toggleDuree.addEventListener('change', function() {
                dureeContainer.style.display = this.checked ? 'block' : 'none';
                if (!this.checked) {
                    document.getElementById('dureeMinutes').value = '';
                }
            });

            // Gestion du formulaire
            const wifiForm = document.getElementById('wifiForm');
            const clientsContainer = document.getElementById('clientsContainer');
            const noClientsMessage = document.getElementById('noClientsMessage');
            let clients = [];

            wifiForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const clientSelect = document.getElementById('clientSelect');
                const selectedOption = clientSelect.options[clientSelect.selectedIndex];
                const clientName = selectedOption.text;
                const clientId = clientSelect.value;

                let duration = '∞';
                if (toggleDuree.checked) {
                    duration = document.getElementById('dureeMinutes').value + ' min';
                }

                // Ajouter le client à la liste
                clients.push({
                    id: clientId,
                    name: clientName,
                    duration: duration,
                    initials: getInitials(clientName)
                });

                updateClientsDisplay();

                // Fermer le modal et réinitialiser le formulaire
                const modal = bootstrap.Modal.getInstance(document.getElementById('addClientModal'));
                modal.hide();
                wifiForm.reset();
                $('#clientSelect').val(null).trigger('change');
                dureeContainer.style.display = 'none';
                toggleDuree.checked = false;
            });

            function getInitials(name) {
                return name.split(' ').map(n => n[0]).join('').toUpperCase();
            }

            function updateClientsDisplay() {
                if (clients.length > 0) {
                    noClientsMessage.style.display = 'none';
                    clientsContainer.style.display = 'flex';

                    // Générer les cartes des clients
                    clientsContainer.innerHTML = '';

                    // Bouton pour ajouter un nouveau client
                    const addBtn = document.createElement('div');
                    addBtn.className = 'add-client-btn';
                    addBtn.innerHTML = `
                        <i class="fas fa-plus"></i>
                        <span>Ajouter</span>
                    `;
                    addBtn.onclick = function() {
                        const modal = new bootstrap.Modal(document.getElementById('addClientModal'));
                        modal.show();
                    };
                    clientsContainer.appendChild(addBtn);

                    // Cartes des clients connectés
                    clients.forEach((client, index) => {
                        const card = document.createElement('div');
                        card.className = 'client-card';
                        card.innerHTML = `
                            <div class="client-avatar">${client.initials}</div>
                            <div class="client-name">${client.name.split(' ')[0]}</div>
                            <div class="client-duration">${client.duration}</div>
                            <button class="disconnect-btn" data-index="${index}">
                                <i class="fas fa-times"></i>
                            </button>
                        `;

                        clientsContainer.appendChild(card);
                    });

                    // Gestion des boutons de déconnexion
                    document.querySelectorAll('.disconnect-btn').forEach(btn => {
                        btn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            const index = parseInt(this.getAttribute('data-index'));
                            clients.splice(index, 1);
                            updateClientsDisplay();
                        });
                    });
                } else {
                    noClientsMessage.style.display = 'block';
                    clientsContainer.style.display = 'none';
                }
            }

            // Réinitialiser le modal quand il est fermé
            $('#addClientModal').on('hidden.bs.modal', function() {
                wifiForm.reset();
                $('#clientSelect').val(null).trigger('change');
                dureeContainer.style.display = 'none';
                toggleDuree.checked = false;
            });
        });
    </script>
</body>

</html>