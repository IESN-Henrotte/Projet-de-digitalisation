<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require($_SERVER['DOCUMENT_ROOT']."/settings/db_settings.php");
require($_SERVER['DOCUMENT_ROOT']."/utilities/db_gets.php");
date_default_timezone_set("Europe/Paris");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Id du bon de commande associé
    $id_bon_commande = $_POST["bon-commande"];

    // On enregistre la photo s'il y en a une
    $there_is_photo = $_FILES["photo"]["name"] != "";
    if ($there_is_photo) {
        $photo_temp_path = $_FILES['photo']['tmp_name'];
        $photo_name = date("Y-m-d")."_".basename($_FILES["photo"]["name"]);
        $photo_destination = "/db_images/{$photo_name}";
        move_uploaded_file($photo_temp_path, "{$_SERVER['DOCUMENT_ROOT']}/db_images/{$photo_name}");
    }
    else {
        $photo_destination = null;
    }

    // Création du bon de livraison
    $prepared_statement = $con->prepare("INSERT INTO BON_LIVRAISON (bon_commande, photo, date_livraison) VALUES (?, ?, ?)");
    $prepared_statement->bind_param("iss", $id_bon_commande, $photo_destination, date("Y-m-d H:i:s"));
    $prepared_statement->execute();
    $id_bon_livraison = $con->insert_id;

    // Création des produits associés au bon de livraison
    $nb_produits = (count($_POST) - 1)/3;
    for ($i=1; $i <= $nb_produits; $i++) {
        $code_produit = $_POST[$i."-code_produit"];
        $description = $_POST[$i."-description"];
        $quantite = $_POST[$i."-quantite"];

        $prepared_statement = $con->prepare("INSERT INTO ENTREE_BON_LIVRAISON (bon_livraison, code_produit, description_produit, quantite_recue) VALUES (?, ?, ?, ?)");
        $prepared_statement->bind_param("issi", $id_bon_livraison, $code_produit, $description, $quantite);
        $prepared_statement->execute();
    }

    // Recherche des problèmes et sauvegarde dans la DB
    require($_SERVER['DOCUMENT_ROOT']."/php/find_problems.php");

    // Redirection vers la page des bons de livraison
    header("Location: /views/bons_livraison.php");
}
?>