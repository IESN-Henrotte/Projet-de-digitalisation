CREATE DATABASE prototype_projet_digitalisation;
USE prototype_projet_digitalisation;

CREATE TABLE BON_COMMANDE (
	id_bon_commande INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    fournisseur VARCHAR(100) NOT NULL,
    photo VARCHAR(255),
    archive BOOL NOT NULL DEFAULT FALSE,
    date_commande DATETIME NOT NULL
);

CREATE TABLE ENTREE_BON_COMMANDE (
	id_entree_bon_commande INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    bon_commande INT NOT NULL,
    code_produit VARCHAR(40) NOT NULL,
    description_produit VARCHAR(255),
    quantite_demandee INT NOT NULL,
    /*prix_produit DECIMAL(5,2) NOT NULL,*/
    FOREIGN KEY (bon_commande) REFERENCES BON_COMMANDE(id_bon_commande)
);

CREATE TABLE BON_LIVRAISON (
	id_bon_livraison INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    bon_commande INT NOT NULL,
    photo VARCHAR(255),
    date_livraison DATETIME NOT NULL,
    
    FOREIGN KEY (bon_commande) REFERENCES BON_COMMANDE(id_bon_commande)
);

CREATE TABLE ENTREE_BON_LIVRAISON (
	id_entree_bon_livraison INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    bon_livraison INT NOT NULL,
    code_produit VARCHAR(40) NOT NULL,
    description_produit VARCHAR(255),
    quantite_recue INT NOT NULL,
    /*prix_produit DECIMAL(5,2) NOT NULL,*/
    
    FOREIGN KEY (bon_livraison) REFERENCES BON_LIVRAISON(id_bon_livraison)
);

CREATE TABLE PROBLEME (
	id_problem INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	bon_livraison INT NOT NULL,
    type_probleme VARCHAR(100) NOT NULL,
    code_produit VARCHAR(40) NOT NULL,
    
    FOREIGN KEY (bon_livraison) REFERENCES BON_LIVRAISON(id_bon_livraison)
);