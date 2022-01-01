<?php
// Fournisseur du bon de commande
$fournisseur = "La ferme du tambourin";

// Création du bon de commande
$photo_destination = "/db_images/exemples/20211125_143125.jpg";
$prepared_statement = $con->prepare("INSERT INTO BON_COMMANDE (fournisseur, date_commande, photo) VALUES (?, ?, ?)");
$prepared_statement->bind_param("sss", $fournisseur, date("Y-m-d H:i:s"), $photo_destination);
$prepared_statement->execute();
$id_bon_commande = $con->insert_id;

// Création des produits associés au bon de commande
/*
(3, "BEUR SAL C", "Beurre salé", 12),
(3, "BEUR NO SAL C", "Beurre non salé", 10),
(3, "LAIT ENT 1L C", "Lait entier 1L", 3),
(3, "MAQ NAT C D", "Maquée nature 250g", 1),
(3, "FRO RAD CIB C D", "Fromage radis ciboulette 200g", 14),
(3, "FRO BL LIS C", "Fromage blanc lisse nature 250g", 8),
(3, "FRO BL FIN H C D", "Fromage blanc fines herbes 250g", 5);
*/
$PRODUITS_COMMANDE = array(
    "1-code_produit" => "BEUR SAL C",
    "1-description" => "Beurre salé",
    "1-quantite" => "12",
    "2-code_produit" => "BEUR NO SAL C",
    "2-description" => "Beurre non salé",
    "2-quantite" => "10",
    "3-code_produit" => "LAIT ENT 1L C",
    "3-description" => "Lait entier 1L",
    "3-quantite" => "3",
    "4-code_produit" => "MAQ NAT C D",
    "4-description" => "Maquée nature 250g",
    "4-quantite" => "1",
    "5-code_produit" => "FRO RAD CIB C D",
    "5-description" => "Fromage radis ciboulette 200g",
    "5-quantite" => "14",
    "6-code_produit" => "FRO BL LIS C",
    "6-description" => "Fromage blanc lisse nature 250g",
    "6-quantite" => "8",
    "7-code_produit" => "FRO BL FIN H C D",
    "7-description" => "Fromage blanc fines herbes 250g",
    "7-quantite" => "5"
);
$nb_produits = count($PRODUITS_COMMANDE)/3;
for ($i=1; $i <= $nb_produits; $i++) {
    $code_produit = $PRODUITS_COMMANDE[$i."-code_produit"];
    $description = $PRODUITS_COMMANDE[$i."-description"];
    $quantite = $PRODUITS_COMMANDE[$i."-quantite"];

    $prepared_statement = $con->prepare("INSERT INTO ENTREE_BON_COMMANDE (bon_commande, code_produit, description_produit, quantite_demandee) VALUES (?, ?, ?, ?)");
    $prepared_statement->bind_param("issi", $id_bon_commande, $code_produit, $description, $quantite);
    $prepared_statement->execute();
}






// Création du bon de livraison
$prepared_statement = $con->prepare("INSERT INTO BON_LIVRAISON (bon_commande, date_livraison) VALUES (?, ?)");
$prepared_statement->bind_param("is", $id_bon_commande, date("Y-m-d H:i:s"));
$prepared_statement->execute();
$id_bon_livraison = $con->insert_id;

// Création des produits associés au bon de livraison
/*
(3, "BEUR SAL C", 12),
(3, "BEUR NO SAL C", 10),
(3, "LAIT ENT 1L C", 3),
(3, "MAQ NAT C D", 2), -- ERREUR quantité produit trop élevé
(3, "FRO RAD CIB C D", 10), -- ERREUR quantité produit trop faible
(3, "FRO BL LIS C", 8),
-- (3, "FRO BL FIN H C D", 5); -- ERREUR produit manquant
(3, "AH BON", 4); -- ERREUR produit non demandé
*/
$PRODUITS_LIVRAISON = array(
    "1-code_produit" => "BEUR SAL C",
    "1-description" => "Beurre salé",
    "1-quantite" => "12",
    "2-code_produit" => "BEUR NO SAL C",
    "2-description" => "Beurre non salé",
    "2-quantite" => "10",
    "3-code_produit" => "LAIT ENT 1L C",
    "3-description" => "Lait entier 1L",
    "3-quantite" => "3",
    "4-code_produit" => "MAQ NAT C D",
    "4-description" => "Maquée nature 250g",
    "4-quantite" => "2",
    "5-code_produit" => "FRO RAD CIB C D",
    "5-description" => "Fromage radis ciboulette 200g",
    "5-quantite" => "10",
    "6-code_produit" => "FRO BL LIS C",
    "6-description" => "Fromage blanc lisse nature 250g",
    "6-quantite" => "8",
    "7-code_produit" => "AH BON",
    "7-description" => "Pommes de terre",
    "7-quantite" => "4"
);
$nb_produits = count($PRODUITS_LIVRAISON)/3;
for ($i=1; $i <= $nb_produits; $i++) {
    $code_produit = $PRODUITS_LIVRAISON[$i."-code_produit"];
    $description = $PRODUITS_LIVRAISON[$i."-description"];
    $quantite = $PRODUITS_LIVRAISON[$i."-quantite"];

    $prepared_statement = $con->prepare("INSERT INTO ENTREE_BON_LIVRAISON (bon_livraison, code_produit, description_produit, quantite_recue) VALUES (?, ?, ?, ?)");
    $prepared_statement->bind_param("issi", $id_bon_livraison, $code_produit, $description, $quantite);
    $prepared_statement->execute();
}

// Recherche des problèmes et sauvegarde dans la DB
require($_SERVER['DOCUMENT_ROOT']."/php/find_problems.php");
?>