<?php
include_once(realpath(__DIR__ . "/front/php/header.php"));
getAdminAccess();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $account_id = getUsersAccountID($user_id);
    $accounts_cb_id = getAccountsCBCodeIdByAccountID($account_id);

    if (empty($user_id)) {
        header('Location: /admin/users', true, 302);
        exit; // Certifique-se de encerrar a execução do script após o redirecionamento
    }

    if (getToken('approvedUserDeleted')) {
        if (empty($_POST) === false) {
            if (isUsersExist($_POST['user_id']) === false) {
                $errors[] = "Desculpe, ocorreu um problema ao processar a exclusão. Por favor, tente novamente.";
            }

        }


        if (empty($errors)) {

            $dir = '/front/images/users/';
            $format = getPathImageFormat($dir, $user_id);

            
            $targetPath = __DIR__ . '/../front/images/users/'.$user_id.'.'.$format;

            if (file_exists($targetPath)) {
                unlink($targetPath);
            }

            doDeleteAccountsCBRow($accounts_cb_id);
            doDeleteUsersRow($user_id);
            doDeleteAccountsRow($account_id);
            echo alertSuccess("O usuário foi excluído com sucesso!!", '/admin/users');

        }


        if (empty($errors) === false) {
            header("HTTP/1.1 401 Not Found");
            echo alertError($errors);
        }
    }


    if (getToken('deleteUser' . $user_id)) {
        ?>
        <div class="alert alert-warning" role="alert">
            Você está prestes a excluir esse usuário, todos as informações referentes a essa pessoa serão deletadas, você tem
            certa disso? <b>Essa ação é IRREVERSIVEL.</b>
        </div>

        <form action="/admin/deleteuser" method="post">
            <input type="hidden" name="token" value="<?php echo addToken('approvedUserDeleted') ?>" />
            <input type="hidden" name="user_id" value="<?php echo $_POST['user_id']; ?>" />
            <a href="/admin/users">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </a>
            <button type="submit" class="btn btn-primary">Aprovar</button>
        </form>

        <?php
    }

} else {
    header('Location: /admin/users', true, 302);
    exit; // Certifique-se de encerrar a execução do script após o redirecionamento
}

include_once(realpath(__DIR__ . "/front/php/footer.php"));
?>