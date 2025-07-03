-- Corrige la table vente_draft pour permettre id_client NULL
ALTER TABLE vente_draft MODIFY id_client INT NULL;
-- Création de la table service_produit pour gérer la consommation de produits par service
CREATE TABLE service_produit (
    id_service_produit INT AUTO_INCREMENT,
    id_service INT NOT NULL,
    id_produit INT NOT NULL,
    quantite_par_service INT NOT NULL, -- Quantité de produit consommée par service
    PRIMARY KEY (id_service_produit),
    FOREIGN KEY (id_service) REFERENCES service (id_service),
    FOREIGN KEY (id_produit) REFERENCES produit (id_produit)
);

INSERT INTO account_type(name, remarque) VALUES
('admin', 'Administrateur'),
('vendeur', 'employé utilisateur');
INSERT INTO user_app (name, username, firstname, email, password, deleted_at, id_account_type) VALUES
('admin', 'admin', 'admin', 'admin', 'admin', NULL, 1),
('user', 'user', 'User', 'user', 'user', NULL, 2);




INSERT INTO branche (nom, description) VALUES
  ('Fourniture',  'Articles et matériel de bureau'),
  ('Connexion',   'Abonnements & équipements réseau'),
  ('Multiservice','Services d''impression, scan, etc.');

INSERT INTO categorie (nom, id_branche) VALUES
  -- Fourniture
  ('Papeterie',    1),
  ('Accessoires',  1),

  -- Connexion
  ('Internet',     2),

  -- Multiservice
  ('Services', 3);


INSERT INTO marque (nom) VALUES
  ('BIC'),
  ('Papyrus'),
  ('HP');

INSERT INTO produit (nom, description, id_marque, id_categorie) VALUES
('Stylo bleu', 'Stylo bille classique', 1, 1),
('Crayon à papier', 'Crayon graphite HB', 1, 1),
('Papier A4', '500 feuilles', 2, 1),
('Classeur', 'Classeur cartonné', 2, 1);

INSERT INTO type_mouvement (type) VALUES ('entrée'), ('sortie');

INSERT INTO stock (quantite, date_mouvement, id_produit, id_mouvement) VALUES
(100, NOW(), 1, 1),
(80, NOW(), 2, 1),
(500, NOW(), 3, 1),
(15, NOW(), 4, 1);

INSERT INTO prix_produit (date_modification, prix, mois, annee, description, id_produit) VALUES
(NOW(), 500, 6, 2025, 'Prix courant', 1),
(NOW(), 400, 6, 2025, 'Prix courant', 2),
(NOW(), 100, 6, 2025, 'Prix courant', 3),
(NOW(), 2500, 6, 2025, 'Prix courant', 4);

INSERT INTO service (nom, description, id_categorie) VALUES
('Photocopie', 'Par page A4', 2),
('Impression', 'Par page A4', 2),
('Scan', 'Par page', 2);

INSERT INTO prix_service (date_modification, prix, mois, annee, description, id_service) VALUES
(NOW(), 100, 6, 2025, 'Tarif standard', 1),   -- Photocopie NB
(NOW(), 400, 6, 2025, 'Tarif standard', 2),   -- Impression couleur
(NOW(), 300, 6, 2025, 'Tarif standard', 3);  -- scan

INSERT INTO type_de_payement (nom) VALUES
('Especes'), ('Mobile Money');

INSERT INTO service_produit (id_service, id_produit, quantite_par_service) VALUES
(1, 3, 1), -- Photocopie consomme 1 papier A4 par service
(2, 3, 1), -- Impression consomme 1 papier A4 par service
(3, 3, 1); -- Scan consomme 1 papier A4 par service (si applicable)