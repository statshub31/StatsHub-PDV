<?php
include_once(realpath(__DIR__ . "/front/php/header.php"));
getAdminAccess();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $game_id = $_POST['game_id'];

    if (empty($game_id)) {
        header('Location: /admin/games', true, 302);
        exit; // Certifique-se de encerrar a execução do script após o redirecionamento
    }

    if (getToken('approvedCloseGame')) {
        if (empty($_POST) === false) {
            if (isGamesExist($_POST['game_id']) === false) {
                $errors[] = "Desculpe, ocorreu um problema ao processar a solicitação. Por favor, tente novamente.";
            }

        }


        if (empty($errors)) {

            $update = array(
                'close_date' => date("Y-m-d"),
                'game_status_id' => 2
            );

            doUpdateGamesRow($_POST['game_id'], $update);
            echo alertSuccess("O jogo foi encerrado com sucesso!!", '/admin/games');

        }


        if (empty($errors) === false) {
            header("HTTP/1.1 401 Not Found");
            echo alertError($errors);
        }
    }


    if (getToken('closeGame' . $game_id)) {
        ?>
        <div class="alert alert-warning" role="alert">
            Você está prestes a encerrar esse jogo, você tem certa disso? <b>Essa ação é IRREVERSIVEL.</b>
        </div>

        <form action="/admin/closegame" method="post">
            <input type="hidden" name="token" value="<?php echo addToken('approvedCloseGame') ?>" />
            <input type="hidden" name="game_id" value="<?php echo $_POST['game_id']; ?>" />
            <a href="/admin/games">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </a>
            <button type="submit" class="btn btn-primary">Aprovar</button>
        </form>

        <?php
    }

} else {
    header('Location: /admin/games', true, 302);
    exit; // Certifique-se de encerrar a execução do script após o redirecionamento
}

include_once(realpath(__DIR__ . "/front/php/footer.php"));
?>