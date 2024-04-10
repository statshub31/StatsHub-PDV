<?php
include_once(realpath(__DIR__ . "/front/php/header.php"));
getAdminAccess();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $game_id = $_POST['game_id'];

    if (empty($game_id)) {
        header('Location: /admin/games', true, 302);
        exit; // Certifique-se de encerrar a execução do script após o redirecionamento
    }

    if (getToken('approvedGameDeleted')) {
        if (empty($_POST) === false) {
            if (isGamesExist($_POST['game_id']) === false) {
                $errors[] = "Desculpe, ocorreu um problema ao processar a exclusão. Por favor, tente novamente.";
            }

        }


        if (empty($errors)) {
            if(getGameTypeID($game_id) == 1) {
                doTruncateGamesScratchCardsByGameID($game_id);
            } else 
            if(getGameTypeID($game_id) == 2) {
                doTruncateGamesTelesenaByGameID($game_id);
            }
            doTruncateGamesRewardsByGameID($game_id);
            doTruncateGame($game_id);
            echo alertSuccess("O jogo foi foi excluído com sucesso!!", '/admin/games');

        }


        if (empty($errors) === false) {
            header("HTTP/1.1 401 Not Found");
            echo alertError($errors);
        }
    }


    if (getToken('deletegame' . $game_id)) {
        ?>
        <div class="alert alert-warning" role="alert">
            Você está prestes a excluir esse jogo, você tem certa disso? <b>Essa ação é IRREVERSIVEL.</b>
        </div>

        <form action="/admin/deletegame" method="post">
            <input type="hidden" name="token" value="<?php echo addToken('approvedGameDeleted') ?>" />
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