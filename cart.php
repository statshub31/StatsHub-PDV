<?php
include_once __DIR__ . '/layout/php/header.php';
?>

<?php
// <!-- INICIO DA VALIDAÇÃO PHP -->
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {

    // ADD ADDRESS
    if (getGeneralSecurityToken('tokenCartMainAddress')) {
        if (empty($_POST) === false) {
            if (!isset($_POST['address'])) {
                $errors[] = "Necessário selecionar um endereço.";
            }

            if (isDatabaseAddressExistID($_POST['address']) === false) {
                $errors[] = "Houve um erro ao salvar endereço, reinicie a pagina e tente novamente";
            }

        }


        if (empty($errors)) {
            $address_add_fields = array(
                'user_id' => $in_user_id,
                'address_id' => $_POST['address']
            );

            if (isDatabaseAddressUserSelectByUserID($in_user_id)) {
                $main = getDatabaseAddressUserSelectByUserID($in_user_id);
                doDatabaseAddressUserSelectUpdate($main, $address_add_fields);
            } else {
                doDatabaseAddressUserSelectInsert($address_add_fields);
            }

            doAlertSuccess("As informações foram alteradas com sucesso!!");
            // Coloque o código que deve ser executado após as verificações bem-sucedidas aqui
        }
    }

    
    // REMOVE ADDRESS
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
                    $errors[] = "Houve um erro ao remover o produto, o mesmo não foi encontrado.";
                }
                
                if (doCartProductIDIsUserID($_POST['cart_product_id'], $in_user_id) === false) {
                    $errors[] = "Houve um erro ao remover o produto, o mesmo não foi encontrado.";
                }
            }

        }


        if (empty($errors)) {
            doRemoveCartProductID($_POST['cart_product_id']);
            doAlertSuccess("O produto, foi removido do seu cadastro!!");
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
                    $errors[] = "Houve um erro ao validar o cep, confirme se o mesmo está correto.";
                }

                if (getDatabaseAddressCoutRowByUserID($in_user_id) > 4) {
                    $errors[] = "Você atingiu a capacidade máxima de endereços permitido.";
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
                    $errors[] = "Houve um erro ao validar o cep, confirme se o mesmo está correto.";
                }

                if (doDatabaseAddressValidateUser($in_user_id, $_POST['address_id']) === false) {
                    $errors[] = "Houve um erro ao editar o endereço, o mesmo não é existente para o seu cadastro.";
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
                    $errors[] = "Houve um erro ao remover o endereço, o mesmo não é existente para o seu cadastro.";
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

                            <a href="/cart/product/remove/<?php echo $cart_product_id ?>">
                                <button type="button" class="btn btn-danger">Remover</button>
                            </a>
                            <a href="/complementedit/product/<?php echo $cart_product_id ?>">
                                <button type="button" class="btn btn-primary">Editar</button>
                            </a>
                        </div>
                        <b>
                            <label class="v">R$ <?php echo doCartTotalPriceProduct($cart_product_list_id) ?></label>
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
        $main_address_id = getDatabaseAddressUserSelectAddressByUserID($in_user_id);

        echo getDatabaseAddressPublicPlace($main_address_id) . ', ';
        echo getDatabaseAddressNumber($main_address_id) . '(';
        echo getDatabaseAddressComplement($main_address_id) . '), ';
        echo getDatabaseAddressNeighborhood($main_address_id) . ', ';
        echo getDatabaseAddressCity($main_address_id) . ' - ';
        echo getDatabaseAddressState($main_address_id);
        ?></div>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addressModal">Alterar</button>
    </section>
    <hr>
    <section id="ticket">
        <div>XTY210
            <small>R$ -25,00</small>
        </div>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ticketModal">Alterar</button>
    </section>
    <hr>
    <section id="pay">
        <div>Pix
        </div>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#paysModal">Alterar</button>
    </section>
    <hr>
    <section id="ticket">
        <div>
            <p class="t">Taxa de entrega
                <label class="v">R$ 10.00</label>
            </p>
            <p class="t">Total de desconto
                <label class="v">R$ 10.00</label>
            </p>
            <b>
                <p class="t">Total do Pedido
                    <label class="v">R$ 10.00</label>
                </p>
            </b>
        </div>
        <button type="button" class="btn btn-primary">Confirmar Compra</button>
    </section>
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
            <div class="modal-body">

                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addAddressModal">Novo
                    Endereço</button><br><br>
                <form action="/cart" method="POST">
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
                                        <td><input <?php echo doCheck(getDatabaseAddressUserSelectAddressByUserID($in_user_id), $user_address_id) ?> type="radio" name="address"
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
                            <!-- ENDEREÇO FIM -->
                        </table>
                    </div>
            </div>
            <div class="modal-footer">
                <input name="token" type="text" value="<?php echo addGeneralSecurityToken('tokenCartMainAddress') ?>"
                    hidden>
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
                <h5 class="modal-title" id="exampleModalScrollableTitle">Endereços</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pays -->
<div class="modal fade" id="paysModal" tabindex="-1" role="dialog" aria-labelledby="paysModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Endereços</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>
</div>


<!--  -->
<!-- CART -->
<!--  -->

<!-- Modal Address Edit -->
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
                                Você está prestes a remover o produto [<?php echo getDatabaseProductName($product_id) ?>],  do carrinho, tem certeza?
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