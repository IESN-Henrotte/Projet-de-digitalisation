<?php
require($_SERVER['DOCUMENT_ROOT']."/settings/db_settings.php");
require($_SERVER['DOCUMENT_ROOT']."/utilities/db_gets.php");
date_default_timezone_set("Europe/Paris");

// Fournisseur du bon de commande
$fournisseur = "BONPAIN";

// Création du bon de commande
$photo_destination = "/db_images/exemples/20211125_143121.jpg";
$prepared_statement = $con->prepare("INSERT INTO BON_COMMANDE (fournisseur, date_commande, photo) VALUES (?, ?, ?)");
$prepared_statement->bind_param("sss", $fournisseur, date("Y-m-d H:i:s"), $photo_destination);
$prepared_statement->execute();
$id_bon_commande = $con->insert_id;

// Création des produits associés au bon de commande
/*
(1, "10420", "Fine Fleur", 1),
(1, "10430", "Grosse Fleur", 3),
(1, "4201", "Jus de pomme", 2),
(1, "48793", "Salades", 6),
*/
$PRODUITS_COMMANDE = array(
    "1-code_produit" => "10420",
    "1-description" => "Fine Fleur",
    "1-quantite" => "1",
    "2-code_produit" => "10430",
    "2-description" => "Grosse Fleur",
    "2-quantite" => "3",
    "3-code_produit" => "4201",
    "3-description" => "Jus de pomme",
    "3-quantite" => "2",
    "4-code_produit" => "48793",
    "4-description" => "Salades",
    "4-quantite" => "6"
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
(1, "10420", 1),
(1, "10431", 4), -- ERREUR code_produit
(1, "4201", 1), -- ERREUR quantite_recue
*/
$PRODUITS_LIVRAISON = array(
    "1-code_produit" => "10420",
    "1-description" => "Fine Fleur",
    "1-quantite" => "1",
    "2-code_produit" => "10431",
    "2-description" => "Sauce tomate",
    "2-quantite" => "4",
    "3-code_produit" => "4201",
    "3-description" => "Jus de pomme",
    "3-quantite" => "1",
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