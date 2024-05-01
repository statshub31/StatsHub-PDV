<?php
include_once (__DIR__ . "/engine/init.php");
?>


<style>
    #content-header {
        text-align: center;
    }

    #name {
        font-size: 15px;
        font-weight: bold;
        font-family: monospace;
    }

    #cnpj {
        font-size: 12px;
        font-weight: bold;
        font-family: monospace;
    }

    #type_delivery {
        text-align: center;
    }

    #type_delivery {
        font-size: 14px;
    }

    hr {
        margin: 10px 0px;
    }

    #date {
        font-family: monospace;
        font-size: 14px;
        text-align: center;
    }

    #number-delivery {
        font-family: "Franklin Gothic Medium", "Arial Narrow", Arial, sans-serif;
        font-size: 15px;
        text-align: center;
    }

    .title {
        font-family: "Franklin Gothic Medium", "Arial Narrow", Arial, sans-serif;
        font-size: 15px;
        text-align: center;
    }

    .order {
        font-size: 15px;
    }

    .value {
        position: relative;
        float: right;
        font-weight: bold;
        font-family: monospace;
    }

    .subvalue {
        position: relative;
        float: right;
        font-weight: bold;
        font-family: monospace;
        font-size: 10px !important;
    }

    li, ul, ol {
        margin: 1px;
    }

    li {
        width: 100%;
    }

    .subtopic {
        font-weight: 600;
    }
</style>

<?php

$order_id = 12;
$first_log = doDatabaseRequestOrderLogsFirstLogByOrderID($order_id);
?>

<div id="content">
    <div id="content-header">
        <label id="name">
            <?php echo getDatabaseSettingsInfoTitle(1) ?>
        </label><br>
        <small id="cnpj">CNPJ: 10.0.0.0.0.0.0.0.</small>
    </div>
    <hr>
    <div id="type_delivery">
        <?php echo getDatabaseDeliveryTitle(1) ?>
    </div>
    <hr>
    <div id="date">
        <?php echo doDate(getDatabaseRequestOrderLogCreated($first_log)) . ' às ' . doTime(getDatabaseRequestOrderLogCreated($first_log)) ?>
    </div>
    <hr>
    <div id="number-delivery">
        PEDIDO #<?php echo $order_id ?>
    </div>
    <hr>
    <label class="title">Itens:</label><br>
    <hr>
    <!-- LISTA CARRINHO START -->
    <?php
    $cart_id = getDatabaseRequestOrderCartID($order_id);
    $cart_list = doDatabaseCartProductsListByCartID($cart_id);
    $itens_count = 0;
    if ($cart_list) {
        foreach ($cart_list as $data) {
            $cart_product_list_id = $data['id']; // PRODUTO CART
            $cart_product_id = getDatabaseCartProductProductID($cart_product_list_id); // PRODUTO_ID
            $obs = getDatabaseCartProductObservation($cart_product_list_id);
            $size_id = getDatabaseCartProductPriceID($cart_product_list_id);
            $measure_id = getDatabaseProductSizeMeasureID($size_id);
            $user_id = getDatabaseCartUserID($cart_product_list_id);
            $itens_count += getDatabaseCartProductAmount($cart_product_list_id);
            $main_address_id = getDatabaseRequestOrderAddressIDSelect($order_id);
            $discount = getDatabaseTicketValue(getDatabaseCartTicketSelectTicketID(getDatabaseCartTicketSelectByCartID($cart_id)));
            ?>
            <label class="order">
                (<?php echo getDatabaseCartProductAmount($cart_product_list_id) ?>)
                <?php echo getDatabaseProductName($cart_product_id) ?> -
                <?php echo getDatabaseProductPriceSize($size_id) ?>
                <?php echo getDatabaseMeasureTitle($measure_id) ?>
                <span class="value">R$ <?php echo doCartTotalPriceProduct($cart_product_list_id) ?></span><br>
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
                <?php echo getDatabaseCartProductObservation($cart_product_list_id); ?>
            </label><br>
            <hr>
            <?php
        }
    }
    ?>
    <label class="title">Dados do Cliente:</label><br>
    <label>
        <span class="subtopic">Nome:</span>
        <?php echo getDatabaseUserName($user_id) ?><br>
    </label>
    <label>
        <span class="subtopic">Telefone:</span>
        <?php echo getDatabaseUserPhone($user_id) ?><br>
    </label>
    <label>
        <span class="subtopic">Quantidade de Itens:</span>
        <?php echo $itens_count ?><br>
    </label>
    <label>
        <span class="subtopic">Entrega:</span><br>
        <?php echo getDatabaseAddressPublicPlace($main_address_id) ?>,
        <?php echo getDatabaseAddressNumber($main_address_id) ?>, (
        <?php echo getDatabaseAddressComplement($main_address_id) ?>)
        <?php echo getDatabaseAddressNeighborhood($main_address_id) ?>,
        <?php echo getDatabaseAddressCity($main_address_id) ?> -
        <?php echo getDatabaseAddressState($main_address_id) ?>.
        <br>
    </label>
    <hr>
    <label class="title">Pagamento:</label><br>
    <label>
        <span class="subtopic">Forma de Pagamento:</span><br>
    </label>
    <label>Desconto: <span class="value">
            <small>Usado o cupom [<?php echo getDatabaseTicketCode(getDatabaseCartTicketSelectTicketID(getDatabaseRequestOrderTicketID($cart_id))) ?>] e obtido o desconto de
                [<?php echo ($discount !== false) ? $discount : 'Nenhum desconto selecionado.' ?>]</small>

        </span>
    </label><br>
    <label>Total: <span class="value">R$
            <?php echo (doCartTotalPrice($order_id) - doCartTotalPriceDiscount($order_id)) ?></span>
    </label><br>
    <!-- <hr>
    <label>Troco para: <span class="value">R$ 10.00</span>
    </label><br>
    <label>Troco: <span class="value">R$ 10.00</span>
    </label><br> -->
</div>

<script>
    window.print();
    window.close();
</script>