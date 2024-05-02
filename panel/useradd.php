<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));
getGeneralSecurityAttendantAccess();

?>

<?php
// <!-- INICIO DA VALIDAÇÃO PHP -->
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {


    // REGISTER
    if (getGeneralSecurityToken('tokenUserAdd')) {
        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('username', 'password', 'name', 'emai', 'phone');
            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                if (doGeneralValidationUserNameFormat($_POST['username']) == false) {
                    $errors[] = "Verificar o campo [usuário], o mesmo possui caracteres invalido. Somente é aceito caracteres alfanuméricos.";
                }

                if (isDatabaseAccountByUsername($_POST['username'])) {
                    $errors[] = "Verificar o campo [usuário], pois o mesmo já é utilizado por outro membro.";
                }

                if (doGeneralValidationPasswordFormat($_POST['password']) == false) {
                    $errors[] = "Verificar o campo [senha], o mesmo possui caracteres invalido. Somente é aceito caracteres [a-z, A-Z, 0-9, !, @, #, $].";
                }

                if (doGeneralValidationNameFormat($_POST['name']) == false) {
                    $errors[] = "Verificar o campo [nome], o mesmo possui caracteres invalido. Somente é aceito caracteres alfabético.";
                }

                if (doGeneralValidationEmailFormat($_POST['email']) == false) {
                    $errors[] = "Verificar o campo [email], o mesmo está num formato inelegivel.";
                }

                if (isDatabaseAccountByEmail($_POST['email'])) {
                    $errors[] = "Verificar o campo [email], pois o mesmo já é utilizado por outro membro.";
                }

                if (isDatabaseUserByPhone($_POST['phone'])) {
                    $errors[] = "Verificar o campo [telefone], pois o mesmo já é utilizado por outro membro.";
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
                if (strlen($_POST['password']) < 8) {
                    $errors[] = "Verificar o campo [senha], a quantidade de caracteres tem que ser maior que 08.";
                }

                if (strlen($_POST['password']) > 20) {
                    $errors[] = "Verificar o campo [senha], a quantidade de caracteres tem que ser maior que 20.";
                }

                if (isset($_POST['rules']) && empty($_POST['rules'])) {
                    $errors[] = "Obrigatório estar de acordo com as regras.";
                }

            }

        }


        if (empty($errors)) {
            $account_database_field = array(
                'username' => $_POST['username'],
                'password' => md5($_POST['password']),
                'email' => $_POST['email'],
                'group_id' => 1,
                'ip' => getGeneralSecurityIPLong(),
                'rules' => (isset($_POST['rules']) && !empty($_POST['rules'])) ? 1 : 0,
                'block' => 0,
                'created' => date("Y-m-d")
            );

            $insert_account_id = doDatabaseAccountInsert($account_database_field);

            $user_database_field = array(
                'account_id' => $insert_account_id,
                'name' => $_POST['name'],
                'phone' => $_POST['phone']
            );

            doDatabaseUserInsert($user_database_field);
            echo doAlertSuccess("O cadastro foi efetuado com sucesso.");
        }
    }

    if (empty($errors) === false) {
        header("HTTP/1.1 401 Not Found");
        echo doAlertError($errors);
    }
}


// <!-- FINAL DA VALIDAÇÃO PHP -->
?>

<form action="/panel/useradd" method="post">

    <div class="form-group">
        <label for="username">Usuário:</label>
        <font color="red">*</font>
        <input type="text" name="username" class="form-control" id="username" value="">
    </div>
    <div class="form-group">
        <label for="name">Nome:</label>
        <font color="red">*</font>
        <input type="text" name="name" class="form-control" id="name" value="">
    </div>
    <div class="form-group">
        <label for="password">Senha:</label>
        <font color="red">*</font>
        <input type="password" name="password" class="form-control" id="password" value="Senha">
    </div>

    <div class="form-group">
        <label for="phone">Telefone:</label>
        <font color="red">*</font>
        <input type="text" name="phone" class="form-control" id="phone" value="">
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <font color="red">*</font>
        <input type="email" name="email" class="form-control" id="email" value="">
    </div>
    <br>
    <!-- <input type="hidden" name="user_id" value="" /> -->

    <input name="token" type="text" value="<?php echo addGeneralSecurityToken('tokenUserAdd') ?>" hidden />
    <a href="/panel/users">
        <button type="button" class="btn btn-secondary">Voltar</button>
    </a>
    <button type="submit" class="btn btn-primary">Adicionar</button>

</form>
<?php
include_once (realpath(__DIR__ . "/layout/php/footer.php"));
?>