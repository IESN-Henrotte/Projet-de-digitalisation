<?php
require($_SERVER['DOCUMENT_ROOT']."/settings/db_settings.php");
require($_SERVER['DOCUMENT_ROOT']."/utilities/db_gets.php");
date_default_timezone_set("Europe/Paris");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Fournisseur du bon de commande
    $fournisseur = $_POST["fournisseur"];

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

    // Création du bon de commande
    $prepared_statement = $con->prepare("INSERT INTO BON_COMMANDE (fournisseur, date_commande, photo) VALUES (?, ?, ?)");
    $prepared_statement->bind_param("sss", $fournisseur, date("Y-m-d H:i:s"), $photo_destination);
    $prepared_statement->execute();
    $id_bon_commande = $con->insert_id;

    // Création des produits associés au bon de commande
    $nb_produits = (count($_POST) - 1)/3;
    for ($i=1; $i <= $nb_produits; $i++) {
        $code_produit = $_POST[$i."-code_produit"];
        $description = $_POST[$i."-description"];
        $quantite = $_POST[$i."-quantite"];

        $prepared_statement = $con->prepare("INSERT INTO ENTREE_BON_COMMANDE (bon_commande, code_produit, description_produit, quantite_demandee) VALUES (?, ?, ?, ?)");
        $prepared_statement->bind_param("issi", $id_bon_commande, $code_produit, $description, $quantite);
        $prepared_statement->execute();
    }

    // Redirection vers la page des bons de commande
    header("Location: /views/bons_commande.php");
}
?>