<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));

$order_id = getURLLastParam();
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Histórico de Pedidos</h1>
    <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" disabled><i
            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
</div>

<br>
<div id="dashboard">
    <div class="row">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Endereço</th>
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
                    <th>Status</th>
                    <th>Opções</th>
                </tr>
            </tfoot>
            <tbody>
                <!-- ORDER LIST START -->
                <?php
                $order_list = doDatabaseRequestOrdersListAll();

                if ($order_list) {
                    foreach ($order_list as $orderData) {
                        $order_list_id = $orderData['id'];
                        $order_cart_id = getDatabaseRequestOrderCartID($order_list_id);
                        $order_user_id = getDatabaseCartUserID($order_cart_id);
                        $order_first_log_id = doDatabaseRequestOrderLogsFirstLogByOrderID($order_list_id);
                        $order_last_log_id = doDatabaseRequestOrderLogsLastLogByOrderID($order_list_id);
                        $order_log_last_id = getDatabaseRequestOrderLogStatusDelivery($order_last_log_id);
                        $order_main_address_id = getDatabaseRequestOrderAddressIDSelect($order_list_id);
                        $percentual = getOrderProgressBarValue($order_list_id);
                        ?>
                        <tr>
                            <td>#<?php echo $order_list_id ?></td>
                            <td>
                                <a target="on_blank" href="users/view/user/<?php echo $order_user_id ?>">
                                    <?php echo getDatabaseUserName($order_user_id) ?>
                                </a>
                            </td>
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
                                <?php echo getDatabaseStatusDeliveryTitle($order_log_last_id) ?>
                            </td>
                            <td>
                                <a href="/panel/orders/order/view/<?php echo $order_list_id ?>">
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
                                <a href="/panel/orders">
                                    <button type="button" class="close" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </a>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                                                        <b>R$ <?php echo sprintf("%.2f", doCartTotalPriceProduct($cart_product_list_id)) ?></b>
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
                                        $log_list = doDatabaseRequestOrderLogsListByOrderID($order_id);
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
                                            <b>Cupom:</b><br>
                                            <?php echo getDatabaseTicketCode(getDatabaseCartTicketSelectTicketID(getDatabaseCartTicketSelectCartID($cart_id))) ?>
                                            <small><?php echo ($discount !== false) ? 'O cliente teve um desconto de [' . doTypeDiscount($discount) . ']' : 'O cliente não selecionou nenhum cupom.' ?></small>
                                        </div>
                                    </section>
                                    <hr>
                                    <section id="pay">
                                        <div>
                                            <b>Pagamento no: </b><?php echo getDatabaseSettingsPayType(getDatabaseRequestOrderPayIDSelect($order_select_id)); ?>
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
                                <a href="/panel/orders">
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
            header('Location: /panel/orders');
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
            }
        });
    });

</script>



<?php
include_once (realpath(__DIR__ . "/layout/php/footer.php"));
?>