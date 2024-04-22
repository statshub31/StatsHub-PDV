<?php
include_once __DIR__ . '/layout/php/header.php';

doGeneralSecurityProtect();

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

                    if(isDatabaseProductPriceExistID($_POST['size']) === false) {
                        $errors[] = "Houve um erro ao adicionar o produto, reinicie a pagina e tente novamente.";
                    }

                    if (!isset($_POST['product_select_id'])) {
                        $errors[] = "Houve um erro ao adicionar o produto, reinicie a pagina e tente novamente.";
                    }

                    if (!isset($_POST['complement'])) {
                        $errors[] = "Necessário escolher uma opção para o complemento.";
                    }


                    if (empty($_POST['product_select_id'])) {
                        $errors[] = "Houve um erro ao adicionar o produto, reinicie a pagina e tente novamente.";
                    }

                    if (isDatabaseProductQuestionExistQuestion($_POST['product_select_id'])) {
                        $count_checked = 1;
                        $total_questions = getDatabaseProductQuestionRowCountByProductID($_POST['product_select_id']);
                        data_dump($total_questions);
                        while ($count_checked <= $total_questions) {
                            if (isDatabaseProductQuestionExistID($_POST['question' . $count_checked]) === false) {
                                $errors[] = "Houve um erro ao adicionar o produto, reinicie a pagina e tente novamente.";
                            }

                            if (isDatabaseProductQuestionValidationProduct($_POST['product_select_id'], $_POST['question' . $count_checked]) === false) {
                                $errors[] = "Houve um erro ao adicionar o produto, reinicie a pagina e tente novamente.";
                            }

                            if (isDatabaseProductQuestionResponseFree($_POST['question' . $count_checked])) {
                                if (doGeneralValidationProductAlphaNumericFormat($_POST['response' . $count_checked]) == false) {
                                    $errors[] = "As respostas só podem conter números e letras.";
                                }
                            } else {

                                if (!isset($_POST['response' . $count_checked])) {
                                    $errors[] = "Obrigatório selecionar ao menos uma resposta para as perguntas de seleção";
                                }

                                if (isDatabaseProductQuestionMultipleResponse($_POST['question' . $count_checked])) {
                                    $count_response = 0;
                                    while (isset($_POST['response' . $count_checked][$count_response])) {
                                        if (isDatabaseProductQuestionResponseValidation($_POST['question' . $count_checked], $_POST['response' . $count_checked][$count_response]) === false) {
                                            $errors[] = "Houve um erro ao adicionar o produto, reinicie a pagina e tente novamente.";
                                        }
                                        ++$count_response;
                                    }
                                }
                            }
                            ++$count_checked;
                        }
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
                        'product_price_id' => $_POST['size'],
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
                    if (isset($_POST['additional'])) {
                        foreach ($_POST['additional'] as $additional_select_list_id) {
                            $cart_product_additional_insert_fields[] = array(
                                'cart_product_id' => $product_cart_id,
                                'additional_id' => $additional_select_list_id
                            );

                        }
                        doDatabaseCartProductAdditionalInsertMultipleRow($cart_product_additional_insert_fields);
                    }
                    // ADICIONAR QUESTIONS

                    if (isDatabaseProductQuestionExistQuestion($_POST['product_select_id'])) {
                        $count_checked = 1;
                        $total_questions = getDatabaseProductQuestionRowCountByProductID($_POST['product_select_id']);
                        while ($count_checked <= $total_questions) {
                            $response_question_product_insert = array();
                            $question_product_insert = array(
                                'cart_product_id' => $product_cart_id,
                                'question_id' => $_POST['question' . $count_checked]
                            );

                            $cart_question_insert_id = doDatabaseCartProductQuestionInsert($question_product_insert);

                            if (isDatabaseProductQuestionResponseFree($_POST['question' . $count_checked])) {

                                $response_question_product_insert = array(
                                    'cart_product_question_id' => $cart_question_insert_id,
                                    'response_text' => $_POST['response' . $count_checked]
                                );
                                doDatabaseCartProductQuestionResponseInsert($response_question_product_insert);
                            } else {
                                if (isDatabaseProductQuestionMultipleResponse($_POST['question' . $count_checked])) {
                                    $count_response = 0;
                                    while (isset($_POST['response' . $count_checked][$count_response])) {
                                        $response_question_product_insert[] = array(
                                            'cart_product_question_id' => $cart_question_insert_id,
                                            'response_id' => $_POST['response' . $count_checked][$count_response]
                                        );
                                        ++$count_response;
                                    }
                                    doDatabaseCartProductQuestionResponseInsertMultipleRow($response_question_product_insert);
                                }
                            }
                            ++$count_checked;
                        }
                    }


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
        <form action="/complement/product/<?php echo $product_select_stock_id ?>" method="post">
            <div class="card" style="width: 100%">
                <img class="card-img-top" style="height: 15rem"
                    src="<?php echo getPathProductImage(getDatabaseProductPhotoName($product_select_id)); ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo getDatabaseProductName($product_select_id) ?></h5>
                    <p class="card-text"><?php echo getDatabaseProductDescription($product_select_id) ?></p>
                </div>
                <div class="list-group-item list-quantity">
                    <button type="button" class="btn btn-sm btn-secondary decrease">-</button>
                    <input type="number" name="quantity" class="form-control quantity" id="quantity" value="1">
                    <button type="button" class="btn btn-sm btn-secondary increase">+</button>
                </div>
            </div>


            <div>

                <section id="sizes">
                    <center>
                        <h6>Tamanhos<font color="red">*</font>
                        </h6>
                    </center>
                    <?php
                    $price_list = doDatabaseProductPricesPriceListByProductID($product_select_id);
                    if ($price_list) {
                        foreach ($price_list as $dataPrice) {
                            $price_list_id = $dataPrice['id'];
                            ?>
                            <div class="size-select">
                                <input type="radio" name="size" value="<?php echo $price_list_id ?>" class="calc"
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
                        <h6>Complementos<font color="red">*</font>
                        </h6>
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
                    <!-- PERGUNTAS START -->
                    <?php
                    $question_list = doDatabaseProductsQuestionsListByProductID($product_select_id);

                    if ($question_list) {
                        $question_count = 1;
                        foreach ($question_list as $dataQuestion) {
                            $question_list_id = $dataQuestion['id'];
                            ?>
                            <div class="question-select">
                                <label><?php echo getDatabaseProductQuestionText($question_list_id) ?>
                                    <?php

                                    if (isDatabaseProductQuestionResponseFree($question_list_id) === false) {
                                        ?>
                                        <font color="red">*</font>
                                    <?php }
                                    ?>
                                </label>
                                <input type="text" name="question<?php echo $question_count ?>" value="<?php echo $question_list_id ?>"
                                    hidden>
                                <br>
                                <!-- RESPOSTAS START -->
                                <?php
                                if (isDatabaseProductQuestionResponseFree($question_list_id)) {
                                    ?>
                                    <input type="text" name="response<?php echo $question_count ?>" class="form-control">

                                    <?php

                                } else {
                                    $response_list = doDatabaseProductsQuestionResponsesListByQuestionID($question_list_id);
                                    if ($response_list) {
                                        foreach ($response_list as $dataQuestionResponse) {
                                            $response_list_id = $dataQuestionResponse['id'];
                                            ?>
                                            <?php
                                            if (isDatabaseProductQuestionMultipleResponse($question_list_id)) {
                                                ?>
                                                <input type="checkbox" name="response<?php echo $question_count ?>[]"
                                                    value="<?php echo $response_list_id ?>" />
                                                <small><?php echo getDatabaseProductQuestionResponseResponse($response_list_id) ?></small><br>
                                                <?php
                                            } else {
                                                ?>
                                                <input type="radio" name="response<?php echo $question_count ?>"
                                                    value="<?php echo $response_list_id ?>" />
                                                <small><?php echo getDatabaseProductQuestionResponseResponse($response_list_id) ?></small><br>
                                                <?php
                                            }
                                        }
                                    }
                                }
                                ?>
                                <!-- RESPOSTAS FIM -->
                            </div>
                            <?php
                            ++$question_count;
                        }
                    }
                    ?>
                    <!-- PERGUNTAS FIM -->
                </section>
                <hr>
                <section class="list-group-item">
                    <div class="form-floating">
                        <label for="floatingTextarea">Observações</label>
                        <textarea class="form-control" name="obs" placeholder="Exemplo: Sem cebola..."
                            id="floatingTextarea"></textarea>
                    </div>
                </section>

                <hr>

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