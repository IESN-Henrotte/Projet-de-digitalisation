<?php
// Fournisseur du bon de commande
$fournisseur = "Couleur Nature";

// Création du bon de commande
$photo_destination = "/db_images/exemples/20211125_150801.jpg";
$prepared_statement = $con->prepare("INSERT INTO BON_COMMANDE (fournisseur, date_commande, photo) VALUES (?, ?, ?)");
$prepared_statement->bind_param("sss", $fournisseur, date("Y-m-d H:i:s"), $photo_destination);
$prepared_statement->execute();
$id_bon_commande = $con->insert_id;

// Création des produits associés au bon de commande
/*
(2, "LBM082", "Soupe de poissons BIO", 6),
(2, "A5066616", "Bonbons BIO orange/citron", 12),
(2, "CB1001", "Risotto cèpes BIO", 3),
(2, "PES1005", "Filet de thon en saumure", 1),
*/
$PRODUITS_COMMANDE = array(
    "1-code_produit" => "LBM082",
    "1-description" => "Soupe de poissons BIO",
    "1-quantite" => "6",
    "2-code_produit" => "A5066616",
    "2-description" => "Bonbons BIO orange/citron",
    "2-quantite" => "12",
    "3-code_produit" => "CB1001",
    "3-description" => "Risotto cèpes BIO",
    "3-quantite" => "3",
    "4-code_produit" => "PES1005",
    "4-description" => "Filet de thon en saumure",
    "4-quantite" => "1"
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
(2, "LBM082", 6),
(2, "A5066616", 12),
(2, "CB1001", 3),
(2, "PES1005", 1),
*/
$PRODUITS_LIVRAISON = array(
    "1-code_produit" => "LBM082",
    "1-description" => "Soupe de poissons BIO",
    "1-quantite" => "6",
    "2-code_produit" => "A5066616",
    "2-description" => "Bonbons BIO orange/citron",
    "2-quantite" => "12",
    "3-code_produit" => "CB1001",
    "3-description" => "Risotto cèpes BIO",
    "3-quantite" => "3",
    "4-code_produit" => "PES1005",
    "4-description" => "Filet de thon en saumure",
    "4-quantite" => "1"
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