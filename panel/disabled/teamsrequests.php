<?php
include_once(realpath(__DIR__ . "/front/php/header.php"));
getMasterAdminAccess();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $account_id = getUsersAccountID($user_id);


    // ALTERAR CARGO
    if (getToken('teamApprovedEditRequest' . $user_id)) {

        if (empty($_POST) === false) {
            if (isGroupsExist($_POST['group_id']) === false) {
                $errors[] = "Desculpe, ocorreu um problema ao processar a alteração. Por favor, tente novamente.";
            }

            if (isUsersExist($_POST['user_id']) === false) {
                $errors[] = "Desculpe, ocorreu um problema ao processar a alteração. Por favor, tente novamente.";
            }
        }


        if (empty($errors)) {

            $account_query_data = array(
                'group_id' => $_POST['group_id'],
            );


            $account_id = doUpdateAccountsRow($account_id, $account_query_data);
            echo alertSuccess("O cargo foi alterado com sucesso!!", '/admin/teams');
        }


        if (empty($errors) === false) {
            header("HTTP/1.1 401 Not Found");
            echo doPopupError($errors);
        }
    }

    if (getToken('teamEditRequest' . $user_id)) {
        ?>
        <form action="/admin/teamsrequests" method="post">
            <h1 class="h3 mb-0 text-gray-800">Alteração de Cargo</h1><br>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="raffle_type">Cargo <small><i class="fa fa-question-circle"
                                aria-hidden="true" data-toggle="tooltip" data-placement="top"
                                title="Cargom em que ele irá ficar."></i></small></label>
                </div>
                <select name="group_id" class="custom-select" id="raffle_type">
                    <?php
                    $raffleTypesArray = doGroupsList();
                    if ($raffleTypesArray) {
                        foreach ($raffleTypesArray as $data) {
                            ?>
                            <option value="<?php echo ($data['id']) ?>" <?php echo (getAccountsGroupID($account_id) == $data['id']) ? 'selected' : '' ?>>
                                <?php echo (getGroupsTitle($data['id'])) ?>
                            </option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>

            <input type="hidden" name="user_id" value="<?php echo $user_id ?>" />
            <input type="hidden" name="token" value="<?php echo addToken('teamApprovedEditRequest' . $user_id) ?>" />
            <a href="/admin/users">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </a>
            <button type="submit" class="btn btn-primary">Aprovar</button>
        </form>
        <?php
    }

    // DELETAR CARGO

    if (getToken('teamApprovedDeleteRequest' . $user_id)) {

        if (empty($_POST) === false) {
            if (isUsersExist($_POST['user_id']) === false) {
                $errors[] = "Desculpe, ocorreu um problema ao processar a alteração. Por favor, tente novamente.";
            }
        }


        if (empty($errors)) {

            $account_query_data = array(
                'group_id' => 1,
            );


            $account_id = doUpdateAccountsRow($account_id, $account_query_data);
            echo alertSuccess("O cargo usuário foi rebaixado a cliente com sucesso!!", '/admin/teams');
        }


        if (empty($errors) === false) {
            header("HTTP/1.1 401 Not Found");
            echo doPopupError($errors);
        }
    }

    if (getToken('teamDeleteRequest' . $user_id)) {
        ?>
        <form action="/admin/teamsrequests" method="post">
            <div class="alert alert-warning" role="alert">
                Você está prestes a rebaixar este usuário, tem certa?
            </div>

            <input type="hidden" name="user_id" value="<?php echo $user_id ?>" />
            <input type="hidden" name="token" value="<?php echo addToken('teamApprovedDeleteRequest' . $user_id) ?>" />
            <a href="/admin/users">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </a>
            <button type="submit" class="btn btn-primary">Aprovar</button>
        </form>
        <?php
    }


}
include_once(realpath(__DIR__ . "/front/php/footer.php"));
?>