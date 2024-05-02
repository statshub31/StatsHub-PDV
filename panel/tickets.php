<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));
getGeneralSecurityManagerAccess();

?>



<?php
// <!-- INICIO DA VALIDAÇÃO PHP -->
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {


    // REMOVE USER

    if (getGeneralSecurityToken('tokenStatusTicket')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('ticket_select_id', 'reason');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                if (isDatabaseTicketExistID($_POST['ticket_select_id']) === false) {
                    $errors[] = "Houve um erro ao processar a solicitação, o cupom selecionado não existe.";
                }

                if (isGeneralSecurityManagerAccess() === false) {
                    $errors[] = "É obrigatório ter um cargo igual ou superior ao de gestor, para executar está ação.";
                }
            }

        }


        if (empty($errors)) {

            $ticket_update_field = array(
                'end' => date("Y-m-d H:i:s"),
                'finished_by' => $in_user_id,
                'status' => 6,
                'reason' => $_POST['reason']
            );

            doDatabaseTicketUpdate($_POST['ticket_select_id'], $ticket_update_field);

            doAlertSuccess("O cupom foi desativado com sucesso.");

        }
    }



    if (empty($errors) === false) {
        header("HTTP/1.1 401 Not Found");
        echo doAlertError($errors);
    }
}


// <!-- FINAL DA VALIDAÇÃO PHP -->
?>


<h1 class="h3 mb-0 text-gray-800">Cupons</h1>
<a href="/panel/ticketadd">
    <button type="submit" class="btn btn-primary">Novo Cupom</button>
</a>
<hr>
<link href="/layout/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>Código</th>
            <th>Quantidade</th>
            <th>Desconto</th>
            <th>Progresso</th>
            <th>Status</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Código</th>
            <th>Quantidade</th>
            <th>Desconto</th>
            <th>Progresso</th>
            <th>Status</th>
            <th>Opções</th>
        </tr>
    </tfoot>
    <tbody>
        <!-- TICKETS LIST -->
        <?php
        $tickets_list = doDatabaseTicketsList();
        if ($tickets_list) {
            foreach ($tickets_list as $data) {
                $ticket_list_id = $data['id'];
                $tickets = getDatabaseTicketAmount($ticket_list_id);
                $tickets_used = getDatabaseTicketAmountUsed($ticket_list_id);
                $ticket_percentual = ($tickets_used / $tickets) * 100;
                ?>
                <tr>
                    <td><?php echo getDatabaseTicketCode($ticket_list_id); ?>
                    </td>
                    <td><?php echo getDatabaseTicketAmount($ticket_list_id); ?></td>
                    <td><?php echo getDatabaseTicketValue($ticket_list_id); ?></td>
                    <td>
                        <label><?php echo $tickets_used; ?></label> de <?php echo $tickets; ?>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: <?php echo $ticket_percentual ?>%;"
                                aria-valuenow="<?php echo $ticket_percentual ?>" aria-valuemin="0" aria-valuemax="100">
                                <?php echo $ticket_percentual ?>%
                            </div>
                        </div>
                    </td>
                    <td><?php echo getDatabaseStatusTitle(getDatabaseTicketStatus($ticket_list_id)); ?><br><small><?php echo getDatabaseTicketReason($ticket_list_id) ?>
                        </small>
                    </td>
                    <td>
                        <a href="/panel/tickets/view/ticket/<?php echo $ticket_list_id ?>">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <!-- VALIDA STATUS -->
                        <?php if (isDatabaseTicketEnabled($ticket_list_id) !== false) { ?>
                            <a href="/panel/tickets/status/ticket/<?php echo $ticket_list_id ?>">
                                <i class="fa-solid fa-circle-stop"></i>
                            </a>
                            <?php
                        } ?>
                    </td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="5">Não existe nenhum cupom cadastrado.
                </td>
                </td>
            </tr>

            <?php
        }
        ?>
        <!-- TICKETS LIST END -->
    </tbody>
</table>

<?php
if (isCampanhaInURL("ticket")) {

    // <!-- Modal REMOVE -->
    if (isCampanhaInURL("status")) {
        $ticket_select_id = getURLLastParam();
        if (isDatabaseTicketExistID($ticket_select_id) && isDatabaseTicketEnabled($ticket_select_id)) {
            ?>

            <div class="modal fade show" style="padding-right: 19px; display: block;" id="editAddressModal" tabindex="-1"
                role="dialog" aria-labelledby="editAddressModalLabel" aria-hidden="true">
                <div class="modal-dialog" style="max-width: 600px" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Cancelamento</h5>
                            <a href="/panel/tickets">
                                <button type="button" class="close" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </a>
                        </div>
                        <div class="modal-body">
                            Você está prestes a interromper a promoção
                            <b>[<?php echo getDatabaseTicketCode($ticket_select_id) ?>]</b>, você tem certeza disso?

                            <div class="alert alert-danger" role="alert">
                                Confirmando está ação, ela não voltara, precisará criar outra promoção.
                            </div>

                            <form action="/panel/tickets" method="post">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Diga o motivo</span>
                                    </div>
                                    <textarea class="form-control" name="reason" aria-label="motivo"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <input type="text" name="ticket_select_id" value="<?php echo $ticket_select_id ?>" hidden />

                                    <input name="token" type="text"
                                        value="<?php echo addGeneralSecurityToken('tokenStatusTicket') ?>" hidden>
                                    <a href="/panel/tickets">
                                        <button type="button" class="btn btn-danger">Cancelar</button>
                                    </a>
                                    <button type="submit" class="btn btn-success">Confirmar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-backdrop fade show"></div>
            <?php
        } else {
            header('Location: /myaccount');
        }
    }
    // <!-- Modal View end -->


    // <!-- Modal REMOVE -->
    if (isCampanhaInURL("view")) {
        $ticket_select_id = getURLLastParam();
        if (isDatabaseTicketExistID($ticket_select_id)) {
            ?>

            <div class="modal fade show" style="padding-right: 19px; display: block;" id="editAddressModal" tabindex="-1"
                role="dialog" aria-labelledby="editAddressModalLabel" aria-hidden="true">
                <div class="modal-dialog" style="max-width: 600px" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Visualização</h5>
                            <a href="/panel/tickets">
                                <button type="button" class="close" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </a>
                        </div>
                        <div class="modal-body">

                            <table border="1" width="100%">
                                <tr>
                                    <th>Código</th>
                                    <td><?php echo getDatabaseTicketCode($ticket_select_id); ?></td>
                                    <th>Quantidade</th>
                                    <td><?php echo getDatabaseTicketAmount($ticket_select_id); ?></td>
                                </tr>
                                <tr>
                                    <th>Desconto</th>
                                    <td><?php echo doTypeDiscount(getDatabaseTicketValue($ticket_select_id)); ?></td>
                                    <th>Progresso</th>
                                    <td><?php echo getDatabaseTicketAmountUsed($ticket_select_id) ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><?php echo getDatabaseStatusTitle(getDatabaseTicketStatus($ticket_select_id)); ?></td>
                                    <th>Previsão Expiração</th>
                                    <td><?php echo doDate(getDatabaseTicketExpiration($ticket_select_id)).' às '.doTime(getDatabaseTicketExpiration($ticket_select_id)); ?></td>
                                </tr>
                                <tr>
                                    <th>Criado em</th>
                                    <td><?php echo doDate(getDatabaseTicketCreated($ticket_select_id)).' às '.doTime(getDatabaseTicketCreated($ticket_select_id)); ?></td>
                                    <th>Criado por</th>
                                    <td><?php echo getDatabaseUserName(getDatabaseTicketCreatedBy($ticket_select_id)); ?></td>
                                </tr>
                                <tr>
                                    <th>Finalizado em</th>
                                    <td><?php echo doDate(getDatabaseTicketEnd($ticket_select_id)).' às '.doTime(getDatabaseTicketEnd($ticket_select_id)); ?></td>
                                    <th>Finalizado por</th>
                                    <td><?php echo getDatabaseUserName(getDatabaseTicketFinishedBy($ticket_select_id)); ?></td>
                                </tr>
                                <tr>
                                    <th colspan="4">
                                        <center>Motivo</center>
                                    </th>
                                </tr>
                                <tr>
                                    <td colspan="4"><?php echo getDatabaseTicketReason($ticket_select_id); ?></td>
                                </tr>
                            </table>
                            <div class="modal-footer">
                                <a href="/panel/tickets">
                                    <button type="button" class="btn btn-secondary">Fechar</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-backdrop fade show"></div>
            <?php
        } else {
            header('Location: /myaccount');
        }
    }
    // <!-- Modal View end -->

}
?>



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

        $('#dataTableDeliverys').DataTable({
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

</script>


<?php
include_once (realpath(__DIR__ . "/layout/php/footer.php"));
?>