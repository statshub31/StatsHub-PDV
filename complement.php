<?php
include_once __DIR__ . '/layout/php/header.php';


?>


<!-- AÇÃO UNITARIA PRODUTO -->
<?php

// <!-- Modal REMOVE -->
if (isCampanhaInURL("product")) {
    $product_select_id = getURLLastParam();
    if (isDatabaseProductExistID($product_select_id)) {
        $product_select_stock_id = getDatabaseStockIDByProductID($product_select_id);
        ?>

        <div class="card" style="width: 100%">
            <img class="card-img-top" style="height: 15rem"
                src="<?php echo getPathProductImage(getDatabaseProductPhotoName($product_select_id)); ?>" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title"><?php echo getDatabaseProductName($product_select_id) ?></h5>
                <p class="card-text"><?php echo getDatabaseProductDescription($product_select_id) ?></p>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <fieldset>
                        <legend>Preços</legend>
                        <?php
                        $price_list = doDatabaseProductPricesPriceListByProductID($product_select_id);
                        if ($price_list) {
                            foreach ($price_list as $dataPrice) {
                                $price_list_id = $dataPrice['id'];
                                ?>
                                <div class="complement-option">
                                    <input type="radio" name="size" value="">
                                    <section class="complement-description">
                                        <h6>
                                            <?php echo getDatabaseProductPriceSize($price_list_id) ?>
                                            (<?php echo getDatabaseMeasureTitle(getDatabaseProductSizeMeasureID($price_list_id)) ?>)
                                        </h6>
                                        <small><?php echo getDatabaseProductPriceDescription($price_list_id) ?></small>
                                        <label class="v">R$ <?php echo getDatabaseProductPrice($price_list_id) ?></label>
                                    </section>
                                </div>
                                <hr>

                                <?php
                            }
                        }
                        ?>
                    </fieldset>

                </li>
                <li class="list-group-item">
                    <fieldset>
                        <legend>Complemento</legend>

                        <?php
                        $complement_list = doDatabaseProductsComplementsListByProductID($product_select_id);
                        if ($complement_list) {
                            foreach ($complement_list as $dataComplement) {
                                $product_complement_id = $dataComplement['id'];
                                $complement_id = getDatabaseProductComplementComplementID($product_complement_id);
                                ?>
                                <div class="complement-option">
                                    <input type="radio" name="complement" value="<?php echo $complement_id ?>">
                                    <section class="complement-description">
                                        <h6><?php echo getDatabaseComplementDescription($complement_id) ?></h6>
                                    </section>
                                </div>
                                <hr>
                                <?php
                            }
                        }
                        ?>
                    </fieldset>

                </li>
                <li class="list-group-item">
                    <fieldset>
                        <legend>Adicional</legend>

                        <?php
                        $additional_list = doDatabaseProductsAdditionalListByProductID($product_select_id);
                        if ($additional_list) {
                            foreach ($additional_list as $dataAdditional) {
                                $product_additional_id = $dataAdditional['id'];
                                $additional_id = getDatabaseProductAdditionalAdditionalID($product_additional_id);
                                ?>
                                <div class="complement-option">
                                    <input type="checkbox" name="additional" value="<?php echo $additional_id ?>">
                                    <section class="complement-description">
                                        <h6><?php echo getDatabaseAdditionalDescription($additional_id) ?></h6>
                                        <label class="v">R$ <?php echo getDatabaseAdditionalTotalPrice($additional_id) ?></label>
                                    </section>
                                </div>
                                <hr>

                                <?php
                            }
                        }
                        ?>
                    </fieldset>
                </li>
                <li class="list-group-item list-quantity">
                    <button class="btn btn-sm btn-secondary decrease">-</button>
                    <input type="number" class="form-control quantity" value="1">
                    <button class="btn btn-sm btn-secondary increase">+</button>
                </li>
                <li class="list-group-item">
                    <div class="form-floating">
                        <label for="floatingTextarea">Observações</label>
                        <textarea class="form-control" placeholder="Exemplo: Sem cebola..." id="floatingTextarea"></textarea>
                    </div>
                </li>
            </ul>
            <b>
                <p class="t">Total do Pedido
                    <label class="v">R$ 10.00</label>
                </p>
            </b>
            <div class="card-body">
                <button type="button" class="btn btn-primary">Adicionar ao Carrinho</button>
                <a href="#" class="card-link"><i class="fa-solid fa-star"></i></a>
                <a href="#" class="card-link"><i class="fa-solid fa-trash"></i></a>
            </div>
        </div>
        <?php
    }

}
?>
<script>
    // Selecionar todos os botões de diminuir e adicionar um evento de clique
    document.querySelectorAll('.decrease').forEach(function (btn) {
        btn.addEventListener('click', function () {
            // Selecionar o input associado a este botão
            var input = this.nextElementSibling;
            // Obter o valor atual do input
            var value = parseInt(input.value);
            // Decrementar o valor, garantindo que não seja menor que 1
            if (value > 1) {
                input.value = value - 1;
            }
        });
    });

    // Selecionar todos os botões de aumentar e adicionar um evento de clique
    document.querySelectorAll('.increase').forEach(function (btn) {
        btn.addEventListener('click', function () {
            // Selecionar o input associado a este botão
            var input = this.previousElementSibling;
            // Obter o valor atual do input
            var value = parseInt(input.value);
            // Incrementar o valor
            input.value = value + 1;
        });
    });

</script>
<?php
include_once __DIR__ . '/layout/php/footer.php';
?>