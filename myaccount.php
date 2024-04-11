<?php
include_once __DIR__ . '/layout/php/header.php';
doGeneralSecurityProtect();

?>

<?php
// <!-- INICIO DA VALIDAÇÃO PHP -->
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {


    // EDIT USER

    if (getGeneralSecurityToken('tokenEditUser')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('username', 'name', 'email', 'phone');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                if (doGeneralValidationUserNameFormat($_POST['username']) == false) {
                    $errors[] = "Verificar o campo [usuário], o mesmo possui caracteres invalido. Somente é aceito caracteres alfanuméricos.";
                }


                if (isDatabaseAccountExistUserName($_POST['username'])) {
                    if (isDatabaseAccountUsernameValidation($_POST['username'], $in_user_id) === false) {
                        $errors[] = "Verificar o campo [usuário], pois o mesmo já é utilizado por outro membro.";
                    }
                }

                if (!empty($_POST['password'])) {
                    if (doGeneralValidationPasswordFormat($_POST['password']) == false) {
                        $errors[] = "Verificar o campo [senha], o mesmo possui caracteres invalido. Somente é aceito caracteres [a-z, A-Z, 0-9, !, @, #, $].";
                    }
                    if (strlen($_POST['password']) < 8) {
                        $errors[] = "Verificar o campo [senha], a quantidade de caracteres tem que ser maior que 08.";
                    }

                    if (strlen($_POST['password']) > 20) {
                        $errors[] = "Verificar o campo [senha], a quantidade de caracteres tem que ser maior que 20.";
                    }
                }

                if (doGeneralValidationNameFormat($_POST['name']) == false) {
                    $errors[] = "Verificar o campo [nome], o mesmo possui caracteres invalido. Somente é aceito caracteres alfabético.";
                }

                if (doGeneralValidationEmailFormat($_POST['email']) == false) {
                    $errors[] = "Verificar o campo [email], o mesmo está num formato inelegivel.";
                }

                if (isDatabaseAccountExistEmail($_POST['email'])) {
                    if (isDatabaseAccountEmailValidation($_POST['email'], $in_account_id) === false) {
                        $errors[] = "Verificar o campo [email], pois o mesmo já é utilizado por outro membro.";
                    }
                }

                if (isDatabaseUserExistPhone($_POST['phone'])) {
                    if (isDatabaseUserPhoneValidation($_POST['phone'], $in_user_id) === false) {
                        $errors[] = "Verificar o campo [telefone], pois o mesmo já é utilizado por outro membro.";
                    }
                }

                if (doGeneralValidationPhoneFormat($_POST['phone']) == false) {
                    $errors[] = "Verificar o campo [telefone], ele está com caracteres invalido, somente é aceito números de 0 a 9.";
                }

                if (strlen($_POST['username']) < 5) {
                    $errors[] = "Verificar o campo [usuário], a quantidade de caracteres tem que ser maior que 05.";
                }

                if (strlen($_POST['username']) > 15) {
                    $errors[] = "Verificar o campo [usuário], a quantidade de caracteres tem que ser maior que 15.";
                }


                if (isset($_FILES['avatarImage']) && $_FILES['avatarImage']['size'] > 0) {
                    // Verifica se o arquivo foi enviado sem erros
                    if ($_FILES['avatarImage']['error'] !== UPLOAD_ERR_OK) {
                        $errors[] = 'Erro no upload do arquivo.';
                    }

                    // Verifica se é uma imagem válida
                    $imageInfo = getimagesize($_FILES['avatarImage']['tmp_name']);
                    if (!$imageInfo || !in_array($imageInfo['mime'], array('image/jpeg', 'image/png'))) {
                        $errors[] = 'O arquivo enviado para a foto de perfil não é uma imagem válida.';
                    } elseif ($imageInfo[0] > 1500 || $imageInfo[1] > 1500) {
                        $errors[] = 'A imagem para foto de perfil precisa ser menor que 1500x1500.';
                    }
                }
            }

        }


        if (empty($errors)) {

            $image = True;

            if (isset($_FILES['avatarImage']) && $_FILES['avatarImage']['size'] > 0) {
                $newName = md5($in_user_id . '_' . date("Y_m_d"));
                removerArquivos(__DIR__ . $image_user_dir, $in_user_id);
                $fileInfo = pathinfo($_FILES['avatarImage']['name']);
                $fileExtension = $fileInfo['extension'];

                if (move_uploaded_file($_FILES['avatarImage']['tmp_name'], __DIR__ . $image_user_dir . $newName . '.' . $fileExtension) === false) {
                    $errors[] = 'Falha ao mover o arquivo para o diretório de destino.';
                    $image = false;
                }
            }

            if ($image) {
                $account_update_field = array(
                    'username' => $_POST['username'],
                    'password' => (!empty($_POST['password']) ? md5($_POST['password']) : NULL),
                    'email' => $_POST['email']
                );

                $user_update_field = array(
                    'name' => $_POST['name'],
                    'phone' => $_POST['phone'],
                    'photo' => (isset($newName)) ? $newName : NULL
                );

                doDatabaseAccountUpdate($in_account_id, $account_update_field);
                doDatabaseUserUpdate($in_user_id, $user_update_field);
                doAlertSuccess("As informações de usuário, foram alteradas!!");
            } else {
                $errors[] = "Houve um erro ao encaminhar a imagem para o servidor, tente novamente.";
            }
        }
    }

    // 
    // ADDRESS
    // 

    // ADD ADDRESS
    if (getGeneralSecurityToken('tokenAddAddress')) {
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
    if (getGeneralSecurityToken('tokenEditAddress')) {

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
    if (getGeneralSecurityToken('tokenRemoveAddress')) {

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


<form action="/myaccount" method="post" enctype="multipart/form-data">
    <div id="user_panel">
        <section id="user_photo">
            <img id="avatarImageSelect" src="<?php echo getPathAvatarImage(getDatabaseUserPhotoName($in_user_id)); ?>">
            <div class="custom-file">
                <input type="file" name="avatarImage" class="custom-file-input" id="inputAvatarImage" accept="image/*">
                <label class="custom-file-label" for="inputAvatarImage">Escolha sua imagem.</label>
            </div>
        </section>
        <section id="user_infos">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="username">Usuário</span>
                </div>
                <input type="text" name="username" class="form-control" placeholder="Usuário" aria-label="Usuário"
                    aria-describedby="username" value="<?php echo getDatabaseAccountUserName($in_account_id) ?>">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="password">Senha</span>
                </div>
                <input type="password" name="password" class="form-control" placeholder="Senha" aria-label="Senha"
                    aria-describedby="password">
            </div>
            <hr>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="cName">Nome</span>
                </div>
                <input type="text" name="name" class="form-control" placeholder="Isaque da Silva" aria-label="nome"
                    aria-describedby="cName" value="<?php echo getDatabaseUserName($in_user_id) ?>">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="email">E-mail</span>
                </div>
                <input type="text" name="email" class="form-control" placeholder="isaque.silva@gmail.com"
                    aria-label="email" aria-describedby="email"
                    value="<?php echo getDatabaseAccountEmail($in_account_id) ?>">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="phone">Celular</span>
                </div>
                <input type="text" name="phone" class="form-control" placeholder="(11) 0 0000-0000" aria-label="phone"
                    aria-describedby="phone" value="<?php echo getDatabaseUserPhone($in_user_id) ?>">
            </div>
        </section>
    </div>

    <br>
    <input name="token" type="text" value="<?php echo addGeneralSecurityToken('tokenEditUser') ?>" hidden>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <hr>
</form>

<div id="user_address">
    <table border="1" width="100%">
        <tr>
            <th>#</th>
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
                    <td>#<?php echo $count ?></td>
                    <td><?php echo getDatabaseAddressPublicPlace($user_address_id) ?>,
                        <?php echo getDatabaseAddressNumber($user_address_id) ?>,
                        <?php echo getDatabaseAddressComplement($user_address_id) ?>,
                        <?php echo getDatabaseAddressNeighborhood($user_address_id) ?>,
                        <?php echo getDatabaseAddressCity($user_address_id) ?>-
                        <?php echo getDatabaseAddressState($user_address_id) ?>
                    </td>
                    <td>
                        <a href="/myaccount/address/edit/<?php echo $user_address_id ?>">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a href="/myaccount/address/remove/<?php echo $user_address_id ?>">
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
<br>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addAddressModal">Novo Endereço</button>

<hr>
<div id="user_history">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
                <th>Data</th>
                <th>Opções</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>#</th>
                <th>Data</th>
                <th>Opções</th>
            </tr>
        </tfoot>
        <tbody>
            <tr>
                <td>#255</td>
                <td>08/04/2024 10:30</td>
                <td><i class="fa-solid fa-eye" data-toggle="modal" data-target="#exampleModal"></i></td>
            </tr>
        </tbody>
    </table>
</div>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
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
                        <tr>
                            <td>
                                <div class="cart-product">
                                    <section class="product-photo">
                                        <img src="/layout/images/products/1.jpeg">
                                    </section>
                                    <section class="product-name">
                                        <label>Produto 1</label>
                                    </section>
                                </div>
                            </td>
                            <td>
                                Descrição do Produto:<br>
                                <small>Observações</small><br>
                                [2x]
                                <b>
                                    <label class="v">R$ 10.00</label>
                                </b>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <div>
                    <section id="address">
                        <div>Av. Arcilio Federzoni, 399, Jardim Silvia, Francisco Morato-SP</div>
                    </section>
                    <hr>
                    <section id="ticket">
                        <div>XTY210
                            <small>R$ -25,00</small>
                        </div>
                    </section>
                    <hr>
                    <section id="pay">
                        <div>Pix
                        </div>
                    </section>
                    <hr>
                    <section id="pay">
                        <div>08/04/2024 10:30
                        </div>
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
                    </section>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


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
            <form action="/myaccount" method="POST">
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
                    <input name="token" type="text" value="<?php echo addGeneralSecurityToken('tokenAddAddress') ?>"
                        hidden>
                    <button type="submit" class="btn btn-success">Confirmar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>



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
                            <a href="/myaccount">
                                <button type="button" class="close">&times;</span>
                                </button>
                            </a>
                        </div>
                        <form action="/myaccount" method="POST">
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
                                <input name="token" type="text" value="<?php echo addGeneralSecurityToken('tokenEditAddress') ?>"
                                    hidden>
                                <button type="submit" class="btn btn-success">Confirmar</button>
                                <a href="/myaccount">
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
            header('Location: /myaccount');
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
                            <a href="/myaccount">
                                <button type="button" class="close">&times;</span>
                                </button>
                            </a>
                        </div>
                        <form action="/myaccount" method="POST">
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
                                <input name="token" type="text" value="<?php echo addGeneralSecurityToken('tokenRemoveAddress') ?>"
                                    hidden>
                                <button type="submit" class="btn btn-success">Confirmar</button>
                                <a href="/myaccount">
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
            header('Location: /myaccount');
        }
    }

}
?>

<script>

    function exibirIMG(input, id) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(id).attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }


    $(document).ready(function () {

        $('#inputAvatarImage').change(function () {
            exibirIMG(this, '#avatarImageSelect');
        });

    });

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
</script>
<?php
include_once __DIR__ . '/layout/php/footer.php';
?>