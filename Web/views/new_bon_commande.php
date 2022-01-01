<?php
require($_SERVER['DOCUMENT_ROOT']."/settings/db_settings.php");
require($_SERVER['DOCUMENT_ROOT']."/utilities/db_gets.php");
?>

<?php ob_start(); ?>
<main class="flex-column">
        <div class="main-header">
            <div class="flex-row icon-and-title">
                <img class="main-left-page-icon" src="/public/images/left.png" alt="Left page button" onclick="window.location.href = '/views/bons_commande.php';">
                <h2 id="main-title" class="bon-title">Création d'un bon de commande</h2>
            </div>
        </div>
        <p>Remplissez les champs ci-dessous et appuyer sur le bouton "Ajouter" pour créer votre bon de commande.</p>
        <form class="flex-column" action="/php/new_bon_commande_action.php" method="POST" enctype="multipart/form-data">
            <div id="fournisseur-input-container">
                <label for="fournisseur">Fournisseur :</label>
                <input type="text" name="fournisseur" id="fournisseur">
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
            <button id="submit-button" type="submit" class="fading-button">Créer le bon de commande</button>
        </form>
</main>

<?php
$content = ob_get_clean();
$title = "Création - Bon de commande"; 
$styles = array("/public/style/views/new_bon_commande.css");
$scripts = array("/public/js/new_bon.js");
require($_SERVER['DOCUMENT_ROOT'].'/template.php');
?>