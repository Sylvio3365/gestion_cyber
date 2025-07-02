-- Insertion de 3 clients
INSERT INTO
    client (nom, prenom, added_at)
VALUES
    ('Dupont', 'Jean', CURRENT_TIMESTAMP()),
    ('Martin', 'Sophie', CURRENT_TIMESTAMP()),
    ('Bernard', 'Pierre', CURRENT_TIMESTAMP());

-- Insertion de 5 postes
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

INSERT INTO branche (nom, description) 
VALUES ('connexion', 'Branche dédiée à la gestion des connexions');

INSERT INTO categorie (nom, id_branche) 
VALUES ('connexion', 1);

INSERT INTO service (description, nom, id_categorie) 
VALUES ('Service dédié à la connexion réseau', 'connexion', 1);

INSERT INTO prix_service (date_modification, prix, mois, annee, description, id_service) 
VALUES (NOW(), 500, 7, 2025, 'Prix pour le connexion 15 minutes', 1);

INSERT INTO account_type (name, remarque)
VALUES ('Admin', 'Compte administrateur');

INSERT INTO user_app (name, username, firstname, email, password, id_account_type)
VALUES ('John Doe', 'johndoe', 'John', 'johndoe@example.com', 'hashedpassword123', 1);


INSERT INTO type_de_payement (id_type_de_payement, nom)
VALUES (1, 'espece');

