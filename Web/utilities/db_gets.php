<?php
function sql_select($con, $statement, $params_type=null, $params=null, $force_array=false) {
    // Prepare query
    $prepared_statement = $con->prepare($statement);

    // Bind parameters if any
    if (strlen($params_type) == 1) {
        $prepared_statement->bind_param($params_type, $params);
    }
    else if (strlen($params_type) > 1) {
        $prepared_statement->bind_param($params_type, ...$params);
    }

    // Get result of the query
    $prepared_statement->execute();
    $result = $prepared_statement->get_result()->fetch_all(MYSQLI_ASSOC);

    // Return result (as an array or as an array of arrays)
    if (count($result) == 1 && $force_array == false) {
        return $result[0];
    }
    return $result;
}

function get_row_with_id($con, $table_name, $id_column_name, $id) {
    return sql_select(
        $con,
        "SELECT * FROM $table_name WHERE $id_column_name = ?",
        "s",
        $id
    );
}

function get_all_bons_commande($con) {
    return sql_select(
        $con,
        "SELECT * FROM BON_COMMANDE",
        force_array: true
    );
}
function get_all_entrees_bon_commande($con, $id_bon_commande) {
    return sql_select(
        $con,
        "SELECT * FROM ENTREE_BON_COMMANDE WHERE bon_commande = ?",
        "i",
        $id_bon_commande,
        force_array: true
    );
}
function get_bon_livraison_linked($con, $id_bon_commande) {
    return sql_select(
        $con,
        "SELECT * FROM BON_LIVRAISON WHERE bon_commande = ?",
        "i",
        $id_bon_commande,
    );
}

function get_all_bons_livraison($con) {
    return sql_select(
        $con,
        "SELECT * FROM BON_LIVRAISON",
        force_array: true
    );
}
function get_all_entrees_bon_livraison($con, $id_bon_livraison) {
    return sql_select(
        $con,
        "SELECT * FROM ENTREE_BON_LIVRAISON WHERE bon_livraison = ?",
        "i",
        $id_bon_livraison,
        force_array: true
    );
}
function get_entree_bon_livraison_with_product_id($con, $id_bon_livraison, $product_id) {
    return sql_select(
        $con,
        "SELECT * FROM ENTREE_BON_LIVRAISON WHERE bon_livraison = ? and code_produit = ? LIMIT 1",
        "is",
        array($id_bon_livraison, $product_id)
    );
}

function livraison_get_produits_errones($con, $id_bon_livraison, $type_probleme) {
    $result_in_associative_array = sql_select(
        $con,
        "SELECT code_produit FROM PROBLEME WHERE bon_livraison = ? and type_probleme = ?",
        "is",
        array($id_bon_livraison, $type_probleme),
        force_array: true
    );

    return array_column($result_in_associative_array, "code_produit", );
}

function all_bons_commande_are_linked($con) {
    $nb_bons_commande = sql_select(
        $con,
        "SELECT COUNT(*) as NB FROM BON_COMMANDE"
    )["NB"];

    $nb_bons_livraison = sql_select(
        $con,
        "SELECT COUNT(*) as NB FROM BON_LIVRAISON"
    )["NB"];
    
    return $nb_bons_commande == $nb_bons_livraison;
}

function bon_livraison_est_correct($con, $id_bon_livraison) {
    $nb_problemes = sql_select(
        $con,
        "SELECT COUNT(*) as NB FROM PROBLEME WHERE bon_livraison = ?",
        "i",
        $id_bon_livraison
    )["NB"];

    return $nb_problemes == 0;
}
?>