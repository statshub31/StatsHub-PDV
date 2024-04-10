<?php
include_once(realpath(__DIR__ . "/front/php/header.php"));
getDisponibleAccountCB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = 1;
    $account_id = getUsersAccountID($user_id);

    if (getToken('newMemberConfirm')) {
        if (empty($_POST) === false) {
            $required_fields = array(
                'fname',
                'lname',
                'phone',
                'email',
                'accountCB'
            );

            ## Remove os que estão desativados da verificação
            if (getDatabaseSettingsPasswordStatus(1)) {
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

                if (isUsersExistByCPF(sanitizeString($_POST['cpf'])) !== false) {
                    $errors[] = "O CPF informado, já se encontra registrado em nosso banco de dados, caso você tenha esquecido a senha, entre em contato com o suporte.";
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

            ## Verificação de tipo
            if (!empty($_POST['invite'])) {
                $invite_id = getAccountsCBCodeIdByCode($_POST['invite']);
                if ($invite_id) {
                    if (!getAccountsCBCodeAvailable($invite_id)) {
                        $errors[] = "O código de indicação já foi utilizado por outro usuário.";
                    }
                } else {
                    $errors[] = "O código de indicação não existe.";
                }
            }
            

            if (doAccountsCBCodeCount() <= 0) {
                $errors[] = "Antes de inserir o usuário, é necessário cadastrar novos códigos de usuário.";
            }

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
            }

            if (isAccountsCBCodeExist($_POST['accountCB']) === false) {
                $errors[] = "Esse código não se encontra registrado em nosso banco de dados.";
            }

            if (getAccountsCBCodeAvailable($_POST['accountCB']) === false) {
                $errors[] = "Esse código não se encontra disponível.";
            }

            if (getSizeString($_POST['fname'], 2, 40) !== true || getWordCount($_POST['fname'], 1) === false) {
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

            if (isAccountsExistByEmail($_POST['email']) !== false) {
                $errors[] = "O Email informado, já se encontra registrado em nosso banco de dados, caso você tenha esquecido a senha, entre em contato com o suporte.";
            }

            if (isUsersExistByPhone(sanitizeString($_POST['phone'])) !== false) {
                $errors[] = "O telefone informado, já se encontra registrado em nosso banco de dados, caso você tenha esquecido a senha, entre em contato com o suporte.";
            }

        }


        if (empty($errors)) {
            $username = getAccountsCBCode($_POST['accountCB']);

            $account_query_data = array(
                'username' => $username,
                'password' => (!empty($_POST['password']) ? md5($_POST['password']) : md5($username)),
                'email' => (!empty($_POST['email']) ? $_POST['email'] : NULL),
                'group_id' => 1,
                'ip' => getIPLong(),
                'rules' => (isset($_POST['rules']) ? 1 : 0),
                'created' => date('Y-m-d'),
            );

            if (!empty($_POST['invite'])) {
                $account_query_data['invited_id'] = getAccountsCBCodeIdByCode($_POST['invite']);
            }

            $account_id = doInsertAccountsRow($account_query_data);

            $code_query_data = array(
                'account_id' => $account_id,
            );


            doUpdateAccountsCBRowByCode($username, $code_query_data);

            $user_account_data = array(
                'account_id' => $account_id,
                'fname' => (!empty($_POST['fname']) ? $_POST['fname'] : NULL),
                'lname' => (!empty($_POST['lname']) ? $_POST['lname'] : NULL),
                'phone' => (!empty($_POST['phone']) ? $_POST['phone'] : NULL),
                'cpf' => (!empty($_POST['cpf']) ? $_POST['cpf'] : NULL),
                'zipcode' => (!empty($_POST['zipcode']) ? $_POST['zipcode'] : NULL),
                'publicplace' => (!empty($_POST['publicplace']) ? $_POST['publicplace'] : NULL),
                'neighborhood' => (!empty($_POST['neighborhood']) ? $_POST['neighborhood'] : NULL),
                'number' => (!empty($_POST['number']) ? $_POST['number'] : NULL),
                'complement' => (!empty($_POST['complement']) ? $_POST['complement'] : NULL),
                'city' => (!empty($_POST['city']) ? $_POST['city'] : NULL),
                'state' => (!empty($_POST['state']) ? $_POST['state'] : NULL),
            );

            $user_id = doInsertUsersRow($user_account_data);


            $user_social_data = array(
                'user_id' => $user_id,
                'inst_status' => 0,
                'face_status' => 0,
                'whats_status' => 0,
            );

            doInsertUserSocialRow($user_social_data);

            $imgDir = realpath(__DIR__ . "/../front/images/");
            $raffleDir = realpath(__DIR__ . "/../front/images/users");
            copy($imgDir . "/avatar.png", $raffleDir . "/" . $user_id . ".png");

            $msg = (empty($_POST['password'])) ? '<p>O código do cliente também serve como sua senha inicial. Fique tranquilo, você pode acessar nosso <a href="' . getDatabaseSettingsURL(1) . '">site</a> e realizar a troca da senha a qualquer momento, mantendo o mesmo código de cliente.</strong></p>' : '';
            $import_data =
                array(
                    'email' => $_POST['email'],
                    'name' => 'No Reply ' . getDatabaseSettingsTitle(1),
                    'subject' => 'Informações de Acesso',
                    'body' => '
                <p>Olá ' . getUsersCName($user_id) . ',</p>
                <p>Agradecemos sinceramente por se tornar um apoiador da nossa loja! É com grande prazer que o recebemos.</p>
                <p>Seu código de cliente é: <strong>[' . $username . '].</strong></p>
                ' . $msg . '
                <p>Estamos entusiasmados em tê-lo como nosso cliente e ansiosos para construir uma parceria duradoura.</p>           
                <p>Em caso de dúvidas ou necessidade de assistência, não hesite em entrar em contato. Juntos, podemos criar um impacto positivo!</p>        
                <p>Agradecemos mais uma vez por se juntar a nós.</p>
    
                <p>Atenciosamente,</p>
                <p>Equipe ' . getDatabaseSettingsTitle(1) . '</p>'
                );

            doSendEmail($import_data);




            if (getDatabaseSettingsGameRegisterStatus(1) && isGameRegisterExist()) {

                $reward_msg =
                    array(
                        'email' => $_POST['email'],
                        'name' => 'No Reply ' . getDatabaseSettingsTitle(1),
                        'subject' => 'Premiação pelo Apoio',
                        'body' => '
        <p>Prezado ' . getUsersCName($user_id) . ',</p>

        <p>Agradecemos pela sua participação em nossa loja! Como forma de reconhecimento, temos o prazer de informar que você tem direito a um prêmio especial. Basta clicar no link abaixo, ir até a loja e resgatar o seu prêmio exclusivo:</p>
    
        <p><a href="' . getDatabaseSettingsURL(1) . '/gamecode/' . randomGamesScratchCardsCode(getGameRegisterID()) . '">Resgatar Prêmio</a></p>
    
        <p>Agradecemos novamente pela sua fidelidade e esperamos que aproveite o seu prêmio.</p>
    
        <p>Atenciosamente,</p>
        <p>Equipe ' . getDatabaseSettingsTitle(1) . '</p>'
                    );
                doSendEmail($reward_msg);
            }

            echo alertSuccess("Cadastro concluído com sucesso! O código do cliente foi enviado para o e-mail cadastrado. Peça ao cliente para que confira sua caixa de entrada para mais informações.", '/admin/users');

        }


        if (empty($errors) === false) {
            header("HTTP/1.1 401 Not Found");
            echo alertError($errors);
        }
    }


}


?>
<form action="/admin/usersadd" method="post">
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <label class="input-group-text" for="accountCB">Código de Cliente <small><i class="fa fa-question-circle"
                        aria-hidden="true" data-toggle="tooltip" data-placement="top"
                        title="Escolha o código do cliente."></i></small></label>
        </div>
        <select name="accountCB" class="custom-select" id="accountCB" onchange="showHideDiv()">
            <option value="0">-- Selecione --</option>
            <?php
            $accountCB = doAccountsCBCodeListAvailable();
            data_dump($accountCB);
            if ($accountCB) {
                foreach ($accountCB as $data) {
                    ?>
                    <option value="<?php echo ($data['id']) ?>">
                        <?php echo (getAccountsCBCode($data['id'])) ?>
                    </option>
                    <?php
                }
            }
            ?>
        </select>
    </div>


    <div class="form-group">
        <label for="inputinvite">Código de Indicação:</label>
        <input type="text" name="invite" class="form-control" id="inputinvite">
    </div>
    <div class="form-group">
        <label for="inputfname">Nome:</label>
        <font color="red">*</font>
        <input type="text" name="fname" class="form-control" id="inputfname" placeholder="Ex: Joao">
    </div>
    <div class="form-group">
        <label for="inputlname">Sobrenome:</label>
        <font color="red">*</font>
        <input type="text" name="lname" class="form-control" id="inputlname" placeholder="Ex: Gonçalves">
    </div>
    <?php
    if (getDatabaseSettingsPasswordStatus(1)) {
        ?>
        <div class="form-group">
            <label for="inputpassword">Senha:</label>
            <font color="red">*</font>
            <input type="password" name="password" class="form-control" id="inputpassword" placeholder="Senha">
        </div>
        <?php
    }
    ?>
    <div class="form-group">
        <label for="inputphone">Telefone:</label>
        <font color="red">*</font>
        <input type="text" name="phone" class="form-control" id="inputphone" placeholder="Ex: (00) 0 0000-0000">
    </div>
    <?php
    if (getDatabaseSettingsCPFStatus(1)) {
        ?>
        <div class="form-group">
            <label for="inputphone">CPF:</label>
            <font color="red">*</font>
            <input type="text" name="cpf" class="form-control" id="inputphone" placeholder="Ex: 000.000.000-00">
        </div>
        <?php
    }
    ?>
    <div class="form-group">
        <label for="inputemail">Email:</label>
        <font color="red">*</font>
        <input type="email" name="email" class="form-control" id="inputemail" placeholder="Ex: teste@gmail.com">
    </div>

    <?php
    if (getDatabaseSettingsAddressStatus(1)) {
        ?>
        <br>
        <hr><br>

        <div class="form-group">
            <label for="zipcode">CEP:</label>
            <font color="red">*</font>
            <input type="text" name="zipcode" class="form-control" id="zipcode" placeholder="Ex: 00000-000"
                oninput="consultarCEP()">
            <p style="margin: 10px 0px;"></p>
            <label for="publicplace">Logradouro</label>
            <font color="red">*</font>
            <input type="text" name="publicplace" class="form-control" id="publicplace" placeholder="Ex: Rua afonso moreno"
                readonly>
            <p style="margin: 10px 0px;"></p>
            <label for="neighborhood">Bairro:</label>
            <font color="red">*</font>
            <input type="text" name="neighborhood" class="form-control" id="neighborhood" placeholder="Ex: Vila Natal"
                readonly>
            <p style="margin: 10px 0px;"></p>
            <label for="number">Número:</label>
            <font color="red">*</font>
            <input type="text" name="number" class="form-control" id="number" placeholder="Ex: 222">
            <p style="margin: 10px 0px;"></p>
            <label for="complement">Complemento:</label>
            <input type="text" name="complement" class="form-control" id="complement"
                placeholder="Ex: Ao lado da padaria São João">
            <p style="margin: 10px 0px;"></p>
            <label for="inputemail">Cidade:</label>
            <font color="red">*</font>
            <input type="text" name="city" class="form-control" id="city" placeholder="Ex: Francisco Morato" readonly>
            <p style="margin: 10px 0px;"></p>
            <label for="state">Estado:</label>
            <font color="red">*</font>
            <input type="text" name="state" class="form-control" id="state" placeholder="Ex: São Paulo" readonly>
        </div>

        <?php
    }
    ?>
    <br>
    <input type="hidden" name="token" value="<?php echo addToken('newMemberConfirm') ?>" />
    <a href="/admin/users">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
    </a>
    <button type="submit" class="btn btn-primary">Cadastrar</button>
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

include_once(realpath(__DIR__ . "/front/php/footer.php"));
?>