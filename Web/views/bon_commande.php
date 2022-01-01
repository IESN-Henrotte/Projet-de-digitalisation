<?php
require($_SERVER['DOCUMENT_ROOT']."/settings/db_settings.php");
require($_SERVER['DOCUMENT_ROOT']."/utilities/db_gets.php");

$id_bon_commande = $_GET["id_bon_commande"];
$bon_commande = get_row_with_id($con, "BON_COMMANDE", "id_bon_commande", $id_bon_commande);
$entrees_bon_commande = get_all_entrees_bon_commande($con, $id_bon_commande);
?>

<?php ob_start(); ?>
<main class="flex-column">
        <div class="main-header">
            <div class="flex-row icon-and-title">
                <img class="main-left-page-icon" src="/public/images/left.png" alt="Left page button" onclick="window.location.href = '/views/bons_commande.php';">
                <h2 id="main-title" class="bon-title">Bon de commande n°<?=$id_bon_commande?> <?=get_bon_livraison_linked($con, $bon_commande["id_bon_commande"]) ? "(lié)" : ""?></h2>
            </div>
            <?php if (!is_null($bon_commande["photo"])): ?>
                <a href="<?=$bon_commande["photo"]?>" target="_blank">
                    <button>Voir la photo</button>
                </a>
            <?php endif; ?>
            <button>Archiver</button>
        </div>
        <div id="infos">
            <p>Fournisseur : <?=$bon_commande["fournisseur"]?></p>
            <p>Date bon commande : <?=date("d-m-Y H:i:s", strtotime($bon_commande["date_commande"]))?></p>
            
        </div>
        <table class="sortable-theme-bootstrap" data-sortable>
            <thead>
                <tr>
                    <th>Code du produit</th>
                    <th>Description</th>
                    <th>Quantité demandée</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($entrees_bon_commande as $entree_bon_commande) : ?>
                    <tr>      
                        <td><?=$entree_bon_commande["code_produit"]?></td>
                        <td><?=$entree_bon_commande["description_produit"]?></td>
                        <td><?=$entree_bon_commande["quantite_demandee"]?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
</main>

<?php
$content = ob_get_clean();
$title = "Liste - Bons de commande"; 
$styles = array("/public/style/views/bons.css", "/public/style/views/bons_commande.css", "/libraries/sortable-0.8.0/css/sortable-theme-bootstrap.css");
$scripts = array("/libraries/sortable-0.8.0/js/sortable.min.js");
require($_SERVER['DOCUMENT_ROOT'].'/template.php');
?>