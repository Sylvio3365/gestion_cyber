-- Insertion de 3 clients
INSERT INTO client (nom, prenom, added_at) 
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

-- Insertion de 3 états de poste
INSERT INTO poste_etat (date_debut, date_fin, id_etat, id_poste)
VALUES 
    ('2024-06-27 09:00:00', NULL, 1, 1),    -- Poste 1 disponible (pas de date de fin)
    ('2024-06-27 08:00:00', NULL, 2, 2),    -- Poste 2 occupé (pas de date de fin)
    ('2024-06-27 10:00:00', '2024-06-27 12:00:00', 3, 3);  -- Poste 3 en maintenance (le 27 juin)

-- -- Insertion de 3 poste_etat
INSERT INTO poste_etat (date_debut, date_fin, id_etat, id_poste)
SELECT CURRENT_TIMESTAMP(), NULL, 1, 1 UNION ALL    -- Poste 1 disponible
SELECT CURRENT_TIMESTAMP(), NULL, 2, 2 UNION ALL    -- Poste 2 occupé
SELECT CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP() + INTERVAL 2 HOUR, 3, 3;  -- Poste 3 en maintenance