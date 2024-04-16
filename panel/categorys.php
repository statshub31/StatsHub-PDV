<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));

?>

<?php
// <!-- INICIO DA VALIDAÇÃO PHP -->
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {


    // ADD CATEGORIA
    if (getGeneralSecurityToken('tokenAddCategory')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('category');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                if (doGeneralCategoryNameFormat($_POST['category']) === false) {
                    $errors[] = "Verifique o nome da categoria informado, somente é aceito caracteres alfabetico.";
                }

                if (isDatabaseCategoryExistTitle($_POST['category'])) {
                    $errors[] = "Está categoria já é existente, crie outra.";
                }

                if (strlen($_POST['category']) > 50) {
                    $errors[] = "Tamanho máximo para nome é de 50 caracteres";
                }

                if(isDatabaseIconExistID($_POST['icon']) === false) {
                    $errors[] = "Houve um erro no icone selecionado, tente novamente.";
                }

                if (isGeneralSecurityManagerAccess() === false) {
                    $errors[] = "É obrigatório ter um cargo igual ou superior ao de gestor, para executar está ação.";
                }
            }

        }


        if (empty($errors)) {

            $category_add_field = array(
                'title' => $_POST['category'],
                'icon_id' => $_POST['icon']
            );

            doDatabaseCategoryInsert($category_add_field);

            doAlertSuccess("A categoria foi adicionada.");

        }
    }

    // REMOVE CATEGORIA
    if (getGeneralSecurityToken('tokenRemoveCategory')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('category_select_id');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {
                if (isDatabaseCategoryExistID($_POST['category_select_id']) === false) {
                    $errors[] = "Houve um erro ao processar solicitação, categoria é inexistente.";
                }

                if (isGeneralSecurityManagerAccess() === false) {
                    $errors[] = "É obrigatório ter um cargo igual ou superior ao de gestor, para executar está ação.";
                }
            }

        }


        if (empty($errors)) {

            doDatabaseCategoryDelete($_POST['category_select_id']);
            doAlertSuccess("A categoria foi excluída.");

        }
    }


    // EDITAR CATEGORIA
    if (getGeneralSecurityToken('tokenEditCategory')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('category_select_id', 'category');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {

                if (isDatabaseCategoryExistID($_POST['category_select_id']) === false) {
                    $errors[] = "Houve um erro ao processar solicitação, categoria é inexistente.";
                }

                if (isDatabaseCategoryExistTitle($_POST['category'])) {
                    if(isDatabaseCategoryTitleValidation($_POST['category'], $_POST['category_select_id']) === false) {
                        $errors[] = "Escolha outro nome para a categoria, pois este já é existente.";
                    }
                }

                if (strlen($_POST['category']) > 50) {
                    $errors[] = "Tamanho máximo para nome é de 50 caracteres";
                }

                if(isDatabaseIconExistID($_POST['icon']) === false) {
                    $errors[] = "Houve um erro no icone selecionado, tente novamente.";
                }

                if (isGeneralSecurityManagerAccess() === false) {
                    $errors[] = "É obrigatório ter um cargo igual ou superior ao de gestor, para executar está ação.";
                }
            }

        }


        if (empty($errors)) {

            $category_update_field = array(
                'title' => $_POST['category'],
                'icon_id' => $_POST['icon']
            );

            doDatabaseCategoryUpdate($_POST['category_select_id'], $category_update_field);
            doAlertSuccess("As informações da categoria foram alteradas.");

        }
    }

    if (empty($errors) === false) {
        header("HTTP/1.1 401 Not Found");
        echo doAlertError($errors);
    }
}


// <!-- FINAL DA VALIDAÇÃO PHP -->
?>



<h1 class="h3 mb-0 text-gray-800">Categorias</h1>
<button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#newCategoryModal">Nova
    Categoria</button>

<hr>
<link href="/layout/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>Categoria</th>
            <th>Icone</th>
            <th>Quantidade de Produtos</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Categoria</th>
            <th>Icone</th>
            <th>Quantidade de Produtos</th>
            <th>Opções</th>
        </tr>
    </tfoot>
    <tbody>
        <!-- LISTAR CATEGORIA START -->
        <?php
        $categorys_list = doDatabaseCategorysList();
        if ($categorys_list) {
            foreach ($categorys_list as $data) {
                $category_list_id = $data['id'];
                ?>
                <tr>
                    <td>
                        <?php echo getDatabaseCategoryTitle($category_list_id); ?>
                    </td>
                    <td>
                        <i class="fa-solid <?php echo getDatabaseIconTitle(getDatabaseCategoryIconID($category_list_id)); ?>"></i>
                    </td>
                    <td>50</td>
                    <td>
                        <a href="/panel/categorys/category/edit/<?php echo $category_list_id ?>">
                            <i class="fa fa-edit" aria-hidden="true"></i>
                        </a>
                        <a href="/panel/categorys/category/remove/<?php echo $category_list_id ?>">
                            <i class="fa fa-trash" aria-hidden="true" data-toggle="modal" data-target="#exampleModal"></i>
                        </a>
                    </td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="3">Não existe nenhuma categoria cadastrada.
                </td>
            </tr>

            <?php
        }
        ?>
        <!-- LISTAR CATEGORIA FIM -->
    </tbody>
</table>

<!-- Modal View -->
<div class="modal fade" id="newCategoryModal" tabindex="-1" role="dialog" aria-labelledby="newCategoryModalTitle"
    aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newCategoryModalTitle">Adicionar Categoria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/panel/categorys" method="POST">
                <div class="modal-body">

                    <div class="input-group">
                        <select class="custom-select" name="icon" id="icon"
                            aria-label="Example select with button addon">
                            <option selected>Icone...
                                <font color="red">*</font>
                            </option>
                            <!-- Icon ACTION LIST START -->
                            <?PHP
                            $icon_action_list = doDatabaseIconList();
                            if ($icon_action_list) {
                                foreach ($icon_action_list as $dataIconAction) {
                                    $icon_action_list_id = $dataIconAction['id'];
                                    ?>
                                    <option value="<?php echo $icon_action_list_id ?>"
                                        data-icon="fa-solid <?php echo getDatabaseIconTitle($icon_action_list_id) ?>">
                                        <?php echo getDatabaseIconTitle($icon_action_list_id) ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                            <!-- Icon ACTION LIST END -->
                        </select>
                        <script>
                            $(document).ready(function () {

                                $('#icon').each(function () {
                                    var iconClass = $(this).find(':selected').data('icon');
                                    $(this).siblings('.select-icon1').remove();
                                    $(this).after('<i class="' + iconClass + ' select-icon1"></i>');
                                });

                                $('#icon').change(function () {
                                    var iconClass = $(this).find(':selected').data('icon');
                                    $(this).siblings('.select-icon1').remove();
                                    $(this).after('<i class="' + iconClass + ' select-icon1"></i>');
                                });
                            });
                        </script>
                    </div><br>
                    <div class="form-group">
                        <label for="category">Categoria:
                            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                    data-placement="top" title="Somente é aceito caracteres alfabético."></i></small>
                        </label>
                        <font color="red">*</font>
                        <input type="text" name="category" class="form-control" id="category" value="">
                    </div>
                    <br>

                </div>

                <div class="modal-footer">
                    <input name="token" type="text" value="<?php echo addGeneralSecurityToken('tokenAddCategory') ?>"
                        hidden>
                    <a href="/panel/categorys">
                        <button type="button" class="btn btn-danger">Cancelar</button>
                    </a>
                    <button type="submit" class="btn btn-success">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php
if (isCampanhaInURL("category")) {

    // <!-- Modal REMOVE -->
    if (isCampanhaInURL("remove")) {
        $category_select_id = getURLLastParam();
        if (isDatabaseCategoryExistID($category_select_id)) {
            ?>

            <div class="modal fade show" style="padding-right: 19px; display: block;" id="editAddressModal" tabindex="-1"
                role="dialog" aria-labelledby="editAddressModalLabel" aria-hidden="true">
                <div class="modal-dialog" style="max-width: 600px" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="newCategoryModalTitle">Remover</h5>
                            <a href="/panel/categorys">
                                <button type="button" class="close" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </a>
                        </div>
                        <div class="modal-body">
                            Você está prestes a interromper a promoção
                            <b>[<?php echo getDatabaseCategoryTitle($category_select_id) ?>]</b>, você tem certeza disso?

                            <div class="alert alert-danger" role="alert">
                                Confirmando está ação, ela não voltara, precisará criar outra categoria.
                            </div>

                            <form action="/panel/categorys" method="post">
                                <div class="modal-footer">
                                    <input type="text" name="category_select_id" value="<?php echo $category_select_id ?>" hidden />

                                    <input name="token" type="text"
                                        value="<?php echo addGeneralSecurityToken('tokenRemoveCategory') ?>" hidden>
                                    <a href="/panel/categorys">
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
        $category_select_id = getURLLastParam();
        if (isDatabaseCategoryExistID($category_select_id)) {
            ?>

            <div class="modal fade show" style="padding-right: 19px; display: block;" id="editAddressModal" tabindex="-1"
                role="dialog" aria-labelledby="editAddressModalLabel" aria-hidden="true">
                <div class="modal-dialog" style="max-width: 600px" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="newCategoryModalTitle">Editar</h5>
                            <a href="/panel/categorys">
                                <button type="button" class="close" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </a>
                        </div>
                        <div class="modal-body">
                            <form action="/panel/categorys" method="post">


                                <div class="input-group">
                                    <select class="custom-select" name="icon" id="icon-edit"
                                        aria-label="Example select with button addon">
                                        <option selected>Icone...
                                            <font color="red">*</font>
                                        </option>
                                        <!-- Icon ACTION LIST START -->
                                        <?PHP
                                        $icon_action_list = doDatabaseIconList();
                                        if ($icon_action_list) {
                                            foreach ($icon_action_list as $dataIconAction) {
                                                $icon_action_list_id = $dataIconAction['id'];
                                                ?>
                                                <option
                                                <?php echo doSelect(getDatabaseCategoryIconID($category_select_id), $icon_action_list_id) ?>
                                                value="<?php echo $icon_action_list_id ?>"
                                                    data-icon="fa-solid <?php echo getDatabaseIconTitle($icon_action_list_id) ?>">
                                                    <?php echo getDatabaseIconTitle($icon_action_list_id) ?>
                                                </option>
                                                <?php
                                            }
                                        }
                                        ?>
                                        <!-- Icon ACTION LIST END -->
                                    </select>
                                    <script>
                                        $(document).ready(function () {

                                            $('#icon-edit').each(function () {
                                                var iconClass = $(this).find(':selected').data('icon');
                                                $(this).siblings('.select-icon1').remove();
                                                $(this).after('<i class="' + iconClass + ' select-icon1"></i>');
                                            });

                                            $('#icon-edit').change(function () {
                                                var iconClass = $(this).find(':selected').data('icon');
                                                $(this).siblings('.select-icon1').remove();
                                                $(this).after('<i class="' + iconClass + ' select-icon1"></i>');
                                            });
                                        });
                                    </script>
                                </div><br>
                                <div class="form-group">
                                    <label for="category">Categoria:
                                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                                data-placement="top" title="Somente é aceito caracteres alfabético."></i></small>
                                    </label>
                                    <font color="red">*</font>
                                    <input type="text" name="category" class="form-control" id="category"
                                        value="<?php echo getDatabaseCategoryTitle($category_select_id) ?>">
                                </div>
                                <br>


                                <div class="modal-footer">
                                    <input type="text" name="category_select_id" value="<?php echo $category_select_id ?>" hidden />

                                    <input name="token" type="text"
                                        value="<?php echo addGeneralSecurityToken('tokenEditCategory') ?>" hidden>
                                    <a href="/panel/categorys">
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