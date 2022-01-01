<?php
require($_SERVER['DOCUMENT_ROOT']."/settings/db_settings.php");
require($_SERVER['DOCUMENT_ROOT']."/utilities/db_gets.php");

$bons_commande = get_all_bons_commande($con);
?>

<?php ob_start(); ?>
<main class="flex-column">
        <div class="main-header">
            <div class="flex-row icon-and-title">
                <img class="main-left-page-icon" src="/public/images/left.png" alt="Left page button" onclick="window.location.href = '/index.php';">
                <h2 id="main-title" class="bon-title">Bons de commande</h2>
            </div>
            <p>Cliquez sur le bon de commande pour afficher ses détails.</p>
        </div>
        <?php if (count($bons_commande) == 0) : ?>
            <p>(Il n'y a aucun bon de commande encodé dans la base de données)</p>
        <?php else : ?>
            <table class="sortable-theme-bootstrap" data-sortable>
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Fournisseur</th>
                        <th>Date</th>
                        <th>N° du bon de livraison associé</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($bons_commande as $bon_commande) : ?>
                        <tr onclick="window.location.href = '/views/bon_commande.php?id_bon_commande=<?=$bon_commande["id_bon_commande"]?>';">    
                            <td><?=$bon_commande["id_bon_commande"]?></td>
                            <td><?=$bon_commande["fournisseur"]?></td>
                            <td><?=date("d-m-Y H:i:s", strtotime($bon_commande["date_commande"]))?></td>
                            <?php
                            $bon_livraison_associe = get_bon_livraison_linked($con, $bon_commande["id_bon_commande"]);
                            ?>
                            <?php if ($bon_livraison_associe == null): ?>
                                <td>Pas encore de bon de livraison associé</td>
                            <?php else: ?>
                                <td><?=$bon_livraison_associe["id_bon_livraison"]?></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a id="add-bon-commande-button" class="bon-add-button fading-button" href="/views/new_bon_commande.php">Créer un bon de commande</a>
            <a id="import-bon-commande-button" class="bon-import-button fading-button" href="#" onclick="alert('Fonctionnalité non dévelopée');">Importer un bon de commande</a>
        <?php endif; ?>
</main>

<?php
$content = ob_get_clean();
$title = "Liste - Bons de commande"; 
$styles = array("/public/style/views/bons.css", "/public/style/views/bons_commande.css", "/libraries/sortable-0.8.0/css/sortable-theme-bootstrap.css");
$scripts = array("/libraries/sortable-0.8.0/js/sortable.min.js");
require($_SERVER['DOCUMENT_ROOT'].'/template.php');
?>