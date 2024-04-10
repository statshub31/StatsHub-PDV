<?php
include_once (realpath(__DIR__ . "/front/php/header.php"));





if ($_SERVER['REQUEST_METHOD'] === 'POST' && isUsersExist($_POST['user_id'])) {

    $user_id = $_POST['user_id'];
    $account_id = getUsersAccountId($user_id);
    $dir = '/front/images/users/';
    $format = getPathImageFormat($dir, $user_id);

    if (empty($user_id)) {
        header('Location: /admin/users', true, 302);
        exit; // Certifique-se de encerrar a execução do script após o redirecionamento
    }

    if (getToken('updateToken' . $user_id)) {
        if (empty($_POST) === false) {
            $required_fields = array(
                'fname',
                'lname',
                'phone',
                'email',
            );

            ## Remove os que estão desativados da verificação
            if (getDatabaseSettingsPasswordStatus(1) && isset($_POST['password'])) {
                $required_fields[] = 'password';

                if (getSizeString($_POST['password'], 2, 15) !== true) {
                    $errors[] = "No campo da senha, é permitido somente senhas entre 2 a 15 caracteres.";
                }

            }

            if (getDatabaseSettingsCPFStatus(1)) {
                $required_fields[] = 'cpf';


                if (isSameCharacter(sanitizeString($_POST['cpf'])) || preg_match("/^[0-9]+$/", sanitizeString($_POST['cpf'])) === false) {
                    $errors[] = "O campo CPF foi preenchido invalidamente, só é aceito caracteres numérico.";
                }

                if (doCPFValidation(sanitizeString($_POST['cpf'])) === false) {
                    $errors[] = "O CPF preenchido é invalido.";
                }

                if (!empty($_POST['cpf'] && getUsersIDByCPF($_POST['cpf']) != $user_id)) {
                    if (isUsersExistByCPF($_POST['cpf'])) {
                        $errors[] = "O CPF informado já se encontra registrado em nosso banco de dados. Caso você tenha esquecido a senha, entre em contato com o suporte.";
                    }
                }

            }

            if (getDatabaseSettingsAddressStatus(1)) {

                $required_fields[] = 'zipcode';
                $required_fields[] = 'publicplace';
                $required_fields[] = 'neighborhood';
                $required_fields[] = 'number';
                $required_fields[] = 'complement';
                $required_fields[] = 'city';
                $required_fields[] = 'state';

                if (isSameCharacter(sanitizeString($_POST['zipcode'])) || preg_match("/^[0-9]+$/", sanitizeString($_POST['zipcode'])) === false) {
                    $errors[] = "O campo CEP foi preenchido invalidamente, só é aceito caracteres numérico.";
                }

                if (isSameCharacter(sanitizeString($_POST['number'])) || preg_match("/^[0-9]+$/", sanitizeString($_POST['number'])) === false) {
                    $errors[] = "O campo número foi preenchido invalidamente, só é aceito caracteres numérico.";
                }

            }


            if (isset($_POST['group_id'])) {
                if (getAccountsGroupID($userData['id']) < 4) {
                    $errors[] = "Você não tem permissão, para alterar o cargo deste usuário.";
                }
                ## Verificação de tipo
                if (isGroupsExist($_POST['group_id']) === false) {
                    $errors[] = "Desculpe, ocorreu um problema ao processar a alteração. Por favor, tente novamente.";
                }

            }

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
            }


            if (getSizeString($_POST['fname'], 2, 50) !== true || getWordCount($_POST['fname'], 1) === false) {
                $errors[] = "Nome invalido, o mesmo é muito curto ou muito longo.";
            }

            if (getStringAZ($_POST['fname']) === false) {
                $errors[] = "No campo nome, somente é aceito caracteres alfabetico e sem acentuação.";
            }

            if (getStringAZ($_POST['lname']) === false) {
                $errors[] = "No campo sobrenome, somente é aceito caracteres alfabetico e sem acentuação.";
            }

            if (isSameCharacter(sanitizeString($_POST['phone'])) || preg_match("/^[0-9]+$/", sanitizeString($_POST['phone'])) === false) {
                $errors[] = "O campo telefone foi preenchido invalidamente, só é aceito caracteres numérico.";
            }
            ## Verificar existencia

            if (isAccountsCBCodeExist($_POST['accountCB']) === false) {
                $errors[] = "Esse código não se encontra registrado em nosso banco de dados.";
            }

            if (!empty($_POST['email'] && getAccountsExistByEmail($_POST['email']) != $account_id)) {
                if (isAccountsExistByEmail($_POST['email'])) {
                    $errors[] = "O Email informado, já se encontra registrado em nosso banco de dados, caso você tenha esquecido a senha, entre em contato com o suporte.";
                }
            }


            if (!empty($_POST['phone'] && getUsersIDByPhone($_POST['phone']) != $user_id)) {
                if (isUsersExistByPhone($_POST['phone'])) {
                    $errors[] = "O telefone informado, já se encontra registrado em nosso banco de dados, caso você tenha esquecido a senha, entre em contato com o suporte.";
                }
            }

            if (isset($_FILES['photo']) && $_FILES['photo']['size'] > 0) {
                // Verifica se o arquivo foi enviado sem erros
                if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
                    $errors[] = 'Erro no upload do arquivo.';
                }

                $imageInfo = getimagesize($_FILES['photo']['tmp_name']);
                if (!$imageInfo || !in_array($imageInfo['mime'], array('image/png', 'image/jpeg', 'image/gif'))) {
                    $errors[] = 'O arquivo não é uma imagem válida.';
                } elseif ($imageInfo[0] > 2500 || $imageInfo[1] > 2500) {
                    $errors[] = 'A imagem excede o tamanho máximo permitido (2500x2500 pixels).';
                }
            }

        }


        if (empty($errors)) {

            $image = True;
            $targetPath = __DIR__ . '/../front/images/users';

            if (isset($_FILES['photo']) && $_FILES['photo']['size'] > 0) {
                removerArquivos($targetPath . '/', $user_id);
                $fileInfo = pathinfo($_FILES['photo']['name']);
                $fileExtension = $fileInfo['extension'];


                if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath . '/' . $user_id . '.' . $fileExtension) === False) {
                    $errors[] = 'Falha ao mover o arquivo para o diretório de destino.';
                    $image = False;
                }
            }

            $account_query_data = array(
                'password' => (!empty($_POST['password']) ? md5($_POST['password']) : NULL),
                'email' => (!empty($_POST['email']) ? $_POST['email'] : NULL),
                'group_id' => (!empty($_POST['group_id']) ? $_POST['group_id'] : NULL),
            );


            doUpdateAccountsRow($account_id, $account_query_data);

            if (getAccountsCBCodeAccountID($account_id) !== false) {
                if (getAccountsCBCodeAccountID($account_id)) {
                    doRemoveUsersOfCode(getAccountsCBCodeIdByAccountID($account_id));
                }

                $account_cb_left = array(
                    'account_id' => $account_id
                );
                doUpdateAccountsCBRow($_POST['accountCB'], $account_cb_left);
            }

            $user_account_data = array(
                'fname' => (!empty($_POST['fname']) ? $_POST['fname'] : NULL),
                'lname' => (!empty($_POST['lname']) ? $_POST['lname'] : NULL),
                'phone' => (!empty($_POST['phone']) ? $_POST['phone'] : NULL),
                'cpf' => (!empty($_POST['cpf']) ? $_POST['cpf'] : NULL),
                'zip_code' => (!empty($_POST['zipcode']) ? $_POST['zipcode'] : NULL),
                'publicplace' => (!empty($_POST['publicplace']) ? $_POST['publicplace'] : NULL),
                'neighborhood' => (!empty($_POST['neighborhood']) ? $_POST['neighborhood'] : NULL),
                'number' => (!empty($_POST['number']) ? $_POST['number'] : NULL),
                'complement' => (!empty($_POST['complement']) ? $_POST['complement'] : NULL),
                'city' => (!empty($_POST['city']) ? $_POST['city'] : NULL),
                'state' => (!empty($_POST['state']) ? $_POST['state'] : NULL),
            );

            doUpdateUsersRow($user_id, $user_account_data);

            echo alertSuccess("As modificações neste cadastro foram salvas com êxito.", "/admin/users");
            destroyToken('updateToken' . $user_id);
        }


        if (empty($errors) === false) {
            header("HTTP/1.1 401 Not Found");
            echo alertError($errors);
        }

    }

    ?>
    <form action="/admin/updateuser" method="post" enctype="multipart/form-data">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <label class="input-group-text" for="accountCB">Código de Cliente <small><i class="fa fa-question-circle"
                            aria-hidden="true" data-toggle="tooltip" data-placement="top"
                            title="Escolha o código do cliente."></i></small></label>
            </div>
            <select name="accountCB" class="custom-select" id="accountCB" onchange="showHideDiv()">
                <option value="0">-- Selecione --</option>
                <?php
                $accountCB = doAccountsCBCodeListAvailablev2($account_id);
                if ($accountCB) {
                    foreach ($accountCB as $data) {
                        ?>
                        <option value="<?php echo ($data['id']) ?>" <?php echo (getAccountsCBCodeIdByAccountID($account_id) == $data['id']) ? 'selected' : '' ?>>
                            <?php echo (getAccountsCBCode($data['id'])) ?>
                        </option>
                        <?php
                    }
                }
                ?>
            </select>
        </div>

        <!-- FOTO -->
        <div class="form-imgs-container">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Foto</span>
                    <div id="previewImagem" style="width: 100px; height: 100px; overflow: hidden; border: 1px solid #ccc;">
                        <img id="imagemSelecionada" style="width: 100%; height: auto;">
                    </div>
                </div>
                <div class="custom-file">
                    <input type="file" name="photo" class="custom-file-input" id="photo" accept="image/*">
                    <label class="custom-file-label" for="photo">Escolha sua foto.</label>
                </div>
            </div>
        </div>

        <script>
            jQuery(document).ready(function ($) {

                function verificarImagem(caminhoDaImagem, $id) {
                    // Adiciona um número aleatório à URL da imagem
                    var novaUrl = caminhoDaImagem + '?' + Math.random();

                    // Atualiza o atributo 'src' da imagem
                    $($id).attr('src', novaUrl);
                }

                $(document).ready(function () {
                    verificarImagem('/front/images/users/<?php echo $user_id ?>.<?php echo $format ?>', '#imagemSelecionada');
                });

                function exibirImagem(input) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#imagemSelecionada').attr('src', e.target.result);
                    };

                    reader.readAsDataURL(input.files[0]);
                }

                $(document).ready(function () {
                    $('#photo').change(function () {
                        exibirImagem(this);
                    });
                });
            });
        </script>

        <!-- FOTO -->
        <div class="form-group">
            <label for="inputfname">Nome:</label>
            <font color="red">*</font>
            <input type="text" name="fname" class="form-control" id="inputfname"
                value="<?php echo getUsersFName($user_id) ?>">
        </div>
        <div class="form-group">
            <label for="inputlname">Sobrenome:</label>
            <font color="red">*</font>
            <input type="text" name="lname" class="form-control" id="inputlname"
                value="<?php echo getUsersLName($user_id) ?>">
        </div>
        <?php
        if (getDatabaseSettingsPasswordStatus(1)) {
            ?>
            <div class="form-group">
                <label for="inputpassword">Senha:</label>
                <font color="red">*</font>
                <input type="password" name="password" class="form-control" id="inputpassword" value="Senha">
            </div>
            <?php
        }
        ?>
        <div class="form-group">
            <label for="inputphone">Telefone:</label>
            <font color="red">*</font>
            <input type="text" name="phone" class="form-control" id="inputphone"
                value="<?php echo getUsersPhone($user_id) ?>">
        </div>
        <?php
        if (getDatabaseSettingsCPFStatus(1)) {
            ?>
            <div class="form-group">
                <label for="inputphone">CPF:</label>
                <font color="red">*</font>
                <input type="text" name="cpf" class="form-control" id="inputphone" value="<?php echo getUsersCPF($user_id) ?>">
            </div>
            <?php
        }
        ?>
        <div class="form-group">
            <label for="inputemail">Email:</label>
            <font color="red">*</font>
            <input type="email" name="email" class="form-control" id="inputemail"
                value="<?php echo getAccountsEmail($account_id) ?>">
        </div>

        <?php
        if (getDatabaseSettingsAddressStatus(1)) {
            ?>
            <br>
            <hr><br>

            <div class="form-group">
                <label for="zipcode">CEP:</label>
                <font color="red">*</font>
                <input type="text" name="zipcode" class="form-control" id="zipcode"
                    value="<?php echo getUsersZIPCode($user_id) ?>" oninput="consultarCEP()">
                <p style="margin: 10px 0px;"></p>
                <label for="publicplace">Logradouro</label>
                <font color="red">*</font>
                <input type="text" name="publicplace" class="form-control" id="publicplace"
                    value="<?php echo getUsersPublicPlace($user_id) ?>" readonly>
                <p style="margin: 10px 0px;"></p>
                <label for="neighborhood">Bairro:</label>
                <font color="red">*</font>
                <input type="text" name="neighborhood" class="form-control" id="neighborhood"
                    value="<?php echo getUsersNeighborhood($user_id) ?>" readonly>
                <p style="margin: 10px 0px;"></p>
                <label for="number">Número:</label>
                <font color="red">*</font>
                <input type="text" name="number" class="form-control" id="number"
                    value="<?php echo getUsersNumber($user_id) ?>">
                <p style="margin: 10px 0px;"></p>
                <label for="complement">Complemento:</label>
                <input type="text" name="complement" class="form-control" id="complement"
                    value="<?php echo getUsersComplement($user_id) ?>">
                <p style="margin: 10px 0px;"></p>
                <label for="city">Cidade:</label>
                <font color="red">*</font>
                <input type="text" name="city" class="form-control" id="city" value="<?php echo getUsersCity($user_id) ?>"
                    readonly>
                <p style="margin: 10px 0px;"></p>
                <label for="state">Estado:</label>
                <font color="red">*</font>
                <input type="text" name="state" class="form-control" id="state" value="<?php echo getUsersState($user_id) ?>"
                    readonly>
            </div>

            <?php
        }
        if (getAccountsGroupID($userData['id']) > 3) {
            ?>


            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="raffle_type">Cargo <small><i class="fa fa-question-circle"
                                aria-hidden="true" data-toggle="tooltip" data-placement="top"
                                title="Função ou posição que ele ocupará."></i></small></label>
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
            <?php

        }
        ?>
        <br>
        <input type="hidden" name="user_id" value="<?php echo $user_id ?>" />
        <input type="hidden" name="token" value="<?php echo addToken('updateToken' . $user_id) ?>" />
        <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
    <script>
        function consultarCEP() {
            const cepInput = document.getElementById('zipcode').value;

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
        function preencherCampos(data) {
            document.getElementById('publicplace').value = data.logradouro || '';
            document.getElementById('neighborhood').value = data.bairro || '';
            document.getElementById('city').value = data.localidade || '';
            document.getElementById('state').value = data.uf || '';
        }

    </script>
    <?php
} else {
    header('Location: /admin/users', true, 302);
    exit; // Certifique-se de encerrar a execução do script após o redirecionamento
}

include_once (realpath(__DIR__ . "/front/php/footer.php"));
?>