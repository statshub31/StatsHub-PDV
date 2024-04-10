<?php
include_once(realpath(__DIR__ . "/front/php/header.php"));
getAdminAccess();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (getToken('createGameScratch')) {

        if (empty($_POST) === false) {
            $required_fields = array('imagem', 'title', 'name', 'amount', 'price');
            
            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
            }

            if (getSizeString($_POST['title'], 2, 100) !== true) {
                $errors[] = "O título inserido é inválido. Certifique-se de que ele tenha entre 2 e 100 caracteres.";
            }

            if (isGameTypeExistAndOpening(1)) {
                $errors[] = "Você já possui um jogo de raspadinha em aberto, não é possível criar outro.";
            }

            if (isGameRegisterExist() && (isset($_POST['registerGame']))) {
                $errors[] = "Você já possui um jogo de registro em aberto, não é possível designar outro.";
            }

            foreach ($_POST['name'] as $input) {
                if (getSizeString($input, 2, 40) !== true) {
                    $errors[] = "O nome inserido '{$input}' é inválido. Certifique-se de que ele tenha entre 2 e 40 caracteres.";
                }
            }

            foreach ($_POST['amount'] as $input) {
                if (is_numeric($input) !== true) {
                    $errors[] = "A quantidade inserida '{$input}' é inválida. Certifique-se de preencher com um número valido.";
                }
            }


            foreach ($_POST['price'] as $input) {
                if (is_numeric($input) !== true) {
                    $errors[] = "O valor inserido '{$input}' é inválido. Certifique-se de preencher com um valor valido.";
                }
            }

            foreach ($_FILES['imagem']['name'] as $key => $value) {
                $file_name = $_FILES['imagem']['name'][$key];
                $file_size = $_FILES['imagem']['size'][$key];
                $file_tmp = $_FILES['imagem']['tmp_name'][$key];
                $file_type = $_FILES['imagem']['type'][$key];

                if ($file_size > 0) {
                    // Verifica se o arquivo foi enviado sem erros
                    if ($_FILES['imagem']['error'][$key] !== UPLOAD_ERR_OK) {
                        $errors[] = 'Erro no upload do arquivo ' . $file_name;
                    }

                    $imageInfo = getimagesize($file_tmp);
                    if (!$imageInfo || !in_array($imageInfo['mime'], array('image/png', 'image/jpeg', 'image/gif'))) {
                        $errors[] = 'O arquivo ' . $file_name . ' não é uma imagem válida.';
                    } elseif ($imageInfo[0] > 1500 || $imageInfo[1] > 1500) {
                        $errors[] = 'A imagem ' . $file_name . ' excede o tamanho máximo permitido (1500x1500 pixels).';
                    }
                }
            }

        }

        if (empty($errors)) {

            $import_game = array(
                'game_type_id' => 1,
                'title' => $_POST['title'],
                'created' => date("Y-m-d"),
                'team_id' => $userData['id'],
                'game_status_id' => 1,
                'register_game' => (isset($_POST['registerGame'])) ? 1 : 0
            );
            $game_id = doInsertGamesRow($import_game);

            // 
            // Premios
            // 

            $numItems = count($_FILES['imagem']['name']);

            for ($i = 0; $i < $numItems; $i++) {
                $photo = uniqid();
                $c = 0;
                $game_rewards_array = array(
                    'title' => $_POST['name'][$i],
                    'game_id' => $game_id,
                    'team_id' => $userData['id'],
                    'price' => $_POST['price'][$i],
                    'created' => date("Y-m-d"),
                    'blocked' => (isset($_POST['block'][$i])) ? 1 : 0,
                    'photo' => $photo
                );

                $targetPath = __DIR__ . '/../front/images/games';

                if (isset($_FILES['imagem'])) {
                    $numFiles = count($_FILES['imagem']['name']);

                    if ($_FILES['imagem']['size'][$i] > 0) {
                        $targetFile = $targetPath . '/' . $photo . '.png';

                        if (move_uploaded_file($_FILES['imagem']['tmp_name'][$i], $targetFile) === false) {
                            $errors[] = 'Falha ao mover o arquivo para o diretório de destino.';
                            $image = false;
                        }
                    }
                }

                $game_reward_id = doInsertGamesRewardsRow($game_rewards_array);

                while ($c < $_POST['amount'][$i]) {
                    $game_scratch_card_array[] = array(
                        'game_id' => $game_id,
                        'reward_id' => $game_reward_id,
                        'code' => generateUniqueCodeScratchCardGame($game_id)
                    );
                    ++$c;
                }


            }

            doInsertGamesScratchCardsRow($game_scratch_card_array);

            echo alertSuccess("O Jogo [" . $_POST['title'] . "] foi criado com sucesso!!");
        }

    }


    if (getToken('createGameTele')) {

        if (empty($_POST) === false) {
            $required_fields = array('imagem', 'title', 'telamount', 'name', 'amount', 'price', 'teleprice');
            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
            }

            if (isGameTypeExistAndOpening(2)) {
                $errors[] = "Você já possui um jogo de telesena em aberto, não é possível criar outro.";
            }

            if (is_numeric($_POST['telamount']) === false) {
                $errors[] = "É obrigatório preencher com um número valido, a quantidade de telesenas";
            }

            if (getSizeString($_POST['title'], 2, 100) !== true) {
                $errors[] = "O título inserido é inválido. Certifique-se de que ele tenha entre 2 e 100 caracteres.";
            }

            if (is_numeric($_POST['teleprice']) !== true) {
                $errors[] = "O valor inserido para receber telesena é inválido. Certifique-se de preencher com um valor valido.";
            }
            
            foreach ($_POST['name'] as $input) {
                if (getSizeString($input, 2, 40) !== true) {
                    $errors[] = "O nome inserido '{$input}' é inválido. Certifique-se de que ele tenha entre 2 e 40 caracteres.";
                }
            }

            foreach ($_POST['amount'] as $input) {
                if (is_numeric($input) !== true) {
                    $errors[] = "A quantidade inserida '{$input}' é inválida. Certifique-se de preencher com um número valido.";
                }
            }

            foreach ($_POST['price'] as $input) {
                if (is_numeric($input) !== true) {
                    $errors[] = "O valor inserido '{$input}' é inválido. Certifique-se de preencher com um valor valido.";
                }
            }

            foreach ($_FILES['imagem']['name'] as $key => $value) {
                $file_name = $_FILES['imagem']['name'][$key];
                $file_size = $_FILES['imagem']['size'][$key];
                $file_tmp = $_FILES['imagem']['tmp_name'][$key];
                $file_type = $_FILES['imagem']['type'][$key];

                if ($file_size > 0) {
                    // Verifica se o arquivo foi enviado sem erros
                    if ($_FILES['imagem']['error'][$key] !== UPLOAD_ERR_OK) {
                        $errors[] = 'Erro no upload do arquivo ' . $file_name;
                    }

                    $imageInfo = getimagesize($file_tmp);
                    if (!$imageInfo || !in_array($imageInfo['mime'], array('image/png', 'image/jpeg', 'image/gif'))) {
                        $errors[] = 'O arquivo ' . $file_name . ' não é uma imagem válida.';
                    } elseif ($imageInfo[0] > 1500 || $imageInfo[1] > 1500) {
                        $errors[] = 'A imagem ' . $file_name . ' excede o tamanho máximo permitido (1500x1500 pixels).';
                    }
                }
            }

        }

        if (empty($errors)) {

            $import_game = array(
                'game_type_id' => 2,
                'title' => $_POST['title'],
                'created' => date("Y-m-d"),
                'team_id' => $userData['id'],
                'value_to_participate' => $_POST['teleprice'],
                'game_status_id' => 1,
                'register_game' => 0,
            );
            $game_id = doInsertGamesRow($import_game);

            // 
            // Premios
            // 

            $numItems = count($_FILES['imagem']['name']);
            $arrayTelesena = array();

            for ($i = 0; $i < $numItems; $i++) {
                $photo = uniqid();
                $c = 0;
                $game_rewards_array = array(
                    'title' => $_POST['name'][$i],
                    'game_id' => $game_id,
                    'team_id' => $userData['id'],
                    'price' => $_POST['price'][$i],
                    'created' => date("Y-m-d"),
                    'blocked' => (isset($_POST['block'][$i])) ? 1 : 0,
                    'photo' => $photo
                );

                $targetPath = __DIR__ . '/../front/images/games';

                if (isset($_FILES['imagem'])) {
                    $numFiles = count($_FILES['imagem']['name']);

                    if ($_FILES['imagem']['size'][$i] > 0) {
                        $targetFile = $targetPath . '/' . $photo . '.png';

                        if (move_uploaded_file($_FILES['imagem']['tmp_name'][$i], $targetFile) === false) {
                            $errors[] = 'Falha ao mover o arquivo para o diretório de destino.';
                            $image = false;
                        }
                    }
                }

                $game_reward_id = doInsertGamesRewardsRow($game_rewards_array);

                $arrayTelesena[] = array(
                    'amount' => $_POST['amount'][$i],
                    'id' => $game_reward_id
                );
            }

            $c = 0;
            while ($c < count($arrayTelesena)) {
                $i = 0; // Inicialize $i antes do segundo loop

                while ($i < $arrayTelesena[$c]['amount']) {
                    $reward = escolherPremio($arrayTelesena, $c);
                    $arrayReward[] = array(
                        'game_id' => $game_id,
                        'code' => generateUniqueCodeTelesenaGame($game_id),
                        'b1_reward_id' => $arrayTelesena[$reward[1]]['id'],
                        'b2_reward_id' => $arrayTelesena[$reward[2]]['id'],
                        'b3_reward_id' => $arrayTelesena[$reward[3]]['id'],
                        'b4_reward_id' => $arrayTelesena[$reward[4]]['id'],
                        'b5_reward_id' => $arrayTelesena[$reward[5]]['id'],
                        'b6_reward_id' => $arrayTelesena[$reward[6]]['id']
                    );
                    $i++; // Incrementa $i para evitar um loop infinito
                }
                $c++; // Incrementa $c para evitar um loop infinito
            }


            $c = 0;
            while ($c < ($_POST['telamount'] - count($arrayTelesena))) {
                $i = 0; // Inicialize $i antes do segundo loop

                $reward = escolherPremio($arrayTelesena, false);
                $arrayReward[] = array(
                    'game_id' => $game_id,
                    'code' => generateUniqueCodeTelesenaGame($game_id),
                    'b1_reward_id' => $arrayTelesena[$reward[1]]['id'],
                    'b2_reward_id' => $arrayTelesena[$reward[2]]['id'],
                    'b3_reward_id' => $arrayTelesena[$reward[3]]['id'],
                    'b4_reward_id' => $arrayTelesena[$reward[4]]['id'],
                    'b5_reward_id' => $arrayTelesena[$reward[5]]['id'],
                    'b6_reward_id' => $arrayTelesena[$reward[6]]['id']
                );
                $i++; // Incrementa $i para evitar um loop infinito
                $c++; // Incrementa $c para evitar um loop infinito
            }

            doInsertGamesTelesenaRow($arrayReward);

            echo alertSuccess("O Jogo [" . $_POST['title'] . "] foi criado com sucesso!!");
        }

    }

    if (empty($errors) === false) {
        header("HTTP/1.1 401 Not Found");
        echo alertError($errors);
    }
}
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/0.9.0/jquery.mask.min.js"
    integrity="sha512-oJCa6FS2+zO3EitUSj+xeiEN9UTr+AjqlBZO58OPadb2RfqwxHpjTU8ckIC8F4nKvom7iru2s8Jwdo+Z8zm0Vg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).ready(function () {
        // Adiciona um evento de input ao campo
        $(".price").on('input', function () {
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
<div>



    <a href="/admin/games">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
    </a><br><br>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <label class="input-group-text" for="raffle_type">Tipo de Jogo <small><i class="fa fa-question-circle"
                        aria-hidden="true" data-toggle="tooltip" data-placement="top"
                        title="Formato de jogo a ser criado."></i></small></label>
        </div>
        <select name="raffle_type" class="custom-select" id="raffle_type" onchange="showHideDiv()">
            <option value="0">-- Selecione --</option>
            <?php
            $raffleTypesArray = doGameTypesList();
            if ($raffleTypesArray) {
                foreach ($raffleTypesArray as $data) {
                    ?>
                    <option value="<?php echo ($data['id']) ?>">
                        <?php echo (getGameTypesTitle($data['id'])) ?>
                    </option>
                    <?php
                }
            }
            ?>
        </select>
    </div>
    <hr>

    <style>
        .menu-configs-nav {
            list-style: none;
            display: table;
            border-bottom: 1px solid;
            width: 100%;
            border-color: #dfe0e2;
        }

        .menu-configs-nav li {
            float: left;
            margin: 0px 10px;
            padding: 5px;
            border-bottom: 2px solid;
            border-color: #dfe0e2;
        }

        .menu-configs-nav li:hover {
            cursor: pointer;
            border-bottom: 2px solid;
            color: white;
            background-color: #00c4ff;
            border-color: #005771 !important;
            padding: 5px;
        }

        .menu-config-select {
            border-bottom: 2px solid;
            color: white;
            background-color: #00c4ff;
            border-color: #005771 !important;
            padding: 5px;
        }

        .hidden {
            display: none;
        }

        .content {
            display: none;
            margin-top: 20px;
        }

        #settings {
            display: block;
        }

        .form-imgs-container {
            display: flex;
            flex-direction: row;
            align-content: center;
            justify-content: center;
            align-items: center;
        }

        :root {
            /*Background color when it's turned off*/
            --vc-off-color: #d1d3d4;

            /*Background color when it's turned on*/
            --vc-on-color: #38cf5b;

            /*Animation speed and type*/
            --vc-animation-speed: 0.40s ease-out;

            /*Font used by the text*/
            --vc-font-family: Arial;

            /*The size used*/
            --vc-font-size: 15px;

            /*The font weight*/
            --vc-font-weight: 800;

            /*Font color when the switch is on*/
            --vc-on-font-color: white;

            /*Font color when the switch is off*/
            --vc-off-font-color: white;

            /*How far the OFF text is from the right side*/
            --vc-label-position-off: 12px;

            /*How far the ON text is from the left side*/
            --vc-label-position-on: 11px;

            /*Small switch width*/
            --vc-width: 100px;

            /*Small switch height*/
            --vc-height: 50px;

            /*Border radius for the handle*/
            --vc-handle-border-radius: 20px;

            /*Border radius for the box*/
            --vc-box-border-radius: 18px;

            /*Shadow for the handle*/
            --vc-handle-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);

            /*Handle color*/
            --vc-handle-color: white;

            /*Handle width*/
            --vc-handle-width: 40px;

            /*Handle height*/
            --vc-handle-height: 40px;

            /*The handle's width while the toggle is clicked*/
            --vc-onclick-width: 30px;

            /*Handle's distance from the top*/
            --vc-handle-top: 5px;
        }

        .vc-toggle-container * {
            font-family: var(--vc-font-family);
            -webkit-transition: var(--vc-animation-speed);
            -moz-transition: var(--vc-animation-speed);
            -o-transition: var(--vc-animation-speed);
            transition: var(--vc-animation-speed);
        }

        .vc-switch {
            width: var(--vc-width);
            height: var(--vc-height);
        }

        .vc-toggle-container label {
            position: relative;
            display: inline-block;
            vertical-align: top;
            border-radius: var(--vc-box-border-radius);
            cursor: pointer;
        }

        .vc-switch-input {
            position: absolute;
            transform: translate3d(5px, 5px, 0);
        }

        .vc-switch-label {
            position: relative;
            display: block;
            height: inherit;
            font-size: var(--vc-font-size);
            font-weight: var(--vc-font-weight);
            background: var(--vc-off-color);
            border-radius: inherit;
        }

        .vc-switch-label:before,
        .vc-switch-label:after {
            position: absolute;
            top: 50%;
            margin-top: -0.5em;
            line-height: 1.1;
        }

        .vc-switch-label:before {
            content: attr(data-off);
            color: var(--vc-on-font-color);
        }

        .vc-switch-label:after {
            content: attr(data-on);
            color: var(--vc-off-font-color);
            opacity: 0;
        }

        .vc-switch-label:before {
            right: var(--vc-label-position-off);
            ;
        }

        .vc-switch-label:after {
            left: var(--vc-label-position-on);
            ;
        }

        .vc-switch-input:checked~.vc-switch-label {
            background: var(--vc-on-color);
        }

        .vc-switch-input:checked~.vc-switch-label:before {
            opacity: 0;
        }

        .vc-switch-input:checked~.vc-switch-label:after {
            opacity: 1;
        }

        .vc-handle {
            position: absolute !important;
            top: var(--vc-handle-top);
            left: 5px;
            background: var(--vc-handle-color);
            border-radius: var(--vc-handle-border-radius);
            box-shadow: var(--vc-handle-shadow);
        }

        .vc-handle {
            width: var(--vc-handle-width);
            height: var(--vc-handle-height);
        }

        .vc-handle:before {
            content: "";
            top: 50%;
            left: 50%;
            position: absolute !important;
            margin: -6px 0 0 -6px;
            width: 12px;
            height: 12px;
            border-radius: 6px;
        }

        .vc-switch-label:active~.vc-handle,
        .vc-handle:active {
            width: var(--vc-onclick-width);
        }

        .vc-switch-input:checked~.vc-handle {
            left: unset;
            right: 5px;
        }

        #row,
        #rowTele {
            display: flex;
            flex-wrap: wrap;
            /* Defina o valor máximo desejado */
            margin: 0 auto;
            /* Adiciona margem automática para centralizar o conteúdo */
        }

        .item {
            width: calc(25% - 10px);
            /* Define a largura do item (25% do contêiner com margens) */
            /* Adicione margens conforme necessário */
            box-sizing: border-box;
            /* Inclui a margem no cálculo da largura */
        }

        .reward,
        .rewardTele {
            width: 26%;
            border: 1px solid #d1d3e2;
            padding: 10px;
            text-align: center;
            border-radius: 10px;
            display: table;
            margin: 5px;
        }

        .excluded,
        .excludedTele {
            position: relative;
            right: 15px;
            float: right;
            border: 1px solid black;
            padding: 0px 5px;
            border-radius: 50%;
            border: 1px solid #d1d3e2;
        }

        .excluded i,
        .excludedTele i {
            position: sticky;
            /* Define posição absoluta para o ícone */
            top: 0;
            left: 0;
            width: 100%;
            /* Garante que o ícone cubra completamente o contêiner */
            height: 100%;
            background: transparent;
            z-index: 1;
            width: 15px;
            /* Coloca o ícone acima do conteúdo da .excluded */
        }

        .excluded:hover,
        .excludedTele:hover {
            border: 1px solid #690909;
            background-color: #e9a4a4;
            color: white;
            cursor: pointer;
        }
    </style>
    <div id="scratch" style="display:none;">


        <form action="/admin/addgame" method="post" enctype="multipart/form-data">

            <?php
            if (getDatabaseSettingsGameRegisterStatus(1)) {
                ?>
                <label for="inputRegisterGameStatus">Deseja habilitar este jogo para novos usuários?</label>
                <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" 
                title="Quando essa opção estiver habilitada, após o usuário ser registrado, o mesmo
                    receberá um email, com um link para a participação do jogo."></i></small>
                <div class="vc-toggle-container">
                    <label class="vc-switch">
                        <input type="checkbox" id="inputRegisterGameStatus" class="vc-switch-input" checked disabled>
                        <input type="checkbox" name="registerGame" id="inputRegisterGameStatus" class="vc-switch-input" checked hidden>
                        <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                        <span class="vc-handle"></span>
                    </label>
                </div>
                <?php
            }
            ?>
            <br>
            <div class="form-group">
                <label for="inputtitle">Título do Jogo:</label>
                <font color="red">*</font>
                <input type="text" name="title" class="form-control" id="inputtitle" placeholder="Premiacao">
            </div>
            <br>
            <button type="button" class="btn btn-secondary" onclick="addReward()">Adicionar Prêmio</button>
            <br>
            <div id="row">
                <div class="reward">
                    <div class="excluded">
                    </div>
                    <div class="form-imgs-container">

                        <div class="input-group mb-3">

                            <div class="input-group-prepend" onclick="selectAndHide()">
                                <span class="input-group-text">Imagem<font color="red">*</font>
                                </span>
                            </div>
                            <div class="custom-file">
                                <input type="file" name="imagem[]" class="custom-file-input" class="imageRewardFile[]"
                                    accept="image/*">
                                <label class="custom-file-label">Escolha sua imagem.</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputfname[]">Nome:</label>
                        <font color="red">*</font>
                        <input type="text" name="name[]" class="form-control" id="inputfname[]" placeholder="Chaveiro">
                    </div>
                    <div class="form-group">
                        <label for="inputamount[]">Quantidade:</label>
                        <font color="red">*</font>
                        <input type="text" name="amount[]" class="form-control" id="inputamount[]" placeholder="10">
                    </div>
                    <div class="form-group">
                        <label for="inputprice[]">Preço:</label>
                        <font color="red">*</font>
                        <input type="text" name="price[]" class="price form-control" id="inputprice[]" placeholder="10.00">
                    </div>

                    <div class="form-group">
                        <label for="inputBlock[]">Deseja que este Prêmio fique bloqueado?</label>
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" 
                title="Para assegurar que ninguém possa reivindicar este prêmio, mantenha-o bloqueado."></i></small>
                        <div class="vc-toggle-container">
                            <label class="vc-switch">
                                <input type="checkbox" name="block[]" id="inputBlock[]" class="vc-switch-input">
                                <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                                <span class="vc-handle"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <input name="token" type="text" value="<?php echo addToken('createGameScratch') ?>" hidden />
            <button class="btn btn-primary" type="submit">Criar</button>
        </form>
    </div>
    <script>

        function addReward() {
            // Clona a div com a classe "reward"
            var newReward = document.querySelector('.reward').cloneNode(true);

            // Adiciona a nova div à div com id "row"
            document.getElementById('row').appendChild(newReward);

            // Adiciona o ícone de remoção à div "excluded"
            var excludeDiv = newReward.querySelector('.excluded');
            var removeIcon = document.createElement('i');
            removeIcon.className = 'fa fa-times';
            removeIcon.addEventListener('click', function () {
                removeReward(this);
            });
            excludeDiv.innerHTML = ''; // Limpa qualquer conteúdo existente
            excludeDiv.appendChild(removeIcon);
        }

        function removeReward(icon) {
            // Obtém o elemento pai (div com classe "reward") do ícone clicado
            var rewardDiv = icon.parentNode.parentNode;

            // Remove a div com classe "reward"
            rewardDiv.remove();
        }

    </script>

    <div id="telesena" style="display:none;">


        <form action="/admin/addgame" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="inputtitle">Título do Jogo:</label>
                <font color="red">*</font>
                <input type="text" name="title" class="form-control" id="inputtitle" placeholder="Premiacao">
            </div>
            <br>
            <div class="form-group">
                <label for="inputtelprice">Valor para receber telesena:</label>
                <font color="red">*</font>
                <input type="text" name="teleprice" class="price form-control" id="inputtelprice" placeholder="10.00">
            </div>
            <br>
            <div class="form-group">
                <label for="inputtelamount">Quantidade total de telesenas:</label>
                <font color="red">*</font>
                <input type="text" name="telamount" class="form-control" id="inputtelamount" placeholder="500">
            </div>
            <br>
            <div id="row">
                <div class="reward">
                    <div class="form-imgs-container">

                        <div class="input-group mb-3">

                            <div class="input-group-prepend" onclick="selectAndHide()">
                            </div>
                            <div class="custom-file">
                                <input type="file" name="imagem[]" class="custom-file-input" class="imageRewardFile[]"
                                    accept="image/*">
                                <label class="custom-file-label">Escolha sua imagem.</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputfname[]">Nome:</label>
                        <font color="red">*</font>
                        <input type="text" name="name[]" class="form-control" id="inputfname[]" placeholder="Chaveiro">
                    </div>
                    <div class="form-group">
                        <label for="inputamount[]">Quantidade:</label>
                        <font color="red">*</font>
                        <input type="text" name="amount[]" class="form-control" id="inputamount[]" placeholder="10">
                    </div>
                    <div class="form-group">
                        <label for="inputprice[]">Preço:</label>
                        <font color="red">*</font>
                        <input type="text" name="price[]" class="price form-control" id="inputprice[]" placeholder="10.00">
                    </div>

                    <div class="form-group">
                        <label for="inputBlock[]">Deseja que este Prêmio fique bloqueado?</label>
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" 
                title="Para assegurar que ninguém possa reivindicar este prêmio, mantenha-o bloqueado."></i></small>
                        <div class="vc-toggle-container">
                            <label class="vc-switch">
                                <input type="checkbox" name="block[]" id="inputBlock[]" class="vc-switch-input">
                                <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                                <span class="vc-handle"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="reward">
                    <div class="form-imgs-container">

                        <div class="input-group mb-3">

                            <div class="input-group-prepend" onclick="selectAndHide()">
                            </div>
                            <div class="custom-file">
                                <input type="file" name="imagem[]" class="custom-file-input" class="imageRewardFile[]"
                                    accept="image/*">
                                <label class="custom-file-label">Escolha sua imagem.</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputfname[]">Nome:</label>
                        <font color="red">*</font>
                        <input type="text" name="name[]" class="form-control" id="inputfname[]" placeholder="Chaveiro">
                    </div>
                    <div class="form-group">
                        <label for="inputamount[]">Quantidade:</label>
                        <font color="red">*</font>
                        <input type="text" name="amount[]" class="form-control" id="inputamount[]" placeholder="10">
                    </div>
                    <div class="form-group">
                        <label for="inputprice[]">Preço:</label>
                        <font color="red">*</font>
                        <input type="text" name="price[]" class="price form-control" id="inputprice[]" placeholder="10.00">
                    </div>

                    <div class="form-group">
                        <label for="inputBlock[]">Deseja que este Prêmio fique bloqueado?</label>
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" 
                title="Para assegurar que ninguém possa reivindicar este prêmio, mantenha-o bloqueado."></i></small>
                        <div class="vc-toggle-container">
                            <label class="vc-switch">
                                <input type="checkbox" name="block[]" id="inputBlock[]" class="vc-switch-input">
                                <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                                <span class="vc-handle"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="reward">
                    <div class="form-imgs-container">

                        <div class="input-group mb-3">

                            <div class="input-group-prepend" onclick="selectAndHide()">
                            </div>
                            <div class="custom-file">
                                <input type="file" name="imagem[]" class="custom-file-input" class="imageRewardFile[]"
                                    accept="image/*">
                                <label class="custom-file-label">Escolha sua imagem.</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputfname[]">Nome:</label>
                        <font color="red">*</font>
                        <input type="text" name="name[]" class="form-control" id="inputfname[]" placeholder="Chaveiro">
                    </div>
                    <div class="form-group">
                        <label for="inputamount[]">Quantidade:</label>
                        <font color="red">*</font>
                        <input type="text" name="amount[]" class="form-control" id="inputamount[]" placeholder="10">
                    </div>
                    <div class="form-group">
                        <label for="inputprice[]">Preço:</label>
                        <font color="red">*</font>
                        <input type="text" name="price[]" class="price form-control" id="inputprice[]" placeholder="10.00">
                    </div>

                    <div class="form-group">
                        <label for="inputBlock[]">Deseja que este Prêmio fique bloqueado?</label>
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" 
                title="Para assegurar que ninguém possa reivindicar este prêmio, mantenha-o bloqueado."></i></small>
                        <div class="vc-toggle-container">
                            <label class="vc-switch">
                                <input type="checkbox" name="block[]" id="inputBlock[]" class="vc-switch-input">
                                <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                                <span class="vc-handle"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <input name="token" type="text" value="<?php echo addToken('createGameTele') ?>" hidden />
            <button class="btn btn-primary" type="submit">Criar</button>
        </form>
    </div>

    <script>

        function showHideDiv() {
            var select = document.getElementById("raffle_type");
            var selectedOption = select.options[select.selectedIndex].value;

            // Esconda todas as divs
            document.getElementById("scratch").style.display = "none";
            document.getElementById("telesena").style.display = "none";

            // Mostre a div correspondente à opção selecionada
            if (selectedOption === "1") {
                document.getElementById("scratch").style.display = "block";
            }
            else if (selectedOption === "2") {
                document.getElementById("telesena").style.display = "block";
            }
            // Adicione mais condições conforme necessário para outras opções
        }
    </script>

    <?php

    include_once(realpath(__DIR__ . "/front/php/footer.php"));
    ?>