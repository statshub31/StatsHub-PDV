<?php
include_once(realpath(__DIR__ . "/front/php/header.php"));
getMasterAdminAccess();

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (getToken('truncateAllDatabaseToken')) {
        if (empty($errors)) {

            echo alertSuccess("O banco de dados foi limpo com sucesso, você precisará fazer logon novamente!!", '/login');
            $exceptions = array(
                //config
                'favicon.png',
                'logo.png',

                //images
                'key.png',
                'login-background.png',
                'map-image.png',
                'reward.png',
                'header-bg.jpg',
                'avatar.png',
                'BS.svg',
                'close-icon.svg',
                
                // levels
                '0.avif',

                // logos
                'facebook.svg',
                'google.svg',
                'ibm.svg',
                'microsft.svg',

                // games_config
                'background.png',
                'background-s.png',
                'background-tele.png',
                '1.png'
            );
            doResetFolders(__DIR__ . "/../front/images/", $exceptions);
            doResetDB();

            if (isset($_SESSION)) {
                session_destroy();
                header('Location: /index');
            }
        }


        if (empty($errors) === false) {
            header("HTTP/1.1 401 Not Found");
            echo alertError($errors);
        }
    }

    if (getToken('truncateDatabaseToken')) {
        if (empty($errors)) {

            echo alertSuccess("O banco de dados foi limpo com sucesso!", '/admin/index');
            $exceptions = array();
            doResetFolders(__DIR__ . "/../front/images/rewards/", $exceptions);
            doResetDBGames();
        }


        if (empty($errors) === false) {
            header("HTTP/1.1 401 Not Found");
            echo alertError($errors);
        }
    }

// }


$type = obterValorParametro();

if ($type == 'all') {
    ?>

    <div class="alert alert-warning" role="alert">
        Você está prestes a excluir todos os dados armazenados no banco de dados. Tem certeza de que deseja prosseguir com
        esta ação? <b>Essa ação é irreversível.</b>
    </div>

    <form action="/admin/reset/all" method="post">
        <input type="hidden" name="token" value="<?php echo addToken('truncateAllDatabaseToken') ?>" />
        <a href="/admin/settings">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        </a>
        <button type="submit" class="btn btn-primary">Aprovar</button>
    </form>

    <?php
} else {
    ?>

<div class="alert alert-warning" role="alert">
        Você está prestes a excluir todos os dados armazenados no banco de dados, referente aos jogos. Tem certeza de que deseja prosseguir com
        esta ação? <b>Essa ação é irreversível.</b>
    </div>

    <form action="/admin/reset/games" method="post">
        <input name="token" value="<?php echo addToken('truncateDatabaseToken') ?>" />
        <a href="/admin/settings">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        </a>
        <button type="submit" class="btn btn-primary">Aprovar</button>
    </form>

    <?php
}

include_once(realpath(__DIR__ . "/front/php/footer.php"));
?>