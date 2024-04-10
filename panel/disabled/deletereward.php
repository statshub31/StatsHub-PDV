<?php
include_once(realpath(__DIR__ . "/front/php/header.php"));
getAdminAccess();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reward_id = $_POST['reward_id'];

    if (empty($reward_id)) {
        header('Location: /admin/rewards', true, 302);
        exit; // Certifique-se de encerrar a execução do script após o redirecionamento
    }

    if (getToken('approvedRewardDeleted')) {
        if (empty($_POST) === false) {
            if (isRewardExist($_POST['reward_id']) === false) {
                $errors[] = "Desculpe, ocorreu um problema ao processar a exclusão. Por favor, tente novamente.";
            }

        }


        if (empty($errors)) {

            doDeleteRewardRow($reward_id);
            echo alertSuccess("O premio foi foi excluído com sucesso!!", '/admin/rewards');

        }


        if (empty($errors) === false) {
            header("HTTP/1.1 401 Not Found");
            echo alertError($errors);
        }
    }


    if (getToken('deleteReward' . $reward_id)) {
        ?>
        <div class="alert alert-warning" role="alert">
            Você está prestes a excluir esse premio, você tem certa disso? <b>Essa ação é IRREVERSIVEL.</b>
        </div>

        <form action="/admin/deletereward" method="post">
            <input type="hidden" name="token" value="<?php echo addToken('approvedRewardDeleted') ?>" />
            <input type="hidden" name="reward_id" value="<?php echo $_POST['reward_id']; ?>" />
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