<?php ob_start(); ?>
<main class="flex-row">
    <div id="choices-container" class="flex-column">
        <a id="bon-commande-button" class="choice-button floating-button" href="/views/bons_commande.php">Gérer les bons de commande</a>
        <a id="bon-livraison-button" class="choice-button floating-button" href="/views/bons_livraison.php">Gérer les bons de livraison</a>
        <!--<a id="incoherence-button" class="choice-button floating-button" href="/views/incoherences.php">Lister les incohérences</a>-->
    </div>
</main>

<?php
$content = ob_get_clean();
$title = "Accueil"; 
$styles = array("/public/style/views/index.css");
require('template.php');
?>