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
                    $errors[] = "Houve um erro ao processar solicitação, complemento é inexistente.";
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
            $stock_total = getDatabaseStockActual(getDatabaseStockIDByProductID($_POST['product_select_id']));

            if ($_POST['action'] == 1 || $_POST['action'] == 3) {
                $stock_total += $_POST['amount'];
            }

            if ($_POST['action'] == 2) {
                $stock_total -= $_POST['amount'];
            }

            if ($stock_total < 0)
                $stock_total = 0;

            $stock_update_fields = array(
                'actual' => $stock_total
            );


            if (isDatabaseProductStockEnabled($_POST['product_select_id'])) {
                doDatabaseStockUpdate($_POST['product_select_id'], $stock_update_fields);
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

                doDatabaseProductUpdate($_POST['product_select_id'], $product_update_fields);
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
                    $errors[] = "Houve um erro ao processar solicitação, complemento é inexistente.";
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
<div class="input-group">
    <select class="custom-select" id="inputGroupSelect04">
        <option selected>-- Ação --</option>
        <option value="1">Remover</option>
        <option value="2">Promocionar</option>
        <option value="3">Montar Kit</option>
        <option value="3">Isentar de Taxa</option>
        <option value="3">Bloquear</option>
        <option value="3">Desbloquear</option>
    </select>
    <div class="input-group-append">
        <button class="btn btn-outline-secondary" type="button">Executar</button>
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
                        <input type="checkbox" name="products" value="<?php echo $product_list_id ?>">
                    </td>
                    <td>
                        <section class="product_photo">
                            <img src="<?php echo getPathProductImage(getDatabaseProductPhotoName($product_list_id)); ?>"></img>
                        </section>
                        <label><?php echo getDatabaseProductName($product_list_id); ?></label>
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
                                                    $responses_question_list = ddoDatabaseProductsQuestionResponsesListByQuestionID($product_question_list_id);
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
                                        <select class="custom-select" name="action" id="inputGroupSelect04"
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

<!-- Modal Remove -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Você está prestes a desativar o usuário <b>[Thiago de Oliveira Lima]</b>, você tem certeza disso?

                <div class="alert alert-danger" role="alert">
                    Confirmando está ação, o usuário não poderá fazer login ou executar qualquer tarefa.
                </div>


                Você está prestes a ativar o usuário <b>[Thiago de Oliveira Lima]</b>, você tem certeza disso?

                <div class="alert alert-warning" role="alert">
                    Confirmando está ação, o usuário voltará a fazer login e executar tarefas de sua função.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success">Confirmar</button>
            </div>
        </div>
    </div>
</div>


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