DROP DATABASE IF EXISTS cyber;

CREATE DATABASE cyber;

USE cyber;

CREATE TABLE
    account_type (
        id_account_type INT AUTO_INCREMENT,
        name VARCHAR(50) NOT NULL,
        remarque VARCHAR(50),
        deleted_at DATETIME,
        PRIMARY KEY (id_account_type),
        UNIQUE (name)
    );

CREATE TABLE
    branche (
        id_branche INT AUTO_INCREMENT,
        nom VARCHAR(50) NOT NULL,
        description VARCHAR(50),
        deleted_at DATETIME,
        PRIMARY KEY (id_branche),
        UNIQUE (nom)
    );

CREATE TABLE
    categorie (
        id_categorie INT AUTO_INCREMENT,
        nom VARCHAR(50),
        id_branche INT NOT NULL,
        PRIMARY KEY (id_categorie),
        FOREIGN KEY (id_branche) REFERENCES branche (id_branche)
    );

CREATE TABLE
    service (
        id_service INT AUTO_INCREMENT,
        description VARCHAR(50),
        nom VARCHAR(50),
        deleted_at DATETIME,
        id_categorie INT NOT NULL,
        PRIMARY KEY (id_service),
        FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie)
    );

CREATE TABLE
    type_mouvement (
        id_mouvement INT AUTO_INCREMENT,
        type VARCHAR(50) NOT NULL,
        PRIMARY KEY (id_mouvement)
    );

CREATE TABLE
    prix_service (
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

CREATE TABLE
    client (
        id_client INT AUTO_INCREMENT,
        nom VARCHAR(50) NOT NULL,
        prenom VARCHAR(50) NOT NULL,
        added_at DATETIME NOT NULL,
        deleted_at DATETIME,
        PRIMARY KEY (id_client)
    );

CREATE TABLE
    statut (
        id_statut INT AUTO_INCREMENT,
        nom VARCHAR(50) NOT NULL,
        description VARCHAR(50),
        deleted_at DATETIME,
        PRIMARY KEY (id_statut),
        UNIQUE (nom)
    );

CREATE TABLE
    prix_achat_service (
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

CREATE TABLE
    marque (
        id_marque INT AUTO_INCREMENT,
        nom VARCHAR(50) NOT NULL,
        deleted_at DATETIME,
        PRIMARY KEY (id_marque)
    );

CREATE TABLE
    poste (
        id_poste INT AUTO_INCREMENT,
        numero_poste VARCHAR(50) NOT NULL,
        deleted_at DATETIME,
        PRIMARY KEY (id_poste),
        UNIQUE (numero_poste)
    );

CREATE TABLE
    etat (
        id_etat INT AUTO_INCREMENT,
        deleted_at DATETIME,
        nom VARCHAR(50) NOT NULL,
        PRIMARY KEY (id_etat),
        UNIQUE (nom)
    );

CREATE TABLE
    poste_etat (
        id_poste_etat INT AUTO_INCREMENT,
        date_debut DATETIME NOT NULL,
        date_fin DATETIME,
        id_etat INT NOT NULL,
        id_poste INT NOT NULL,
        PRIMARY KEY (id_poste_etat),
        FOREIGN KEY (id_etat) REFERENCES etat (id_etat),
        FOREIGN KEY (id_poste) REFERENCES poste (id_poste)
    );

CREATE TABLE
    historique_connexion (
        id_historique_connection VARCHAR(50),
        date_debut DATETIME NOT NULL,
        date_fin DATETIME,
        id_client INT NOT NULL,
        id_poste INT,
        PRIMARY KEY (id_historique_connection),
        FOREIGN KEY (id_client) REFERENCES client (id_client),
        FOREIGN KEY (id_poste) REFERENCES poste (id_poste)
    );

CREATE TABLE
    type_de_payement (
        id_type_de_payement INT AUTO_INCREMENT,
        nom VARCHAR(50) NOT NULL,
        PRIMARY KEY (id_type_de_payement)
    );

CREATE TABLE
    user_app (
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

CREATE TABLE
    produit (
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

CREATE TABLE
    stock (
        id_stock INT AUTO_INCREMENT,
        quantite INT NOT NULL,
        date_mouvement DATETIME,
        id_produit INT NOT NULL,
        id_mouvement INT NOT NULL,
        PRIMARY KEY (id_stock),
        FOREIGN KEY (id_produit) REFERENCES produit (id_produit),
        FOREIGN KEY (id_mouvement) REFERENCES type_mouvement (id_mouvement)
    );

CREATE TABLE
    prix_produit (
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

CREATE TABLE
    vente_draft (
        id_vente_draft INT AUTO_INCREMENT,
        date_creation DATETIME NOT NULL,
        id_user INT NOT NULL,
        id_client INT NOT NULL,
        PRIMARY KEY (id_vente_draft),
        FOREIGN KEY (id_user) REFERENCES user_app (id_user),
        FOREIGN KEY (id_client) REFERENCES client (id_client)
    );

CREATE TABLE
    vente_draft_produit (
        id_vente_draft_produit INT AUTO_INCREMENT,
        quantite INT NOT NULL,
        prix_unitaire DECIMAL(25, 2) NOT NULL,
        id_vente_draft INT NOT NULL,
        id_produit INT NOT NULL,
        PRIMARY KEY (id_vente_draft_produit),
        FOREIGN KEY (id_vente_draft) REFERENCES vente_draft (id_vente_draft),
        FOREIGN KEY (id_produit) REFERENCES produit (id_produit)
    );

CREATE TABLE
    vente_draft_service (
        id_vente_draft_service INT AUTO_INCREMENT,
        quantite INT NOT NULL,
        prix_unitaire DECIMAL(25, 2) NOT NULL,
        id_service INT NOT NULL,
        id_vente_draft INT NOT NULL,
        PRIMARY KEY (id_vente_draft_service),
        FOREIGN KEY (id_service) REFERENCES service (id_service),
        FOREIGN KEY (id_vente_draft) REFERENCES vente_draft (id_vente_draft)
    );

CREATE TABLE
    vente (
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

CREATE TABLE
    prix_achat_produit (
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

CREATE TABLE
    vente_draft_statut (
        id_vente_draft INT,
        id_statut INT,
        date_modification DATETIME,
        PRIMARY KEY (id_vente_draft, id_statut),
        FOREIGN KEY (id_vente_draft) REFERENCES vente_draft (id_vente_draft),
        FOREIGN KEY (id_statut) REFERENCES statut (id_statut)
    );