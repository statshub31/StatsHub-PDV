<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));
?>


<?php
// <!-- INICIO DA VALIDAÇÃO PHP -->
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {


    // REMOVE USER

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

                if ($order_last_log_id == 3) {
                    if (isDatabaseUserExistID($_POST['deliveryman']) === false || (empty($_POST['deliveryman']))) {
                        $errors[] = "É necessário selecionar o motoboy que vai fazer a entrega, o mesmo precisa estar registrado.";
                    }
                }
            }

        }


        if (empty($errors)) {
            $new_order_status_id = getDatabaseRequestOrderLogStatusDelivery($order_last_log_id) + 1;

            $order_log_update_fields = array(
                'request_order_id' => $_POST['order_id'],
                'status_delivery' => $new_order_status_id,
                'created' => date("Y-m-d H:i:s")
            );

            doDatabaseRequestOrderLogInsert($order_log_update_fields);

            if ($new_order_status_id == 4) {
                $order_update_fields = array(
                    'deliveryman' => $_POST['deliveryman']
                );
                doDatabaseRequestOrderUpdate($_POST['order_id'], $order_update_fields);
            }

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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">5
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800"> 10 Minutos</div>
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
                                Finalizado</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">5
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
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">5
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
                $order_list = doDatabaseRequestOrdersListByConfirmed();

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
                            <td>#<?php echo $order_list_id ?></td>
                            <td><?php echo getDatabaseUserName($order_user_id) ?></td>
                            <td><?php echo doBRDateTime(getDatabaseRequestOrderLogCreated($order_first_log_id)) ?></td>
                            <td>
                                <?php
                                echo getDatabaseAddressPublicPlace($order_main_address_id) . ', ';
                                echo getDatabaseAddressNumber($order_main_address_id) . '(';
                                echo getDatabaseAddressComplement($order_main_address_id) . '), ';
                                echo getDatabaseAddressNeighborhood($order_main_address_id) . ', ';
                                echo getDatabaseAddressCity($order_main_address_id) . ' - ';
                                echo getDatabaseAddressState($order_main_address_id);
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
                                    <?php
                                    if (getDatabaseRequestOrderLogStatusDelivery($order_last_log_id) == 2) {
                                        ?>
                                        <input name="token" type="text" value="<?php echo $tokenOrder ?>" hidden />
                                        <button type="submit" class="btn btn-warning">Aceitar Pedido</button>
                                        <?php
                                    }
                                    if (getDatabaseRequestOrderLogStatusDelivery($order_last_log_id) == 3) {
                                        ?>
                                        <input name="token" type="text" value="<?php echo $tokenOrder ?>" hidden />
                                        <!-- VALIDA SE TEM PERMISSÃO PARA Visualização -->
                                        <?php
                                        if (isGeneralSecurityManagerAccess()) {
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
                                        }
                                        ?>

                                        <!-- VALIDA SE TEM PERMISSÃO PARA Visualização -->
                                        <button type="submit" class="btn btn-dark">Sair para Entrega</button>
                                        <?php
                                    }
                                    if (getDatabaseRequestOrderLogStatusDelivery($order_last_log_id) == 4) {
                                        ?>
                                        <input name="token" type="text" value="<?php echo $tokenOrder ?>" hidden />
                                        <button type="submit" class="btn btn-success">Confirmar Entrega</button>
                                        <?php
                                    }
                                    ?>
                                    <br><br>
                                    <input name="token" type="text" value="<?php echo $tokenOrder ?>" hidden />
                                    <button type="submit" class="btn btn-danger">Cancelar Pedido</button>
                                </form>
                            </td>
                            <td><i class="fa-solid fa-eye"></i></td>
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

<?php
include_once (realpath(__DIR__ . "/layout/php/footer.php"));
?>