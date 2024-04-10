<?php
include_once (realpath(__DIR__ . "/front/php/header.php"));
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {

    if (getToken('registerPurchase')) {
        if (empty($_POST) === false) {
            $required_fields = array('email', 'amount');
            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
            }

            if (empty($_POST["email"]) === false) {
                if (isAccountsExistByEmail($_POST["email"]) === false) {
                    $errors[] = "Desculpe, mas o email é inexistente.";
                }
            }

            if (is_numeric($_POST["price"]) === false) {
                $errors[] = "É obrigatório que o preço seja um valor.";
            }

        }


        if (empty($errors)) {
            $user_id = getAccountsIDByEmail($_POST['email']);
            $import_data_query = array(
                'user_id' => $user_id,
                'amount' => $_POST['amount'],
                'total_price' => (float) $_POST['price'],
                'team_id' => $userData['id'],
                'date_purchase' => date('Y-m-d')
            );

            $experienceActual = getUsersExperience($user_id);
            $array_tele = array();
            $gameTele_id = getGameTypeExistAndOpening(2);
            $exp = false;
            $tele = false;
            $i = 0;

            if ($_POST['price'] >= getGameValueToParticipate($gameTele_id) && ($gameTele_id !== false)) {
                $calcTele = ceil($_POST['price'] / getGameValueToParticipate($gameTele_id));

                while ($i < $calcTele) {
                    $random_tele_id = randomGamesTelesenaCode($gameTele_id);
                    $array_tele[] = $random_tele_id;
                    $import_random_tele = array(
                        'user_id' => $user_id
                    );
                    doUpdateGamesTelesenaRow(getGamesTelesenaIDByCode($random_tele_id), $import_random_tele);
                    $tele = true;
                    ++$i;
                }
            }

            if (getDatabaseSettingsRewardPerProductStatus(1)) {
                if ($_POST['amount'] >= getDatabaseSettingsValueForExp(1)) {
                    $calc = ceil($_POST['amount'] / getDatabaseSettingsValueForExp(1));
                    $experienceReceived = $calc * getDatabaseSettingsValueFromExp(1);

                    $exp = true;
                }
            }

            if (getDatabaseSettingsRewardPerTotalPurchaseStatus(1)) {
                if ($_POST['price'] >= getDatabaseSettingsValueForExp(1)) {
                    $calc = ceil($_POST['price'] / getDatabaseSettingsValueForExp(1));
                    $experienceReceived = $calc * getDatabaseSettingsValueFromExp(1);

                    $exp = true;
                }
            }

            if (isset($experienceReceived) === false)
                $experienceReceived = 0;

            $experienceResult = $experienceActual + $experienceReceived;

            // Define a quantidade necessária para subir de nível
            $pointsPerLevelUp = 100;

            // Calcula o novo nível e a experiência restante
            $newLevel = floor($experienceResult / $pointsPerLevelUp);
            $remainingExperience = $experienceResult % $pointsPerLevelUp;

            // Atualiza os dados do usuário
            $import_data_user_query = array(
                'experience' => ($remainingExperience < 0) ? 0 : $remainingExperience,
                'level' => getUsersLevel($user_id) + $newLevel
            );

            doUpdateUsersRow($user_id, $import_data_user_query);

            $purchase_id = doInsertProductsPurchaseRow($import_data_query);

            ## Se ele foi indicado
            $account_id = getUsersAccountID($user_id);
            $invited_id = getAccountsInvitedID(getUsersAccountID($user_id));

            if ($invited_id !== false) {
                $account_invited_id = getAccountsCBCodeAccountID($invited_id);
                $user_invited_id = getUsersIDByAccountID($account_invited_id);

                $import_data_user_invite_query = array(
                    'experience' => ($remainingExperience < 0) ? 0 : $remainingExperience,
                    'level' => getUsersLevel($user_invited_id) + $newLevel
                );

                doUpdateUsersRow($user_invited_id, $import_data_user_invite_query);

                $import_data_purchase_log = array(
                    'user_id' => $user_id,
                    'invite_user_id' => $user_invited_id,
                    'purchase_id' => $purchase_id,
                    'date_created' => date('Y-m-d')
                );

                doInsertGuestPurchaseLogsRow($import_data_purchase_log);
            }


            if ($tele) {
                $msg[] = '
                <p>Olá ' . getUsersCName($user_id) . ',</p>
                <p>Agradecemos sinceramente por continuar nos apoiar!</p>
                <p>Confira agora as suas Premiadinhas FM através das seguintes URL:</strong></p>';

                foreach ($array_tele as $key => $value) {
                    $msg[] = "<a href='" . getDatabaseSettingsURL(1) . "/gametele/$value'>$value</a><br>";
                }

                $msg[] = '<p>Estamos contente em tê-lo como nosso cliente.</p>           
                <p>Em caso de dúvidas ou necessidade de assistência, não hesite em entrar em contato. Juntos, podemos criar um impacto positivo!</p>        
                <p>Agradecemos mais uma vez por seu apoio.</p>

                <p>Atenciosamente,</p>
                <p>Equipe ' . getDatabaseSettingsTitle(1) . '</p>';

                $t = implode($msg);

                $import_data =
                    array(
                        'email' => $_POST['email'],
                        'name' => 'No Reply ' . getDatabaseSettingsTitle(1),
                        'subject' => 'Sua compra gerou a FM Premiada',
                        'body' => $t
                    );

                doSendEmail($import_data);
            }
            if ($newLevel > 0) {
                echo alertSuccess("Opaa!!! O cliente subiu de nível, entregue-o brinde do mesmo.");
                $list = doRewardsList();

                if ($list) {
                    foreach ($list as $data) {
                        $reward_id = $data['id'];

                        if (getUsersLevel($user_id) >= getRewardLevel($reward_id)) {
                            if (isRewardCheckExistByRewardAndUserID($reward_id, $user_id) === false) {
                                $import_data_reward = array(
                                    'reward_id' => $reward_id,
                                    'user_id' => $user_id,
                                    'date_limit' => date("Y-m-d"),
                                    'code' => UniqueVoucher()
                                );
                                doInsertRewardsCheckRow($import_data_reward);
                            }
                        }
                    }
                }
            } else {
                if($exp && $tele) {
                    echo alertSuccess("Compra cadastrada, e pontuação adicionada ao histórico de cliente, usuário recebeu ".$calcTele." telesena(s).");
                } else
                if ($exp) {
                    echo alertSuccess("Compra cadastrada, e pontuação adicionada ao histórico de cliente.");
                } else
                    if ($tele) {
                        echo alertSuccess("Compra cadastrada, e usuário recebeu ".$calcTele." telesena(s).");
                    } else {
                        echo alertSuccess("Compra cadastrada.");
                    }
            }
            // Coloque o código que deve ser executado após as verificações bem-sucedidas aqui
        }
    }

    if (getToken('confirmReceived')) {
        if (empty($_POST) === false) {
            if (isRewardCheckExist($_POST['check_id']) === false) {
                $errors[] = "Desculpe, ocorreu um problema ao processar a solicitação. Por favor, tente novamente.";
            }

            if (isRewardCheckExistByCode($_POST['code']) === false) {
                $errors[] = 'Código não reconhecido, tente novamente.';
            } elseif (getRewardCheckValidation($_POST['check_id'], $_POST['code']) === false) {
                $errors[] = 'Este código não pertence a está premiação.';
            }

            if (getRewardCheckStatusByCode($_POST['code']) === 'received') {
                $errors[] = 'Esta premiação já consta como recebida.';
            }
        }

        if (empty($errors)) {
            $import_data_reward_check = array(
                'team_id' => $userData['id'],
                'date_received' => date('Y-m-d'),
                'status' => 'received'
            );

            doUpdateRewardsCheckRow($_POST['check_id'], $import_data_reward_check);
            echo alertSuccess("Entrega foi confirmada!!");
        }

    }

    if (empty($errors) === false) {
        header("HTTP/1.1 401 Not Found");
        echo alertError($errors);
    }
}
?>
<h1 class="h3 mb-0 text-gray-800">Registrar Compras</h1>
</form>
<hr>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/0.9.0/jquery.mask.min.js"
    integrity="sha512-oJCa6FS2+zO3EitUSj+xeiEN9UTr+AjqlBZO58OPadb2RfqwxHpjTU8ckIC8F4nKvom7iru2s8Jwdo+Z8zm0Vg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).ready(function () {
        // Adiciona um evento de input ao campo
        $("#price").on('input', function () {
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
<form action="/admin/registerpurchase" method="post">

    <div class="form-group">
        <label for="inputfname">Email:</label>
        <input type="text" name="email" class="form-control" id="inputfname" placeholder="cliente@gmail.com">
    </div>
    <div class="form-group">
        <label for="inputlname">Quantidade de Produtos:</label>
        <input type="text" name="amount" class="form-control" id="inputlname" placeholder="10">
    </div>

    <div class="form-group">
        <label for="price">Valor Pago:</label>
        <input type="text" name="price" class="form-control" id="price" placeholder="10.00">
    </div>
    <input type="hidden" name="token" value="<?php echo addToken('registerPurchase') ?>" />

    <button type="submit" class="btn btn-primary">Cadastrar</button>
</form>


<style>
    #raffle-img-container {
        width: 30px;
        margin-right: 10px;
    }

    #raffle-img-container img {
        width: 100%;
        height: 100%;
    }

    .tier {
        padding: 0px 7px;
        border: 1px solid #c9dfc9;
        border-radius: 5px;
        background-color: #119500;
        font-weight: bold;
        color: white;
    }

    #table {
        display: none;
        /* Começa oculto */
    }
</style>
<br>
<hr>
<br>

<button type="submit" class="btn btn-primary" onclick="toggleTable()">Lista de Premiação</button><br><br>
<div id="table">
    <link href="/front/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
                <th>Premio</th>
                <th>Cliente</th>
                <th>Opções</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>#</th>
                <th>Premio</th>
                <th>Cliente</th>
                <th>Opções</th>
            </tr>
        </tfoot>
        <tbody>
            <?php
            $listReward = doRewardsCheckList(true);
            $token = addToken('confirmReceived');
            if ($listReward) {
                foreach ($listReward as $data) {
                    $reward_id = getRewardCheckRewardID($data['id']);
                    $user_id = getRewardCheckUserID($data['id']);
                    $dir = '/front/images/rewards/';
                    $format = getPathImageFormat($dir, getRewardPhoto($reward_id));
                    ?>
                    <tr>
                        <td>
                            <section id="raffle-img-container">
                                <img
                                    src="/../../../front/images/rewards/<?php echo getRewardPhoto($reward_id) ?>.<?php echo $format; ?>"></img>
                            </section>
                        </td>
                        <td>
                            <?php echo getRewardName($reward_id); ?>
                        </td>
                        <td>
                            <?php echo getUsersCName($user_id) ?>
                        </td>
                        <td>
                            <button class="btn-success" type="button" data-toggle="modal"
                                data-target="#registerPurchaseModal<?php echo $data['id']; ?>">Entregar</button>
                        </td>
                    </tr>
                    <!-- Modal -->
                    <div class="modal fade" id="registerPurchaseModal<?php echo $data['id']; ?>" tabindex="-1" role="dialog"
                        aria-labelledby="registerPurchaseModal<?php echo $data['id']; ?>Label" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="registerPurchaseModal<?php echo $data['id']; ?>Label">Confirmar
                                        a entrega</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="/admin/registerpurchase" method="post">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="inputlname">Informe o codigo de premiação:</label>
                                            <input type="text" name="code" class="form-control" id="inputlname">
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                        <input type="hidden" name="check_id" value="<?php echo $data['id'] ?>" />
                                        <input type="hidden" name="token" value="<?php echo $token ?>" />
                                        <button type="submit" class="btn btn-primary">Confirmar</button>
                                </form>
                            </div>
                        </div>
                    </div>
        </div>
        <?php
                }
            }
            ?>
</tbody>
</table>
</div>

<script>
    $(document).ready(function () {
        $('#dataTable').DataTable({
            "language": {
                "search": "Pesquisar:",
                "info": "Mostrando _START_ até _END_ de _TOTAL_ entradas",
                "lengthMenu": "Mostrar _MENU_",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Próximo"
                }
                // Outras opções de linguagem...
            }
        });
    });

    function toggleTable() {
        var tableDiv = document.getElementById('table');
        tableDiv.style.display = (tableDiv.style.display === 'block') ? 'none' : 'block';
    }
</script>
<?php
include_once (realpath(__DIR__ . "/front/php/footer.php"));
?>