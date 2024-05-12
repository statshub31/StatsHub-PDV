<?php
include_once __DIR__ . '/layout/php/header.php';
doGeneralSecurityProtect();

?>


<!-- AÇÃO UNITARIA PRODUTO -->
<?php

// <!-- Modal PRODUCT -->
if (isCampanhaInURL("product")) {
    $product_cart_select_id = getURLLastParam();
    if (isDatabaseCartProductExistID($product_cart_select_id)) {
        doGeneralSecurityComplement($product_cart_select_id);
        $product_id = getDatabaseCartProductProductID($product_cart_select_id);
        if (isDatabaseProductExistID($product_id)) {
            $product_amount_select = getDatabaseCartProductAmount($product_cart_select_id);
            $product_price_select = getDatabaseCartProductPriceID($product_cart_select_id);

            $product_complement_select = getDatabaseCartProductComplementByCartProductID($product_cart_select_id);
            $complement_select = getDatabaseCartProductComplementComplementID($product_complement_select);
            ?>
            <!-- START -->
            <?PHP


            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
                if (getGeneralSecurityToken('editProductCart')) {

                    if (empty($_POST) === false) {
                        if (doGeneralValidationNumberFormat($_POST['quantity']) == false) {
                            $errors[] = "Somente são aceitos caracteres numéricos na quantidade.";
                        }

                        if (!isset($_POST['size'])) {
                            $errors[] = "Selecione o tamanho do item.";
                        }

                        if (isOpen() === false) {
                            $errors[] = "O estabelecimento está fechado no momento.";
                        }

                        if (isDatabaseProductPriceExistID($_POST['size']) === false) {
                            $errors[] = "Houve um erro ao alterar o produto. Por favor, atualize a página e tente novamente.";
                        }

                        if (!isset($_POST['product_select_id'])) {
                            $errors[] = "Houve um erro ao alterar o produto. Por favor, atualize a página e tente novamente.";
                        }

                        if (isProductInStock($_POST['product_select_id']) === false) {
                            $errors[] = "Houve um erro ao adicionar o produto. Por favor, atualize a página e tente novamente.";
                        }

                        if (getProductInStock($_POST['product_select_id'], $_POST['quantity']) === false) {
                            $errors[] = "Desculpe-nos, mas só temos a seguinte quantidade disponível: [" . getDatabaseStockActual(getDatabaseStockIDByProductID($_POST['product_select_id'])) . "].";
                        }

                        if (isset($_POST['additional'])) {
                            foreach ($_POST['additional'] as $verifyAdditional) {
                                if (isDatabaseAdditionalBlocked($verifyAdditional) || isDatabaseAdditionalExistID($verifyAdditional) === false) {
                                    $errors[] = "Houve um erro ao adicionar o produto. Por favor, recarregue a página e tente novamente.";
                                }
                            }
                        }

                        if (getDatabaseProductComplementRowCountByProductID($_POST['product_select_id']) > 0) {
                            if (!isset($_POST['complement'])) {
                                $errors[] = "É necessário escolher uma opção para o complemento.";
                            } else {
                                if (isDatabaseComplementBlocked($_POST['complement']) || isDatabaseComplementExistID($_POST['complement']) === false) {
                                    $errors[] = "Houve um erro ao adicionar o produto. Por favor, atualize a página e tente novamente.";
                                }
                            }
                        }
                        if (empty($_POST['product_select_id'])) {
                            $errors[] = "Houve um erro ao alterar o produto. Por favor, atualize a página e tente novamente.";
                        }

                        if (isDatabaseProductQuestionExistQuestion($product_id)) {
                            $count_checked = 1;
                            $total_questions = getDatabaseProductQuestionRowCountByProductID($product_id);
                            while ($count_checked <= $total_questions) {
                                if (isDatabaseProductQuestionExistID($_POST['question' . $count_checked]) === false) {
                                    $errors[] = "Houve um erro ao adicionar o produto. Por favor, recarregue a página e tente novamente.";
                                }

                                if (isDatabaseProductQuestionValidationProduct($product_id, $_POST['question' . $count_checked]) === false) {
                                    $errors[] = "Houve um erro ao adicionar o produto. Por favor, recarregue a página e tente novamente.";
                                }

                                if (isDatabaseProductQuestionResponseFree($_POST['question' . $count_checked])) {
                                    if (doGeneralValidationProductAlphaNumericFormat($_POST['response' . $count_checked]) == false) {
                                        $errors[] = "As respostas só podem conter números e letras.";
                                    }
                                } else {

                                    if (!isset($_POST['response' . $count_checked])) {
                                        $errors[] = "É obrigatório selecionar ao menos uma resposta para as perguntas de seleção.";
                                    }

                                    if (isDatabaseProductQuestionMultipleResponse($_POST['question' . $count_checked])) {
                                        $count_response = 0;
                                        while (isset($_POST['response' . $count_checked][$count_response])) {
                                            if (isDatabaseProductQuestionResponseValidation($_POST['question' . $count_checked], $_POST['response' . $count_checked][$count_response]) === false) {
                                                $errors[] = "Houve um erro ao adicionar o produto. Por favor, recarregue a página e tente novamente.";
                                            }
                                            ++$count_response;
                                        }
                                    }
                                }
                                ++$count_checked;
                            }
                        }


                        if (isDatabaseCartProductExistID($_POST['product_select_id']) === false) {
                            $errors[] = "Houve um erro ao alterar o produto. Por favor, atualize a página e tente novamente.";
                        }

                        if (isCartProductValidationUser($in_user_id, $_POST['product_select_id']) === false) {
                            $errors[] = "Houve um erro ao alterar o produto. Por favor, atualize a página e tente novamente.";
                        }

                    }


                    if (empty($errors)) {

                        // ADICIONAR CARRINHO
                        $cart_product_insert_fields = array(
                            'product_price_id' => $_POST['size'],
                            'amount' => $_POST['quantity'],
                            'observation' => $_POST['obs']
                        );

                        doDatabaseCartProductUpdate($product_cart_select_id, $cart_product_insert_fields);


                        // ADICIONAR COMPLEMENTO

                        if (getDatabaseProductComplementRowCountByProductID($_POST['product_select_id']) > 0) {
                            $cart_product_complement_insert_fields = array(
                                'complement_id' => $_POST['complement']
                            );

                            doDatabaseCartProductComplementUpdate($product_complement_select, $cart_product_complement_insert_fields);

                        }

                        // // // ADICIONAR ADICIONAL
                        doDatabaseCartProductAdditionalDeleteByCartProductUnlimited($product_cart_select_id);


                        if (isset($_POST['additional'])) {
                            foreach ($_POST['additional'] as $additional_select_list_id) {
                                $cart_product_additional_insert_fields[] = array(
                                    'cart_product_id' => $product_cart_select_id,
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
                                    $id_question_update = getDatabaseCartProductQuestionIDByCartAndQuestID($product_cart_select_id, $_POST['question' . $count_checked]);
                                    doDatabaseCartProductQuestionResponseUpdate($id_question_update, $response_question_product_insert, false);
                                } else {
                                    // if (isDatabaseProductQuestionMultipleResponse($_POST['question' . $count_checked])) {
                                    $count_response = 0;
                                    $id_question_update = getDatabaseCartProductQuestionIDByCartAndQuestID($product_cart_select_id, $_POST['question' . $count_checked]);
                                    doDatabaseCartProductQuestionResponseDeleteByQuestionIDUnlimited($id_question_update);

                                    while (isset($_POST['response' . $count_checked][$count_response])) {
                                        $response_question_product_insert[] = array(
                                            'cart_product_question_id' => $id_question_update,
                                            'response_id' => $_POST['response' . $count_checked][$count_response]
                                        );
                                        ++$count_response;
                                    }
                                    doDatabaseCartProductQuestionResponseInsertMultipleRow($response_question_product_insert);
                                    // }
                                }
                                ++$count_checked;
                            }
                        }

                        header("Location: /cart");
                    }



                    if (empty($errors) === false) {
                        header("HTTP/1.1 401 Not Found");
                        echo doAlertError($errors);
                    }
                }
            }
            ?>
            <!-- FIM -->


            <form action="/complementedit/product/<?php echo $product_cart_select_id ?>" method="post">
                <div class="card" style="width: 100%">
                    <img class="card-img-top" style="height: 15rem"
                        src="<?php echo getPathProductImage(getDatabaseProductPhotoName($product_id)); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo getDatabaseProductName($product_id) ?></h5>
                        <p class="card-text"><?php echo getDatabaseProductDescription($product_id) ?></p>
                    </div>
                    <div class="list-group-item list-quantity">
                        <button type="button" class="btn btn-sm btn-secondary decrease">-</button>
                        <input type="number" name="quantity" oninput="atualizarTotal()" class="form-control quantity" id="quantity"
                            value="<?php echo $product_amount_select ?>" required>
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
                                        price="<?php echo (isDatabaseProductPromotionExistIDByProductID($product_id)) ? sprintf("%.2f", doCalcDiscountPromotion($product_id, $price_list_id)) : getDatabaseProductPrice($price_list_id) ?>"
                                        required>
                                    <label><?php echo getDatabaseProductPriceSize($price_list_id) ?>
                                        (<?php echo getDatabaseMeasureTitle(getDatabaseProductSizeMeasureID($price_list_id)) ?>)</label>
                                    <?php
                                    if (isDatabaseProductPromotionExistIDByProductID($product_id)) {
                                        ?>
                                        <label class="v">
                                            <small><strike>R$ <?php echo getDatabaseProductPrice($price_list_id) ?></strike> por </small> <b> R$
                                                <?php echo sprintf("%.2f", doCalcDiscountPromotion($product_id, $price_list_id)) ?></b></label>

                                        <?php
                                    } else {
                                        ?>
                                        <label class="v">R$ <?php echo getDatabaseProductPrice($price_list_id) ?></label>
                                        <?php
                                    }
                                    ?>
                                    <br>
                                    <small><?php echo getDatabaseProductPriceDescription($price_list_id) ?></small>
                                </div>

                                <?php
                            }
                        }
                        ?>
                    </section>
                    <hr>
                    <?php
                    if (getDatabaseProductComplementRowCountByProductID($product_id) > 0) {
                        ?>
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
                                            value="<?php echo $complement_id ?>" required>
                                        <small><?php echo getDatabaseComplementDescription($complement_id) ?></small>
                                    </div>

                                    <?php
                                }
                            }
                            ?>
                        </section>
                        <hr>
                        <?php
                    }
                    if (getDatabaseProductAdditionalRowCountByProductID($product_id) > 0) {
                        ?>
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
                                        <input <?php
                                        echo doCheck(isDatabaseCartProductAdditionalExistIDByCartAndAdditionalID($product_cart_select_id, $additional_id), 1) ?> class="calc" type="checkbox" name="additional[]"
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
                        <?php
                    }
                    if (getDatabaseProductQuestionRowCountByProductID($product_id) > 0) {
                        ?>
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
                                    $question_response_select = getDatabaseCartProductQuestionIDByCartAndQuestID($product_cart_select_id, $question_list_id);
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
                                                        <input <?php
                                                        echo doCheck(doDatabaseCartProductQuestionResponseIDExistByCartAndQuestID($question_response_select, $response_list_id), 1) ?> type="checkbox" name="response<?php echo $question_count ?>[]"
                                                            value="<?php echo $response_list_id ?>" />
                                                        <small><?php echo getDatabaseProductQuestionResponseResponse($response_list_id) ?></small><br>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <input <?php
                                                        echo doCheck(doDatabaseCartProductQuestionResponseIDExistByCartAndQuestID($question_response_select, $response_list_id), 1) ?> type="radio" name="response<?php echo $question_count ?>"
                                                            value="<?php echo $response_list_id ?>" required />
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
                        <?php
                    }
                    ?>
                    <section class="list-group-item">
                        <div class="form-floating">
                            <label for="floatingTextarea">Observações</label>
                            <textarea class="form-control" name="obs" placeholder="Exemplo: Sem cebola..."
                                id="floatingTextarea"><?php echo getDatabaseCartProductObservation($product_cart_select_id) ?></textarea>
                        </div>
                    </section>

                    <hr>

                    <b>
                        <p class="t">Total do Pedido
                            <label class="v">R$ <span
                                    id="total"><?php echo doCartTotalPriceProduct($product_cart_select_id) ?></span></label>
                        </p>
                    </b>
                    <div>
                        <input name="product_select_id" type="text" value="<?php echo $product_cart_select_id ?>" hidden>
                        <input name="token" type="text" value="<?php echo addGeneralSecurityToken('editProductCart') ?>" hidden>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <a href="/complementedit/product/remove/<?php echo $product_cart_select_id ?>">
                            <i class="fa-solid fa-trash"></i>
                        </a>
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
    } else {
        header('Location: /cart');
    }

    if (isCampanhaInURL("remove")) {
        $cart_product_id = getURLLastParam();
        if (isDatabaseCartProductExistID($cart_product_id)) {
            $product_id = getDatabaseCartProductProductID($cart_product_id);
            ?>

            <div class="modal fade show" style="padding-right: 19px; display: block;" id="removeProductModal" tabindex="-1"
                role="dialog" aria-labelledby="removeProductModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="removeProductModalLabel">Remover Produto</h5>
                            <a href="/complementedit/product/<?php echo $cart_product_id ?>">
                                <button type="button" class="close">&times;</span>
                                </button>
                            </a>
                        </div>
                        <form action="/cart" method="POST">
                            <div class="modal-body">
                                Você está prestes a remover o produto [<?php echo getDatabaseProductName($product_id) ?>], do
                                carrinho, tem certeza?
                            </div>
                            <div class="modal-footer">
                                <input name="cart_product_id" type="text" value="<?php echo $cart_product_id ?>" hidden>
                                <input name="token" type="text"
                                    value="<?php echo addGeneralSecurityToken('tokenCartRemoveProduct') ?>" hidden>
                                <button type="submit" class="btn btn-success">Confirmar</button>
                                <a href="/complementedit/product/<?php echo $cart_product_id ?>">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
            <?php
        } else {
            header('Location: /cart');
        }
    }

}
?>



<?php
include_once __DIR__ . '/layout/php/footer.php';
?>