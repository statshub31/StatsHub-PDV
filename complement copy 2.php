<?php
include_once __DIR__ . '/layout/php/header.php';


?>


<!-- AÇÃO UNITARIA PRODUTO -->
<?php

// <!-- Modal PRODUCT -->
if (isCampanhaInURL("product")) {
    $product_select_id = getURLLastParam();
    if (isDatabaseProductExistID($product_select_id)) {
        $product_select_stock_id = getDatabaseStockIDByProductID($product_select_id);
        ?>
        <!-- START -->
        <?PHP


        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
            // if (getGeneralSecurityToken('incrementedProduct')) {
            if (1 == 1) {
                data_dump($_POST);

                if (empty($_POST) === false) {
                    if (!isset($_POST['size'])) {
                        $errors[] = "Selecione o tamanho do item.";
                    }

                    if (!isset($_POST['product_select_id'])) {
                        $errors[] = "Houve um erro ao adicionar o produto, reinicie a pagina e tente novamente.";
                    }

                    if (empty($_POST['product_select_id'])) {
                        $errors[] = "Houve um erro ao adicionar o produto, reinicie a pagina e tente novamente.";
                    }

                }


                if (empty($errors)) {
                    if (isDatabaseCartExistIDByUserID($in_user_id)) {
                        $cart_id = getDatabaseCartExistIDByUserID($in_user_id);
                    } else {
                        // Carrinho Criar
                        $cart_insert_fields = array(
                            'user_id' => $in_account_id,
                            'status' => 2,
                            'created' => date('Y-m-d H:i:s')
                        );

                        $cart_id = doDatabaseCartInsert($cart_insert_fields);
                    }

                    // ADICIONAR CARRINHO
                    $cart_product_insert_fields = array(
                        'cart_id' => $cart_id,
                        'product_id' => $_POST['product_select_id'],
                        'amount' => $_POST['quantity'],
                        'observation' => $_POST['obs']
                    );

                    $product_cart_id = doDatabaseCartProductInsert($cart_product_insert_fields);

                    // ADICIONAR COMPLEMENTO

                    $cart_product_complement_insert_fields = array(
                        'cart_product_id' => $product_cart_id,
                        'complement_id' => $_POST['complement']
                    );

                    doDatabaseCartProductComplementInsert($cart_product_complement_insert_fields);


                    // ADICIONAR ADICIONAL

                    foreach ($_POST['additional'] as $additional_select_list_id) {
                        $cart_product_additional_insert_fields[] = array(
                            'cart_product_id' => $product_cart_id,
                            'additional_id' => $additional_select_list_id
                        );

                    }
                    doDatabaseCartProductAdditionalInsertMultipleRow($cart_product_additional_insert_fields);


                    doAlertSuccess("Produto adicionado ao carrinho com sucesso.");
                }



                if (empty($errors) === false) {
                    header("HTTP/1.1 401 Not Found");
                    echo doAlertError($errors);
                }
            }
        }

        ?>

        <!-- FIM -->

        <!-- FORMULARIO -->
        <form action="/complement/product/<?php echo $product_select_stock_id ?>" method="post">
            <div class="card" style="width: 100%">
                <img class="card-img-top" style="height: 15rem" 
                    src="<?php echo getPathProductImage(getDatabaseProductPhotoName($product_select_id)); ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo getDatabaseProductName($product_select_id) ?></h5>
                    <p class="card-text"><?php echo getDatabaseProductDescription($product_select_id) ?></p>
                </div>
            <hr>
            <div>
                <section id="sizes">
                    <center>
                        <h6>Tamanhos</h6>
                    </center>
                    <?php
                    $price_list = doDatabaseProductPricesPriceListByProductID($product_select_id);
                    if ($price_list) {
                        foreach ($price_list as $dataPrice) {
                            $price_list_id = $dataPrice['id'];
                            ?>
                            <div class="size-select">
                                <input type="radio" name="size" value="<?php echo $product_select_id ?>" class="calc"
                                    price="<?php echo getDatabaseProductPrice($price_list_id) ?>">
                                <label><?php echo getDatabaseProductPriceSize($price_list_id) ?>
                                    (<?php echo getDatabaseMeasureTitle(getDatabaseProductSizeMeasureID($price_list_id)) ?>)</label>
                                <label class="v">R$ <?php echo getDatabaseProductPrice($price_list_id) ?></label>
                                <br>
                                <small><?php echo getDatabaseProductPriceDescription($price_list_id) ?></small>
                            </div>

                            <?php
                        }
                    }
                    ?>
                </section>
                <hr>
                <section id="complements">
                    <center>
                        <h6>Complementos</h6>
                    </center>

                    <?php
                    $complement_list = doDatabaseProductsComplementsListByProductID($product_select_id);
                    if ($complement_list) {
                        foreach ($complement_list as $dataComplement) {
                            $product_complement_id = $dataComplement['id'];
                            $complement_id = getDatabaseProductComplementComplementID($product_complement_id);
                            ?>
                            <div class="complement-select">
                                <input type="radio" name="complement" value="<?php echo $complement_id ?>">
                                <small><?php echo getDatabaseComplementDescription($complement_id) ?></small>
                            </div>

                            <?php
                        }
                    }
                    ?>
                </section>
                <hr>
                <section id="additional">
                    <center>
                        <h6>Adicionais</h6>
                    </center>

                    <?php
                    $additional_list = doDatabaseProductsAdditionalListByProductID($product_select_id);
                    if ($additional_list) {
                        foreach ($additional_list as $dataAdditional) {
                            $product_additional_id = $dataAdditional['id'];
                            $additional_id = getDatabaseProductAdditionalAdditionalID($product_additional_id);
                            ?>
                            <div class="additional-select">
                                <input class="calc" type="checkbox" name="additional[]" value="<?php echo $additional_id ?>"
                                    price="<?php echo getDatabaseAdditionalTotalPrice($additional_id) ?>">
                                <br>
                                <small><?php echo getDatabaseAdditionalDescription($additional_id) ?></small>
                                <label class="v">R$ <?php echo getDatabaseAdditionalTotalPrice($additional_id) ?></label>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </section>
                <hr>
                <section id="questions">
                    <center>
                        <h6>Perguntas</h6>
                    </center>
                    <div class="question-select">
                        <label>Quer Ketchup?</label>
                        <br>

                        <input type="radio" /> <small>Vem um tamanho pequeno</small><br>
                        <input type="radio" /> <small>Vem um tamanho pequeno</small><br>
                        <input type="radio" /> <small>Vem um tamanho pequeno</small><br>

                        <input type="checkbox" /> <small>Vem um tamanho pequeno</small><br>
                        <input type="checkbox" /> <small>Vem um tamanho pequeno</small><br>
                        <input type="checkbox" /> <small>Vem um tamanho pequeno</small>
                    </div>
                </section>
                <hr>
                <section id="obs">
                    <center>
                        <h6>Observações</h6>
                    </center>
                    <textarea class="form-control" name="obs" placeholder="Exemplo: Sem cebola..."
                        id="floatingTextarea"></textarea>
                </section>
            </div>

            <b>
                <p class="t">Total do Pedido
                    <label class="v">R$ <span id="total">0.00</span></label>
                </p>
            </b>
            <div>
                <input name="product_select_id" type="text" value="<?php echo $product_select_id ?>" hidden>
                <input name="token" type="text" value="<?php echo addGeneralSecurityToken('incrementedProduct') ?>" hidden>
                <button type="submit" class="btn btn-primary">Adicionar ao Carrinho</button>
                <a href="#" class="card-link"><i class="fa-solid fa-star"></i></a>
                <a href="#" class="card-link"><i class="fa-solid fa-trash"></i></a>
            </div>
            </div>
        </form>

        <script>
            // CARRINHO START
            let total = 0;
            // Função para atualizar o total com base no estado do checkbox
            function atualizarTotal() {
                total = 0;
                document.querySelectorAll('.calc').forEach(function (element) {
                    if (element.checked) {
                        const price = parseFloat(element.getAttribute('price'));
                        total += price;
                    }
                });

                const quantity = document.getElementById('quantity').value;
                document.getElementById('total').textContent = (quantity * total).toFixed(2);
            }

            // Adicionar event listener para todos os elementos com a classe 'calc'
            document.querySelectorAll('.calc').forEach(function (element) {
                element.addEventListener('click', atualizarTotal);
            });
            // CARRINHO FIM



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
                    atualizarTotal();
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
                    atualizarTotal();
                });
            });

        </script>
        <?php
    }

}
?>



<?php
include_once __DIR__ . '/layout/php/footer.php';
?>