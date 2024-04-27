<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));

?>




<?php
// <!-- INICIO DA VALIDAÇÃO PHP -->
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {

    // STOCK PRODUCTS
    if (getGeneralSecurityToken('tokenStock')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('product_select_id', 'amount', 'reason');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if (isDatabaseStockActionExistID($_POST['action']) === false) {
                $errors[] = "Selecione uma ação.";
            }

            if ($required_fields_status) {
                if (isDatabaseProductExistID($_POST['product_select_id']) === false) {
                    $errors[] = "Houve um erro ao processar solicitação, produto é inexistente.";
                }

                if (doGeneralValidationNumberFormat($_POST['amount']) == false) {
                    $errors[] = "É necessário preencher com um valor numérico o campo de quantidade.";
                }

                if (isGeneralSecurityManagerAccess() === false) {
                    $errors[] = "É obrigatório ter um cargo igual ou superior ao de gestor, para executar está ação.";
                }
            }

        }


        if (empty($errors)) {
            $stock_edit_id = getDatabaseStockIDByProductID($_POST['product_select_id']);
            $stock_total = getDatabaseStockActual($stock_edit_id);

            if ($_POST['action'] == 1 || $_POST['action'] == 3) {
                $stock_total += $_POST['amount'];
            }

            if ($_POST['action'] == 2) {
                $stock_total -= $_POST['amount'];
            }

            if ($stock_total < 0)
                $stock_total = 0;



            if (isDatabaseProductStockEnabled($stock_edit_id)) {

                $stock_update_fields = array(
                    'actual' => $stock_total
                );
                doDatabaseStockUpdate($stock_edit_id, $stock_update_fields);
            } else {
                $product_stock_insert_fields = array(
                    'product_id' => $_POST['product_select_id'],
                    'min' => $_POST['amount'],
                    'actual' => $_POST['amount']
                );

                doDatabaseStockInsert($product_stock_insert_fields);

                $product_update_fields = array(
                    'stock_status' => 1
                );

                doDatabaseProductUpdate($stock_edit_id, $product_update_fields);
            }


            $log_stock_insert_fields = array(
                'product_id' => $_POST['product_select_id'],
                'action_id' => $_POST['action'],
                'user_id' => $in_user_id,
                'amount' => $_POST['amount'],
                'reason' => $_POST['reason'],
                'date' => date('Y-m-d H:i:s')
            );

            doDatabaseLogStockInsert($log_stock_insert_fields);

            doAlertSuccess("O estoque foi ajustado com sucesso.");

        }
    }


    // PRODUCT REMOVE
    if (getGeneralSecurityToken('tokenRemoveProduct')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('product_select_id');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                if (isDatabaseProductExistID($_POST['product_select_id']) === false) {
                    $errors[] = "Houve um erro ao processar solicitação, produto é inexistente.";
                }

                if (isGeneralSecurityManagerAccess() === false) {
                    $errors[] = "É obrigatório ter um cargo igual ou superior ao de gestor, para executar está ação.";
                }
            }

        }


        if (empty($errors)) {

            doDatabaseRemoveProduct($_POST['product_select_id']);
            doAlertSuccess("Foram removido todas as informações vinculado a este produto.");

        }
    }


    // TOKEN REMOVE SELECT PRODUCTS
    if (getGeneralSecurityToken('tokenActionRemoveProducts')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('products');
            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                foreach ($_POST['products'] as $product_remove_id) {
                    if (isDatabaseProductExistID($product_remove_id) === false) {
                        $errors[] = "Houve um erro ao processar solicitação, um ou mais dos produtos é inexistente.";
                    }
                }

                if (isGeneralSecurityManagerAccess() === false) {
                    $errors[] = "É obrigatório ter um cargo igual ou superior ao de gestor, para executar está ação.";
                }
            }

        }


        if (empty($errors)) {

            doDatabaseRemoveProducts($_POST['products']);
            doAlertSuccess("Foram removido todas as informações vinculado aos produtos selecionados.");

        }
    }



    // TOKEN BLOCK SELECT PRODUCTS
    if (getGeneralSecurityToken('tokenActionBlockProducts')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('products');
            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                foreach ($_POST['products'] as $product_remove_id) {
                    if (isDatabaseProductExistID($product_remove_id) === false) {
                        $errors[] = "Houve um erro ao processar solicitação, um ou mais dos produtos é inexistente.";
                    }
                }

                if (isGeneralSecurityManagerAccess() === false) {
                    $errors[] = "É obrigatório ter um cargo igual ou superior ao de gestor, para executar está ação.";
                }
            }

        }


        if (empty($errors)) {
            doDatabaseBlockProducts($_POST['products']);
            doAlertSuccess("Foram bloqueado todos os produtos selecionados.");

        }
    }


    // TOKEN UNBLOCK SELECT PRODUCTS
    if (getGeneralSecurityToken('tokenActionUnblockProducts')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('products');
            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                foreach ($_POST['products'] as $product_remove_id) {
                    if (isDatabaseProductExistID($product_remove_id) === false) {
                        $errors[] = "Houve um erro ao processar solicitação, um ou mais dos produtos é inexistente.";
                    }
                }

                if (isGeneralSecurityManagerAccess() === false) {
                    $errors[] = "É obrigatório ter um cargo igual ou superior ao de gestor, para executar está ação.";
                }
            }

        }


        if (empty($errors)) {
            doDatabaseUnblockProducts($_POST['products']);
            doAlertSuccess("Foram bloqueado todos os produtos selecionados.");

        }
    }


    // TOKEN PROMOTION SELECT PRODUCTS
    if (getGeneralSecurityToken('tokenActionPromotionProducts')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('products', 'type', 'value');
            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                foreach ($_POST['products'] as $product_remove_id) {
                    if (isDatabaseProductExistID($product_remove_id) === false) {
                        $errors[] = "Houve um erro ao processar solicitação, um ou mais dos produtos é inexistente.";
                    }
                }

                if (isDatabasePromotionExistID($_POST['type']) === false) {
                    $errors[] = "Houve um erro ao processar o tipo de promoção, tente novamente.";
                }

                if (doGeneralValidationNumberFormat($_POST['value']) == false) {
                    $errors[] = "É obrigatório o preenchimento de um valor numérico no campo de desconto.";
                }

                if ($_POST['value'] <= 0) {
                    $errors[] = "É obrigatório um valor maior que zero no desconto.";
                }

                if (isGeneralSecurityManagerAccess() === false) {
                    $errors[] = "É obrigatório ter um cargo igual ou superior ao de gestor, para executar está ação.";
                }

                foreach ($_POST['products'] as $product_promotion_id) {
                    if (isDatabaseProductPromotionEnabledByProductID($product_promotion_id)) {
                        $errors[] = "O produto [" . getDatabaseProductName($product_promotion_id) . "] já tem uma promoção em andamento, desabilite ela para criar outra.";
                    }
                }

                if (!empty($_POST['expiration'])) {
                    if (date("Y-m-d H:i:s") > $_POST['expiration']) {
                        $errors[] = "Data de expiração, é obrigatório ser maior que a atual";
                    }
                }
            }

        }


        if (empty($errors)) {
            foreach ($_POST['products'] as $product_promotion_id) {
                $promotion_products_fields[] = array(
                    'product_id' => $product_promotion_id,
                    'promotion_id' => $_POST['type'],
                    'value' => $_POST['value'],
                    'cumulative' => (isset($_POST['cumulative']) ? 1 : 0),
                    'created' => date("Y-m-d H:i:s"),
                    'created_by' => $in_user_id,
                    'expiration' => (!empty($_POST['expiration']) ? $_POST['expiration'] : NULL)
                );
            }

            doDatabaseProductPromotionInsertMultipleRow($promotion_products_fields);
            doAlertSuccess("Promoção iniciada com sucesso para todos os produtos selecionados.");

        }
    }


    // TOKEN PROMOTION SELECT PRODUCTS
    if (getGeneralSecurityToken('tokenActionRemovePromotionProducts')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('products');
            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                foreach ($_POST['products'] as $product_remove_id) {
                    if (isDatabaseProductExistID($product_remove_id) === false) {
                        $errors[] = "Houve um erro ao processar solicitação, um ou mais dos produtos é inexistente.";
                    }
                }

                if (isGeneralSecurityManagerAccess() === false) {
                    $errors[] = "É obrigatório ter um cargo igual ou superior ao de gestor, para executar está ação.";
                }

            }

        }


        if (empty($errors)) {
            doDatabaseDepromotionProducts($_POST['products'], $in_user_id);
            doAlertSuccess("Promoção desativada com sucesso para todos os produtos selecionados.");
        }
    }

    // TOKEN EXCEPTION SELECT PRODUCTS
    if (getGeneralSecurityToken('tokenActionExemptionProducts')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('products');
            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                foreach ($_POST['products'] as $product_remove_id) {
                    if (isDatabaseProductExistID($product_remove_id) === false) {
                        $errors[] = "Houve um erro ao processar solicitação, um ou mais dos produtos é inexistente.";
                    }
                }

                if (isGeneralSecurityManagerAccess() === false) {
                    $errors[] = "É obrigatório ter um cargo igual ou superior ao de gestor, para executar está ação.";
                }

            }

        }


        if (empty($errors)) {
            doDatabaseExceptionProducts($_POST['products'], $in_user_id);
            doAlertSuccess("Aplicado a alteração com sucesso para todos os produtos selecionados.");
        }
    }


    if (empty($errors) === false) {
        header("HTTP/1.1 401 Not Found");
        echo doAlertError($errors);
    }
}


// <!-- FINAL DA VALIDAÇÃO PHP -->
?>












<h1 class="h3 mb-0 text-gray-800">Produtos</h1>
<a href="/panel/productadd">
    <button type="submit" class="btn btn-primary">Novo Produto</button>
</a>
<a href="/panel/complementadd">
    <button type="submit" class="btn btn-primary">Novo Complemento</button>
</a>
<hr>
<form action="/panel/products/action" method="post">
    <div class="input-group">
        <select class="custom-select" id="action-products" name="action-products">
            <option selected>-- Ação --</option>
            <option value="1">Remover</option>
            <option value="2">Depromocionar</option>
            <option value="3">Promocionar</option>
            <option value="4">Des/Isentar de Taxa</option>
            <option value="5">Bloquear</option>
            <option value="6">Desbloquear</option>
        </select>
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="submit">Executar</button>
        </div>
    </div>
    <hr>
    <link href="/layout/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Marcar</th>
                <th>Produto</th>
                <th>Descrição</th>
                <th>Estoque(Min/Actual)</th>
                <th>Opções</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Marcar</th>
                <th>Produto</th>
                <th>Descrição</th>
                <th>Estoque(Min/Actual)</th>
                <th>Opções</th>
            </tr>
        </tfoot>
        <tbody>
            <!-- PRODUTOS LISTA START -->
            <?php
            $product_list = doDatabaseProductsList();
            if ($product_list) {
                foreach ($product_list as $data) {
                    $product_list_id = $data['id'];
                    $product_list_stock_id = getDatabaseStockIDByProductID($product_list_id);
                    ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="products[]" value="<?php echo $product_list_id ?>">
                        </td>
                        <td>
                            <section class="product_photo">
                                <img
                                    src="<?php echo getPathProductImage(getDatabaseProductPhotoName($product_list_id)); ?>"></img>
                            </section>
                            <label><?php echo getDatabaseProductName($product_list_id); ?></label>

                            <?php
                            if (isDatabaseProductEnabled($product_list_id) === false) {
                                ?>
                                <i class="fa-solid fa-lock"></i>
                                <?php
                            }
                            if (isDatabaseProductPromotionEnabledByProductID($product_list_id)) {
                                $promotion_id = getDatabaseProductPromotionByProductID($product_list_id);
                                ?>
                                <label class="label alert-success" data-toggle="tooltip" data-html="true" title="Produto foi posto em promoção pelo Usuário[<?php echo getDatabaseUserName(getDatabaseProductPromotionCreatedBY($promotion_id)) ?>]
                                <br><b>Expira em</b>: <?php echo getDatabaseProductPromotionExpiration($promotion_id) ?>
                                <br><b>Valor(<?php echo getDatabasePromotionTitle(getDatabaseProductPromotionType($promotion_id)) ?>)</b>: <?php echo getDatabaseProductPromotionValue($promotion_id) ?>
                                ">Promoção</label>
                                <?php
                            }

                            if (isDatabaseProductFeeExemptionEnabledByProductID($product_list_id)) {
                                $exemption_id = getDatabaseProductFeeExemptionByProductID($product_list_id);
                                ?>
                                <label class="label alert-dark" data-toggle="tooltip" data-html="true" title="Produto foi isentado de entrega pelo Usuário[<?php echo getDatabaseUserName(getDatabaseProductFeeExemptionCreatedBY($exemption_id)) ?>]
                                ">Isento</label>
                                <?php
                            }
                            ?>
                        </td>
                        <td><?php echo getDatabaseProductDescription($product_list_id); ?></td>
                        <td><?php echo getDatabaseStockActual($product_list_stock_id); ?>/<?php echo getDatabaseStockMin($product_list_stock_id); ?>
                        </td>
                        <td>
                            <a href="/panel/productedit/edit/product/<?php echo $product_list_id ?>">
                                <i class="fa fa-edit" aria-hidden="true"></i>
                            </a>
                            <a href="/panel/products/remove/product/<?php echo $product_list_id ?>">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                            <a href="/panel/products/view/product/<?php echo $product_list_id ?>">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                    <?php
                }
            } else { ?>

                <tr>
                    <td colspan="6">Nenhum produto cadastrado ainda.</td>
                </tr>
                <?php
            }
            ?>
            <!-- PRODUTOS LISTA END -->
        </tbody>
    </table>
</form>



<!-- AÇÃO UNITARIA PRODUTO -->
<?php
if (isCampanhaInURL("product")) {

    // <!-- Modal REMOVE -->
    if (isCampanhaInURL("view")) {
        $product_select_id = getURLLastParam();
        if (isDatabaseProductExistID($product_select_id)) {
            $product_select_stock_id = getDatabaseStockIDByProductID($product_select_id);
            ?>

            <div class="modal fade show" style="padding-right: 19px; display: block;" id="viewProductModal" tabindex="-1"
                role="dialog" aria-labelledby="viewProductModalLabel" aria-hidden="true">
                <div class="modal-dialog" style="max-width: 800px" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewProductModalTitle">Visualização</h5>
                            <a href="/panel/products">
                                <button type="button" class="close" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </a>
                        </div>
                        <div class="modal-body">

                            <div id="user_panel">
                                <section class="product-photo-circle">
                                    <img src="<?php echo getPathProductImage(getDatabaseProductPhotoName($product_select_id)); ?>">
                                </section>
                                <section style="width: 40%">
                                    <b><label>Cod:</label></b>
                                    <span><?php echo getDatabaseProductCode($product_select_id) ?></span><br>
                                    <b><label>Medida:</label></b>
                                    <span><?php echo getDatabaseMeasureTitle(getDatabaseProductSizeMeasureID($product_select_id)) ?></span><br>
                                    <b><label>Categoria:</label></b>
                                    <span><?php echo getDatabaseCategoryTitle(getDatabaseProductCategoryID($product_select_id)) ?></span><br>
                                </section>
                                <section style="width: 40%">
                                    <b><label>Estoque Atual:</label></b>
                                    <span><?php echo getDatabaseStockActual($product_select_stock_id); ?></span><br>
                                    <b><label>Estoque Minimo:</label></b>
                                    <span><?php echo getDatabaseStockMin($product_select_stock_id); ?></span><br>
                                </section>
                                <section style="width: 100%">
                                    <b><label>Descrição:</label></b>
                                    <span><?php echo getDatabaseProductDescription($product_select_id) ?></span><br>
                                </section>

                                <a href="/panel/products/view/product/stock/<?php echo $product_select_id ?>">
                                    <button type="submit" class="btn btn-primary">Ajustar
                                        Estoque</button>
                                </a>
                            </div>
                            <br>

                            <div>
                                <table border="1" width="100%">
                                    <tr>
                                        <th>#</th>
                                        <th>Tamanho</th>
                                        <th>Descrição</th>
                                        <th>Preço(Un)</th>
                                    </tr>
                                    <!-- LISTA TAMANHOS START -->
                                    <?php
                                    $product_measure_list = doDatabaseProductPricesPriceListByProductID($product_select_id);
                                    if ($product_measure_list) {
                                        $count_measure_list = 0;
                                        foreach ($product_measure_list as $data) {
                                            ++$count_measure_list;
                                            $product_measure_list_id = $data['id'];
                                            ?>
                                            <tr>
                                                <td>#<?php echo $count_measure_list ?></td>
                                                <td><?php echo getDatabaseProductPriceSize($product_measure_list_id) ?>
                                                    <?php echo getDatabaseMeasureTitle(getDatabaseProductSizeMeasureID($product_measure_list_id)) ?>
                                                </td>
                                                <td><?php echo getDatabaseProductPriceDescription($product_measure_list_id) ?></td>
                                                <td>R$ <?php echo getDatabaseProductPrice($product_measure_list_id) ?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                    <!-- LISTA TAMANHOS END -->
                                </table>
                            </div>
                            <br>

                            <div>
                                <table border="1" width="100%">
                                    <tr>
                                        <th>#</th>
                                        <th>Adicional</th>
                                        <th>Preço</th>
                                        <th>Desconto</th>
                                        <th>Total</th>
                                    </tr>

                                    <!-- LISTA ADICIONAIS START -->
                                    <?php
                                    $product_additional_list = doDatabaseProductsAdditionalListByProductID($product_select_id);
                                    if ($product_additional_list) {
                                        $count_additional_list = 0;
                                        foreach ($product_additional_list as $data) {
                                            ++$count_additional_list;
                                            $product_additional_list_id = $data['id'];
                                            ?>
                                            <tr>
                                                <td>#<?php echo $count_additional_list ?></td>
                                                <td><?php echo getDatabaseAdditionalDescription(getDatabaseProductAdditionalAdditionalID($product_additional_list_id)) ?>
                                                </td>
                                                <td>R$
                                                    <?php echo getDatabaseAdditionalCostPrice(getDatabaseProductAdditionalAdditionalID($product_additional_list_id)) ?>
                                                </td>
                                                <td>R$
                                                    <?php echo getDatabaseAdditionalSalePrice(getDatabaseProductAdditionalAdditionalID($product_additional_list_id)) ?>
                                                </td>
                                                <td>R$
                                                    <?php echo getDatabaseAdditionalTotalPrice(getDatabaseProductAdditionalAdditionalID($product_additional_list_id)) ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                    <!-- LISTA ADICIONAIS END -->
                                </table>
                            </div>
                            <br>

                            <div>
                                <table border="1" width="100%">
                                    <tr>
                                        <th>#</th>
                                        <th>Descrição</th>
                                    </tr>

                                    <!-- LISTA ADICIONAIS START -->
                                    <?php
                                    $product_complements_list = doDatabaseProductsComplementsListByProductID($product_select_id);
                                    if ($product_complements_list) {
                                        $count_complements_list = 0;
                                        foreach ($product_complements_list as $data) {
                                            ++$count_complements_list;
                                            $product_complements_list_id = $data['id'];
                                            ?>
                                            <tr>
                                                <td>#<?php echo $count_complements_list ?></td>
                                                <td><?php echo getDatabaseComplementDescription(getDatabaseProductComplementComplementID($product_complements_list_id)) ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                    <!-- LISTA ADICIONAIS END -->
                                </table>
                            </div>
                            <br>

                            <div>
                                <table border="1" width="100%">
                                    <tr>
                                        <th>#</th>
                                        <th>Pergunta</th>
                                        <th>Multipla Resposta</th>
                                        <th>Resposta Livre</th>
                                        <th>Respostas</th>
                                    </tr>
                                    <?php
                                    $product_questions_list = doDatabaseProductsQuestionsListByProductID($product_select_id);
                                    if ($product_questions_list) {
                                        $count_product_question_list = 0;
                                        foreach ($product_questions_list as $data) {
                                            $responses = array();
                                            ++$count_product_question_list;
                                            $product_question_list_id = $data['id'];
                                            ?>
                                            <tr>
                                                <td>#<?php echo $count_product_question_list ?></td>
                                                <td><?php echo getDatabaseProductQuestionText($product_question_list_id) ?></td>
                                                <td><?php echo doYN(getDatabaseProductQuestionMultipleResponse($product_question_list_id)) ?>
                                                </td>
                                                <td><?php echo doYN(getDatabaseProductQuestionResponseFree($product_question_list_id)) ?>
                                                </td>
                                                <td>
                                                    <!-- RESPOSTAS LISTA START -->
                                                    <?php
                                                    $responses_question_list = doDatabaseProductsQuestionResponsesListByQuestionID($product_question_list_id);
                                                    if ($responses_question_list) {
                                                        foreach ($responses_question_list as $dataResponse) {
                                                            $response_question_list_id = $dataResponse['id'];
                                                            $responses[] = getDatabaseProductQuestionResponseResponse($response_question_list_id);
                                                        }


                                                        if (!empty($responses)) {
                                                            $resp_exceto_ultima = array_slice($responses, 0, -1);
                                                            $response_all = implode(", ", $resp_exceto_ultima);
                                                            $response_all .= " e " . end($responses) . ".";

                                                            echo $response_all;
                                                        } else {
                                                            echo "Nenhuma resposta cadastrada.";
                                                        }
                                                    }
                                                    ?>
                                                    <!-- RESPOSTAS LISTA FIM -->
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <!-- STOCK START -->
            <?php
            if (isCampanhaInURL("stock")) {
                ?>

                <!-- Modal Stock -->
                <div class="modal fade show" style="padding-right: 19px; display: block;" id="stockModal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Ajuste de Estoque</h5>
                                <a href="/panel/products/view/product/<?php echo $product_select_id ?>">
                                    <button type="button" class="close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </a>
                            </div>
                            <form action="/panel/products/view/product/<?php echo $product_select_id ?>" method="post">
                                <div class="modal-body">
                                    <?php
                                    if (isDatabaseProductStockEnabled($product_select_id) === false) {
                                        ?>
                                        <div class="alert alert-info" role="alert">
                                            O estoque para este produto, está desabilitado, caso você faça uma entrada, o valor inserido
                                            será dado como minimo e o estoque será habilitado automaticamente.
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <div class="input-group">
                                        <select class="custom-select" name="action" id="action"
                                            aria-label="Example select with button addon">
                                            <option selected>Escolha uma Ação...
                                                <font color="red">*</font>
                                            </option>
                                            <!-- STOCK ACTION LIST START -->
                                            <?PHP
                                            $stock_action_list = doDatabaseStockActionsList();
                                            if ($stock_action_list) {
                                                foreach ($stock_action_list as $dataStockAction) {
                                                    $stock_action_list_id = $dataStockAction['id'];
                                                    ?>
                                                    <option value="<?php echo $stock_action_list_id ?>">
                                                        <?php echo getDatabaseStockActionTitle($stock_action_list_id) ?>
                                                    </option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <!-- STOCK ACTION LIST END -->
                                        </select>
                                    </div><br>

                                    <div class="input-group">
                                        <span class="input-group-text">Quantidade
                                            <font color="red">*</font>
                                        </span>
                                        <input type="text" name="amount" class="form-control">
                                    </div><br>

                                    <div class="input-group">
                                        <span class="input-group-text">Motivo:
                                            <font color="red">*</font>
                                        </span>
                                        <input type="text" name="reason" class="form-control">
                                    </div>

                                </div>
                                <div class="modal-footer">

                                    <input name="product_select_id" type="text" value="<?php echo $product_select_id ?>" hidden>
                                    <input name="token" type="text" value="<?php echo addGeneralSecurityToken('tokenStock') ?>" hidden>
                                    <a href="/panel/products/view/product/<?php echo $product_select_id ?>">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                    </a>
                                    <button type="submit" class="btn btn-success">Confirmar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <?php
            } ?>
            <!-- STOCK FIM -->

            <div class="modal-backdrop fade show"></div>
            <?php
        } else {
            header('Location: /myaccount');
        }
    }

    // <!-- Modal REMOVE -->
    if (isCampanhaInURL("remove")) {
        $product_select_id = getURLLastParam();
        if (isDatabaseProductExistID($product_select_id)) {
            ?>

            <div class="modal fade show" style="padding-right: 19px; display: block;" id="removeProductModal" tabindex="-1"
                role="dialog" aria-labelledby="removeProductModalLabel" aria-hidden="true">
                <div class="modal-dialog" style="max-width: 600px" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="removeProductModalTitle">Remover</h5>
                            <a href="/panel/products">
                                <button type="button" class="close" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </a>
                        </div>
                        <div class="modal-body">
                            Você está prestes a remover o produto
                            <b>[<?php echo getDatabaseProductName($product_select_id) ?>]</b>, você tem certeza disso?

                            <div class="alert alert-danger" role="alert">
                                Confirmando está ação, você afirma que poderá remover do histórico do banco de dados, toda e
                                qualquer informação, vinculada ao mesmo.
                            </div>

                            <form action="/panel/products" method="post">
                                <div class="modal-footer">
                                    <input type="text" name="product_select_id" value="<?php echo $product_select_id ?>" hidden />

                                    <input name="token" type="text"
                                        value="<?php echo addGeneralSecurityToken('tokenRemoveProduct') ?>" hidden>
                                    <a href="/panel/products">
                                        <button type="button" class="btn btn-danger">Cancelar</button>
                                    </a>
                                    <button type="submit" class="btn btn-success">Confirmar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-backdrop fade show"></div>
            <?php
        } else {
            header('Location: /myaccount');
        }
    }

}
?>
<!-- AÇÃO UNITARIA PRODUTO END -->


<!-- AÇÃO DE EXECUÇÃO TODOS -->

<?php
if (isCampanhaInURL("products")) {

    if (isCampanhaInURL("action")) {
        if (empty($_POST['products'])) {
            echo doAlertWarning("Você precisa selecionar ao menos um item.");
        } else {
            // <!-- Modal REMOVE -->

            if ($_POST['action-products'] == 1) {
                ?>

                <div class="modal fade show" style="padding-right: 19px; display: block;" id="viewProductModal" tabindex="-1"
                    role="dialog" aria-labelledby="viewProductModalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="max-width: 800px" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewProductModalTitle">Confirmação de Ação</h5>
                                <a href="/panel/products">
                                    <button type="button" class="close" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </a>
                            </div>
                            <form action="/panel/products" method="post">
                                <div class="modal-body">
                                    Você está prestes a remover os produtos abaixo, você tem certeza disso?<br>
                                    <table class="table">
                                        <tr>
                                            <th>#Produto</th>
                                        </tr>
                                        <?php
                                        foreach ($_POST['products'] as $product_remove_id) {
                                            ?>
                                            <tr>
                                                <td><?php echo getDatabaseProductName($product_remove_id); ?>
                                                    <input type="text" value="<?php echo $product_remove_id; ?>" name="products[]" hidden />
                                                </td>
                                            </tr>
                                            <?php
                                        }

                                        ?>
                                    </table>

                                    <div class="alert alert-danger" role="alert">
                                        Confirmando está ação, você afirma que poderá remover do histórico do banco de dados, toda e
                                        qualquer informação, vinculada ao mesmo.
                                    </div>

                                    <div class="modal-footer">
                                        <input name="token" type="text"
                                            value="<?php echo addGeneralSecurityToken('tokenActionRemoveProducts') ?>" hidden>
                                        <a href="/panel/products">
                                            <button type="button" class="btn btn-danger">Cancelar</button>
                                        </a>
                                        <button type="submit" class="btn btn-success">Confirmar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="modal-backdrop fade show"></div>
                <?php
            }


            // <!-- Modal BLOCK -->

            if ($_POST['action-products'] == 5) {
                ?>

                <div class="modal fade show" style="padding-right: 19px; display: block;" id="viewProductModal" tabindex="-1"
                    role="dialog" aria-labelledby="viewProductModalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="max-width: 800px" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewProductModalTitle">Confirmação de Ação</h5>
                                <a href="/panel/products">
                                    <button type="button" class="close" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </a>
                            </div>
                            <form action="/panel/products" method="post">
                                <div class="modal-body">
                                    Você está prestes a bloquear os produtos abaixo, você tem certeza disso?<br>
                                    <table class="table">
                                        <tr>
                                            <th>#Produto</th>
                                        </tr>
                                        <?php
                                        foreach ($_POST['products'] as $product_remove_id) {
                                            ?>
                                            <tr>
                                                <td><?php echo getDatabaseProductName($product_remove_id); ?>
                                                    <input type="text" value="<?php echo $product_remove_id; ?>" name="products[]" hidden />
                                                </td>
                                            </tr>
                                            <?php
                                        }

                                        ?>
                                    </table>

                                    <div class="alert alert-warning" role="alert">
                                        Confirmando está ação, o produto não ficará mais visivel para os clientes.
                                    </div>

                                    <div class="modal-footer">
                                        <input name="token" type="text"
                                            value="<?php echo addGeneralSecurityToken('tokenActionBlockProducts') ?>" hidden>
                                        <a href="/panel/products">
                                            <button type="button" class="btn btn-danger">Cancelar</button>
                                        </a>
                                        <button type="submit" class="btn btn-success">Confirmar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="modal-backdrop fade show"></div>
                <?php
            }


            // <!-- Modal UNBLOCK -->

            if ($_POST['action-products'] == 6) {
                ?>

                <div class="modal fade show" style="padding-right: 19px; display: block;" id="viewProductModal" tabindex="-1"
                    role="dialog" aria-labelledby="viewProductModalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="max-width: 800px" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewProductModalTitle">Confirmação de Ação</h5>
                                <a href="/panel/products">
                                    <button type="button" class="close" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </a>
                            </div>
                            <form action="/panel/products" method="post">
                                <div class="modal-body">
                                    Você está prestes a desbloquear os produtos abaixo, você tem certeza disso?<br>
                                    <table class="table">
                                        <tr>
                                            <th>#Produto</th>
                                        </tr>
                                        <?php
                                        foreach ($_POST['products'] as $product_remove_id) {
                                            ?>
                                            <tr>
                                                <td><?php echo getDatabaseProductName($product_remove_id); ?>
                                                    <input type="text" value="<?php echo $product_remove_id; ?>" name="products[]" hidden />
                                                </td>
                                            </tr>
                                            <?php
                                        }

                                        ?>
                                    </table>

                                    <div class="alert alert-warning" role="alert">
                                        Confirmando está ação, o produto voltará a ficar visivel para os clientes.
                                    </div>

                                    <div class="modal-footer">
                                        <input name="token" type="text"
                                            value="<?php echo addGeneralSecurityToken('tokenActionUnblockProducts') ?>" hidden>
                                        <a href="/panel/products">
                                            <button type="button" class="btn btn-danger">Cancelar</button>
                                        </a>
                                        <button type="submit" class="btn btn-success">Confirmar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="modal-backdrop fade show"></div>
                <?php
            }



            // <!-- Modal PROMOTION -->

            if ($_POST['action-products'] == 3) {
                ?>

                <div class="modal fade show" style="padding-right: 19px; display: block;" id="viewProductModal" tabindex="-1"
                    role="dialog" aria-labelledby="viewProductModalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="max-width: 800px" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewProductModalTitle">Confirmação de Ação</h5>
                                <a href="/panel/products">
                                    <button type="button" class="close" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </a>
                            </div>
                            <form action="/panel/products" method="post">
                                <div class="modal-body">

                                    <div class="input-group">
                                        <select class="custom-select" name="type" id="type"
                                            aria-label="Example select with button addon">
                                            <option selected>Tipo de Desconto...
                                                <font color="red">*</font>
                                            </option>
                                            <!-- PROMOTION ACTION LIST START -->
                                            <?PHP
                                            $promotion_action_list = doDatabasePromotionList();
                                            if ($promotion_action_list) {
                                                foreach ($promotion_action_list as $dataPromotionAction) {
                                                    $promotion_action_list_id = $dataPromotionAction['id'];
                                                    ?>
                                                    <option value="<?php echo $promotion_action_list_id ?>">
                                                        <?php echo getDatabasePromotionTitle($promotion_action_list_id) ?>
                                                    </option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <!-- PROMOTION ACTION LIST END -->
                                        </select>
                                    </div><br>
                                    <div class="form-group">
                                        <label for="value">Valor:
                                            <font color="red">*</font>
                                            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="Caso o tipo seja percentual, o mesmo será deduzido em %, por exemplo: se você inserir 20, e a opção percentual selecionado, a promoção será de 20%, caso for selecionado reais, será de R$ 20.00 o desconto..."></i></small>
                                        </label>
                                        <input name="value" type="text" class="form-control" id="value" value="">
                                    </div>

                                    <div class="form-group">
                                        <label for="expiration">Data de Expiração
                                            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="Escolha uma data para expirar a promoção. Caso não queira uma data, deixe em branco."></i></small>
                                        </label>
                                        <input name="expiration" type="date" class="form-control" id="expiration" value="">
                                    </div>

                                    <div class="form-group">
                                        <label for="cumulative">Deseja que seja acumulativo a promoção?</label>
                                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                                data-placement="top"
                                                title="Caso habilite está função, se o cliente possui um cupom de desconto, o desconto de ambos serão somados."></i></small>
                                        <div class="vc-toggle-container">
                                            <label class="vc-switch">
                                                <input type="checkbox" name="cumulative" id="cumulative" class="vc-switch-input">
                                                <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                                                <span class="vc-handle"></span>
                                            </label>
                                        </div>
                                    </div>


                                    <table class="table">
                                        <tr>
                                            <th>#Produto</th>
                                        </tr>
                                        <?php
                                        foreach ($_POST['products'] as $product_remove_id) {
                                            ?>
                                            <tr>
                                                <td><?php echo getDatabaseProductName($product_remove_id); ?>
                                                    <input type="text" value="<?php echo $product_remove_id; ?>" name="products[]" hidden />
                                                </td>
                                            </tr>
                                            <?php
                                        }

                                        ?>
                                    </table>

                                    <div class="alert alert-warning" role="alert">
                                        Confirmando está ação, o produto ficará em promoção.
                                    </div>

                                    <div class="modal-footer">
                                        <input name="token" type="text"
                                            value="<?php echo addGeneralSecurityToken('tokenActionPromotionProducts') ?>" hidden>
                                        <a href="/panel/products">
                                            <button type="button" class="btn btn-danger">Cancelar</button>
                                        </a>
                                        <button type="submit" class="btn btn-success">Confirmar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="modal-backdrop fade show"></div>
                <?php
            }


            // <!-- Modal DEPROMOTION -->

            if ($_POST['action-products'] == 2) {
                ?>

                <div class="modal fade show" style="padding-right: 19px; display: block;" id="viewProductModal" tabindex="-1"
                    role="dialog" aria-labelledby="viewProductModalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="max-width: 800px" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewProductModalTitle">Confirmação de Ação</h5>
                                <a href="/panel/products">
                                    <button type="button" class="close" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </a>
                            </div>
                            <form action="/panel/products" method="post">
                                <div class="modal-body">
                                    Você está prestes a desabilitar a promoção para os produtos abaixo, você tem certeza disso?<br>
                                    <table class="table">
                                        <tr>
                                            <th>#Produto</th>
                                        </tr>
                                        <?php
                                        foreach ($_POST['products'] as $product_remove_id) {
                                            ?>
                                            <tr>
                                                <td><?php echo getDatabaseProductName($product_remove_id); ?>
                                                    <input type="text" value="<?php echo $product_remove_id; ?>" name="products[]" hidden />
                                                </td>
                                            </tr>
                                            <?php
                                        }

                                        ?>
                                    </table>

                                    <div class="alert alert-warning" role="alert">
                                        Confirmando está ação, o produto voltará ao preço normal.
                                    </div>

                                    <div class="modal-footer">
                                        <input name="token" type="text"
                                            value="<?php echo addGeneralSecurityToken('tokenActionRemovePromotionProducts') ?>" hidden>
                                        <a href="/panel/products">
                                            <button type="button" class="btn btn-danger">Cancelar</button>
                                        </a>
                                        <button type="submit" class="btn btn-success">Confirmar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="modal-backdrop fade show"></div>
                <?php
            }



            // <!-- Modal ISENTAR -->

            if ($_POST['action-products'] == 4) {
                ?>

                <div class="modal fade show" style="padding-right: 19px; display: block;" id="viewProductModal" tabindex="-1"
                    role="dialog" aria-labelledby="viewProductModalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="max-width: 800px" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewProductModalTitle">Confirmação de Ação</h5>
                                <a href="/panel/products">
                                    <button type="button" class="close" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </a>
                            </div>
                            <form action="/panel/products" method="post">
                                <div class="modal-body">
                                    Você está prestes a isentar a entrega para os produtos abaixo, você tem certeza disso?<br>
                                    <table class="table">
                                        <tr>
                                            <th>#Produto</th>
                                        </tr>
                                        <?php
                                        foreach ($_POST['products'] as $product_remove_id) {
                                            ?>
                                            <tr>
                                                <td><?php echo getDatabaseProductName($product_remove_id); ?>
                                                    <input type="text" value="<?php echo $product_remove_id; ?>" name="products[]" hidden />
                                                </td>
                                            </tr>
                                            <?php
                                        }

                                        ?>
                                    </table>

                                    <div class="alert alert-warning" role="alert">
                                        Confirmando está ação, os produtos selecionados que já estiverem isentos de taxa de entrega,
                                        voltaram a ser taxados, e caso não esteja isento, passará a ficar.
                                    </div>

                                    <div class="modal-footer">
                                        <input name="token" type="text"
                                            value="<?php echo addGeneralSecurityToken('tokenActionExemptionProducts') ?>" hidden>
                                        <a href="/panel/products">
                                            <button type="button" class="btn btn-danger">Cancelar</button>
                                        </a>
                                        <button type="submit" class="btn btn-success">Confirmar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="modal-backdrop fade show"></div>
                <?php
            }
        }
    }

}

?>

<!-- AÇÃO DE EXECUÇÃO TODOS FIM -->

<script>
    $(document).ready(function () {
        $('#dataTable').DataTable({
            "language": {
                "search": "Pesquisar:",
                "info": "Mostrando _START_ até _END_ de _TOTAL_ entradas",
                "lengthMenu": "Mostrar _MENU_",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Próximo"
                }
                // Outras opções de linguagem...
            }
        });

        $('#dataTableDeliverys').DataTable({
            "language": {
                "search": "Pesquisar:",
                "info": "Mostrando _START_ até _END_ de _TOTAL_ entradas",
                "lengthMenu": "Mostrar _MENU_",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Próximo"
                }
                // Outras opções de linguagem...
            }
        });
    });

</script>


<?php
include_once (realpath(__DIR__ . "/layout/php/footer.php"));
?>