<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));
?>

<script>
    var timerID;

    function atualizarPagina() {
        location.reload(); // Recarrega a página
    }

    timerID = setInterval(atualizarPagina, 15 * 1000);


</script>

<?php
// <!-- INICIO DA VALIDAÇÃO PHP -->
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {


    // STATUS ORDER

    if (getGeneralSecurityToken('tokenOrder')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('order_id');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if (isDatabaseRequestOrderExistID($_POST['order_id']) === false) {
                $errors[] = "Houve um erro ao processar a solicitação, reinicie a pagina e tente novamente.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                $order_last_log_id = doDatabaseRequestOrderLogsLastLogByOrderID($_POST['order_id']);

                if (getDatabaseRequestOrderLogStatusDelivery($order_last_log_id) == 3 && isDatabaseRequestOrderSelectAddress($_POST['order_id'])) {
                    if (isDatabaseUserExistID($_POST['deliveryman']) === false || (empty($_POST['deliveryman']))) {
                        $errors[] = "É necessário selecionar o motoboy que vai fazer a entrega, o mesmo precisa estar registrado.";
                    }
                }
            }

        }


        if (empty($errors)) {
            if(isset($_POST['deliveryman'])) {
                doUpdateOrderDeliveryStatus($_POST['order_id'], $_POST['deliveryman']);
            } else {
                doUpdateOrderDeliveryStatus($_POST['order_id']);
            }

        }
    }


    if (getGeneralSecurityToken('tokenCartCancelOrderConfirm')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('order_id', 'reason');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if (isDatabaseRequestOrderExistID($_POST['order_id']) === false) {
                $errors[] = "Houve um erro ao processar a solicitação, reinicie a pagina e tente novamente.";
                $required_fields_status = false;
            }

        }


        if (empty($errors)) {

            doRequestOrderLogInsert($_POST['order_id'], 6);

            $order_update_fields = array(
                'status' => 6,
                'reason' => $_POST['reason']
            );

            doIncreaseStock($_POST['order_id']);
            destroyGeneralSecurityToken('tokenCartCancelOrderConfirm');
            doDatabaseRequestOrderUpdate($_POST['order_id'], $order_update_fields);

        }
    }

    // CONFIRMAÇÃO DO CANCELAMENTO
    if (getGeneralSecurityToken('tokenOrderCancel')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('order_id');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if (isDatabaseRequestOrderExistID($_POST['order_id']) === false) {
                $errors[] = "Houve um erro ao processar a solicitação, reinicie a pagina e tente novamente.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
            }

        }


        if (empty($errors)) {
            ?>

            <div class="modal fade show" style="padding-right: 19px; display: block;" id="cancelOrderModal" tabindex="-1"
                role="dialog" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cancelOrderModalLabel">Cancelar</h5>
                            <a href="/panel/index">
                                <button type="button" class="close">&times;</span>
                                </button>
                            </a>
                        </div>
                        <form action="/panel/index" method="POST">
                            <div class="modal-body">
                                Você está prestes a cancelar o pedi [#<?php echo $_POST['order_id']; ?>], tem certeza?
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Motivo<font color="red">*</font>:</span>
                                    </div>
                                    <textarea class="form-control" name="reason" aria-label="With textarea"></textarea>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <input name="order_id" type="text" value="<?php echo $_POST['order_id'] ?>" hidden>
                                <input name="token" type="text"
                                    value="<?php echo addGeneralSecurityToken('tokenCartCancelOrderConfirm') ?>" hidden>
                                <button type="submit" class="btn btn-success">Confirmar</button>
                                <a href="/panel/index">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
            <?php
        }
    }

    if (empty($errors) === false) {
        header("HTTP/1.1 401 Not Found");
        echo doAlertError($errors);
    }
}


// <!-- FINAL DA VALIDAÇÃO PHP -->
?>




















<?php
$tokenOrder = addGeneralSecurityToken('tokenOrder');
$tokenOrderCancel = addGeneralSecurityToken('tokenOrderCancel');
?>













<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" disabled><i
            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
</div>

<hr>

<br>
<div id="dashboard">
    <div class="row">

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Aguardando</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo doDatabaseRequestOrderLogCountRowByStatus(2) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Em Preparo</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo doDatabaseRequestOrderLogCountRowByStatus(3) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-spinner fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Entregue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo doDatabaseRequestOrderLogCountRowByStatus(5) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-circle-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Cancelado
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                        <?php echo doDatabaseRequestOrderLogCountRowByStatus(6) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-x fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <link href="/front/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Endereço</th>
                    <th>Tempo</th>
                    <th>Status</th>
                    <th>Opções</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Endereço</th>
                    <th>Tempo</th>
                    <th>Status</th>
                    <th>Opções</th>
                </tr>
            </tfoot>
            <tbody>
                <!-- ORDER LIST START -->
                <?php
                $order_list = doDatabaseRequestOrdersList();

                if ($order_list) {
                    foreach ($order_list as $orderData) {
                        $order_list_id = $orderData['id'];
                        $order_cart_id = getDatabaseRequestOrderCartID($order_list_id);
                        $order_user_id = getDatabaseCartUserID($order_cart_id);
                        $order_first_log_id = doDatabaseRequestOrderLogsFirstLogByOrderID($order_list_id);
                        $order_last_log_id = doDatabaseRequestOrderLogsLastLogByOrderID($order_list_id);
                        $order_main_address_id = getDatabaseRequestOrderAddressIDSelect($order_list_id);
                        $percentual = getOrderProgressBarValue($order_list_id);
                        ?>
                        <tr>
                            <td><?php echo $order_list_id ?></td>
                            <td><?php echo getDatabaseUserName($order_user_id) ?></td>
                            <td><?php echo doBRDateTime(getDatabaseRequestOrderLogCreated($order_first_log_id)) ?></td>
                            <td>
                                <?php
                                if (isDatabaseRequestOrderSelectAddress($order_list_id)) {
                                    echo getDatabaseAddressPublicPlace($order_main_address_id) . ', ';
                                    echo getDatabaseAddressNumber($order_main_address_id) . '(';
                                    echo getDatabaseAddressComplement($order_main_address_id) . '), ';
                                    echo getDatabaseAddressNeighborhood($order_main_address_id) . ', ';
                                    echo getDatabaseAddressCity($order_main_address_id) . ' - ';
                                    echo getDatabaseAddressState($order_main_address_id);
                                } else {
                                    echo 'Retirada no Local';
                                }
                                ?>
                            </td>
                            <td>
                                Minímo:
                                <div class="progress">
                                    <div class="progress-bar bg-warning" aria-hidden="true" data-toggle="tooltip"
                                        data-placement="top"
                                        title="Restam [<?php echo $percentual['minutes_min'] ?>] minutos para atingir o limite mínimo de entrega."
                                        role="progressbar" style="width: <?php echo $percentual['min'] ?>%"
                                        aria-valuenow="<?php echo $percentual['min'] ?>" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                Máximo:
                                <div class="progress">
                                    <div class="progress-bar bg-success" aria-hidden="true" data-toggle="tooltip"
                                        data-placement="top"
                                        title="Restam [<?php echo $percentual['minutes_max'] ?>] minutos para atingir o limite máximo de entrega."
                                        role="progressbar" style="width: <?php echo $percentual['max'] ?>%"
                                        aria-valuenow="<?php echo $percentual['max'] ?>" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <form action="/panel/index" method="POST">
                                    <input name="order_id" type="text" value="<?php echo $order_list_id ?>" hidden />
                                    <input name="token" type="text" value="<?php echo $tokenOrder ?>" hidden />
                                    <?php

                                    if (getDatabaseRequestOrderLogStatusDelivery($order_last_log_id) == 2) {
                                        ?>
                                        <button type="submit" class="btn btn-warning">Aceitar Pedido</button>
                                        <?php
                                    }

                                    if (getDatabaseRequestOrderLogStatusDelivery($order_last_log_id) == 3) {
                                        ?>
                                        <!-- VALIDA SE TEM PERMISSÃO PARA Visualização -->
                                        <?php
                                        if (isGeneralSecurityManagerAccess()) {
                                            if (isDatabaseRequestOrderSelectAddress($order_list_id)) {
                                                ?>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="group_id">Motoboy <small><i
                                                                    class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                                                    data-placement="top"
                                                                    title="Necessário selecionar quem vai entregar."></i></small></label>
                                                    </div>
                                                    <select name="deliveryman" class="custom-select" id="deliveryman">
                                                        <!-- LISTA DE PERMISSÕES -->
                                                        <?php
                                                        $deliveryman_list = doDeliveryManList();
                                                        if ($deliveryman_list) {
                                                            foreach ($deliveryman_list as $dataDeliveryMan) {
                                                                $deliveryman_list_id = $dataDeliveryMan['id'];
                                                                ?>
                                                                <option value="<?php echo $deliveryman_list_id ?>">
                                                                    <?php echo getDatabaseUserName($deliveryman_list_id) ?>
                                                                </option>
                                                                <?php

                                                            }
                                                        }
                                                        ?>
                                                        <!-- LISTA DE PERMISSÕES FIM -->
                                                    </select>

                                                </div>
                                                <?php
                                                ?>

                                                <!-- VALIDA SE TEM PERMISSÃO PARA Visualização -->
                                                <button type="submit" class="btn btn-dark">Sair para Entrega</button>
                                                <?php
                                            } else {
                                                ?>
                                                <button type="submit" class="btn btn-dark">Liberar Pedido</button>
                                                <?php
                                            }
                                        }

                                    }
                                    if (getDatabaseRequestOrderLogStatusDelivery($order_last_log_id) == 4 || getDatabaseRequestOrderLogStatusDelivery($order_last_log_id) == 7) {
                                        ?>
                                        <input name="token" type="text" value="<?php echo $tokenOrder ?>" hidden />
                                        <button type="submit" class="btn btn-success">Confirmar Entrega</button>
                                        <?php
                                    }
                                    ?>
                                    <br><br>
                                </form>
                                <form action="/panel/index" method="post">

                                    <input name="order_id" type="text" value="<?php echo $order_list_id ?>" hidden>
                                    <input name="token" type="text" value="<?php echo $tokenOrderCancel ?>" hidden />
                                    <button type="submit" class="btn btn-danger">Cancelar Pedido</button>
                                </form>
                            </td>
                            <td>
                                <a href="/panel/index/order/view/<?php echo $order_list_id ?>">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="7">Não existe nenhum pedido em espera.</td>
                    </tr>
                    <?php
                }
                ?>
                <!-- ORDER LIST END -->
            </tbody>
        </table>
    </div>
</div>







<!-- AÇÃO UNITARIA PRODUTO -->
<?php
if (isCampanhaInURL("order")) {

    // <!-- Modal REMOVE -->
    if (isCampanhaInURL("view")) {
        $order_select_id = getURLLastParam();
        if (isDatabaseRequestOrderExistID($order_select_id)) {
            ?>
            <script>

                // Para a atualização da página
                function pararAtualizacao() {
                    clearInterval(timerID);
                }
            </script>
            <div class="modal-open">
                <div class="modal fade show" style="padding-right: 19px; display: block;" id="viewOrderModal" tabindex="-1"
                    role="dialog" aria-labelledby="viewOrderModalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="max-width: 800px" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewOrderModalTitle">Visualização</h5>
                                <a href="/panel/index">
                                    <button type="button" class="close" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </a>
                            </div>
                            <div class="modal-body">

                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Pedido</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- LISTA CARRINHO START -->
                                        <?php
                                        $cart_id = getDatabaseRequestOrderCartID($order_select_id);
                                        $cart_list = doDatabaseCartProductsListByCartID($cart_id);
                                        if ($cart_list) {
                                            foreach ($cart_list as $data) {
                                                $cart_product_list_id = $data['id']; // PRODUTO CART
                                                $cart_product_id = getDatabaseCartProductProductID($cart_product_list_id); // PRODUTO_ID
                                                $obs = getDatabaseCartProductObservation($cart_product_list_id);
                                                $size_id = getDatabaseCartProductPriceID($cart_product_list_id);
                                                $measure_id = getDatabaseProductSizeMeasureID($size_id);
                                                $user_id = getDatabaseCartUserID($cart_product_list_id);
                                                $discount = getDatabaseTicketValue(getDatabaseCartTicketSelectTicketID(getDatabaseCartTicketSelectByCartID($cart_id)));
                                                ?>
                                                <tr>
                                                    <td>
                                                        <style>
                                                            .v {
                                                                float: right;
                                                            }

                                                            li,
                                                            ul,
                                                            ol {
                                                                margin: 1px;
                                                            }

                                                            li {
                                                                width: 100%;
                                                            }

                                                            .subtopic {
                                                                font-weight: 600;
                                                            }
                                                        </style>
                                                        <small>
                                                            (<?php echo getDatabaseCartProductAmount($cart_product_list_id) ?>x)
                                                            <a data-toggle="tooltip" data-placement="top"
                                                                title="<?php echo getDatabaseProductDescription($cart_product_id) ?>"
                                                                href="/panel/products/view/product/<?php echo $cart_product_id ?>">
                                                                <?php echo getDatabaseProductName($cart_product_id) ?>
                                                            </a> -
                                                            <?php echo getDatabaseProductPriceSize($size_id) ?>
                                                            <?php echo getDatabaseMeasureTitle($measure_id) ?>
                                                            <nav>
                                                                <ul class="subtopic"># Complementos</ul>
                                                                <ol>
                                                                    <?php
                                                                    $complement_list = doDatabaseProductsComplementsListByProductID($cart_product_id);
                                                                    $product_complement_select = getDatabaseCartProductComplementByCartProductID($cart_product_list_id);
                                                                    $complement_select = getDatabaseCartProductComplementComplementID($product_complement_select);

                                                                    if ($complement_list) {
                                                                        foreach ($complement_list as $dataComplement) {
                                                                            $product_complement_id = $dataComplement['id'];
                                                                            $complement_id = getDatabaseProductComplementComplementID($product_complement_id);
                                                                            ?>
                                                                            <?php echo ($complement_select == $complement_id) ? '<li>' . getDatabaseComplementDescription($complement_id) . '</li>' : '' ?>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </ol>

                                                            </nav>
                                                            <br>
                                                            <nav>
                                                                <ul class="subtopic"># Adicionais</ul>
                                                                <ol>

                                                                    <?php
                                                                    $additional_list = doDatabaseProductsAdditionalListByProductID($cart_product_id);
                                                                    if ($additional_list) {
                                                                        foreach ($additional_list as $dataAdditional) {
                                                                            $product_additional_id = $dataAdditional['id'];
                                                                            $additional_id = getDatabaseProductAdditionalAdditionalID($product_additional_id);
                                                                            ?>
                                                                            <?php echo (isDatabaseCartProductAdditionalExistIDByCartAndAdditionalID($cart_product_list_id, $additional_id) == 1) ? '<li>' . getDatabaseAdditionalDescription($additional_id) . ' <b><span class="subvalue">R$ ' . sprintf("%.2f", getDatabaseAdditionalTotalPrice($additional_id)) . '</span></b></li>' : '' ?>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </ol>

                                                            </nav>
                                                            <span class="subtopic">Observações:</span><br>
                                                            <?php echo ($obs) ? $obs : 'Vazio'; ?>
                                                            <br>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <b>R$
                                                            <?php echo sprintf("%.2f", doCartTotalPriceProduct($cart_product_list_id)) ?></b>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            echo 'Não existe nenhum produto no carrinho.';
                                        }
                                        ?>
                                        <!-- LISTA CARRINHO END -->
                                    </tbody>
                                </table>

                                <div>
                                    <fieldset id="historic">
                                        <legend>Histórico</legend>

                                        <!-- LOG START -->
                                        <?php
                                        $log_list = doDatabaseRequestOrderLogsListByOrderID($order_select_id);
                                        if ($log_list) {
                                            foreach ($log_list as $dataLog) {
                                                $log_list_id = $dataLog['id'];
                                                ?>
                                                <p><?php echo doTime(getDatabaseRequestOrderLogCreated($log_list_id)) ?> -
                                                    <?php echo getDatabaseStatusDeliveryTitle(getDatabaseRequestOrderLogStatusDelivery($log_list_id)); ?>
                                                </p>
                                                <?php
                                            }
                                        }
                                        ?>
                                        <!-- LOG END -->
                                    </fieldset>
                                </div>
                                <hr>
                                <div>
                                    <section id="address">
                                        <div>
                                            <b>Endereço:</b><br><?php
                                            $main_address_id = getDatabaseUserSelectAddressByUserID($in_user_id);
                                            echo getDatabaseAddressPublicPlace($main_address_id) . ', ';
                                            echo getDatabaseAddressNumber($main_address_id) . '(';
                                            echo getDatabaseAddressComplement($main_address_id) . '), ';
                                            echo getDatabaseAddressNeighborhood($main_address_id) . ', ';
                                            echo getDatabaseAddressCity($main_address_id) . ' - ';
                                            echo getDatabaseAddressState($main_address_id);
                                            $discount = getDatabaseTicketValue(getDatabaseCartTicketSelectTicketID(getDatabaseCartTicketSelectByCartID($cart_id)));
                                            ?>
                                        </div>
                                    </section>
                                    <hr>
                                    <section id="ticket">
                                        <div>
                                            <?php echo getDatabaseTicketCode(getDatabaseCartTicketSelectTicketID(getDatabaseCartTicketSelectCartID($cart_id))) ?>
                                            <small>Usuário obteve desconto de
                                                [<?php echo ($discount !== false) ? $discount : 'Nenhum desconto selecionado.' ?>]</small>
                                        </div>
                                    </section>
                                    <hr>
                                    <section id="ticket">
                                        <div>
                                            <b>Cupom:</b><br>
                                            <?php echo getDatabaseTicketCode(getDatabaseCartTicketSelectTicketID(getDatabaseCartTicketSelectCartID($cart_id))) ?>
                                            <small><?php echo ($discount !== false) ? 'O cliente teve um desconto de [' . doTypeDiscount($discount) . ']' : 'O cliente não selecionou nenhum cupom.' ?></small>
                                        </div>
                                    </section>
                                    <hr>

                                    <section id="pay">
                                        <div>
                                            <b>Pagamento no:
                                            </b>
                                            <?php echo getDatabaseSettingsPayType(getDatabaseRequestOrderPayIDSelect($order_select_id)); ?><br>
                                            <?php
                                            if (getDatabaseRequestOrderPayIDSelect($order_select_id) == getDatabaseSettingsPayMoney(1)) { ?>
                                                <label for="change">Troco para:
                                                    <?php echo getDatabaseRequestOrderChangeOf($order_select_id) ?></label>
                                            <?php }
                                            ?>
                                        </div>
                                    </section>
                                    <hr>
                                    <section id="totals">
                                        <div>
                                            <p class="t">Taxa de entrega
                                                <label class="v">R$ <?php echo getDatabaseSettingsDeliveryFee(1) ?></label>
                                            </p>
                                            <p class="t">Desconto de Cupom
                                                <label class="v">- <?php echo doTypeDiscount($discount) ?></label>
                                            </p>
                                            <b>
                                                <p class="t">Total do Pedido
                                                    <label class="v">R$
                                                        <?php echo sprintf("%.2f", (doCartTotalPrice($cart_id) - doCartTotalPriceDiscount($cart_id))) ?></label>
                                                </p>
                                            </b>
                                        </div>
                                    </section>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <a href="/panel/index">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                </a>
                            </div>
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
            },
            "order": [[0, 'asc']] // Ordenar pela primeira coluna em ordem ascendente
        });
    });

</script>



<?php
include_once (realpath(__DIR__ . "/layout/php/footer.php"));
?>