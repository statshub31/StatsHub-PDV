<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));
getGeneralSecurityAttendantAccess();

?>


<?php
// <!-- INICIO DA VALIDAÇÃO PHP -->
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {

    // ATIVAR/DESATIVAR SWITCH
    if (getGeneralSecurityToken('tokenSwitch')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('complement_select_id');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                if (isDatabaseComplementExistID($_POST['complement_select_id']) === false) {
                    $errors[] = "Houve um erro ao processar solicitação, complemento é inexistente.";
                }

                if (isGeneralSecurityManagerAccess() === false) {
                    $errors[] = "É obrigatório ter um cargo igual ou superior ao de gestor, para executar está ação.";
                }
            }

        }


        if (empty($errors)) {
            $complement_status_toggle = (getDatabaseComplementsStatus($_POST['complement_select_id']) == 2) ? 3 : 2;

            $complement_status_update_field = array(
                'status' => $complement_status_toggle
            );

            doDatabaseComplementUpdate($_POST['complement_select_id'], $complement_status_update_field);

            if (getDatabaseComplementsStatus($_POST['complement_select_id']) == 2)
                doAlertSuccess("O complemento foi desbloqueado.");

            if (getDatabaseComplementsStatus($_POST['complement_select_id']) == 3)
                doAlertSuccess("O complemento foi bloqueado.");

        }
    }


    // REMOVER COMPLEMENTO
    if (getGeneralSecurityToken('tokenRemoveComplement')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('complement_select_id');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                if (isDatabaseComplementExistID($_POST['complement_select_id']) === false) {
                    $errors[] = "Houve um erro ao processar solicitação, complemento é inexistente.";
                }

                if (isGeneralSecurityManagerAccess() === false) {
                    $errors[] = "É obrigatório ter um cargo igual ou superior ao de gestor, para executar está ação.";
                }
            }

        }


        if (empty($errors)) {
            doDatabaseComplementDelete($_POST['complement_select_id']);
            doAlertSuccess("O complemento foi removido com sucesso.");
        }
    }


    // EDITAR COMPLEMENTO
    if (getGeneralSecurityToken('tokenEditComplement')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('complement_select_id', 'category', 'description');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                if (isDatabaseComplementExistID($_POST['complement_select_id']) === false) {
                    $errors[] = "Houve um erro ao processar solicitação, complemento é inexistente.";
                }


                if (isDatabaseCategoryExistID($_POST['category']) === false) {
                    $errors[] = "Houve um erro ao processar solicitação, categoria é inexistente.";
                }

                if (!empty($_POST['code'])) {
                    if (isDatabaseComplementEnabledByCode($_POST['code'])) {
                        if (isDatabaseComplementValidationCode($_POST['code'], $_POST['complement_select_id']) === false) {
                            $errors[] = "O codigo é existente, preencha com outro ou deixe em branco.";
                        }
                    }
                }

                if (isGeneralSecurityManagerAccess() === false) {
                    $errors[] = "É obrigatório ter um cargo igual ou superior ao de gestor, para executar está ação.";
                }
            }

        }


        if (empty($errors)) {
            $complement_update_fields = array(
                'code' => (!empty($_POST['code']) ? $_POST['code'] : NULL),
                'category_id' => $_POST['category'],
                'description' => $_POST['description']
            );

            doDatabaseComplementUpdate($_POST['complement_select_id'], $complement_update_fields);

            doAlertSuccess("As informações do complemento foram alteradas!");

        }
    }


    if (empty($errors) === false) {
        header("HTTP/1.1 401 Not Found");
        echo doAlertError($errors);
    }
}


// <!-- FINAL DA VALIDAÇÃO PHP -->
?>







<h1 class="h3 mb-0 text-gray-800">Complementos</h1>
<a href="/panel/complementadd">
    <button type="submit" class="btn btn-primary">Novo Complemento</button>
</a>
<hr hidden>
<div class="input-group" disabled hidden>
    <select class="custom-select" id="inputGroupSelect04">
        <option selected>-- Ação --</option>
        <option value="1">Remover</option>
        <option value="2">Promocionar</option>
        <option value="3">Montar Kit</option>
        <option value="3">Isentar de Taxa</option>
        <option value="3">Bloquear</option>
        <option value="3">Desbloquear</option>
    </select>
    <div class="input-group-append">
        <button class="btn btn-outline-secondary" type="button">Executar</button>
    </div>
</div>
<hr>
<link href="/layout/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>Código</th>
            <th>Descrição</th>
            <th>Status</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Código</th>
            <th>Descrição</th>
            <th>Status</th>
            <th>Opções</th>
        </tr>
    </tfoot>
    <tbody>
        <!-- COMPLEMENTOS LISTA START -->
        <?php
        $tokenSwitch = addGeneralSecurityToken('tokenSwitch');
        $complements_list = doDatabaseComplementsList();
        if ($complements_list) {
            foreach ($complements_list as $data) {
                $complement_list_id = $data['id'];
                ?>
                <tr>
                    <td>
                        <label><?php echo getDatabaseComplementCode($complement_list_id) ?></label>
                    </td>
                    <td>
                        <label><?php echo getDatabaseComplementDescription($complement_list_id) ?></label>
                    </td>
                    <td>
                        <form action="/panel/complements" method="post">

                            <input name="token" type="text" value="<?php echo $tokenSwitch ?>" hidden />
                            <input name="complement_select_id" type="text" value="<?php echo $complement_list_id ?>" hidden />
                            <div class="vc-toggle-container">
                                <label class="vc-switch">
                                    <input type="checkbox" onchange="submitForm(this)" class="vc-switch-input" <?php echo doCheck(getDatabaseComplementsStatus($complement_list_id), 2) ?>>
                                    <span data-on="Disp" data-off="Indis" class="vc-switch-label"></span>
                                    <span class="vc-handle"></span>
                                </label>
                            </div>
                        </form>
                    </td>
                    <td>
                        <a href="/panel/complements/edit/complement/<?php echo $complement_list_id ?>">
                            <i class="fa fa-edit" aria-hidden="true"></i>
                        </a>
                        <a href="/panel/complements/remove/complement/<?php echo $complement_list_id ?>">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
                <?php
            }
        } else {
            ?>

            <tr>
                <td colspan="6">Não existe nenhum complemento cadastrado.
                </td>
            </tr>

            <?php
        }
        ?>
        <!-- COMPLEMENTOS LISTA END -->
    </tbody>
</table>


<?php
if (isCampanhaInURL("complement")) {

    // <!-- Modal REMOVE -->
    if (isCampanhaInURL("remove")) {
        $complement_select_id = getURLLastParam();
        if (isDatabaseComplementExistID($complement_select_id)) {
            ?>

            <div class="modal fade show" style="padding-right: 19px; display: block;" id="removeComplementModal" tabindex="-1"
                role="dialog" aria-labelledby="removeComplementModalLabel" aria-hidden="true">
                <div class="modal-dialog" style="max-width: 600px" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="removeComplementModalTitle">Remover</h5>
                            <a href="/panel/complements">
                                <button type="button" class="close" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </a>
                        </div>
                        <div class="modal-body">
                            Você está prestes a remover o complemento
                            <b>[<?php echo getDatabaseComplementDescription($complement_select_id) ?>]</b>, você tem certeza disso?

                            <div class="alert alert-danger" role="alert">
                                Confirmando está ação, pode impactar produtos que utilizam dele.
                            </div>

                            <form action="/panel/complements" method="post">
                                <div class="modal-footer">
                                    <input type="text" name="complement_select_id" value="<?php echo $complement_select_id ?>"
                                        hidden />

                                    <input name="token" type="text"
                                        value="<?php echo addGeneralSecurityToken('tokenRemoveComplement') ?>" hidden>
                                    <a href="/panel/complements">
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



    // <!-- Modal EDIT -->
    if (isCampanhaInURL("edit")) {
        $complement_select_id = getURLLastParam();
        if (isDatabaseComplementExistID($complement_select_id)) {
            ?>

            <div class="modal fade show" style="padding-right: 19px; display: block;" id="editComplementModal" tabindex="-1"
                role="dialog" aria-labelledby="editComplementModalLabel" aria-hidden="true">
                <div class="modal-dialog" style="max-width: 600px" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editComplementModalTitle">Editar</h5>
                            <a href="/panel/complements">
                                <button type="button" class="close" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </a>
                        </div>
                        <div class="modal-body">
                            <form action="/panel/complements" method="post">
                                <section id="product-left">
                                    <div class="form-group">
                                        <label for="cod">COD. Pers.</label>
                                        <input type="text" name="code" class="form-control" id="cod"
                                            value="<?php echo getDatabaseComplementCode($complement_select_id) ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="category">Categoria:</label>
                                        <font color="red">*</font>
                                        <select class="custom-select" name="category" id="category">
                                            <option selected>Escolha...</option>
                                            <!-- CATEGORIA LISTA START -->
                                            <?php
                                            $list_category = doDatabaseCategorysList();
                                            if ($list_category) {
                                                foreach ($list_category as $data) {
                                                    $category_list_id = $data['id'];
                                                    ?>
                                                    <option <?php echo doSelect(getDatabaseComplementCategoryID($complement_select_id), $category_list_id) ?> value="<?php echo $category_list_id ?>">
                                                        <?php echo getDatabaseCategoryTitle($category_list_id) ?>
                                                    </option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <!-- CATEGORIA LISTA FIM -->

                                        </select>
                                    </div>
                                </section>
                                <section id="product-left">
                                    <div class="form-group">
                                        <label for="description">Descrição:</label>
                                        <font color="red">*</font>
                                        <textarea class="form-control" name="description"
                                            id="description"><?php echo getDatabaseComplementDescription($complement_select_id) ?></textarea>
                                    </div>
                                </section>

                                <div class="modal-footer">
                                    <input type="text" name="complement_select_id" value="<?php echo $complement_select_id ?>"
                                        hidden />

                                    <input name="token" type="text"
                                        value="<?php echo addGeneralSecurityToken('tokenEditComplement') ?>" hidden>
                                    <a href="/panel/complements">
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
}
?>

<script>
    // SWITCH
    function submitForm(checkbox) {
        var form = checkbox.closest('form');
        if (checkbox.checked) {
            form.submit();
        } else {
            // Se o checkbox for desmarcado, você pode enviar um valor vazio ou fazer algo diferente, como resetar o formulário
            // Aqui, vamos simplesmente enviar um valor vazio
            var hiddenInput = document.createElement('input');
            form.appendChild(hiddenInput);
            form.submit();
        }
    }



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