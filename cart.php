<?php
include_once __DIR__ . '/layout/php/header.php';
doGeneralSecurityProtect();
$cart_id = doGeneralSecurityCart();
?>

<?php
// <!-- INICIO DA VALIDAÇÃO PHP -->
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {

    // REQUEST ORDER
    if (getGeneralSecurityToken('tokenRequestOrder')) {

        if (empty($_POST) === false) {
            if (isDatabaseCartExistIDByUserID($in_user_id) === false) {
                $errors[] = "Ocorreu um erro ao processar a compra. Por favor, atualize a página e tente novamente.";
            } else {
                if (getDatabaseCartProductRowCountByCartID($cart_id) <= 0) {
                    $errors[] = "Ocorreu um erro ao processar a compra. Por favor, atualize a página e tente novamente.";
                }
            }

            if(getDatabaseSettingsDeliveryOrderMin(1) > doCartTotalPrice($cart_id)) {
                $errors[] = "O pedido mínimo para o restaurante é de [R$ ".getDatabaseSettingsDeliveryOrderMin(1)."].";
            }

            if (isOpen() === false) {
                $errors[] = "O estabelecimento está fechado no momento.";
            }

            if (getDatabaseUserSelectPayID(getDatabaseUserSelectByUserID($in_user_id)) == getDatabaseSettingsPayMoney(1)) {
                if (empty($_POST['change'])) {
                    $errors[] = "Forneça o valor para troco. Se não for necessário, digite 0.";
                }

                if (doGeneralValidationPriceFormat($_POST['change']) == false) {
                    $errors[] = "No valor de troco, apenas valores numéricos são aceitos.";
                }
            }

            if (isDatabaseCartTicketSelectByCartID($cart_id)) {
                if (isProductPromotionCumulative($cart_id) === false) {
                    $errors[] = "Não foi possível inserir o cupom, pois parece haver um produto em promoção no carrinho.";
                }
            }

            if (isProductUnblocked($cart_id) === false) {
                $errors[] = "Houve um erro. Por favor, remova os produtos do carrinho e refaça a compra.";
            }

            if (isDatabaseCartEnabled($cart_id)) {
                if (isDatabaseCartExistIDByUserID($in_user_id) != $cart_id) {
                    $errors[] = "Houve um erro ao salvar o método de pagamento. Por favor, atualize a página e tente novamente.";
                }
            }

        }


        if (empty($errors)) {
            $cart_update_fields = array(
                'status' => 7
            );

            $main_address = getDatabaseUserSelectAddressByUserID($in_user_id);
            $ticket = getDatabaseCartTicketSelectByCartID($cart_id);
            $pay = getDatabaseUserSelectPayID($cart_id);

            doDatabaseCartUpdate($cart_id, $cart_update_fields);

            $request_order_insert_fields = array(
                'cart_id' => $cart_id,
                'address_id_select' => ($main_address !== false) ? $main_address : NULL,
                'ticket_id_select' => ($ticket !== false) ? $ticket : NULL,
                'pay_id' => ($pay !== false) ? $pay : NULL,
                'status' => 2
            );

            if ($ticket !== false) {
                doDatabaseTicketUsed($ticket);
                $value = getDatabaseTicketAmountUsed($ticket);

                if (($value + 1) > getDatabaseTicketAmount($id_sanitize)) {

                    $ticket_fields = array(
                        'status' => 7,
                    );

                    doDatabaseTicketUpdate($ticket, $ticket_fields);
                }

            }

            if (isset($_POST['change'])) {
                $request_order_insert_fields['change_of'] = (!empty($_POST['change']) ? $_POST['change'] : NULL);
            }

            $request_order_id_insert = doDatabaseRequestOrderInsert($request_order_insert_fields);

            doRequestOrderLogInsert($request_order_id_insert, 2);

            $cart_ticket_update_fields = array(
                'used' => 1
            );

            if (isDatabaseCartTicketSelectByCartID($cart_id))
                doDatabaseCartTicketSelectUpdate($ticket, $cart_ticket_update_fields);

            // STOCK
            doDecreaseStock($request_order_id_insert);

            header('Location: /order/' . $request_order_id_insert);
        }
    }

    // SELECT MAIN METHOD PAY
    if (getGeneralSecurityToken('tokenCartMainPay')) {
        if (empty($_POST) === false) {
            if (!isset($_POST['method_pay'])) {
                $errors[] = "É necessário selecionar um método de pagamento.";
            }

            if (isDatabaseSettingsPayExist($_POST['method_pay']) === false) {
                $errors[] = "Houve um erro ao salvar o método de pagamento. Por favor, atualize a página e tente novamente.";
            }

        }


        if (empty($errors)) {
            $pay_add_fields = array(
                'user_id' => $in_user_id,
                'pay_id' => $_POST['method_pay']
            );

            if (isDatabaseUserSelectByUserID($in_user_id)) {
                $main = getDatabaseUserSelectByUserID($in_user_id);
                doDatabaseUserSelectUpdate($main, $pay_add_fields);
            } else {
                doDatabaseUserSelectInsert($pay_add_fields);
            }

            doAlertSuccess("As informações foram alteradas com sucesso!!");
            // Coloque o código que deve ser executado após as verificações bem-sucedidas aqui
        }
    }


    // SELECT TICKET DISCOUNT
    if (getGeneralSecurityToken('tokenCartTicketSelect')) {
        if (empty($_POST) === false) {
            // Validação se o ID está preenchido
            if (!isset($_POST['ticket_select'])) {
                $errors[] = "É necessário selecionar um cupom.";
            }

            // Validação que se foi selecionado um ID e for diferente de Nenhum, validamos se o cupom realmente existe.
            if ($_POST['ticket_select'] != 0) {
                if (isDatabaseTicketExistID($_POST['ticket_select']) === false) {
                    $errors[] = "Houve um erro ao salvar o cupom. Por favor, atualize a página e tente novamente.";
                }

                if (isProductPromotionCumulative($cart_id) === false) {
                    $errors[] = "Não foi possível inserir o cupom, pois parece haver um produto em promoção no carrinho.";
                }

                if (isDatabaseTicketExpiration($_POST['ticket_select'])) {
                    $errors[] = "O cupom inserido está expirado.";

                    $ticket_fields = array(
                        'status' => 7,
                    );

                    doDatabaseTicketUpdate($ticket, $ticket_fields);
                }

                if (isDatabaseTicketLimit($_POST['ticket_select'])) {
                    $errors[] = "O cupom já atingiu a cota disponível.";

                    $ticket_fields = array(
                        'status' => 7,
                    );

                    doDatabaseTicketUpdate($ticket, $ticket_fields);
                }
            }

            // Validamos se o carrinho existe
            if (isDatabaseCartExistIDByUserID($in_user_id) === false) {
                $errors[] = "Houve um erro ao salvar o cupom. Por favor, atualize a página e tente novamente.";
            }

            // Valida se o cupom já foi usado
            if (isDatabaseCartTicketSelectUsed($_POST['ticket_select'], $in_user_id)) {
                $errors[] = "Houve um erro ao salvar o cupom. Por favor, atualize a página e tente novamente.";
            }


        }


        if (empty($errors)) {
            $cart_id = getDatabaseCartExistIDByUserID($in_user_id);
            $ticket_add_id = array(
                'cart_id' => $cart_id,
                'user_id' => $in_user_id,
                'ticket_id' => ($_POST['ticket_select'] != 0) ? $_POST['ticket_select'] : NULL
            );

            if ($_POST['ticket_select'] == 0) {
                if (isDatabaseCartTicketSelectByCartID($cart_id)) {
                    doDatabaseCartTicketSelectDeleteByCartID($cart_id);
                }
            } else {
                if (isDatabaseCartTicketSelectByCartID($cart_id)) {
                    $main = getDatabaseCartTicketSelectByCartID($cart_id);
                    doDatabaseCartTicketSelectUpdate($main, $ticket_add_id);
                } else {
                    doDatabaseCartTicketSelectInsert($ticket_add_id);
                }
            }

            doAlertSuccess("Alterações de cupom efetuado com sucesso!!");
        }
    }

    // SELECT MAIN ADDRESS
    if (getGeneralSecurityToken('tokenCartMainAddress')) {
        if (empty($_POST) === false) {
            if (!isset($_POST['address'])) {
                $errors[] = "É necessário selecionar um endereço.";
            }

            if ($_POST['address'] != 0) {
                if (isDatabaseAddressExistID($_POST['address']) === false) {
                    $errors[] = "Houve um erro ao salvar o endereço. Por favor, atualize a página e tente novamente.";
                }

                if (doDatabaseAddressValidateUser($in_user_id, $_POST['address']) === false) {
                    $errors[] = "Houve um erro ao salvar o endereço. O endereço não foi encontrado.";
                }
            }

        }


        if (empty($errors)) {
            $address_add_fields = array(
                'user_id' => $in_user_id,
                'address_id' => ($_POST['address'] != 0) ? $_POST['address'] : NULL
            );

            if (isDatabaseUserSelectByUserID($in_user_id)) {
                $main = getDatabaseUserSelectByUserID($in_user_id);
                doDatabaseUserSelectUpdate($main, $address_add_fields, false, true);
            } else {
                doDatabaseUserSelectInsert($address_add_fields);
            }

            doAlertSuccess("As informações foram alteradas com sucesso!!");
        }
    }


    // REMOVE CART PRODUCT
    if (getGeneralSecurityToken('tokenCartRemoveProduct')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('cart_product_id');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                if (isDatabaseCartProductExistID($_POST['cart_product_id']) === false) {
                    $errors[] = "Houve um erro ao remover o produto. O produto não foi encontrado.";
                }

                if (doCartProductIDIsUserID($_POST['cart_product_id'], $in_user_id) === false) {
                    $errors[] = "Houve um erro ao remover o produto. O produto não foi encontrado.";
                }
            }

        }


        if (empty($errors)) {
            doRemoveCartProductID($_POST['cart_product_id']);
            doGeneralSecurityCart();
            doAlertSuccess("O produto, foi removido do seu carrinho!!");
            // Coloque o código que deve ser executado após as verificações bem-sucedidas aqui
        }
    }


    // 
    // ADDRESS
    // 



    // ADD ADDRESS
    if (getGeneralSecurityToken('tokenCartAddAddress')) {
        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('zip_code', 'publicplace', 'neighborhood', 'number', 'city');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                if (is_numeric(sanitizeSpecial($_POST['zip_code'])) === false) {
                    $errors[] = "Houve um erro ao validar o CEP. Por favor, confirme se o mesmo está correto.";
                }

                if (getDatabaseAddressCoutRowByUserID($in_user_id) > 4) {
                    $errors[] = "Você atingiu a capacidade máxima de endereços permitida.";
                }
            }
        }


        if (empty($errors)) {
            $address_add_fields = array(
                'user_id' => $in_user_id,
                'zip_code' => $_POST['zip_code'],
                'publicplace' => $_POST['publicplace'],
                'neighborhood' => $_POST['neighborhood'],
                'number' => $_POST['number'],
                'complement' => (!empty($_POST['complement']) ? $_POST['complement'] : NULL),
                'city' => $_POST['city'],
                'state' => $_POST['state']
            );

            doDatabaseAddressInsert($address_add_fields);
            doAlertSuccess("As informações foram alteradas com sucesso!!");
            // Coloque o código que deve ser executado após as verificações bem-sucedidas aqui
        }
    }


    // EDIT ADDRESS
    if (getGeneralSecurityToken('tokenCartEditAddress')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('address_id', 'zip_code', 'publicplace', 'neighborhood', 'number', 'city');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                if (is_numeric(sanitizeSpecial($_POST['zip_code'])) === false) {
                    $errors[] = "Houve um erro ao validar o CEP. Por favor, confirme se o mesmo está correto.";
                }

                if (doDatabaseAddressValidateUser($in_user_id, $_POST['address_id']) === false) {
                    $errors[] = "Houve um erro ao editar o endereço. O endereço não existe no seu cadastro.";
                }
            }

        }


        if (empty($errors)) {
            $address_add_fields = array(
                'user_id' => $in_user_id,
                'zip_code' => $_POST['zip_code'],
                'publicplace' => $_POST['publicplace'],
                'neighborhood' => $_POST['neighborhood'],
                'number' => $_POST['number'],
                'complement' => (!empty($_POST['complement']) ? $_POST['complement'] : NULL),
                'city' => $_POST['city'],
                'state' => $_POST['state']
            );

            doDatabaseAddressUpdate($_POST['address_id'], $address_add_fields);
            doAlertSuccess("O Endereço, foi cadastrado!!");
            // Coloque o código que deve ser executado após as verificações bem-sucedidas aqui
        }
    }

    // REMOVE ADDRESS
    if (getGeneralSecurityToken('tokenCartRemoveAddress')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('address_id');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                if (doDatabaseAddressValidateUser($in_user_id, $_POST['address_id']) === false) {
                    $errors[] = "Houve um erro ao remover o endereço. O endereço não existe no seu cadastro.";
                }
            }

        }


        if (empty($errors)) {
            doDatabaseAddressDelete($_POST['address_id']);
            doAlertSuccess("O Endereço, foi removido do seu cadastro!!");
            // Coloque o código que deve ser executado após as verificações bem-sucedidas aqui
        }
    }



    if (empty($errors) === false) {
        header("HTTP/1.1 401 Not Found");
        echo doAlertError($errors);
    }
}


// <!-- FINAL DA VALIDAÇÃO PHP -->
?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/0.9.0/jquery.mask.min.js"
    integrity="sha512-oJCa6FS2+zO3EitUSj+xeiEN9UTr+AjqlBZO58OPadb2RfqwxHpjTU8ckIC8F4nKvom7iru2s8Jwdo+Z8zm0Vg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).ready(function () {
        // Adiciona um evento de input ao campo
        $(".priceFormat").on('input', function () {
            // Obtém o valor atual do campo
            var inputValue = $(this).val();

            // Remove todos os caracteres não numéricos
            var numericValue = inputValue.replace(/[^0-9]/g, '');

            // Verifica se o valor numérico não está vazio
            if (numericValue !== '') {
                // Converte para número e formata com duas casas decimais
                var formattedValue = (parseFloat(numericValue) / 100).toFixed(2);

                // Define o valor formatado de volta no campo
                $(this).val(formattedValue);
            }
        });
    });
</script>
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
        $cart_id = getDatabaseCartExistIDByUserID($in_user_id);
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
                                <img src="<?php echo getPathProductImage(getDatabaseProductPhotoName($cart_product_id)) ?>">
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
                        <div class="options">

                            <a href="/cart/product/remove/<?php echo $cart_product_list_id ?>">
                                <button type="button" class="btn btn-danger">Remover</button>
                            </a>
                            <a href="/complementedit/product/<?php echo $cart_product_list_id ?>">
                                <button type="button" class="btn btn-primary">Editar</button>
                            </a>
                        </div>
                        <b>
                            <label class="v">R$
                                <?php echo sprintf("%.2f", doCartTotalPriceProduct($cart_product_list_id)) ?></label>
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
<hr>

<div>
    <section id="address">
        <div><?php
        $discount = getDatabaseTicketValue(getDatabaseCartTicketSelectTicketID(getDatabaseCartTicketSelectByCartID($cart_id)));
        echoUserMainAddress($in_user_id);
        ?></div>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addressModal">Alterar</button>
    </section>
    <hr>
    <section id="ticket">
        <div>
            <?php echo getDatabaseTicketCode(getDatabaseCartTicketSelectTicketID(getDatabaseCartTicketSelectCartID($cart_id))) ?>
            <small><?php echo ($discount !== false) ? 'Você terá um desconto de [' . doTypeDiscount($discount) . ']' : 'Você não selecionou nenhum cupom.' ?></small>
        </div>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ticketModal">Alterar</button>
    </section>
    <hr>
    <form action="/cart" method="POST">
        <section id="pay">
            <div>
                <?php echo getDatabaseSettingsPayType(getDatabaseUserSelectPayID(getDatabaseUserSelectByUserID($in_user_id))); ?>
                <?php
                if (getDatabaseUserSelectPayID(getDatabaseUserSelectByUserID($in_user_id)) == getDatabaseSettingsPayMoney(1)) { ?>
                    <div class="form-group">
                        <label for="change">Troco para:</label>
                        <input type="text" name="change" class="form-control priceFormat" id="change" value="" required>
                    </div>
                <?php }
                ?>
            </div>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#paysModal">Alterar</button>
        </section>
        <hr>
        <section id="ticket">
            <div>
                <p class="t">Taxa de entrega
                    <label class="v">R$ <?php echo getDatabaseSettingsDeliveryFee(1) ?></label>
                </p>
                <p class="t">Desconto de Cupom
                    <label class="v">-<?php echo doTypeDiscount($discount) ?></label>
                </p>
                <b>
                    <p class="t">Total do Pedido
                        <label class="v">R$
                            <?php echo sprintf("%.2f", (doCartTotalPrice($cart_id) - doCartTotalPriceDiscount($cart_id))) ?></label>
                    </p>
                </b>
            </div>
            <input name="token" type="text" value="<?php echo addGeneralSecurityToken('tokenRequestOrder') ?>" hidden>
            <button type="submit" class="btn btn-primary">Confirmar Compra</button>
        </section>
    </form>
</div>


<!-- Modal -->
<div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="addressModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Endereços</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="/cart" method="POST">
                <div class="modal-body">

                    <button type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#addAddressModal">Novo
                        Endereço</button><br><br>
                    <div id="user_address">
                        <table border="1" width="100%">
                            <tr>
                                <th></th>
                                <th>Endereço</th>
                                <th>Opções</th>
                            </tr>
                            <!-- ENDEREÇO INICIO -->
                            <?php
                            $address_list = doDatabaseAddressListByUserID($in_user_id);

                            if ($address_list) {
                                $count = 0;
                                foreach ($address_list as $data) {
                                    $user_address_id = $data['id'];
                                    ++$count;
                                    ?>
                                    <tr>
                                        <td><input <?php echo doCheck(getDatabaseUserSelectAddressByUserID($in_user_id), $user_address_id) ?> type="radio" name="address"
                                                value="<?php echo $user_address_id ?>"></td>
                                        <td><?php echo getDatabaseAddressPublicPlace($user_address_id) ?>,
                                            <?php echo getDatabaseAddressNumber($user_address_id) ?>,
                                            <?php echo getDatabaseAddressComplement($user_address_id) ?>,
                                            <?php echo getDatabaseAddressNeighborhood($user_address_id) ?>,
                                            <?php echo getDatabaseAddressCity($user_address_id) ?>-
                                            <?php echo getDatabaseAddressState($user_address_id) ?>
                                        </td>
                                        <td>
                                            <a href="/cart/address/edit/<?php echo $user_address_id ?>">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="/cart/address/remove/<?php echo $user_address_id ?>">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php

                                }
                            } else { ?>

                                <tr>
                                    <td>#</td>
                                    <td>Não existe nenhum endereço cadastrado.
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>

                            <tr>
                                <td><input <?php echo doCheck(getDatabaseUserSelectAddressByUserID($in_user_id), NULL) ?> type="radio" name="address" value="0"></td>
                                <td colspan="2">Retirada no Local
                                </td>
                            </tr>
                            <!-- ENDEREÇO FIM -->
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <input name="token" type="text"
                        value="<?php echo addGeneralSecurityToken('tokenCartMainAddress') ?>" hidden>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Ticket -->
<div class="modal fade" id="ticketModal" tabindex="-1" role="dialog" aria-labelledby="ticketModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Cupons</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="/cart" method="POST">
                <div class="modal-body">
                    <div id="user_pay">
                        <table border="1" width="100%">
                            <tr>
                                <th></th>
                                <th>Código</th>
                                <th>Valor de Desconto</th>
                                <th>Descrição</th>
                            </tr>
                            <!-- PAGAMENTO INICIO -->
                            <?php
                            $ticket_list = doDatabaseTicketsListByStatus();

                            if ($ticket_list) {
                                foreach ($ticket_list as $dataTicket) {
                                    $ticket_list_id = $dataTicket['id'];
                                    if (isDatabaseCartTicketSelectUsed($ticket_list_id, $in_user_id) === false) {
                                        ?>
                                        <tr>
                                            <td><input <?php echo doCheck(isDatabaseCartTicketSelectNotUsed($ticket_list_id, $cart_id), 1) ?> type="radio" name="ticket_select"
                                                    value="<?php echo $ticket_list_id ?>" />
                                            </td>
                                            <td><?php echo getDatabaseTicketCode($ticket_list_id) ?></td>
                                            <td><?php echo getDatabaseTicketValue($ticket_list_id) ?></td>
                                            <td>Utilize este cupom, para receber o desconto de
                                                [<?php echo getDatabaseTicketValue($ticket_list_id) ?>]</td>
                                        </tr>
                                        <?php
                                    }
                                }
                            } else { ?>
                                <tr>
                                    <td colspan="3">Não existe cupom disponivel.</td>
                                </tr><?php
                            } ?>
                            <!-- PAGAMENTO FIM -->

                            <tr>
                                <td><input type="radio" name="ticket_select" value="0" />
                                </td>
                                <td colspan="3">Nenhum</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <input name="token" type="text"
                        value="<?php echo addGeneralSecurityToken('tokenCartTicketSelect') ?>" hidden>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Pays -->
<div class="modal fade" id="paysModal" tabindex="-1" role="dialog" aria-labelledby="paysModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Escolha o Metodo de Pagamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="/cart" method="POST">
                <div class="modal-body">
                    <div id="user_pay">
                        <table border="1" width="100%">
                            <tr>
                                <th colspan="3">
                                    <center>
                                        Pagamento Presencial
                                    </center>
                                </th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>Tipo</th>
                                <th>Chave</th>
                            </tr>
                            <!-- PAGAMENTO INICIO -->
                            <?php
                            $pay_list = doDatabaseSettingsPayListByStatus();

                            if ($pay_list) {
                                foreach ($pay_list as $dataPay) {
                                    $pay_list_id = $dataPay['id'];
                                    ?>
                                    <tr>
                                        <td><input <?php echo doCheck(getDatabaseUserSelectPayID(getDatabaseUserSelectByUserID($in_user_id)), $pay_list_id) ?> type="radio" name="method_pay"
                                                value="<?php echo $pay_list_id ?>" /></td>
                                        <td><?php echo getDatabaseSettingsPayType($pay_list_id) ?></td>
                                        <td><?php echo getDatabaseSettingsPayKey($pay_list_id) ?></td>
                                    </tr>
                                    <?php
                                }
                            } else { ?>
                                <tr>
                                    <td colspan="3">Não existe métodos de pagamento liberado.</td>
                                </tr><?php
                            } ?>
                            <!-- PAGAMENTO FIM -->
                            <!-- 
                            <tr>
                                <th colspan="3">
                                    <center>Outras opções</center>
                                </th>
                            </tr>
                            <tr>
                                <td><input type="radio" name="method_pay" value="" /></td>
                                <td colspan="2">Retirada no Estabalecimento</td>
                            </tr> -->
                            <!-- <tr>
                                <th colspan="3">
                                    <center>Pagamento Online</center>
                                </th>
                            </tr> -->
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <input name="token" type="text" value="<?php echo addGeneralSecurityToken('tokenCartMainPay') ?>"
                        hidden>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!--  -->
<!-- CART -->
<!--  -->

<!-- REMOVER PRODUCT -->
<?php
if (isCampanhaInURL("product")) {
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
                            <a href="/cart">
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
                                <a href="/cart">
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


<!--  -->
<!-- ENDEREÇO -->
<!--  -->


<!-- Modal Address Edit -->
<?php
if (isCampanhaInURL("address")) {
    if (isCampanhaInURL("edit")) {
        $edit_address_id = getURLLastParam();
        if (doDatabaseAddressValidateUser($in_user_id, $edit_address_id)) {
            ?>

            <div class="modal fade show" style="padding-right: 19px; display: block;" id="editAddressModal" tabindex="-1"
                role="dialog" aria-labelledby="editAddressModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editAddressModalLabel">Editar Endereço</h5>
                            <a href="/cart">
                                <button type="button" class="close">&times;</span>
                                </button>
                            </a>
                        </div>
                        <form action="/cart" method="POST">
                            <div class="modal-body">

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">CEP</span>
                                    </div>
                                    <input type="text" id="edit_zip_code" name="zip_code" class="form-control"
                                        placeholder="07900000" aria-label="cep" aria-describedby="zip_code"
                                        oninput="consultarCEPEdit()"
                                        value="<?php echo getDatabaseAddressZipCode($edit_address_id) ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rua/Avenida</span>
                                    </div>
                                    <input type="text" id="edit_publicplace" name="publicplace" class="form-control"
                                        placeholder="Rua Silvio" aria-label="publicplace" aria-describedby="publicplace"
                                        value="<?php echo getDatabaseAddressPublicPlace($edit_address_id) ?>" hidden>

                                    <input type="text" id="edit_publicplacev2" class="form-control" placeholder="Rua Silvio"
                                        aria-label="publicplace" aria-describedby="publicplacev2"
                                        value="<?php echo getDatabaseAddressPublicPlace($edit_address_id) ?>" disabled>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Bairro</span>
                                    </div>
                                    <input type="text" id="edit_neighborhood" name="neighborhood" class="form-control"
                                        placeholder="Silvio" aria-label="Bairro" aria-describedby="neighborhood"
                                        value="<?php echo getDatabaseAddressNeighborhood($edit_address_id) ?>" hidden>
                                    <input type="text" id="edit_neighborhoodv2" class="form-control" placeholder="Silvio"
                                        aria-label="Bairro" aria-describedby="neighborhoodv2"
                                        value="<?php echo getDatabaseAddressNeighborhood($edit_address_id) ?>" disabled>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Número</span>
                                    </div>
                                    <input type="text" id="edit_number" name="number" class="form-control" placeholder="255"
                                        aria-label="number" aria-describedby="number"
                                        value="<?php echo getDatabaseAddressNumber($edit_address_id) ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Complemento</span>
                                    </div>
                                    <input type="text" id="edit_complement" name="complement" class="form-control"
                                        placeholder="Ao lado da padaria" aria-label="complement" aria-describedby="complement"
                                        value="<?php echo getDatabaseAddressComplement($edit_address_id) ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Cidade</span>
                                    </div>
                                    <input type="text" id="edit_city" name="city" class="form-control"
                                        placeholder="Francisco Morato" aria-label="city" aria-describedby="city"
                                        value="<?php echo getDatabaseAddressCity($edit_address_id) ?>" hidden>
                                    <input type="text" id="edit_cityv2" class="form-control" placeholder="Francisco Morato"
                                        aria-label="city" aria-describedby="cityv2"
                                        value="<?php echo getDatabaseAddressCity($edit_address_id) ?>" disabled>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Estado</span>
                                    </div>
                                    <input type="text" id="edit_state" name="state" class="form-control" placeholder="SP"
                                        aria-label="state" aria-describedby="state"
                                        value="<?php echo getDatabaseAddressState($edit_address_id) ?>" hidden>
                                    <input type="text" id="edit_statev2" class="form-control" placeholder="SP" aria-label="state"
                                        aria-describedby="statev2" value="<?php echo getDatabaseAddressState($edit_address_id) ?>"
                                        disabled>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input name="address_id" type="text" value="<?php echo $edit_address_id ?>" hidden>
                                <input name="token" type="text"
                                    value="<?php echo addGeneralSecurityToken('tokenCartEditAddress') ?>" hidden>
                                <button type="submit" class="btn btn-success">Confirmar</button>
                                <a href="/cart">
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

    if (isCampanhaInURL("remove")) {
        $edit_address_id = getURLLastParam();
        if (doDatabaseAddressValidateUser($in_user_id, $edit_address_id)) {
            ?>

            <div class="modal fade show" style="padding-right: 19px; display: block;" id="editAddressModal" tabindex="-1"
                role="dialog" aria-labelledby="editAddressModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editAddressModalLabel">Remover Endereço</h5>
                            <a href="/cart">
                                <button type="button" class="close">&times;</span>
                                </button>
                            </a>
                        </div>
                        <form action="/cart" method="POST">
                            <div class="modal-body">
                                Você está prestes a remover o endereço, [
                                <?php echo getDatabaseAddressZipCode($edit_address_id) ?>,
                                <?php echo getDatabaseAddressPublicPlace($edit_address_id) ?>,
                                <?php echo getDatabaseAddressNeighborhood($edit_address_id) ?>,
                                <?php echo getDatabaseAddressNumber($edit_address_id) ?>,
                                <?php echo getDatabaseAddressComplement($edit_address_id) ?>,
                                <?php echo getDatabaseAddressCity($edit_address_id) ?>-
                                <?php echo getDatabaseAddressState($edit_address_id) ?>
                                ], tem certeza?
                            </div>
                            <div class="modal-footer">
                                <input name="address_id" type="text" value="<?php echo $edit_address_id ?>" hidden>
                                <input name="token" type="text"
                                    value="<?php echo addGeneralSecurityToken('tokenCartRemoveAddress') ?>" hidden>
                                <button type="submit" class="btn btn-success">Confirmar</button>
                                <a href="/cart">
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

<!-- Modal Address Add -->
<div class="modal fade" id="addAddressModal" tabindex="-1" role="dialog" aria-labelledby="addAddressModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAddressModalLabel">Adicionar Endereço</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/cart" method="POST">
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">CEP</span>
                        </div>
                        <input type="text" id="zip_code" name="zip_code" class="form-control" placeholder="07900000"
                            aria-label="cep" aria-describedby="zip_code" oninput="consultarCEP()">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rua/Avenida</span>
                        </div>
                        <input type="text" id="publicplace" name="publicplace" class="form-control"
                            placeholder="Rua Silvio" aria-label="publicplace" aria-describedby="publicplace" hidden>
                        <input type="text" id="publicplacev2" class="form-control" placeholder="Rua Silvio"
                            aria-label="publicplace" aria-describedby="publicplacev2" disabled>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Bairro</span>
                        </div>
                        <input type="text" id="neighborhood" name="neighborhood" class="form-control"
                            placeholder="Silvio" aria-label="Bairro" aria-describedby="neighborhood" hidden>
                        <input type="text" id="neighborhoodv2" class="form-control" placeholder="Silvio"
                            aria-label="Bairro" aria-describedby="neighborhoodv2" disabled>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Número</span>
                        </div>
                        <input type="text" id="number" name="number" class="form-control" placeholder="255"
                            aria-label="number" aria-describedby="number">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Complemento</span>
                        </div>
                        <input type="text" id="complement" name="complement" class="form-control"
                            placeholder="Ao lado da padaria" aria-label="complement" aria-describedby="complement">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Cidade</span>
                        </div>
                        <input type="text" id="city" name="city" class="form-control" placeholder="Francisco Morato"
                            aria-label="city" aria-describedby="city" hidden>
                        <input type="text" id="cityv2" class="form-control" placeholder="Francisco Morato"
                            aria-label="city" aria-describedby="cityv2" disabled>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Estado</span>
                        </div>
                        <input type="text" id="state" name="state" class="form-control" placeholder="SP"
                            aria-label="state" aria-describedby="state" hidden>
                        <input type="text" id="statev2" class="form-control" placeholder="SP" aria-label="state"
                            aria-describedby="statev2" disabled>
                    </div>
                </div>
                <div class="modal-footer">
                    <input name="token" type="text" value="<?php echo addGeneralSecurityToken('tokenCartAddAddress') ?>"
                        hidden>
                    <button type="submit" class="btn btn-success">Confirmar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>

    function consultarCEP() {
        const cepInput = document.getElementById('zip_code').value;

        // Verifica se o CEP tem 8 caracteres antes de fazer a consulta
        if (cepInput.length === 8) {
            const url = `https://viacep.com.br/ws/${cepInput}/json/`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    preencherCampos(data);
                })
                .catch(error => console.error("Erro ao obter dados de endereço:", error));
        }
    }

    function consultarCEPEdit() {
        const cepInput = document.getElementById('edit_zip_code').value;

        // Verifica se o CEP tem 8 caracteres antes de fazer a consulta
        if (cepInput.length === 8) {
            const url = `https://viacep.com.br/ws/${cepInput}/json/`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    preencherCamposEdit(data);
                })
                .catch(error => console.error("Erro ao obter dados de endereço:", error));
        }
    }

    function preencherCampos(data) {
        document.getElementById('publicplace').value = data.logradouro || '';
        document.getElementById('neighborhood').value = data.bairro || '';
        document.getElementById('city').value = data.localidade || '';
        document.getElementById('state').value = data.uf || '';

        document.getElementById('publicplacev2').value = data.logradouro || '';
        document.getElementById('neighborhoodv2').value = data.bairro || '';
        document.getElementById('cityv2').value = data.localidade || '';
        document.getElementById('statev2').value = data.uf || '';
    }

    function preencherCamposEdit(data) {
        document.getElementById('edit_publicplace').value = data.logradouro || '';
        document.getElementById('edit_neighborhood').value = data.bairro || '';
        document.getElementById('edit_city').value = data.localidade || '';
        document.getElementById('edit_state').value = data.uf || '';

        document.getElementById('edit_publicplacev2').value = data.logradouro || '';
        document.getElementById('edit_neighborhoodv2').value = data.bairro || '';
        document.getElementById('edit_cityv2').value = data.localidade || '';
        document.getElementById('edit_statev2').value = data.uf || '';
    }

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
        });
    });
</script>

<?php
include_once __DIR__ . '/layout/php/footer.php';
?>