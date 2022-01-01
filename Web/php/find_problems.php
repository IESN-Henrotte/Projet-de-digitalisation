<?php
$bon_livraison = get_row_with_id($con, "BON_LIVRAISON", "id_bon_livraison", $id_bon_livraison);
$produits_voulus = get_all_entrees_bon_commande($con, $bon_livraison["bon_commande"]);
$produits_livres = get_all_entrees_bon_livraison($con, $id_bon_livraison);

// Recherche des erreurs entre le bon de commande et le bon de livraison
$errors = array(
    "produit_manquant" => array(),
    "produit_cite_plusieurs_fois" => array(),
    "produit_non_demande" => array(),
    "quantite_pas_assez_elevee" => array(),
    "quantite_trop_elevee" => array()
);

// Missing and too much products listed
foreach($produits_voulus as $produit_voulu) {
    $nb_fois_produit = 0;

    foreach($produits_livres as $produit_livre) {
        if ($produit_voulu["code_produit"] == $produit_livre["code_produit"]) {
            $nb_fois_produit++;
        }
    }

    if ($nb_fois_produit == 0) {
        array_push($errors["produit_manquant"], $produit_voulu["code_produit"]);
    }
    else if ($nb_fois_produit > 1) {
        array_push($errors["produit_cite_plusieurs_fois"], $produit_voulu["code_produit"]);
    }
}

// Products not asked
foreach($produits_livres as $produit_livre) {
    $produit_demande = false;

    foreach($produits_voulus as $produit_voulu) {
        if ($produit_livre["code_produit"] == $produit_voulu["code_produit"]) {
            $produit_demande = true;
            break;
        }
    }

    if (!$produit_demande) {
        array_push($errors["produit_non_demande"], $produit_livre["code_produit"]);
    }
}

// Quantity problems
foreach($produits_voulus as $produit_voulu) {
    $not_enough = false;
    $too_much = false;

    foreach($produits_livres as $produit_livre) {
        if ($produit_voulu["code_produit"] == $produit_livre["code_produit"]) {
            if ($produit_livre["quantite_recue"] > $produit_voulu["quantite_demandee"]) {
                $too_much = true;  
                
            }
            else if ($produit_livre["quantite_recue"] < $produit_voulu["quantite_demandee"]) {
                $not_enough = true;
            }
        }
    }

    if ($not_enough) {
        array_push($errors["quantite_pas_assez_elevee"], $produit_voulu["code_produit"]);
    }
    else if ($too_much) {
        array_push($errors["quantite_trop_elevee"], $produit_voulu["code_produit"]);
    }
}

// Sauvegarde dans la base de données
foreach($errors as $type_probleme => $codes_produit) {
    foreach($codes_produit as $code_produit) {
        $prepared_statement = $con->prepare("INSERT INTO PROBLEME (bon_livraison, type_probleme, code_produit) VALUES (?, ?, ?)");
        $prepared_statement->bind_param("iss", $id_bon_livraison, $type_probleme, $code_produit);
        $prepared_statement->execute();
    }
}
?>