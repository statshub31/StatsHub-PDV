<?php
include_once __DIR__ . '/layout/php/header.php';
?>

<?php

$order_id = getURLLastParam();
if (isDatabaseRequestOrderExistID($order_id) === false) {
} else {
    $cart_id = getDatabaseRequestOrderCartID($order_id);
    $order_last_log_id = doDatabaseRequestOrderLogsLastLogByOrderID($order_id);

    if (isDatabaseCartUserValidation($in_user_id, $cart_id) === false) {
        header('Location: /cart');
    }
}

?>

<div id="order-status">
    <style>
    </style>
    <section id="order-status-delivery">
        <label id="order-delivery-forecast">Previsão de Entrega:</label><br>
        <span class="stime"><?php echo getMinTimeOrderDelivery($order_id) ?></span> -
        <span class="stime"><?php echo getMaxTimeOrderDelivery($order_id) ?></span>
        <div>

            <div class="barra">
                <div <?php echo 'style="background-color:'.doStyleProgress(getDatabaseRequestOrderLogStatusDelivery($order_last_log_id)).'"' ?> class="progresso"></div>
            </div>
            <div id="order-status-info">
                <section class="order-main-status" data-toggle="collapse" href="#collapseExample" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    <label class="bolinha" <?php echo 'style="box-shadow: 0 0 10px '.doStyleProgress(getDatabaseRequestOrderLogStatusDelivery($order_last_log_id)).'; background-color: '.doStyleProgress(getDatabaseRequestOrderLogStatusDelivery($order_last_log_id)).'"' ?>></label>
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

                            <label class="bolinha" <?php echo 'style="animation: none; box-shadow: 0 0 10px '.doStyleProgress(getDatabaseRequestOrderLogStatusDelivery($log_list_id)).'; background-color: '.doStyleProgress(getDatabaseRequestOrderLogStatusDelivery($log_list_id)).'"' ?>></label>
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
        <label id="order-total">Total <span>R$ <?php echo (doCartTotalPrice($cart_id) - doCartTotalPriceDiscount($cart_id)) ?></span></label>
    </section>

    <div class="available" hidden>
        <div class="second-available-frame">
            <section>
                <label>Comida</label>
                <div id="stars">
                    <section class="star">
                        <img src="/layout/images/model/star-a.svg">
                    </section>
                    <section class="star">
                        <img src="/layout/images/model/star-a.svg">
                    </section>
                    <section class="star">
                        <img src="/layout/images/model/star-a.svg">
                    </section>
                    <section class="star">
                        <img src="/layout/images/model/star-a.svg">
                    </section>
                    <section class="star">
                        <img src="/layout/images/model/star-a.svg">
                    </section>
                </div>
            </section>
            <section>
                <label>Embalagem</label>
                <div id="stars">
                    <section class="star">
                        <img src="/layout/images/model/star-a.svg">
                    </section>
                    <section class="star">
                        <img src="/layout/images/model/star-a.svg">
                    </section>
                    <section class="star">
                        <img src="/layout/images/model/star-a.svg">
                    </section>
                    <section class="star">
                        <img src="/layout/images/model/star-a.svg">
                    </section>
                    <section class="star">
                        <img src="/layout/images/model/star-a.svg">
                    </section>
                </div>
            </section>
            <section>
                <label>Tempo de Entrega</label>
                <div id="stars">
                    <section class="star">
                        <img src="/layout/images/model/star-a.svg">
                    </section>
                    <section class="star">
                        <img src="/layout/images/model/star-a.svg">
                    </section>
                    <section class="star">
                        <img src="/layout/images/model/star-a.svg">
                    </section>
                    <section class="star">
                        <img src="/layout/images/model/star-a.svg">
                    </section>
                    <section class="star">
                        <img src="/layout/images/model/star-a.svg">
                    </section>
                </div>
            </section>
            <section>
                <label>Custo Beneficio</label>
                <div id="stars">
                    <section class="star">
                        <img src="/layout/images/model/star-a.svg">
                    </section>
                    <section class="star">
                        <img src="/layout/images/model/star-a.svg">
                    </section>
                    <section class="star">
                        <img src="/layout/images/model/star-a.svg">
                    </section>
                    <section class="star">
                        <img src="/layout/images/model/star-a.svg">
                    </section>
                    <section class="star">
                        <img src="/layout/images/model/star-a.svg">
                    </section>
                </div>
            </section>
            <button type="button" class="btn btn-success">Enviar</button>
        </div>


        <textarea class="form-control third-available-frame comments" aria-label="With textarea"></textarea>
    </div>
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
                        $cart_id = 1;
                        $cart_list = doDatabaseCartProductsListByCartID($cart_id);
                        if ($cart_list) {
                            foreach ($cart_list as $data) {
                                $cart_product_list_id = $data['id']; // PRODUTO CART
                                $cart_product_id = getDatabaseCartProductProductID($cart_product_list_id); // PRODUTO_ID
                                $obs = getDatabaseCartProductObservation($cart_product_list_id);
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

                                        <small>Observação:
                                            <?php echo ($obs) ? $obs : 'Vazio' ?>
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
                        <div><?php
                        $main_address_id = getDatabaseUserSelectAddressByUserID($in_user_id);
                        echo getDatabaseAddressPublicPlace($main_address_id) . ', ';
                        echo getDatabaseAddressNumber($main_address_id) . '(';
                        echo getDatabaseAddressComplement($main_address_id) . '), ';
                        echo getDatabaseAddressNeighborhood($main_address_id) . ', ';
                        echo getDatabaseAddressCity($main_address_id) . ' - ';
                        echo getDatabaseAddressState($main_address_id);
                        $discount = getDatabaseTicketValue(getDatabaseCartTicketSelectTicketID(getDatabaseCartTicketSelectByCartID($cart_id)));
                        ?></div>
                    </section>
                    <hr>
                    <section id="ticket">
                        <div>
                            <?php echo getDatabaseTicketCode(getDatabaseCartTicketSelectTicketID(getDatabaseCartTicketSelectCartID($cart_id))) ?>
                            <small>(Você terá um desconto de
                                [<?php echo ($discount !== false) ? $discount : 'Você não selecionou nenhum cupom.' ?>])</small>
                        </div>
                    </section>
                    <hr>
                    <section id="pay">
                        <div>Pix
                        </div>
                    </section>
                    <hr>
                    <section id="ticket">
                        <div>
                            <p class="t">Taxa de entrega
                                <label class="v">R$ <?php echo getDatabaseSettingsDeliveryFee(1) ?></label>
                            </p>
                            <p class="t">Desconto de Cupom
                                <label class="v">-<?php
                                if (doGeneralValidationPriceType($discount)) {
                                    echo $discount;
                                } else {
                                    echo 'R$ ' . (int) $discount;
                                }
                                ?></label>
                            </p>
                            <b>
                                <p class="t">Total do Pedido
                                    <label class="v">R$
                                        <?php echo (doCartTotalPrice($cart_id) - doCartTotalPriceDiscount($cart_id)) ?></label>
                                </p>
                            </b>
                        </div>
                    </section>
                </div>

            </div>
            <div class="modal-footer">
                <input name="token" type="text" value="<?php echo addGeneralSecurityToken('tokenCartMainAddress') ?>"
                    hidden>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
            </form>
        </div>
    </div>
</div>


<?php
include_once __DIR__ . '/layout/php/footer.php';
?>