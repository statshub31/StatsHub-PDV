<?php
include_once __DIR__ . '/layout/php/header.php';
?>

<?php
$order_id = getURLLastParam();
$order_last_log_id = doGeneralSecurityOrder($order_id);
$cart_id = getDatabaseRequestOrderCartID($order_id);
?>

<?php
// <!-- INICIO DA VALIDAÇÃO PHP -->
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {


    // EDIT USER

    if (getGeneralSecurityToken('tokenAvailable')) {
        if (empty($_POST) === false) {
            $required_fields_status = true;

            if ((!isset($_POST['food'])) || (!isset($_POST['box'])) || (!isset($_POST['deliverytime'])) || (!isset($_POST['costbenefit']))) {
                $errors[] = "Obrigatório o preenchimento de pontuação.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                if (isDatabaseRequestOrderExistID($_POST['order_id']) === false) {
                    $errors[] = "Houve um erro ao enviar a avaliação, reinicie a pagina e tente novamente";
                } else {
                    $cart_id = getDatabaseRequestOrderCartID($_POST['order_id']);
                    if (isDatabaseCartUserValidation($in_user_id, $cart_id) === false) {
                        $errors[] = "Houve um erro ao enviar a avaliação, reinicie a pagina e tente novamente";
                    }
                }

                if ($_POST['food'] < 0 || $_POST['food'] > 5) {
                    $errors[] = "Houve um erro ao enviar a avaliação, reinicie a pagina e tente novamente";
                }
                if ($_POST['box'] < 0 || $_POST['box'] > 5) {
                    $errors[] = "Houve um erro ao enviar a avaliação, reinicie a pagina e tente novamente";
                }
                if ($_POST['deliverytime'] < 0 || $_POST['deliverytime'] > 5) {
                    $errors[] = "Houve um erro ao enviar a avaliação, reinicie a pagina e tente novamente";
                }
                if ($_POST['costbenefit'] < 0 || $_POST['costbenefit'] > 5) {
                    $errors[] = "Houve um erro ao enviar a avaliação, reinicie a pagina e tente novamente";
                }

                if (!empty($_POST['comment'])) {
                    if (doGeneralValidationDescriptionFormat($_POST['comment']) == false) {
                        $errors[] = "Reveja o comentário, existem caracteres invalido.";
                    }

                    if (strlen($_POST['comment']) > 100) {
                        $errors[] = "Está muito cumprido está validação, se limite a 100 caracteres.";
                    }
                }
            }

        }


        if (empty($errors)) {
            $available_insert_fields = array(
                'request_order_id' => $_POST['order_id'],
                'created' => date('Y-m-d H:i:s'),
                'food' => $_POST['food'],
                'box' => $_POST['box'],
                'deliverytime' => $_POST['deliverytime'],
                'costbenefit' => $_POST['costbenefit'],
                'comment' => sanitize($_POST['comment'])
            );

            doDatabaseRequestOrderAvailableInsert($available_insert_fields);
            doAlertSuccess("As informações de usuário, foram alteradas!!");
        }
    }


    if (empty($errors) === false) {
        header("HTTP/1.1 401 Not Found");
        echo doAlertError($errors);
    }
}


// <!-- FINAL DA VALIDAÇÃO PHP -->
?>


<div id="order-status">
    <section id="order-status-delivery">
        <?php
        if (getDatabaseRequestOrderLogStatusDelivery($order_last_log_id) != 5) {
            ?>
            <label id="order-delivery-forecast">Previsão de Entrega:</label><br>
            <span class="stime"><?php echo getMinTimeOrderDelivery($order_id) ?></span> -
            <span class="stime"><?php echo getMaxTimeOrderDelivery($order_id) ?></span>
            <?php
        }
        ?>
        <div id="order-image">
            <img
                src="<?php echo getPathModelImage('statusdelivery' . getDatabaseRequestOrderLogStatusDelivery($order_last_log_id)) ?>" />
        </div>
        <br>
        <div>
            <?php
            if (getDatabaseRequestOrderLogStatusDelivery($order_last_log_id) != 5) {
                ?>
                <div class="barra">
                    <div <?php echo 'style="background-color:' . doStyleProgress(getDatabaseRequestOrderLogStatusDelivery($order_last_log_id)) . '"' ?>
                        class="progresso"></div>
                </div>
                <?php
            }
            ?>
            <div id="order-status-info">
                <section class="order-main-status" data-toggle="collapse" href="#collapseExample" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    <label class="bolinha" <?php echo 'style="box-shadow: 0 0 10px ' . doStyleProgress(getDatabaseRequestOrderLogStatusDelivery($order_last_log_id)) . '; background-color: ' . doStyleProgress(getDatabaseRequestOrderLogStatusDelivery($order_last_log_id)) . '"' ?>></label>
                    <label><?php echo getDatabaseStatusDeliveryTitle(getDatabaseRequestOrderLogStatusDelivery($order_last_log_id)) ?></label>
                    <label><?php echo doTime(getDatabaseRequestOrderLogCreated($order_last_log_id)) ?></label>
                </section>
            </div>
        </div>
        <div class="collapse" id="collapseExample">
            <div class="card card-body w-100">
                <!-- LOG START -->
                <?php
                $log_list = doDatabaseRequestOrderLogsListByOrderID($order_id);
                if ($log_list) {
                    foreach ($log_list as $dataLog) {
                        $log_list_id = $dataLog['id'];
                        ?>
                        <section class="order-main-status" data-toggle="collapse" href="#collapseExample" role="button"
                            aria-expanded="false" aria-controls="collapseExample">

                            <label class="bolinha" <?php echo 'style="animation: none; box-shadow: 0 0 10px ' . doStyleProgress(getDatabaseRequestOrderLogStatusDelivery($log_list_id)) . '; background-color: ' . doStyleProgress(getDatabaseRequestOrderLogStatusDelivery($log_list_id)) . '"' ?>></label>
                            <label><?php echo getDatabaseStatusDeliveryTitle(getDatabaseRequestOrderLogStatusDelivery($log_list_id)) ?></label>
                            <label><?php echo doTime(getDatabaseRequestOrderLogCreated($log_list_id)) ?></label>
                        </section>
                        <?php
                    }
                }
                ?>
                <!-- LOG END -->
            </div>
        </div>

    </section>
    <section id="order-info">
        <label>Detalhes do Pedido:</label>
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#orderInfoModal">Ver mais</button>
        <label id="order-number">#<?php echo $order_id ?></label>
        <hr>
        <label>Pagamento na Entrega</label>
        <label id="order-total">Total <span>R$
                <?php echo sprintf("%.2f", (doCartTotalPrice($cart_id) - doCartTotalPriceDiscount($cart_id))) ?></span></label>
    </section>
    <?php
    if (getDatabaseRequestOrderLogStatusDelivery($order_last_log_id) == 5) {
        $available_id = getDatabaseRequestOrderAvailableExistByOrderID($order_id);
        ?>
        <form action="/order/<?php echo $order_id ?>" method="POST">
            <div class="available">
                <div class="second-available-frame">
                    <section>
                        <label>Comida</label>
                        <div id="stars">
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableFoodAvailable($available_id, 1)) ? 'star colorstar' : ''; ?>">
                                <input <?php
                                echo doCheck(isDatabaseRequestOrderAvailableFoodAvailable($available_id, 1), 1)
                                    ?> type="radio" name="food" id="starFood1" value="1"
                                    onclick="fillPreviousStars(1, 'Food')" hidden>
                                <label for="starFood1">
                                    <img src="/layout/images/model/star-a.svg">
                                </label>
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableFoodAvailable($available_id, 2)) ? 'star colorstar' : ''; ?>">
                                <input <?php
                                echo doCheck(isDatabaseRequestOrderAvailableFoodAvailable($available_id, 1), 2)
                                    ?> type="radio" name="food" id="starFood2" value="2"
                                    onclick="fillPreviousStars(2, 'Food')" hidden>
                                <label for="starFood2">
                                    <img src="/layout/images/model/star-a.svg">
                                </label>
                            </section>
                            <section class="star <?php
                            echo (isDatabaseRequestOrderAvailableFoodAvailable($available_id, 3)) ? 'star colorstar' : '';
                            ?>">
                                <input <?php
                                echo doCheck(isDatabaseRequestOrderAvailableFoodAvailable($available_id, 1), 3)
                                    ?> type="radio" name="food" id="starFood3" value="3"
                                    onclick="fillPreviousStars(3, 'Food')" hidden>
                                <label for="starFood3">
                                    <img src="/layout/images/model/star-a.svg">
                                </label>
                            </section>
                            <section class="star <?php
                            echo (isDatabaseRequestOrderAvailableFoodAvailable($available_id, 4)) ? 'star colorstar' : '';
                            ?>">
                                <input <?php
                                echo doCheck(isDatabaseRequestOrderAvailableFoodAvailable($available_id, 1), 4)
                                    ?> type="radio" name="food" id="starFood4" value="4"
                                    onclick="fillPreviousStars(4, 'Food')" hidden>
                                <label for="starFood4">
                                    <img src="/layout/images/model/star-a.svg">
                                </label>
                            </section>
                            <section class="star <?php
                            echo (isDatabaseRequestOrderAvailableFoodAvailable($available_id, 5)) ? 'star colorstar' : '';
                            ?>">
                                <input <?php
                                echo doCheck(isDatabaseRequestOrderAvailableFoodAvailable($available_id, 1), 5)
                                    ?> type="radio" name="food" id="starFood5" value="5"
                                    onclick="fillPreviousStars(5, 'Food')" hidden>
                                <label for="starFood5">
                                    <img src="/layout/images/model/star-a.svg">
                                </label>
                            </section>
                        </div>
                    </section>
                    <section>
                        <label>Embalagem</label>
                        <div id="stars">
                            <section class="star <?php
                            echo (isDatabaseRequestOrderAvailableBoxAvailable($available_id, 1)) ? 'star colorstar' : '';
                            ?>">
                                <input <?php
                                echo doCheck(isDatabaseRequestOrderAvailableBoxAvailable($available_id, 1), 1)
                                    ?> type="radio" name="box" id="starBox1" value="1" onclick="fillPreviousStars(1, 'Box')"
                                    hidden>
                                <label for="starBox1">
                                    <img src="/layout/images/model/star-a.svg">
                                </label>
                            </section>
                            <section class="star <?php
                            echo (isDatabaseRequestOrderAvailableBoxAvailable($available_id, 2)) ? 'star colorstar' : '';
                            ?>">
                                <input <?php
                                echo doCheck(isDatabaseRequestOrderAvailableBoxAvailable($available_id, 1), 2)
                                    ?> type="radio" name="box" id="starBox2" value="2" onclick="fillPreviousStars(2, 'Box')"
                                    hidden>
                                <label for="starBox2">
                                    <img src="/layout/images/model/star-a.svg">
                                </label>
                            </section>
                            <section class="star <?php
                            echo (isDatabaseRequestOrderAvailableBoxAvailable($available_id, 3)) ? 'star colorstar' : '';
                            ?>">
                                <input <?php
                                echo doCheck(isDatabaseRequestOrderAvailableBoxAvailable($available_id, 1), 3)
                                    ?> type="radio" name="box" id="starBox3" value="3" onclick="fillPreviousStars(3, 'Box')"
                                    hidden>
                                <label for="starBox3">
                                    <img src="/layout/images/model/star-a.svg">
                                </label>
                            </section>
                            <section class="star <?php
                            echo (isDatabaseRequestOrderAvailableBoxAvailable($available_id, 4)) ? 'star colorstar' : '';
                            ?>">
                                <input <?php
                                echo doCheck(isDatabaseRequestOrderAvailableBoxAvailable($available_id, 1), 4)
                                    ?> type="radio" name="box" id="starBox4" value="4" onclick="fillPreviousStars(4, 'Box')"
                                    hidden>
                                <label for="starBox4">
                                    <img src="/layout/images/model/star-a.svg">
                                </label>
                            </section>
                            <section class="star <?php
                            echo (isDatabaseRequestOrderAvailableBoxAvailable($available_id, 5)) ? 'star colorstar' : '';
                            ?>">
                                <input <?php
                                echo doCheck(isDatabaseRequestOrderAvailableBoxAvailable($available_id, 1), 5)
                                    ?> type="radio" name="box" id="starBox5" value="5" onclick="fillPreviousStars(5, 'Box')"
                                    hidden>
                                <label for="starBox5">
                                    <img src="/layout/images/model/star-a.svg">
                                </label>
                            </section>
                        </div>
                    </section>
                    <section>
                        <label>Tempo de Entrega</label>
                        <div id="stars">
                            <section class="star <?php
                            echo (isDatabaseRequestOrderAvailableDeliveryTimeAvailable($available_id, 1)) ? 'star colorstar' : '';
                            ?>">
                                <input <?php
                                echo doCheck(isDatabaseRequestOrderAvailableDeliveryTimeAvailable($available_id, 1), 1)
                                    ?> type="radio" name="deliverytime" id="starDeliveryTime1" value="1"
                                    onclick="fillPreviousStars(1, 'DeliveryTime')" hidden>
                                <label for="starDeliveryTime1">
                                    <img src="/layout/images/model/star-a.svg">
                                </label>
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableDeliveryTimeAvailable($available_id, 2)) ? 'star colorstar' : ''; ?>">
                                <input <?php echo doCheck(isDatabaseRequestOrderAvailableDeliveryTimeAvailable($available_id, 1), 2) ?>
                                    type="radio" name="deliverytime" id="starDeliveryTime2" value="2"
                                    onclick="fillPreviousStars(2, 'DeliveryTime')" hidden>
                                <label for="starDeliveryTime2">
                                    <img src="/layout/images/model/star-a.svg">
                                </label>
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableDeliveryTimeAvailable($available_id, 3)) ? 'star colorstar' : ''; ?>">
                                <input <?php echo doCheck(isDatabaseRequestOrderAvailableDeliveryTimeAvailable($available_id, 1), 3) ?>
                                    type="radio" name="deliverytime" id="starDeliveryTime3" value="3"
                                    onclick="fillPreviousStars(3, 'DeliveryTime')" hidden>
                                <label for="starDeliveryTime3">
                                    <img src="/layout/images/model/star-a.svg">
                                </label>
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableDeliveryTimeAvailable($available_id, 4)) ? 'star colorstar' : ''; ?>">
                                <input <?php echo doCheck(isDatabaseRequestOrderAvailableDeliveryTimeAvailable($available_id, 1), 4) ?>
                                    type="radio" name="deliverytime" id="starDeliveryTime4" value="4"
                                    onclick="fillPreviousStars(4, 'DeliveryTime')" hidden>
                                <label for="starDeliveryTime4">
                                    <img src="/layout/images/model/star-a.svg">
                                </label>
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableDeliveryTimeAvailable($available_id, 5)) ? 'star colorstar' : ''; ?>">
                                <input <?php echo doCheck(isDatabaseRequestOrderAvailableDeliveryTimeAvailable($available_id, 1), 5) ?>
                                    type="radio" name="deliverytime" id="starDeliveryTime5" value="5"
                                    onclick="fillPreviousStars(5, 'DeliveryTime')" hidden>
                                <label for="starDeliveryTime5">
                                    <img src="/layout/images/model/star-a.svg">
                                </label>
                            </section>
                        </div>
                    </section>

                    <section>
                        <label>Custo Beneficio</label>
                        <div id="stars">
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableCostBenefitAvailable($available_id, 1)) ? 'star colorstar' : ''; ?>">
                                <input <?php echo doCheck(isDatabaseRequestOrderAvailableCostBenefitAvailable($available_id, 1), 1) ?> type="radio" name="costbenefit" id="starCostBenefit1" value="1"
                                    onclick="fillPreviousStars(1, 'CostBenefit')" hidden>
                                <label for="starCostBenefit1">
                                    <img src="/layout/images/model/star-a.svg">
                                </label>
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableCostBenefitAvailable($available_id, 2)) ? 'star colorstar' : ''; ?>">
                                <input <?php echo doCheck(isDatabaseRequestOrderAvailableCostBenefitAvailable($available_id, 1), 2) ?> type="radio" name="costbenefit" id="starCostBenefit2" value="2"
                                    onclick="fillPreviousStars(2, 'CostBenefit')" hidden>
                                <label for="starCostBenefit2">
                                    <img src="/layout/images/model/star-a.svg">
                                </label>
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableCostBenefitAvailable($available_id, 3)) ? 'star colorstar' : ''; ?>">
                                <input <?php echo doCheck(isDatabaseRequestOrderAvailableCostBenefitAvailable($available_id, 1), 3) ?> type="radio" name="costbenefit" id="starCostBenefit3" value="3"
                                    onclick="fillPreviousStars(3, 'CostBenefit')" hidden>
                                <label for="starCostBenefit3">
                                    <img src="/layout/images/model/star-a.svg">
                                </label>
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableCostBenefitAvailable($available_id, 4)) ? 'star colorstar' : ''; ?>">
                                <input <?php echo doCheck(isDatabaseRequestOrderAvailableCostBenefitAvailable($available_id, 1), 4) ?> type="radio" name="costbenefit" id="starCostBenefit4" value="4"
                                    onclick="fillPreviousStars(4, 'CostBenefit')" hidden>
                                <label for="starCostBenefit4">
                                    <img src="/layout/images/model/star-a.svg">
                                </label>
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableCostBenefitAvailable($available_id, 5)) ? 'star colorstar' : ''; ?>">
                                <input <?php echo doCheck(isDatabaseRequestOrderAvailableCostBenefitAvailable($available_id, 1), 5) ?> type="radio" name="costbenefit" id="starCostBenefit5" value="5"
                                    onclick="fillPreviousStars(5, 'CostBenefit')" hidden>
                                <label for="starCostBenefit5">
                                    <img src="/layout/images/model/star-a.svg">
                                </label>
                            </section>
                        </div>
                    </section>
                    <?php
                    if (isDatabaseRequestOrderAvailableExistByOrderID($order_id) === false) {
                        ?>
                        <input name="order_id" type="text" value="<?php echo $order_id ?>" hidden>
                        <input name="token" type="text" value="<?php echo addGeneralSecurityToken('tokenAvailable') ?>" hidden>
                        <button type="submit" class="btn btn-success">Enviar</button>
                    <?php }
                    ?>
                </div>


                <textarea <?php echo (isDatabaseRequestOrderAvailableExistByOrderID($order_id) ? 'disabled' : '') ?>
                    name="comment" class="form-control third-available-frame comments"
                    aria-label="With textarea"><?php echo getDatabaseRequestOrderAvailableComment(getDatabaseRequestOrderAvailableExistByOrderID($order_id)) ?></textarea>
            </div>
            <?php
    }
    ?>

</div>




<!-- Modal -->
<div class="modal fade " id="orderInfoModal" tabindex="-1" role="dialog" aria-labelledby="orderInfoModal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" style="max-width: 900px" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Informações</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <link href="/front/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <!-- LISTA CARRINHO START -->
                        <?php
                        $cart_id = getDatabaseRequestOrderCartID($order_id);
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
                                        <div class="cart-product">
                                            <section class="product-photo">
                                                <img
                                                    src="<?php echo getPathProductImage(getDatabaseProductPhotoName($cart_product_id)) ?>">
                                            </section>
                                            <section class="product-name">
                                                <label><?php echo getDatabaseProductName($cart_product_id) ?> </label>
                                            </section>
                                        </div>
                                    </td>
                                    <td>
                                        Descrição do Produto:
                                        <small><?php echo getDatabaseProductDescription($cart_product_id) ?></small><br>

                                        <hr>
                                                        <small>
                                                            (<?php echo getDatabaseCartProductAmount($cart_product_list_id) ?>x)
                                                            <?php echo getDatabaseProductName($cart_product_id) ?> -
                                                            <?php echo getDatabaseProductPriceSize($size_id) ?>
                                                            <?php echo getDatabaseMeasureTitle($measure_id) ?>
                                                            <nav>
                                                                <ul class="subtopic">Complementos</ul>
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

                                                            <nav>
                                                                <ul class="subtopic">Adicionais</ul>
                                                                <ol>

                                                                    <?php
                                                                    $additional_list = doDatabaseProductsAdditionalListByProductID($cart_product_id);
                                                                    if ($additional_list) {
                                                                        foreach ($additional_list as $dataAdditional) {
                                                                            $product_additional_id = $dataAdditional['id'];
                                                                            $additional_id = getDatabaseProductAdditionalAdditionalID($product_additional_id);
                                                                            ?>
                                                                            <?php echo (isDatabaseCartProductAdditionalExistIDByCartAndAdditionalID($cart_product_list_id, $additional_id) == 1) ? '<li>' . getDatabaseAdditionalDescription($additional_id) . '
                                 <span class="subvalue">R$ 5.00</span>
                                 </li>' : '' ?>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </ol>

                                                            </nav>
                                                            <span class="subtopic">Observações:</span><br>
                                                            <?php echo ($obs) ? $obs : 'Vazio'; ?>
                                                            <br>
                                                        </small><br>
                                        <b>
                                            <label class="v">R$
                                                <?php echo doCartTotalPriceProduct($cart_product_list_id) ?></label>
                                        </b>
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
                    <section id="address">
                        <div>
                            <b>Endereço:</b><br>
                            <?php
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
                            <b>Cupom:</b><br>
                            <?php echo getDatabaseTicketCode(getDatabaseCartTicketSelectTicketID(getDatabaseCartTicketSelectCartID($cart_id))) ?>
                            <small><?php echo ($discount !== false) ? 'Você terá um desconto de [' . doTypeDiscount($discount) . ']' : 'Você não selecionou nenhum cupom.' ?></small>
                        </div>
                    </section>
                    <hr>
                    <section id="pay">
                        <div>
                            <?php echo getDatabaseSettingsPayType(getDatabaseRequestOrderPayIDSelect($order_id)); ?><br>
                            <?php
                            if (getDatabaseRequestOrderPayIDSelect($order_id) == getDatabaseSettingsPayMoney(1)) { ?>
                                <label for="change">Troco para:
                                    <?php echo getDatabaseRequestOrderChangeOf($order_id) ?></label>
                            <?php }
                            ?>
                        </div>
                    </section>
                    <hr>
                    <section id="ticket">
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function fillPreviousStars(selectedStar, type) {
        for (let i = 1; i <= selectedStar; i++) {
            document.getElementById('star' + type + i).parentNode.classList.add('colorstar');
        }

        for (let i = selectedStar + 1; i <= 5; i++) {
            document.getElementById('star' + type + i).parentNode.classList.remove('colorstar');
        }
    }

    function atualizarPagina() {
        location.reload();
    }

    setInterval(atualizarPagina, 15 * 1000);

</script>

<?php
include_once __DIR__ . '/layout/php/footer.php';
?>