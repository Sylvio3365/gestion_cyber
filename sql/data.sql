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
