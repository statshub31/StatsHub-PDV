<?php
include_once (__DIR__ . "/engine/init.php");
doGeneralSecurityLoginRedirect()

?>
<?php
// <!-- INICIO DA VALIDAÇÃO PHP -->
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {

    // LOGIN
    if (getGeneralSecurityToken('tokenLogin')) {
        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('username', 'password');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                $login = getDatabaseAccountLoginValidation($_POST['username'], $_POST['password']);
                if ($login === false) {
                    $errors[] = "Usuário e senha, não é reconhecido em nosso banco de dados.";
                } else {
                    if (isDatabaseAccountBlock($login)) {
                        $errors[] = "Não é possível o login para está conta, a mesma está bloqueada.";
                    }
                }

            }
        }


        if (empty($errors)) {

            $login = getDatabaseAccountLoginValidation($_POST['username'], $_POST['password']);

            setGeneralSecuritySession('account_id', $login);
            header('Location: /myaccount');
            exit();
            // Coloque o código que deve ser executado após as verificações bem-sucedidas aqui
        }
    }


    // REGISTER
    if (getGeneralSecurityToken('tokenRegister')) {
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


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ===== CSS ===== -->
    <link rel="stylesheet" href="/layout/css/login.css">

    <!-- ===== BOX ICONS ===== -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>

    <title>Login</title>
</head>

<body>
    <div class="login">
        <div class="login__content">
            <div class="login__img">
                <img src="/layout/images/config/login.png" alt="">
            </div>

            <div class="login__forms">
                <form action="/login" method="POST" class="login__registre" id="login-in">
                    <h1 class="login__title">Login</h1>

                    <div class="login__box">
                        <i class='bx bx-user login__icon'></i>
                        <input type="text" name="username" placeholder="Usuário" class="login__input">
                    </div>

                    <div class="login__box">
                        <i class='bx bx-lock-alt login__icon'></i>
                        <input type="password" name="password" placeholder="Senha" class="login__input">
                    </div>

                    <a href="#" class="login__forgot">Esqueceu sua senha?</a>

                    <input name="token" type="text" value="<?php echo addGeneralSecurityToken('tokenLogin') ?>"
                        hidden />
                    <button type="submit" style="width: 100%; border: none; cursor: pointer"
                        class="login__button">Entrar</button>


                    <div>
                        <span class="login__account">Não tem uma conta?</span>
                        <span class="login__signin" id="sign-up">Cadastre</span>
                    </div>
                </form>

                <form action="/login" method="POST" class="login__create none" id="login-up">
                    <h1 class="login__title">Criar Conta</h1>

                    <div class="login__box">
                        <i class='bx bx-user login__icon'></i>
                        <input type="text" name="username" placeholder="Usuário" class="login__input">
                    </div>

                    <div class="login__box">
                        <i class='bx bx-lock-alt login__icon'></i>
                        <input type="password" name="password" placeholder="Senha" class="login__input">
                    </div>

                    <div class="login__box">
                        <i class='bx bx-user login__icon'></i>
                        <input type="text" name="name" placeholder="Nome" class="login__input">
                    </div>

                    <div class="login__box">
                        <i class='bx bx-at login__icon'></i>
                        <input type="text" name="email" placeholder="Email" class="login__input">
                    </div>

                    <div class="login__box">
                        <i class='bx bx-phone login__icon'></i>
                        <input type="text" name="phone" placeholder="Celular" class="login__input">
                    </div>

                    <div class="login__box">
                        <input type="checkbox" name="rules">
                        <span>Você confirma que leu os termos?</span>
                    </div>

                    <input name="token" type="text" value="<?php echo addGeneralSecurityToken('tokenRegister') ?>"
                        hidden />
                    <button type="submit" style="width: 100%; border: none; cursor: pointer"
                        class="login__button">Cadastrar</button>
                    <div>
                        <span class="login__account">Já tem uma conta ?</span>
                        <span class="login__signup" id="sign-in">Entre</span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--===== MAIN JS =====-->
    <script src="/layout/js/login.js"></script>
</body>

</html>