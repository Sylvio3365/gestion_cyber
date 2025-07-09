drop database cyber;

CREATE database cyber;

use cyber;

CREATE TABLE account_type (
    id_account_type INT AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    remarque VARCHAR(50),
    deleted_at DATETIME,
    PRIMARY KEY (id_account_type),
    UNIQUE (name)
);

CREATE TABLE branche (
    id_branche INT AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    description VARCHAR(50),
    deleted_at DATETIME,
    PRIMARY KEY (id_branche),
    UNIQUE (nom)
);

CREATE TABLE categorie (
    id_categorie INT AUTO_INCREMENT,
    nom VARCHAR(50),
    id_branche INT NOT NULL,
    PRIMARY KEY (id_categorie),
    FOREIGN KEY (id_branche) REFERENCES branche (id_branche)
);

CREATE TABLE service (
    id_service INT AUTO_INCREMENT,
    description VARCHAR(50),
    nom VARCHAR(50),
    deleted_at DATETIME,
    id_categorie INT NOT NULL,
    PRIMARY KEY (id_service),
    FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie)
);

CREATE TABLE type_mouvement (
    id_mouvement INT AUTO_INCREMENT,
    type VARCHAR(50) NOT NULL,
    PRIMARY KEY (id_mouvement)
);

CREATE TABLE prix_service (
    id_prix_service INT AUTO_INCREMENT,
    date_modification DATETIME,
    prix DECIMAL(15, 2) NOT NULL,
    mois INT NOT NULL,
    annee INT NOT NULL,
    description VARCHAR(50) NOT NULL,
    id_service INT NOT NULL,
    PRIMARY KEY (id_prix_service),
    FOREIGN KEY (id_service) REFERENCES service (id_service)
);

CREATE TABLE client (
    id_client INT AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    added_at DATETIME NOT NULL,
    deleted_at DATETIME,
    PRIMARY KEY (id_client)
);

CREATE TABLE statut (
    id_statut INT AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    description VARCHAR(50),
    deleted_at DATETIME,
    PRIMARY KEY (id_statut),
    UNIQUE (nom)
);

CREATE TABLE prix_achat_service (
    id_prix_achat_service INT AUTO_INCREMENT,
    mois INT NOT NULL,
    date_modification VARCHAR(50),
    annee INT NOT NULL,
    prix DECIMAL(20, 2) NOT NULL,
    etat INT NOT NULL,
    id_service INT NOT NULL,
    PRIMARY KEY (id_prix_achat_service),
    FOREIGN KEY (id_service) REFERENCES service (id_service)
);

CREATE TABLE marque (
    id_marque INT AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    deleted_at DATETIME,
    PRIMARY KEY (id_marque)
);

CREATE TABLE poste (
    id_poste INT AUTO_INCREMENT,
    numero_poste VARCHAR(50) NOT NULL,
    deleted_at DATETIME,
    PRIMARY KEY (id_poste),
    UNIQUE (numero_poste)
);

CREATE TABLE etat (
    id_etat INT AUTO_INCREMENT,
    deleted_at DATETIME,
    nom VARCHAR(50) NOT NULL,
    PRIMARY KEY (id_etat),
    UNIQUE (nom)
);

CREATE TABLE poste_etat (
    id_poste_etat INT AUTO_INCREMENT,
    date_debut DATETIME NOT NULL,
    date_fin DATETIME,
    id_etat INT NOT NULL,
    id_poste INT NOT NULL,
    PRIMARY KEY (id_poste_etat),
    FOREIGN KEY (id_etat) REFERENCES etat (id_etat),
    FOREIGN KEY (id_poste) REFERENCES poste (id_poste)
);

CREATE TABLE historique_connexion (
    id_historique_connection VARCHAR(50),
    date_debut DATETIME NOT NULL,
    date_fin DATETIME,
    id_client INT NOT NULL,
    id_poste INT,
    PRIMARY KEY (id_historique_connection),
    FOREIGN KEY (id_client) REFERENCES client (id_client),
    FOREIGN KEY (id_poste) REFERENCES poste (id_poste)
);

CREATE TABLE type_de_payement (
    id_type_de_payement INT AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    PRIMARY KEY (id_type_de_payement)
);

CREATE TABLE user_app (
    id_user INT AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL,
    firstname VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(250) NOT NULL,
    deleted_at DATETIME,
    id_account_type INT NOT NULL,
    PRIMARY KEY (id_user),
    UNIQUE (name),
    UNIQUE (email),
    FOREIGN KEY (id_account_type) REFERENCES account_type (id_account_type)
);

CREATE TABLE produit (
    id_produit INT AUTO_INCREMENT,
    description VARCHAR(50),
    nom VARCHAR(50) NOT NULL,
    deleted_at DATETIME,
    id_marque INT NOT NULL,
    id_categorie INT NOT NULL,
    PRIMARY KEY (id_produit),
    FOREIGN KEY (id_marque) REFERENCES marque (id_marque),
    FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie)
);

CREATE TABLE stock (
    id_stock INT AUTO_INCREMENT,
    quantite INT NOT NULL,
    date_mouvement DATETIME,
    id_produit INT NOT NULL,
    id_mouvement INT NOT NULL,
    PRIMARY KEY (id_stock),
    FOREIGN KEY (id_produit) REFERENCES produit (id_produit),
    FOREIGN KEY (id_mouvement) REFERENCES type_mouvement (id_mouvement)
);

CREATE TABLE prix_produit (
    id_prix_produit INT AUTO_INCREMENT,
    date_modification DATETIME,
    prix DECIMAL(20, 4) NOT NULL,
    mois INT NOT NULL,
    annee INT NOT NULL,
    description VARCHAR(50),
    id_produit INT NOT NULL,
    PRIMARY KEY (id_prix_produit),
    FOREIGN KEY (id_produit) REFERENCES produit (id_produit)
);

CREATE TABLE vente_draft (
    id_vente_draft INT AUTO_INCREMENT,
    date_creation DATETIME NOT NULL,
    id_user INT NOT NULL,
    id_client INT NOT NULL,
    PRIMARY KEY (id_vente_draft),
    FOREIGN KEY (id_user) REFERENCES user_app (id_user),
    FOREIGN KEY (id_client) REFERENCES client (id_client)
);

CREATE TABLE vente_draft_produit (
    id_vente_draft_produit INT AUTO_INCREMENT,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(25, 2) NOT NULL,
    id_vente_draft INT NOT NULL,
    id_produit INT NOT NULL,
    PRIMARY KEY (id_vente_draft_produit),
    FOREIGN KEY (id_vente_draft) REFERENCES vente_draft (id_vente_draft),
    FOREIGN KEY (id_produit) REFERENCES produit (id_produit)
);

CREATE TABLE vente_draft_service (
    id_vente_draft_service INT AUTO_INCREMENT,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(25, 2) NOT NULL,
    id_service INT NOT NULL,
    id_vente_draft INT NOT NULL,
    PRIMARY KEY (id_vente_draft_service),
    FOREIGN KEY (id_service) REFERENCES service (id_service),
    FOREIGN KEY (id_vente_draft) REFERENCES vente_draft (id_vente_draft)
);

CREATE TABLE vente (
    id_vente INT AUTO_INCREMENT,
    date_vente DATETIME NOT NULL,
    total DECIMAL(25, 2) NOT NULL,
    argent_donner DECIMAL(25, 2) NOT NULL,
    id_type_de_payement INT NOT NULL,
    id_vente_draft INT NOT NULL,
    PRIMARY KEY (id_vente),
    UNIQUE (id_vente_draft),
    FOREIGN KEY (id_type_de_payement) REFERENCES type_de_payement (id_type_de_payement),
    FOREIGN KEY (id_vente_draft) REFERENCES vente_draft (id_vente_draft)
);

CREATE TABLE prix_achat_produit (
    id_prix_achat_produit INT AUTO_INCREMENT,
    annee INT NOT NULL,
    mois INT NOT NULL,
    date_modification DATETIME NOT NULL,
    etat INT NOT NULL,
    prix DECIMAL(15, 2) NOT NULL,
    id_produit INT NOT NULL,
    PRIMARY KEY (id_prix_achat_produit),
    FOREIGN KEY (id_produit) REFERENCES produit (id_produit)
);

CREATE TABLE vente_draft_statut (
    id_vente_draft INT,
    id_statut INT,
    date_modification DATETIME,
    PRIMARY KEY (id_vente_draft, id_statut),
    FOREIGN KEY (id_vente_draft) REFERENCES vente_draft (id_vente_draft),
    FOREIGN KEY (id_statut) REFERENCES statut (id_statut)
);

CREATE TABLE parametre_wifi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mdp VARCHAR(255) NOT NULL,
    date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO parametre_wifi (mdp) 
VALUES ('MonMotDePasseSecret');


CREATE OR REPLACE VIEW vue_produits_services_branche AS
SELECT
    b.id_branche,
    b.nom AS nom_branche,
    'produit' AS type,
    p.id_produit AS id,
    p.nom,
    p.description,
    c.nom AS categorie,
    COALESCE(
        (
            SELECT pp1.prix
            FROM prix_produit pp1
            WHERE
                pp1.id_produit = p.id_produit
                AND pp1.mois = MONTH(CURDATE())
                AND pp1.annee = YEAR(CURDATE())
            ORDER BY pp1.date_modification DESC
            LIMIT 1
        ),
        (
            SELECT pp2.prix
            FROM prix_produit pp2
            WHERE
                pp2.id_produit = p.id_produit
            ORDER BY pp2.date_modification DESC
            LIMIT 1
        )
    ) AS prix,
    (
        SELECT SUM(s.quantite)
        FROM stock s
        WHERE
            s.id_produit = p.id_produit
    ) AS stock
FROM
    produit p
    INNER JOIN categorie c ON c.id_categorie = p.id_categorie
    INNER JOIN branche b ON b.id_branche = c.id_branche
WHERE
    p.deleted_at IS NULL
    AND b.nom != 'Connexion'
UNION
SELECT
    b.id_branche,
    b.nom AS nom_branche,
    'service' AS type,
    s.id_service AS id,
    s.nom,
    s.description,
    c.nom AS categorie,
    COALESCE(
        (
            SELECT ps1.prix
            FROM prix_service ps1
            WHERE
                ps1.id_service = s.id_service
                AND ps1.mois = MONTH(CURDATE())
                AND ps1.annee = YEAR(CURDATE())
            ORDER BY ps1.date_modification DESC
            LIMIT 1
        ),
        (
            SELECT ps2.prix
            FROM prix_service ps2
            WHERE
                ps2.id_service = s.id_service
            ORDER BY ps2.date_modification DESC
            LIMIT 1
        )
    ) AS prix,
    NULL AS stock
FROM
    service s
    INNER JOIN categorie c ON c.id_categorie = s.id_categorie
    INNER JOIN branche b ON b.id_branche = c.id_branche
WHERE
    s.deleted_at IS NULL
    AND b.nom != 'Connexion';

CREATE OR REPLACE VIEW vue_stats_par_branche AS
SELECT
    b.id_branche,
    b.nom AS nom_branche,
    'produit' AS type_vente,
    p.id_produit AS id_article,
    p.nom AS nom_article,
    v.date_vente,
    vdp.quantite,
    vdp.prix_unitaire,
    (
        vdp.quantite * vdp.prix_unitaire
    ) AS total
FROM
    vente_draft_produit vdp
    JOIN vente_draft vd ON vdp.id_vente_draft = vd.id_vente_draft
    JOIN vente v ON vd.id_vente_draft = v.id_vente_draft
    JOIN produit p ON vdp.id_produit = p.id_produit
    JOIN categorie c ON p.id_categorie = c.id_categorie
    JOIN branche b ON c.id_branche = b.id_branche
UNION ALL
SELECT
    b.id_branche,
    b.nom AS nom_branche,
    'service' AS type_vente,
    s.id_service AS id_article,
    s.nom AS nom_article,
    v.date_vente,
    vds.quantite,
    vds.prix_unitaire,
    (
        vds.quantite * vds.prix_unitaire
    ) AS total
FROM
    vente_draft_service vds
    JOIN vente_draft vd ON vds.id_vente_draft = vd.id_vente_draft
    JOIN vente v ON vd.id_vente_draft = v.id_vente_draft
    JOIN service s ON vds.id_service = s.id_service
    JOIN categorie c ON s.id_categorie = c.id_categorie
    JOIN branche b ON c.id_branche = b.id_branche;