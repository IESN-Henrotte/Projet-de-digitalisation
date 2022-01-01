var nbProducts = 1;

function addProduct() {
    nbProducts++;
    
    var productInputs = $(`
<div id="${nbProducts}-produit" class="product-inputs flex-row">
    <div class="product-input">
        <label id="label-${nbProducts}-code_produit" for="${nbProducts}-code_produit">Code produit :</label>
        <input type="text" name="${nbProducts}-code_produit" id="${nbProducts}-code_produit" required>
    </div>
    <div class="product-input">
        <label id="label-${nbProducts}-description" for="${nbProducts}-description">Description :</label>
        <input type="text" name="${nbProducts}-description" id="${nbProducts}-description" required>
    </div>
    <div class="product-input">
        <label id="label-${nbProducts}-quantite" for="${nbProducts}-quantite">Quantité :</label>
        <input type="number" name="${nbProducts}-quantite" id="${nbProducts}-quantite" min="1" required>
    </div>
    <button id="button-${nbProducts}-remove" class="remove-product-button" data-product-linked="${nbProducts}" onclick="removeProduct(this);">X</button>
</div>`);
    $("#new-product-button").before(productInputs);
}

function removeProduct(button) {
    let idProductLinked = $(button).data("product-linked");
    $(`#${idProductLinked}-produit`).remove();

    // Le 2 devient le 3, le 3 devient le 2, le 4 devient le 3, ...
    if (nbProducts >= 2) {
        for (let index = idProductLinked; index <= nbProducts; index++) {
            $(`#${index}-produit`).prop("id", `${index-1}-produit`);
            
            $(`#label-${index}-code_produit`).attr("for", `${index-1}-code_produit`);
            $(`#label-${index}-code_produit`).prop("id", `label-${index-1}-code_produit`);
            $(`#${index}-code_produit`).attr("name", `${index-1}-code_produit`);
            $(`#${index}-code_produit`).prop("id", `${index-1}-code_produit`);
            
            $(`#label-${index}-description`).attr("for", `${index-1}-description`);
            $(`#label-${index}-description`).prop("id", `label-${index-1}-description`);
            $(`#${index}-description`).attr("name", `${index-1}-description`);
            $(`#${index}-description`).prop("id", `${index-1}-description`);
            
            $(`#label-${index}-quantite`).attr("for", `${index-1}-quantite`);
            $(`#label-${index}-quantite`).prop("id", `label-${index-1}-quantite`);
            $(`#${index}-quantite`).attr("name", `${index-1}-quantite`);
            $(`#${index}-quantite`).prop("id", `${index-1}-quantite`);

            $(`#button-${index}-remove`).attr("data-product-linked", index-1);
            $(`#button-${index}-remove`).prop("id", `button-${index-1}-remove`);
        }
    }
    
    nbProducts--;
}