<?php
require($_SERVER['DOCUMENT_ROOT']."/settings/db_settings.php");
require($_SERVER['DOCUMENT_ROOT']."/utilities/db_gets.php");

$bons_commande = get_all_bons_commande($con);
?>

<?php ob_start(); ?>
<main class="flex-column">
        <div class="main-header">
            <div class="flex-row icon-and-title">
                <img class="main-left-page-icon" src="/public/images/left.png" alt="Left page button" onclick="window.location.href = '/views/bons_livraison.php';">
                <h2 id="main-title" class="bon-title">Création d'un bon de livraison</h2>
            </div>
        </div>
        <?php if(all_bons_commande_are_linked($con)): ?>
            <p>Vous ne pouvez créer un nouveau bon de livraison car tous les bons de commande créés ont déjà un bon de commande qui leur est lié.</p>
        <?php else: ?>
            <p>Remplissez les champs ci-dessous et appuyer sur le bouton "Ajouter" pour créer votre bon de livraison.</p>
            <form class="flex-column" action="/php/new_bon_livraison_action.php" method="POST" enctype="multipart/form-data">
                <div id="bon-commande-input-container">
                    <label for="bon-commande">Bon commande associé :</label>
                    <select name="bon-commande" id="bon-commande">
                        <?php foreach($bons_commande as $bon_commande): ?>
                            <?php
                            $aucun_bon_de_commande_lie = get_bon_livraison_linked($con, $bon_commande["id_bon_commande"]) == null;
                            $non_archive = $bon_commande["archive"] == false;
                            ?>
                            <?php if($aucun_bon_de_commande_lie && $non_archive): ?>
                                <option value="<?=$bon_commande["id_bon_commande"]?>"><?=$bon_commande["id_bon_commande"]?>   |   <?=$bon_commande["fournisseur"]?>   |   <?=date("d-m-Y H:i:s", strtotime($bon_commande["date_commande"]))?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div id="photo-input-container">
                    <label for="photo">Photo (facultatif):</label>
                    <input type="file" id="photo" name="photo" accept="image/png, image/jpeg">
                </div>
                <div id="products">
                    <h4>Liste de produits :</h4>
                    <div id="1-produit" class="product-inputs flex-row">
                        <div class="product-input">
                            <label id="label-1-code_produit" for="1-code_produit">Code produit :</label>
                            <input type="text" name="1-code_produit" id="1-code_produit" required>
                        </div>
                        <div class="product-input">
                            <label id="label-1-description" for="1-description">Description :</label>
                            <input type="text" name="1-description" id="1-description" required>
                        </div>
                        <div class="product-input">
                            <label id="label-1-quantite" for="1-quantite">Quantité :</label>
                            <input type="number" name="1-quantite" id="1-quantite" min="1" required>
                        </div>
                        <button id="button-1-remove" class="remove-product-button" data-product-linked="1" onclick="removeProduct(this);">X</button>
                    </div>
                    <button id="new-product-button" onclick="addProduct();">Ajouter un nouveau produit</button>
                </div>
                <button id="submit-button" type="submit" class="fading-button">Créer le bon de livraison</button>
            </form>
        <?php endif; ?>
</main>

<?php
$content = ob_get_clean();
$title = "Création - Bon de livraison"; 
$styles = array("/public/style/views/new_bon_livraison.css");
$scripts = array("/public/js/new_bon.js");
require($_SERVER['DOCUMENT_ROOT'].'/template.php');
?>