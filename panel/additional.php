<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));

?>




<?php
// <!-- INICIO DA VALIDAÇÃO PHP -->
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {

    // ATIVAR/DESATIVAR SWITCH
    if (getGeneralSecurityToken('tokenSwitch')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('additional_select_id');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                if (isDatabaseAdditionalExistID($_POST['additional_select_id']) === false) {
                    $errors[] = "Houve um erro ao processar solicitação, adicional é inexistente.";
                }

                if (isGeneralSecurityManagerAccess() === false) {
                    $errors[] = "É obrigatório ter um cargo igual ou superior ao de gestor, para executar está ação.";
                }
            }

        }


        if (empty($errors)) {
            $additional_status_toggle = (getDatabaseAdditionalStatus($_POST['additional_select_id']) == 2) ? 3 : 2;

            $additional_status_update_field = array(
                'status' => $additional_status_toggle
            );

            doDatabaseAdditionalUpdate($_POST['additional_select_id'], $additional_status_update_field);

            if (getDatabaseAdditionalStatus($_POST['additional_select_id']) == 2)
                doAlertSuccess("O adicional foi desbloqueado.");

            if (getDatabaseAdditionalStatus($_POST['additional_select_id']) == 3)
                doAlertSuccess("O adicional foi bloqueado.");

        }
    }


    // REMOVER Additional
    if (getGeneralSecurityToken('tokenRemoveAdditional')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('additional_select_id');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                if (isDatabaseAdditionalExistID($_POST['additional_select_id']) === false) {
                    $errors[] = "Houve um erro ao processar solicitação, o adicional é inexistente.";
                }

                if (isGeneralSecurityManagerAccess() === false) {
                    $errors[] = "É obrigatório ter um cargo igual ou superior ao de gestor, para executar está ação.";
                }
            }

        }


        if (empty($errors)) {
            doDatabaseAdditionalDelete($_POST['additional_select_id']);
            doAlertSuccess("O Additionalo foi removido com sucesso.");
        }
    }


    // EDITAR Additional
    if (getGeneralSecurityToken('tokenEditAdditional')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('additional_select_id', 'category', 'description', 'cost-price', 'sale-price');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                if (isDatabaseAdditionalExistID($_POST['additional_select_id']) === false) {
                    $errors[] = "Houve um erro ao processar solicitação, o adicional é inexistente.";
                }


                if (isDatabaseCategoryExistID($_POST['category']) === false) {
                    $errors[] = "Houve um erro ao processar solicitação, categoria é inexistente.";
                }

                if (!empty($_POST['code'])) {
                    if (isDatabaseAdditionalEnabledByCode($_POST['code'])) {
                        if (isDatabaseAdditionalValidationCode($_POST['code'], $_POST['additional_select_id']) === false) {
                            $errors[] = "O codigo é existente, preencha com outro ou deixe em branco.";
                        }
                    }
                }

                if (doGeneralValidationPriceFormat($_POST['cost-price']) == false) {
                    $errors[] = "No valor de custo, somente é aceito valores númerico";
                }

                if (doGeneralValidationPriceFormat($_POST['sale-price']) == false) {
                    $errors[] = "No valor de desconto, somente é aceito valores númerico";
                }


                if (isGeneralSecurityManagerAccess() === false) {
                    $errors[] = "É obrigatório ter um cargo igual ou superior ao de gestor, para executar está ação.";
                }
            }

        }


        if (empty($errors)) {
            $additional_update_fields = array(
                'code' => (!empty($_POST['code']) ? $_POST['code'] : NULL),
                'category_id' => $_POST['category'],
                'description' => $_POST['description'],
                'cost_price' => $_POST['cost-price'],
                'sale_price' => $_POST['sale-price']
            );

            doDatabaseAdditionalUpdate($_POST['additional_select_id'], $additional_update_fields);

            doAlertSuccess("As informações do adicional foram alteradas!");

        }
    }


    if (empty($errors) === false) {
        header("HTTP/1.1 401 Not Found");
        echo doAlertError($errors);
    }
}


// <!-- FINAL DA VALIDAÇÃO PHP -->
?>






<h1 class="h3 mb-0 text-gray-800">Adicionais</h1>
<a href="/panel/additionaladd">
    <button type="submit" class="btn btn-primary">Novo Adicional</button>
</a>
<hr hidden>
<div class="input-group" hidden disabled>
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
            <th>Categoria</th>
            <th>Descrição</th>
            <th>Preço de Custo</th>
            <th>Preço de Venda</th>
            <th>Status</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Categoria</th>
            <th>Descrição</th>
            <th>Preço de Custo</th>
            <th>Preço de Venda</th>
            <th>Status</th>
            <th>Opções</th>
        </tr>
    </tfoot>
    <tbody>
        <!-- ADDIONAL LIST START -->
        <?php
        $additional_list = doDatabaseAdditionalList();

        if ($additional_list) {

            $tokenSwitch = addGeneralSecurityToken('tokenSwitch');
            foreach ($additional_list as $data) {
                $additional_list_id = $data['id'];
                ?>
                <tr>
                    <td>
                        <label><?php echo getDatabaseCategoryTitle(getDatabaseAdditionalCategoryID($additional_list_id)); ?></label>
                    </td>
                    <td>
                        <?php echo getDatabaseAdditionalDescription($additional_list_id); ?>
                    </td>
                    <td>
                        <?php echo getDatabaseAdditionalCostPrice($additional_list_id); ?>
                    </td>
                    <td>
                        <?php echo getDatabaseAdditionalSalePrice($additional_list_id); ?>
                    </td>
                    <td>

                        <form action="/panel/additional" method="post">
                            <input name="token" type="text" value="<?php echo $tokenSwitch ?>" hidden />
                            <input name="additional_select_id" type="text" value="<?php echo $additional_list_id ?>" hidden />
                            <div class="vc-toggle-container">
                                <label class="vc-switch">
                                    <input type="checkbox" onchange="submitForm(this)" class="vc-switch-input" <?php echo doCheck(getDatabaseAdditionalStatus($additional_list_id), 2) ?>>
                                    <span data-on="Disp" data-off="Indis" class="vc-switch-label"></span>
                                    <span class="vc-handle"></span>
                                </label>
                            </div>
                        </form>
                    </td>
                    <td>
                        <a href="/panel/additional/edit/additional/<?php echo $additional_list_id; ?>">
                            <i class="fa fa-edit" aria-hidden="true"></i>
                        </a>
                        <a href="/panel/additional/remove/additional/<?php echo $additional_list_id; ?>">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="6">Não existe nenhum adicional cadastrado.
                </td>
            </tr>

            <?php
        }
        ?>
        <!-- ADDIONAL LIST END -->
    </tbody>
</table>



<?php
if (isCampanhaInURL("additional")) {

    // <!-- Modal REMOVE -->
    if (isCampanhaInURL("remove")) {
        $additional_select_id = getURLLastParam();
        if (isDatabaseAdditionalExistID($additional_select_id)) {
            ?>

            <div class="modal fade show" style="padding-right: 19px; display: block;" id="removeAdditionalModal" tabindex="-1"
                role="dialog" aria-labelledby="removeAdditionalModalLabel" aria-hidden="true">
                <div class="modal-dialog" style="max-width: 600px" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="removeAdditionalModalTitle">Remover</h5>
                            <a href="/panel/additional">
                                <button type="button" class="close" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </a>
                        </div>
                        <div class="modal-body">
                            Você está prestes a remover o adicional
                            <b>[<?php echo getDatabaseAdditionalDescription($additional_select_id) ?>]</b>, você tem certeza disso?

                            <div class="alert alert-danger" role="alert">
                                Confirmando está ação, pode impactar produtos que utilizam dele.
                            </div>

                            <form action="/panel/additional" method="post">
                                <div class="modal-footer">
                                    <input type="text" name="additional_select_id" value="<?php echo $additional_select_id ?>"
                                        hidden />

                                    <input name="token" type="text"
                                        value="<?php echo addGeneralSecurityToken('tokenRemoveAdditional') ?>" hidden>
                                    <a href="/panel/additional">
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
        $additional_select_id = getURLLastParam();
        if (isDatabaseAdditionalExistID($additional_select_id)) {
            ?>

            <div class="modal fade show" style="padding-right: 19px; display: block;" id="editAdditionalModal" tabindex="-1"
                role="dialog" aria-labelledby="editAdditionalModalLabel" aria-hidden="true">
                <div class="modal-dialog" style="max-width: 600px" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editAdditionalModalTitle">Editar</h5>
                            <a href="/panel/additional">
                                <button type="button" class="close" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </a>
                        </div>
                        <div class="modal-body">
                            <form action="/panel/additional" method="post">
                                <section id="product-left">
                                    <div class="form-group">
                                        <label for="cod">COD. Pers.</label>
                                        <input type="text" name="code" class="form-control" id="cod" value="<?php echo getDatabaseAdditionalCode($additional_select_id); ?>">
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
                                                    <option <?php echo doSelect(getDatabaseAdditionalCategoryID($additional_select_id), $category_list_id) ?> value="<?php echo $category_list_id ?>">
                                                        <?php echo getDatabaseCategoryTitle($category_list_id) ?>
                                                    </option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <!-- CATEGORIA LISTA FIM -->

                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="cost-price">Preço de Custo:</label>
                                        <font color="red">*</font>
                                        <input type="text" name="cost-price" class="form-control" id="cost-price" value="<?php echo getDatabaseAdditionalCostPrice($additional_select_id); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="sale-price">Preço de Venda:</label>
                                        <font color="red">*</font>
                                        <input type="text" name="sale-price" class="form-control" id="sale-price" value="<?php echo getDatabaseAdditionalSalePrice($additional_select_id); ?>">
                                    </div>
                                </section>
                                <section id="product-left">
                                    <div class="form-group">
                                        <label for="description">Descrição:</label>
                                        <font color="red">*</font>
                                        <textarea class="form-control" name="description" id="description"
                                            aria-label="With textarea"><?php echo getDatabaseAdditionalDescription($additional_select_id); ?></textarea>
                                    </div>
                                </section>

                                <div class="modal-footer">
                                    <input type="text" name="additional_select_id" value="<?php echo $additional_select_id ?>"
                                        hidden />

                                    <input name="token" type="text"
                                        value="<?php echo addGeneralSecurityToken('tokenEditAdditional') ?>" hidden>
                                    <a href="/panel/additional">
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