<?php
require($_SERVER['DOCUMENT_ROOT']."/settings/db_settings.php");
require($_SERVER['DOCUMENT_ROOT']."/utilities/db_gets.php");

$bons_livraison = get_all_bons_livraison($con);
?>

<?php ob_start(); ?>
<main class="flex-column">
        <div class="main-header">
            <div class="flex-row icon-and-title">
                <img class="main-left-page-icon" src="/public/images/left.png" alt="Left page button" onclick="window.location.href = '/index.php';">
                <h2 id="main-title" class="bon-title">Bons de livraison</h2>
            </div>
            <p>Cliquez sur le bon de livraison pour le comparer avec son bon de commande.</p>
        </div>
        <?php if (count($bons_livraison) == 0) : ?>
            <p>(Il n'y a aucun bon de livraison encodé dans la base de données)</p>
        <?php else : ?>
            <table class="sortable-theme-bootstrap" data-sortable>
                <thead>
                    <tr>
                        <th>Fournisseur</th>
                        <th>Date de livraison</th>
                        <th>N° du bon de commande associé</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($bons_livraison as $bon_livraison) : ?>
                        <?php
                        $bon_commande_associe = get_row_with_id($con, "BON_COMMANDE", "id_bon_commande", $bon_livraison["bon_commande"]);
                        $bon_livraison_est_correcte = bon_livraison_est_correct($con, $bon_livraison["id_bon_livraison"]);
                        ?>
                        <tr class="<?=$bon_livraison_est_correcte ? "correct-bon-livraison" : "incorrect-bon-livraison"?>" onclick="window.location.href = '/views/comparaison.php?id_bon_livraison=<?=$bon_livraison["id_bon_livraison"]?>';">    
                            <td><?=$bon_commande_associe["fournisseur"]?></td>
                            <td><?=date("d-m-Y H:i:s", strtotime($bon_livraison["date_livraison"]))?></td>
                            <td><?=$bon_livraison["bon_commande"]?></td>
                            <td><?=$bon_livraison_est_correcte ? "Correct" : "Incorrect"?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a id="add-bon-livraison-button" class="bon-add-button fading-button" href="/views/new_bon_livraison.php">Créer un bon de livraison</a>
            <a id="import-bon-livraison-button" class="bon-import-button fading-button" href="#" onclick="alert('Fonctionnalité non dévelopée');">Importer un bon de livraison</a>
        <?php endif; ?>
</main>

<?php
$content = ob_get_clean();
$title = "Liste - Bons de livraison"; 
$styles = array("/public/style/views/bons.css", "/public/style/views/bons_livraison.css", "/libraries/sortable-0.8.0/css/sortable-theme-bootstrap.css");
$scripts = array("/libraries/sortable-0.8.0/js/sortable.min.js");
require($_SERVER['DOCUMENT_ROOT'].'/template.php');
?>