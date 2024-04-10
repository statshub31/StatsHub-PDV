<?php
include_once(realpath(__DIR__ . "/front/php/header.php"));
getAdminAccess();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST) && getToken('sendEmail')) {
    showLoading();

    if (empty($_POST) === false) {
        $required_fields = array('typeSend', 'subject', 'message');

        if ($_POST['typeSend'] == 1)
            $required_fields[] = 'oneEmail';


        if (validateRequiredFields($_POST, $required_fields) === false) {
            $errors[] = "Obrigatório o preenchimento de todos os campos.";
        }

        if ($_POST['typeSend'] < 1 || $_POST['typeSend'] > 2) {
            $errors[] = "Tipo de envio inválido. Se o problema persistir, entre em contato com um administrador.";
        }

    }


    if (empty($errors)) {
        if ($_POST['typeSend'] == 1) {
            $additionalRecipients = [$_POST['oneEmail'], ''];
            $reward_msg =
                array(
                    'email' => $_POST['oneEmail'],
                    'name' => 'No Reply ' . getDatabaseSettingsTitle(1),
                    'subject' => $_POST['subject'],
                    'body' => $_POST['message']
                );
            doSendEmail($reward_msg);
        } else
            if ($_POST['typeSend'] == 2) {

                $list = doAccountsList();
                $additionalRecipients = [];
                if ($list) {
                    foreach ($list as $data) {
                        $account_id = $data['id'];
                        $user_id = getUsersIDByAccountID($account_id);
                        $additionalRecipients[getAccountsEmail($account_id)] = getUsersCName($user_id);
                    }
                }
                $reward_msg =
                    array(
                        'email' => $_POST['oneEmail'],
                        'name' => 'No Reply ' . getDatabaseSettingsTitle(1),
                        'subject' => $_POST['subject'],
                        'body' => $_POST['message']
                    );

                doSendEmailMultiples($reward_msg, false, $additionalRecipients);
                echo alertSuccess("Sucesso!!");
            }


        $conteudo[] = '
            <div>
                <b>Funcionário:</b> ' . getUsersCName($userData['id']) . '<br>
                <b>Data:</b> ' . date("d/m/Y H:i:s") . '<br>
                <b>Assunto:</b> ' . $_POST["subject"] . '<br>
                <b>Mensagem</b>: 
                <div style="border: 1px solid black; padding: 10px;">' . processContent($_POST['message']) . '</div>
            </div>
            <div style="page-break-before: always;">
                <table border="1">
                    <tr>
                        <th>#</th>
                        <th>Email</th>
                        <th>Usuário</th>
                    </tr>';
        $c = 0;
        foreach ($additionalRecipients as $email => $name) {
            ++$c;
            $conteudo[] = '
                <tr>
                    <td>#' . $c . '</td>
                    <td>' . $email . '</td>
                    <td>' . $name . '</td>
                </tr>';
        }
        $conteudo[] = '
                </table>
            </div>
            ';
        $conteudo = implode($conteudo);
        doCreateExportPDF($conteudo, "email_" . date("d_m_Y_H_i_s") . ".pdf");
    }


    if (empty($errors) === false) {
        header("HTTP/1.1 401 Not Found");
        echo alertError($errors);
    }
    hiddenLoading();
}


?>
<script>
    function showHideDiv() {
        var raffleType = document.getElementById("formSend");
        var divToToggle = document.getElementById("divToToggle");

        if (raffleType.value == "1") {
            divToToggle.style.display = "block";
        } else {
            divToToggle.style.display = "none";
        }
    }
</script>


<div class="alert alert-info" role="alert">
    <center>
        <h3>Observações</h3>
    </center>
    <h5><strong>1.</strong> Registro de Emails:</h5>
    <strong>1.1.</strong> Todos os emails encaminhados serão automaticamente registrados e seus logs serão
    armazenados em um arquivo PDF.<br>
    <strong>1.2.</strong> O acesso aos logs só será permitido mediante solicitação técnica.<br><br>

    <h5><strong>2.</strong> Tratamento de Imagens:</h5>
    <strong>2.1.</strong> A funcionalidade de recortar (CTRL+C/CTRL+V) imagens não está disponível.<br>
    <strong>2.2.</strong> Para enviar imagens, utilize exclusivamente a barra de ferramentas designada para esse
    fim.<br><br>

    <h5><strong>3.</strong> Envio para Todos os Usuários:</h5>
    <strong>3.1.</strong>Se a opção de enviar para todos os usuários for selecionada, esteja ciente de que o processo
    pode levar algum
    tempo, dependendo da quantidade de clientes cadastrados.</p>

</div>

<form action="/admin/sendemail" method="post">
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <label class="input-group-text" for="formSend">Deseja encaminhar email para:<small><i
                        class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                        title="Para quem será encaminhado."></i></small></label>
        </div>
        <select name="typeSend" class="custom-select" id="formSend" onchange="showHideDiv()">
            <option value="0">-- Selecione --</option>
            <option value="1">Usuário Especifico</option>
            <option value="2">Todos os usuários</option>
        </select>
    </div>


    <div id="divToToggle" style="display: none;">
        <div class="form-row align-items-center">
            <div class="w-100">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Email</div>
                    </div>
                    <input type="text" class="form-control" name="oneEmail" id="oneEmail"
                        placeholder="usuario@gmail.com">
                </div>
            </div>
        </div>
    </div>


    <div class="form-row align-items-center">
        <div class="w-100">
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text">Assunto:</div>
                </div>
                <input type="text" class="form-control" name="subject" id="subject">
            </div>
        </div>
    </div>
    <script src="//js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
    <script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>
    <div class="form-row align-items-center">
        <div class="w-100">
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text">Mensagem:</div>
                </div>
                <textarea class="form-control" name="message" id="message" rows="3"></textarea>

            </div>
        </div>
    </div>


    <input name="token" type="text" value="<?php echo addToken('sendEmail') ?>" hidden />
    <button type="submit" class="btn btn-primary btn-user">Enviar</button>
</form>
<?php
include_once(realpath(__DIR__ . "/front/php/footer.php"));
?>