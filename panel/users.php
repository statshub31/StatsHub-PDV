<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));
getGeneralSecurityAttendantAccess();

?>


<?php
// <!-- INICIO DA VALIDAÇÃO PHP -->
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {


    // REMOVE USER

    if (getGeneralSecurityToken('tokenRemoveUser')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('user_select_id');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                if (isDatabaseUserExistID($_POST['user_select_id']) === false) {
                    $errors[] = "Houve um erro ao processar a solicitação, o usuário selecionado não existe.";
                }

                if (isGeneralSecurityManagerAccess() === false) {
                    $errors[] = "É obrigatório ter um cargo igual ou superior ao de gestor, para executar está ação.";
                }
            }

        }


        if (empty($errors)) {
            $account_select_id = getDatabaseUserAccountID($_POST['user_select_id']);
            $block_status = (getDatabaseAccountBlock($account_select_id) == 0) ? 1 : 0;

            $account_update_field = array(
                'block' => $block_status
            );

            doDatabaseAccountUpdate($account_select_id, $account_update_field);

            if ($block_status == 0)
                doAlertSuccess("O usuário foi bloqueado da plataforma.");

            if ($block_status == 1)
                doAlertSuccess("O usuário foi desbloqueado da plataforma.");

        }
    }


    if (getGeneralSecurityToken('tokenUserUpdate')) {


        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('username', 'name', 'email', 'phone');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {

                if (isGeneralSecurityManagerAccess() === false) {
                    $errors[] = "É obrigatório ter um cargo igual ou superior ao de gestor, para executar está ação.";
                }

                if (isDatabaseUserExistID($_POST['user_select_id']) === false) {
                    $errors[] = "Houve um erro ao aplicar as alterações, o usuário é inexistente.";
                }

                if (doGeneralValidationUserNameFormat($_POST['username']) == false) {
                    $errors[] = "Verificar o campo [usuário], o mesmo possui caracteres invalido. Somente é aceito caracteres alfanuméricos.";
                }

                if (isDatabaseAccountExistUserName($_POST['username'])) {
                    if (isDatabaseAccountUsernameValidation($_POST['username'], $_POST['user_select_id']) === false) {
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
                    if (isDatabaseAccountEmailValidation($_POST['email'], $_POST['user_select_id']) === false) {
                        $errors[] = "Verificar o campo [email], pois o mesmo já é utilizado por outro membro.";
                    }
                }

                if (isDatabaseUserExistPhone($_POST['phone'])) {
                    if (isDatabaseUserPhoneValidation($_POST['phone'], $_POST['user_select_id']) === false) {
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

            }

        }


        if (empty($errors)) {

            $user_select_id = $_POST['user_select_id'];
            $account_select_id = getDatabaseUserAccountID($user_select_id);

            $account_update_field = array(
                'username' => $_POST['username'],
                'password' => (!empty($_POST['password']) ? md5($_POST['password']) : NULL),
                'email' => $_POST['email'],
                'group_id' => $_POST['group_id']
            );

            $user_update_field = array(
                'name' => $_POST['name'],
                'phone' => $_POST['phone']
            );

            doDatabaseAccountUpdate($account_select_id, $account_update_field);
            doDatabaseUserUpdate($user_select_id, $user_update_field);
            doAlertSuccess("As informações do usuário, foram alteradas!!");
        }


    }



    if (empty($errors) === false) {
        header("HTTP/1.1 401 Not Found");
        echo doAlertError($errors);
    }
}


// <!-- FINAL DA VALIDAÇÃO PHP -->
?>


<h1 class="h3 mb-0 text-gray-800">Usuários</h1>
<a href="/panel/useradd">
    <button type="submit" class="btn btn-primary">Novo Usuário</button>
</a>
<hr>
<link href="/layout/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>Foto</th>
            <th>Nome</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Foto</th>
            <th>Nome</th>
            <th>Opções</th>
        </tr>
    </tfoot>
    <tbody>
        <!-- USUÁRIOS -->
        <?php
        $users_list = doDatabaseUsersList();

        if ($users_list) {
            foreach ($users_list as $data) {
                $user_id = $data['id'];
                ?>
                <tr>
                    <td>
                        <section class="users_photo">
                            <img src="<?php echo getPathAvatarImage(getDatabaseUserPhotoName($user_id)); ?>"></img>
                        </section>
                    </td>
                    <td> <?php echo getDatabaseUserName($user_id) ?>
                    </td>
                    <td>
                        <a href="/panel/users/edit/user/<?php echo $user_id ?>">
                            <i class="fa fa-edit" aria-hidden="true"></i>
                        </a>
                        <a href="/panel/users/block/user/<?php echo $user_id ?>">
                            <!-- ICONE -->
                            <?php
                            if (getDatabaseAccountBlock(getDatabaseUserAccountID($user_id)) == 1) {
                                ?>
                                <i class="fa fa-lock" aria-hidden="true"></i>
                                <?php
                            }
                            if (getDatabaseAccountBlock(getDatabaseUserAccountID($user_id)) == 0) {
                                ?>
                                <i class="fa fa-unlock" aria-hidden="true"></i>
                                <?php
                            }
                            ?>
                            <!-- ICONE FIM -->
                        </a>
                        <a href="/panel/users/view/user/<?php echo $user_id ?>">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
        <!-- USUÁRIOS -->
    </tbody>
</table>


<?php
if (isCampanhaInURL("user")) {

    // <!-- Modal View -->
    if (isCampanhaInURL("view")) {
        $user_select_id = getURLLastParam();
        if (isDatabaseUserExistID($user_select_id)) {
            $account_select_id = getDatabaseUserAccountID($user_select_id);
            ?>
            <div class="modal-open">
                <div class="modal fade show" style="padding-right: 19px; display: block;" id="editAddressModal" tabindex="-1"
                    role="dialog" aria-labelledby="editAddressModalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="max-width: 600px" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Visualização</h5>
                                <a href="/panel/users">
                                    <button type="button" class="close" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </a>
                            </div>
                            <div class="modal-body">

                                <div id="user_panel">
                                    <section id="user_photo">
                                        <img src="<?php echo getPathAvatarImage(getDatabaseUserPhotoName($user_select_id)); ?>">
                                    </section>
                                    <section id="user_infos">
                                        <b><label>Nome:</label></b>
                                        <span><?php echo getDatabaseUserName($user_select_id); ?></span><br>
                                        <b><label>Email:</label></b>
                                        <span><?php echo getDatabaseAccountEmail($account_select_id); ?></span><br>
                                        <b><label>Celular:</label></b>
                                        <span><?php echo getDatabaseUserPhone($user_select_id); ?></span><br>
                                        <b><label>Cargo:</label></b>
                                        <span><?php echo getDatabaseGroupTitle(getDatabaseAccountGroupID($account_select_id)); ?></span><br>
                                    </section>
                                </div>
                                <br>

                                <div id="user_address">
                                    <table border="1" width="100%">
                                        <tr>
                                            <th>#</th>
                                            <th>Endereço</th>
                                        </tr>
                                        <!-- ENDEREÇO INICIO -->
                                        <?php
                                        $address_list = doDatabaseAddressListByUserID($user_select_id);

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
                                <div id="user_history">
                                    <table class="table table-bordered" id="dataTableDeliverys" width="100%" cellspacing="0">
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
                                            <!-- INICIO LISTA PEDIDO -->
                                            <?php
                                            $cart_list = doDatabaseCartsListByUserIDAllStatus($user_select_id);
                                            if ($cart_list) {
                                                foreach ($cart_list as $dataCart) {
                                                    $cart_list_id = $dataCart['id'];
                                                    $order_id = getDatabaseRequestOrderByCartID($cart_list_id);
                                                    $first_status = doDatabaseRequestOrderLogsFirstLogByOrderID($order_id);
                                                    ?>
                                                    <tr>
                                                        <td>#<?php echo $order_id ?></td>
                                                        <td><?php echo doDate(getDatabaseRequestOrderLogCreated($first_status)) . ' às ' . doTime(getDatabaseRequestOrderLogCreated($first_status)); ?>
                                                        </td>
                                                        <td>
                                                            <a target="on_blank" href="/panel/orders/order/view/<?php echo $order_id ?>">
                                                                <i class="fa-solid fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php

                                                }
                                            }
                                            ?>
                                            <!-- FIM LISTA PEDIDO -->
                                        </tbody>
                                    </table>
                                </div>


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
    // <!-- Modal View end -->

    if (isCampanhaInURL("block")) {
        $user_select_id = getURLLastParam();
        if (isDatabaseUserExistID($user_select_id)) {
            $account_select_id = getDatabaseUserAccountID($user_select_id);
            ?>
            <div class="modal fade show" style="padding-right: 19px; display: block;" id="editAddressModal" tabindex="-1"
                role="dialog" aria-labelledby="editAddressModalLabel" aria-hidden="true">
                <div class="modal-dialog" style="max-width: 600px" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Restrição</h5>
                            <a href="/panel/users">
                                <button type="button" class="close" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </a>
                        </div>
                        <form action="/panel/users" method="post">
                            <div class="modal-body">
                                <?php
                                if (getDatabaseAccountBlock($account_select_id) == 0) {
                                    ?>
                                    Você está prestes a aplicar uma restrição ao usuário
                                    <b>[<?php echo getDatabaseUserName($user_select_id) ?>]</b>,
                                    você tem certeza disso?
                                    <br>
                                    <br>
                                    <div class="alert alert-danger" role="alert">
                                        Confirmando está ação, o usuário não poderá fazer login ou executar qualquer tarefa.
                                    </div>
                                    <?php
                                }

                                if (getDatabaseAccountBlock($account_select_id) == 1) {
                                    ?>

                                    Você está prestes a ativar o usuário <b>[<?php echo getDatabaseUserName($user_select_id) ?>]</b>,
                                    você tem certeza disso?

                                    <div class="alert alert-warning" role="alert">
                                        Confirmando está ação, o usuário voltará a fazer login e executar tarefas de sua função.
                                    </div>

                                    <?php
                                }

                                ?>
                            </div>
                            <div class="modal-footer">
                                <input type="text" name="user_select_id" value="<?php echo $user_select_id ?>" hidden />

                                <input name="token" type="text" value="<?php echo addGeneralSecurityToken('tokenRemoveUser') ?>"
                                    hidden>
                                <a href="/panel/users">
                                    <button type="button" class="btn btn-danger">Cancelar</button>
                                </a>
                                <button type="submit" class="btn btn-success">Confirmar</button>
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

    // <!-- Modal View end -->
    if (isCampanhaInURL("edit")) {
        $user_select_id = getURLLastParam();
        if (isDatabaseUserExistID($user_select_id)) {
            $account_select_id = getDatabaseUserAccountID($user_select_id);
            ?>
            <div class="modal fade show" style="padding-right: 19px; display: block;" id="editAddressModal" tabindex="-1"
                role="dialog" aria-labelledby="editAddressModalLabel" aria-hidden="true">
                <div class="modal-dialog" style="max-width: 600px" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Alteração</h5>
                            <a href="/panel/users">
                                <button type="button" class="close" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </a>
                        </div>
                        <form action="/panel/users" method="post">
                            <div class="modal-body">

                                <div class="form-group">
                                    <label for="username">Usuário:</label>
                                    <font color="red">*</font>
                                    <input type="text" name="username" class="form-control" id="username"
                                        value="<?php echo getDatabaseAccountUserName($account_select_id); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="name">Nome:</label>
                                    <font color="red">*</font>
                                    <input type="text" name="name" class="form-control" id="name"
                                        value="<?php echo getDatabaseUserName($user_select_id); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="password">Senha:</label>
                                    <input type="password" name="password" class="form-control" id="password" placeholder="Senha">
                                </div>

                                <div class="form-group">
                                    <label for="phone">Telefone:</label>
                                    <font color="red">*</font>
                                    <input type="text" name="phone" class="form-control" id="phone"
                                        value="<?php echo getDatabaseUserPhone($user_select_id); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <font color="red">*</font>
                                    <input type="email" name="email" class="form-control" id="email"
                                        value="<?php echo getDatabaseAccountEmail($account_select_id); ?>">
                                </div>
                                <!-- VALIDA SE TEM PERMISSÃO PARA Visualização -->
                                <?php
                                if (isGeneralSecurityManagerAccess()) {
                                    ?>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="group_id">Cargo <small><i class="fa fa-question-circle"
                                                        aria-hidden="true" data-toggle="tooltip" data-placement="top"
                                                        title="Função ou posição que ele ocupará."></i></small></label>
                                        </div>
                                        <select name="group_id" class="custom-select" id="group_id">
                                            <!-- LISTA DE PERMISSÕES -->
                                            <?php
                                            $group_access_list = doDatabaseGroupList();
                                            if ($group_access_list) {
                                                foreach ($group_access_list as $data) {
                                                    $group_list_id = $data['id'];
                                                    ?>
                                                    <option value="<?php echo $group_list_id ?>" <?php echo doSelect(getDatabaseAccountGroupID($account_select_id), $group_list_id) ?>>
                                                        <?php echo getDatabaseGroupTitle($group_list_id) ?>
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
                            </div>
                            <div class="modal-footer">
                                <input type="text" name="user_select_id" value="<?php echo $user_select_id ?>" hidden />

                                <input name="token" type="text" value="<?php echo addGeneralSecurityToken('tokenUserUpdate') ?>"
                                    hidden>
                                <a href="/panel/users">
                                    <button type="button" class="btn btn-danger">Cancelar</button>
                                </a>
                                <button type="submit" class="btn btn-success">Confirmar</button>
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