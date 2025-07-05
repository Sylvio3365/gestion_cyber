-- Correction de la table vente_draft pour permettre id_client NULL
ALTER TABLE vente_draft MODIFY id_client INT NULL;

-- Création de la table service_produit pour gérer la consommation de produits par service
CREATE TABLE
    service_produit (
        id_service_produit INT AUTO_INCREMENT,
        id_service INT NOT NULL,
        id_produit INT NOT NULL,
        quantite_par_service INT NOT NULL, -- Quantité de produit consommée par service
        PRIMARY KEY (id_service_produit),
        FOREIGN KEY (id_service) REFERENCES service (id_service) ON DELETE CASCADE,
        FOREIGN KEY (id_produit) REFERENCES produit (id_produit) ON DELETE CASCADE
    );

-- Insérer des valeurs dans account_type
INSERT INTO
    account_type (name, remarque)
VALUES
    ('admin', 'Administrateur'),
    ('vendeur', 'Employé utilisateur');

-- Insérer des valeurs dans user_app
INSERT INTO
    user_app (
        name,
        username,
        firstname,
        email,
        password,
        deleted_at,
        id_account_type
    )
VALUES
    (
        'admin',
        'admin',
        'admin',
        'admin',
        'admin',
        NULL,
        1
    ),
    ('user', 'user', 'User', 'user', 'user', NULL, 2);

-- Insérer des valeurs dans branche
INSERT INTO
    branche (nom, description)
VALUES
    ('Bureautique', ''),
    ('MultiService', ''),
    ('Connexion', '');

-- Insérer des valeurs dans categorie
INSERT INTO
    categorie (nom, id_branche)
VALUES
    ('Connexion', 3),
    ('Fournitures', 1), -- Pour stylos, papier, etc.
    ('Services', 2);

-- Pour photocopie, impression, etc.
-- Insérer des valeurs dans marque
INSERT INTO
    marque (nom)
VALUES
    ('BIC'),
    ('Papyrus'),
    ('HP');

-- Insérer des valeurs dans produit
INSERT INTO
    produit (nom, description, id_marque, id_categorie)
VALUES
    ('Stylo bleu', 'Stylo bille classique', 1, 2),
    ('Crayon à papier', 'Crayon graphite HB', 1, 2),
    ('Papier A4', '500 feuilles', 2, 2),
    ('Classeur', 'Classeur cartonné', 2, 2);

-- Insérer des valeurs dans type_mouvement
INSERT INTO
    type_mouvement (type)
VALUES
    ('entrée'),
    ('sortie');

-- Insérer des valeurs dans stock
INSERT INTO
    stock (
        quantite,
        date_mouvement,
        id_produit,
        id_mouvement
    )
VALUES
    (100, NOW(), 1, 1),
    (80, NOW(), 2, 1),
    (500, NOW(), 3, 1),
    (15, NOW(), 4, 1);

-- Insérer des valeurs dans prix_produit
INSERT INTO
    prix_produit (
        date_modification,
        prix,
        mois,
        annee,
        description,
        id_produit
    )
VALUES
    (NOW(), 500, 6, 2025, 'Prix courant', 1),
    (NOW(), 400, 6, 2025, 'Prix courant', 2),
    (NOW(), 100, 6, 2025, 'Prix courant', 3),
    (NOW(), 2500, 6, 2025, 'Prix courant', 4);

-- Insérer des valeurs dans service
INSERT INTO
    service (nom, description, id_categorie)
VALUES
    ('Photocopie', 'Par page A4', 3),
    ('Impression', 'Par page A4', 3),
    ('Scan', 'Par page', 3);

-- Insérer des valeurs dans prix_service
INSERT INTO
    prix_service (
        date_modification,
        prix,
        mois,
        annee,
        description,
        id_service
    )
VALUES
    (NOW(), 100, 6, 2025, 'Tarif standard', 1), -- Photocopie NB
    (NOW(), 400, 6, 2025, 'Tarif standard', 2), -- Impression couleur
    (NOW(), 300, 6, 2025, 'Tarif standard', 3);

INSERT INTO
    prix_service (
        date_modification,
        prix,
        mois,
        annee,
        description,
        id_service
    )
VALUES
    (NOW(), 100, 7, 2025, 'Tarif standard', 1), -- Photocopie NB
    (NOW(), 400, 7, 2025, 'Tarif standard', 2), -- Impression couleur
    (NOW(), 300, 7, 2025, 'Tarif standard', 3);

-- Scan
-- Insérer des valeurs dans type_de_payement
INSERT INTO
    type_de_payement (nom)
VALUES
    ('Especes'),
    ('Mobile Money');

-- Insérer des valeurs dans service_produit
INSERT INTO
    service_produit (id_service, id_produit, quantite_par_service)
VALUES
    (1, 3, 1), -- Photocopie consomme 1 papier A4 par service
    (2, 3, 1), -- Impression consomme 1 papier A4 par service
    (3, 3, 1);

ALTER TABLE historique_connexion ADD statut INT NOT NULL DEFAULT 0;

ALTER TABLE historique_connexion MODIFY COLUMN id_historique_connection INT NOT NULL AUTO_INCREMENT,
DROP PRIMARY KEY,
ADD PRIMARY KEY (id_historique_connection);

INSERT INTO
    poste (numero_poste)
VALUES
    ('P001'),
    ('P002'),
    ('P003'),
    ('P004'),
    ('P005');

-- Insertion d'états (nécessaire pour poste_etat)
INSERT INTO
    etat (nom)
VALUES
    ('Disponible'),
    ('Occupé'),
    ('En maintenance');

-- Insertion de 3 clients
INSERT INTO
    client (nom, prenom, added_at)
VALUES
    ('Dupont', 'Jean', CURRENT_TIMESTAMP()),
    ('Martin', 'Sophie', CURRENT_TIMESTAMP()),
    ('Bernard', 'Pierre', CURRENT_TIMESTAMP());

INSERT INTO service (description, nom, id_categorie) 
VALUES ('Service dédié à la connexion réseau', 'connexion', 1);

INSERT INTO prix_service (date_modification, prix, mois, annee, description, id_service) 
VALUES (NOW(), 500, 7, 2025, 'Prix pour le connexion 15 minutes', 4);

INSERT INTO prix_service (date_modification, prix, mois, annee, description, id_service) 
VALUES (NOW(), 600, 7, 2025, 'Prix nouveau pour impression', 2);