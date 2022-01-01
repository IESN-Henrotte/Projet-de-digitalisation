INSERT INTO BON_COMMANDE (fournisseur, date_commande, photo, respecte)
VALUES
("BONPAIN", "2021-12-06 10:48:02", "/db_images/exemples/20211125_143121.jpg", false),
("Couleur Nature", "2021-09-14 09:34:54", "/db_images/exemples/20211125_150801.jpg", true),
("La ferme du tambourin", "2021-10-05 08:01:12", "/db_images/exemples/20211125_143125.jpg", false);

INSERT INTO ENTREE_BON_COMMANDE (bon_commande, code_produit, description_produit, quantite_demandee)
VALUES
(1, "10420", "Fine Fleur", 1),
(1, "10430", "Grosse Fleur", 3),
(1, "4201", "Jus de pomme", 2),
(1, "48793", "Salades", 6),
(1, "7258", "Fromage de chèvre", 4),
(2, "LBM082", "Soupe de poissons BIO", 6),
(2, "A5066616", "Bonbons BIO orange/citron", 12),
(2, "CB1001", "Risotto cèpes BIO", 3),
(2, "PES1005", "Filet de thon en saumure", 1),
(3, "BEUR SAL C", "Beurre salé", 12),
(3, "BEUR NO SAL C", "Beurre non salé", 10),
(3, "LAIT ENT 1L C", "Lait entier 1L", 3),
(3, "MAQ NAT C D", "Maquée nature 250g", 1),
(3, "FRO RAD CIB C D", "Fromage radis ciboulette 200g", 14),
(3, "FRO BL LIS C", "Fromage blanc lisse nature 250g", 8),
(3, "FRO BL FIN H C D", "Fromage blanc fines herbes 250g", 5);


INSERT INTO BON_LIVRAISON (bon_commande, date_livraison)
VALUES
(1, '2021-12-10 08:14:30'),
(2, '2021-10-03 06:05:58'),
(3, '2021-10-07 07:51:43');

INSERT INTO ENTREE_BON_LIVRAISON (bon_livraison, code_produit, quantite_recue)
VALUES
(1, "10420", 1),
(1, "10431", 4), -- ERREUR code_produit
(1, "4201", 1), -- ERREUR quantite_recue
(2, "LBM082", 6),
(2, "A5066616", 12),
(2, "CB1001", 3),
(2, "PES1005", 1),
(3, "BEUR SAL C", 12),
(3, "BEUR NO SAL C", 10),
(3, "LAIT ENT 1L C", 3),
(3, "MAQ NAT C D", 2), -- ERREUR quantité produit trop élevé
(3, "FRO RAD CIB C D", 10), -- ERREUR quantité produit trop faible
(3, "FRO BL LIS C", 8),
-- (3, "FRO BL FIN H C D", 5); -- ERREUR produit manquant
(3, "AH BON", 4); -- ERREUR produit non demandé



SELECT * FROM BON_COMMANDE;
SELECT * FROM ENTREE_BON_COMMANDE;
SELECT * FROM BON_LIVRAISON;
SELECT * FROM ENTREE_BON_LIVRAISON;
SELECT * FROM PROBLEME;

SELECT * FROM PROBLEME WHERE bon_livraison = 4 and type_probleme = "produit_manquant";
SELECT COUNT(*) FROM PROBLEME WHERE bon_livraison = 3;

SELECT COUNT(*) FROM BON_COMMANDE
WHERE respecte IS NULL;

SELECT COUNT(*) FROM BON_COMMANDE;
SELECT COUNT(*) FROM BON_LIVRAISON;

SELECT ENTREE_BON_COMMANDE.bon_commande,
	   ENTREE_BON_COMMANDE.code_produit as code_produit_bon_commande,
       ENTREE_BON_COMMANDE.description_produit,
       quantite_demandee,
       bon_livraison,
       ENTREE_BON_LIVRAISON.code_produit as code_produit_bon_livraison,
       quantite_recue
FROM ENTREE_BON_COMMANDE
INNER JOIN BON_LIVRAISON
	ON BON_LIVRAISON.bon_commande = ENTREE_BON_COMMANDE.bon_commande
INNER JOIN ENTREE_BON_LIVRAISON
	ON ENTREE_BON_LIVRAISON.bon_livraison = BON_LIVRAISON.id_bon_livraison
WHERE ENTREE_BON_COMMANDE.bon_commande = 1;



SELECT * FROM ENTREE_BON_LIVRAISON WHERE bon_livraison = 1 and code_produit = "10420" LIMIT 1;