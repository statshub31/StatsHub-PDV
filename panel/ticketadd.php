<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));
?>

<?php
// <!-- INICIO DA VALIDAÇÃO PHP -->
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {


    // REMOVE USER

    // if (getGeneralSecurityToken('tokenAddTicket')) {
    if (1 == 1) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('code', 'value', 'amount');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                if (doGeneralValidationCodeFormat($_POST['code']) === false) {
                    $errors[] = "Altere o código, ele precisa possuí caracteres alfanumérico.";
                }

                if (doGeneralValidationDiscountFormat($_POST['value']) === false) {
                    $errors[] = "Altere o desconto, só é aceito números de 0 a 9 e %.";
                }

                if (doGeneralValidationNumberFormat($_POST['amount']) === false) {
                    $errors[] = "Altere a quantidade, só é aceito números de 0 a 9.";
                }

                if (!empty($_POST['expiration'])) {
                    if ($_POST['expiration'] <= date("Y-m-d")) {
                        $errors[] = "Altere a data de expiração, ela precisa ser superior a atual.";
                    }
                }

                if (isDatabaseTicketEnabledByCode($_POST['code'])) {
                    $errors[] = "Existe um código desse ativo, para criar outro com o mesmo código é necessário encerrar o atual.";
                }

                if (isGeneralSecurityManagerAccess() === false) {
                    $errors[] = "É obrigatório ter um cargo igual ou superior ao de gestor, para executar está ação.";
                }
            }

        }


        if (empty($errors)) {

            $ticket_insert_field = array(
                'code' => $_POST['code'],
                'value' => $_POST['value'],
                'amount' => $_POST['amount'],
                'expiration' => (!empty($_POST['expiration']) ? $_POST['expiration'] : NULL),
                'created' => date('Y-m-d H:i:s'),
                'created_by' => $in_user_id,
                'status' => 2
            );

            doDatabaseTicketInsert($ticket_insert_field);

            doAlertSuccess("O cupom foi criado com sucesso.");

        }
    }



    if (empty($errors) === false) {
        header("HTTP/1.1 401 Not Found");
        echo doAlertError($errors);
    }
}


// <!-- FINAL DA VALIDAÇÃO PHP -->
?>


<form action="/panel/ticketadd" method="post">

    <div id="product-add-info" class="content">
        <div id="product-info">
            <section id="product-left">
                <div class="form-group">
                    <label for="cod">COD. Pers.
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top"
                                title="Caso estiver em branco, será criado um código aleatorio."></i></small>
                    </label>
                    <font color="red">*</font>
                    <input type="text" name="code" class="form-control" id="cod" value="">
                </div>

                <div class="form-group">
                    <label for="amount">Quantidade:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top"
                                title="Quantidade de cupons. Caso fique em branco, será ilimitado."></i></small>
                    </label>
                    <input type="text" name="amount" class="form-control" id="amount" value="">
                </div>
            </section>

            <section id="product-left">

                <div class="form-group">
                    <label for="discount">Valor de Desconto:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top"
                                title="Caso queira o desconto em porcentagem, adicione o %."></i></small>
                    </label>
                    <font color="red">*</font>
                    <input type="text" name="value" class="form-control" id="discount" value="">
                </div>

                <div class="form-group">
                    <label for="expiration">Data de Expiração:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top"
                                title="Após a data mencionada, não poderá mais ser utilizado o cupom. Caso fique em branco, não irá expirar."></i></small>
                    </label>
                    <input type="date" name="expiration" class="form-control" id="expiration" value="">
                </div>
            </section>
        </div>
    </div>

    <br>
    <input name="token" type="text" value="<?php echo addGeneralSecurityToken('tokenAddTicket') ?>" hidden>
    <button type="submit" class="btn btn-primary">Atualizar</button>
</form>

<?php
include_once (realpath(__DIR__ . "/layout/php/footer.php"));
?>