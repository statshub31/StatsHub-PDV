<?php
include_once __DIR__ . '/layout/php/header.php';

doGeneralSecurityProtect();

?>


<!-- AÇÃO UNITARIA PRODUTO -->
<?php

// <!-- Modal PRODUCT -->
if (isCampanhaInURL("product")) {
    $product_select_id = getURLLastParam();
    if (isDatabaseCartProductExistID($product_select_id)) {
        $product_id = getDatabaseCartProductProductID($product_select_id);
        $product_amount_select = getDatabaseCartProductAmount($product_select_id);
        $product_price_select = getDatabaseCartProductPriceID($product_select_id);

        $product_complement_select = getDatabaseCartProductComplementByCartProductID($product_select_id);
        $complement_select = getDatabaseCartProductComplementComplementID($product_complement_select);

        ?>
        <!-- START -->
        <?PHP


        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
            // if (getGeneralSecurityToken('editProductCart')) {
            if (1 == 1) {

                if (empty($_POST) === false) {
                    $product_id = getDatabaseCartProductProductID($_POST['product_select_id']);

                    $product_amount_select = getDatabaseCartProductAmount($_POST['product_select_id']);
                    $product_price_select = getDatabaseCartProductPriceID($_POST['product_select_id']);

                    $product_complement_select = getDatabaseCartProductComplementByCartProductID($_POST['product_select_id']);
                    $complement_select = getDatabaseCartProductComplementComplementID($product_complement_select);


                    if (isDatabaseCartProductExistID($_POST['product_select_id']) === false) {
                        $errors[] = "Houve um erro ao alterar o produto, reinicie a pagina e tente novamente.";
                    }

                    if (!isset($_POST['size'])) {
                        $errors[] = "Selecione o tamanho do item.";
                    }

                    if (isDatabaseProductPriceExistID($_POST['size']) === false) {
                        $errors[] = "Houve um erro ao alterar o produto, reinicie a pagina e tente novamente.";
                    }

                    if (!isset($_POST['product_select_id'])) {
                        $errors[] = "Houve um erro ao alterar o produto, reinicie a pagina e tente novamente.";
                    }

                    if (!isset($_POST['complement'])) {
                        $errors[] = "Necessário escolher uma opção para o complemento.";
                    }


                    if (empty($_POST['product_select_id'])) {
                        $errors[] = "Houve um erro ao alterar o produto, reinicie a pagina e tente novamente.";
                    }

                    if (isDatabaseProductQuestionExistQuestion($product_id)) {
                        $count_checked = 1;
                        $total_questions = getDatabaseProductQuestionRowCountByProductID($product_id);
                        while ($count_checked <= $total_questions) {
                            if (isDatabaseProductQuestionExistID($_POST['question' . $count_checked]) === false) {
                                $errors[] = "Houve um erro ao adicionar o produto, reinicie a pagina e tente novamente.";
                            }

                            if (isDatabaseProductQuestionValidationProduct($product_id, $_POST['question' . $count_checked]) === false) {
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

                    // ADICIONAR CARRINHO
                    $cart_product_insert_fields = array(
                        'product_price_id' => $_POST['size'],
                        'amount' => $_POST['quantity'],
                        'observation' => $_POST['obs']
                    );

                    doDatabaseCartProductUpdate($product_id, $cart_product_insert_fields);

                    // ADICIONAR COMPLEMENTO
                    $cart_product_complement_insert_fields = array(
                        'complement_id' => $_POST['complement']
                    );

                    doDatabaseCartProductComplementUpdate($product_complement_select, $cart_product_complement_insert_fields);


                    // // // ADICIONAR ADICIONAL
                    doDatabaseCartProductAdditionalDeleteByCartProductUnlimited($product_id);

                    
                    if (isset($_POST['additional'])) {
                        foreach ($_POST['additional'] as $additional_select_list_id) {
                            $cart_product_additional_insert_fields[] = array(
                                'cart_product_id' => $product_id,
                                'additional_id' => $additional_select_list_id
                            );

                        }
                        doDatabaseCartProductAdditionalInsertMultipleRow($cart_product_additional_insert_fields);
                    }

                    // ADICIONAR QUESTIONS
                    
                    if (isDatabaseProductQuestionExistQuestion($product_id)) {
                        $count_checked = 1;
                        $total_questions = getDatabaseProductQuestionRowCountByProductID($product_id);
                        while ($count_checked <= $total_questions) {
                            $response_question_product_insert = array();

                            // $question_product_insert = array(
                            //     'cart_product_id' => $product_cart_id,
                            //     'question_id' => $_POST['question' . $count_checked]
                            // );

                            // $cart_question_insert_id = doDatabaseCartProductQuestionInsert($question_product_insert);

                            if (isDatabaseProductQuestionResponseFree($_POST['question' . $count_checked])) {

                                $response_question_product_insert = array(
                                    'response_text' => (!empty($_POST['response' . $count_checked])) ? $_POST['response' . $count_checked] : NULL
                                );
                                $id_question_update = getDatabaseCartProductQuestionIDByCartAndQuestID($product_id, $_POST['question' . $count_checked]);
                                doDatabaseCartProductQuestionResponseUpdate($id_question_update, $response_question_product_insert, false);
                            }
                             else {
                                if (isDatabaseProductQuestionMultipleResponse($_POST['question' . $count_checked])) {
                                    $count_response = 0;
                                    $id_question_update = getDatabaseCartProductQuestionIDByCartAndQuestID($product_id, $_POST['question' . $count_checked]);
                                    doDatabaseCartProductQuestionResponseDeleteByQuestionIDUnlimited($id_question_update);

                                    while (isset($_POST['response' . $count_checked][$count_response])) {
                                        $response_question_product_insert[] = array(
                                            'cart_product_question_id' => $id_question_update,
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


                    doAlertSuccess("Produto adicionado ao carrinho com sucesso.", true);
                }



                if (empty($errors) === false) {
                    header("HTTP/1.1 401 Not Found");
                    echo doAlertError($errors);
                }
            }
        }

        ?>
        <!-- FIM -->
        <form action="/complementedit/product/<?php echo $product_select_id ?>" method="post">
            <div class="card" style="width: 100%">
                <img class="card-img-top" style="height: 15rem"
                    src="<?php echo getPathProductImage(getDatabaseProductPhotoName($product_id)); ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo getDatabaseProductName($product_id) ?></h5>
                    <p class="card-text"><?php echo getDatabaseProductDescription($product_id) ?></p>
                </div>
                <div class="list-group-item list-quantity">
                    <button type="button" class="btn btn-sm btn-secondary decrease">-</button>
                    <input type="number" name="quantity" class="form-control quantity" id="quantity"
                        value="<?php echo $product_amount_select ?>">
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
                    $price_list = doDatabaseProductPricesPriceListByProductID($product_id);
                    if ($price_list) {
                        foreach ($price_list as $dataPrice) {
                            $price_list_id = $dataPrice['id'];
                            ?>
                            <div class="size-select">
                                <input <?php echo doCheck($product_price_select, $price_list_id) ?> type="radio" name="size"
                                    value="<?php echo $price_list_id ?>" class="calc"
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
                    $complement_list = doDatabaseProductsComplementsListByProductID($product_id);
                    if ($complement_list) {
                        foreach ($complement_list as $dataComplement) {
                            $product_complement_id = $dataComplement['id'];
                            $complement_id = getDatabaseProductComplementComplementID($product_complement_id);
                            ?>
                            <div class="complement-select">
                                <input <?php echo doCheck($complement_select, $complement_id) ?> type="radio" name="complement"
                                    value="<?php echo $complement_id ?>">
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
                    $additional_list = doDatabaseProductsAdditionalListByProductID($product_id);
                    if ($additional_list) {
                        foreach ($additional_list as $dataAdditional) {
                            $product_additional_id = $dataAdditional['id'];
                            $additional_id = getDatabaseProductAdditionalAdditionalID($product_additional_id);
                            ?>
                            <div class="additional-select">
                                <input <?php echo doCheck(isDatabaseCartProductAdditionalExistIDByCartAndAdditionalID($product_select_id, $additional_id), 1) ?> class="calc" type="checkbox" name="additional[]"
                                    value="<?php echo $additional_id ?>"
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
                    $question_list = doDatabaseProductsQuestionsListByProductID($product_id);

                    if ($question_list) {
                        $question_count = 1;
                        foreach ($question_list as $dataQuestion) {
                            $question_list_id = $dataQuestion['id'];
                            $question_response_select = getDatabaseCartProductQuestionIDByCartAndQuestID($product_select_id, $question_list_id);
                            $response_text = getDatabaseCartProductQuestionResponseText($question_response_select);
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
                                    <input type="text" name="response<?php echo $question_count ?>" class="form-control"
                                        value="<?php echo $response_text ?>">

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
                                                <input <?php echo doCheck(doDatabaseCartProductQuestionResponseIDExistByCartAndQuestID($question_response_select, $response_list_id), 1) ?> type="checkbox"
                                                    name="response<?php echo $question_count ?>[]" value="<?php echo $response_list_id ?>" />
                                                <small><?php echo getDatabaseProductQuestionResponseResponse($response_list_id) ?></small><br>
                                                <?php
                                            } else {
                                                ?>
                                                <input <?php echo doCheck($response_select, $response_list_id) ?> type="radio"
                                                    name="response<?php echo $question_count ?>" value="<?php echo $response_list_id ?>" />
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
                            id="floatingTextarea"><?php echo getDatabaseCartProductObservation($product_select_id) ?></textarea>
                    </div>
                </section>

                <hr>

                <b>
                    <p class="t">Total do Pedido
                        <label class="v">R$ <span
                                id="total"><?php echo doCartTotalPriceProduct($product_select_id) ?></span></label>
                    </p>
                </b>
                <div>
                    <input name="product_select_id" type="text" value="<?php echo $product_select_id ?>" hidden>
                    <input name="token" type="text" value="<?php echo addGeneralSecurityToken('editProductCart') ?>" hidden>
                    <button type="submit" class="btn btn-primary">Salvar</button>
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