<?php
require($_SERVER['DOCUMENT_ROOT']."/settings/db_settings.php");
require($_SERVER['DOCUMENT_ROOT']."/utilities/db_gets.php");


$id_bon_livraison = $_GET["id_bon_livraison"];
$bon_livraison = get_row_with_id($con, "BON_LIVRAISON", "id_bon_livraison", $id_bon_livraison);
$bon_livraison_est_correcte = bon_livraison_est_correct($con, $id_bon_livraison);
$bon_commande = get_row_with_id($con, "BON_COMMANDE", "id_bon_commande", $bon_livraison["bon_commande"]);
$produits_voulus = get_all_entrees_bon_commande($con, $bon_livraison["bon_commande"]);
$produits_livres = get_all_entrees_bon_livraison($con, $id_bon_livraison);

$codes_produits_errones = array(
    "produit_manquant" => livraison_get_produits_errones($con, $id_bon_livraison, "produit_manquant"),
    "produit_cite_plusieurs_fois" => livraison_get_produits_errones($con, $id_bon_livraison, "produit_cite_plusieurs_fois"),
    "produit_non_demande" => livraison_get_produits_errones($con, $id_bon_livraison, "produit_non_demande"),
    "quantite_pas_assez_elevee" => livraison_get_produits_errones($con, $id_bon_livraison, "quantite_pas_assez_elevee"),
    "quantite_trop_elevee" => livraison_get_produits_errones($con, $id_bon_livraison, "quantite_trop_elevee")
)
?>

<?php ob_start(); ?>
<main class="flex-column">
    <div class="main-header">
        <div class="flex-row icon-and-title">
            <img class="main-left-page-icon" src="/public/images/left.png" alt="Left page button" onclick="window.location.href = '/views/bons_livraison.php';">
            <h2 id="main-title" class="bon-title">Comparaison entre le bon de livraison et le bon de commande n°<?=$bon_commande["id_bon_commande"]?></h2>
        </div>
        <?php if (!is_null($bon_commande["photo"])): ?>
            <a href="<?=$bon_commande["photo"]?>" target="_blank">
                <button>Voir la photo du bon de commande</button>
            </a>
        <?php endif; ?>
        <?php if (!is_null($bon_livraison["photo"])): ?>
            <a href="<?=$bon_livraison["photo"]?>" target="_blank">
                <button>Voir la photo du bon de livraison</button>
            </a>
        <?php endif; ?>
        <?php if (!$bon_livraison_est_correcte): ?>
            <button>Envoyer un rapport des problèmes au fournisseur</button>
        <?php endif; ?>
    </div>
    <div id="infos">
        <p>Fournisseur : <?=$bon_commande["fournisseur"]?></p>
        <p>Date bon commande : <?=date("d-m-Y H:i:s", strtotime($bon_commande["date_commande"]))?></p>
        <p>Date bon livraison : <?=date("d-m-Y H:i:s", strtotime($bon_livraison["date_livraison"]))?></p>
    </div>
    <table class="sortable-theme-bootstrap" data-sortable>
        <thead>
            <tr>
                <th>Code du produit</th>
                <th>Description</th>
                <th>Quantité demandée</th>
                <th>Quantité livrée</th>
                <th>Problème</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($produits_voulus as $produit_voulu) : ?>
                <?php
                if (in_array($produit_voulu["code_produit"], $codes_produits_errones["produit_manquant"])) {
                    $error_class = "error-class-produit-manquant";
                    $error_msg = "Ce produit n'a pas été livré";
                }
                else if (in_array($produit_voulu["code_produit"], $codes_produits_errones["produit_cite_plusieurs_fois"])) {
                    $error_class = "error-class-produit-cite-plusieurs-fois";
                    $error_msg = "Ce produit a été cité plusieurs fois dans le bon de livraison";
                }
                else if (in_array($produit_voulu["code_produit"], $codes_produits_errones["quantite_pas_assez_elevee"])) {
                    $error_class = "error-class-quantite-pas-assez-elevee";
                    $error_msg = "Ce produit a été livré en trop petite quantité";
                }
                else if (in_array($produit_voulu["code_produit"], $codes_produits_errones["quantite_trop_elevee"])) {
                    $error_class = "error-class-quantite-trop-elevee";
                    $error_msg = "Ce produit a été livré en trop grande quantité";
                }
                else {
                    $error_class = "no-problem";
                    $error_msg = "Aucun problème";
                }
                ?>
                <?php $produit_livre = get_entree_bon_livraison_with_product_id($con, $id_bon_livraison, $produit_voulu["code_produit"]); ?>
                <tr class="<?=$error_class?>">      
                    <td><?=$produit_voulu["code_produit"]?></td>
                    <td><?=$produit_voulu["description_produit"]?></td>
                    <td><?=$produit_voulu["quantite_demandee"]?></td>
                    <td><?=(array_key_exists("quantite_recue", $produit_livre)) ? $produit_livre["quantite_recue"] : "0"?></td>
                    <td><?=$error_msg?></td>
                </tr>
            <?php endforeach; ?>
            <?php foreach ($produits_livres as $produit_livre): ?>
                <?php if (in_array($produit_livre["code_produit"], $codes_produits_errones["produit_non_demande"])) : ?>
                    <tr class="error-class-produit-non-demande">      
                    <td><?=$produit_livre["code_produit"]?></td>
                    <td><?=$produit_livre["description_produit"]?></td>
                    <td>0</td>
                    <td><?=$produit_livre["quantite_recue"]?></td>
                    <td>Ce produit n'a pas été demandé</td>
                </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php
$content = ob_get_clean();
$title = "Comparaison - Bon de commande et de livraison"; 
$styles = array("/public/style/views/comparaison.css", "/libraries/sortable-0.8.0/css/sortable-theme-bootstrap.css");
$scripts = array("/libraries/sortable-0.8.0/js/sortable.min.js");
require($_SERVER['DOCUMENT_ROOT'].'/template.php');
?>