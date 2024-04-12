<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));
?>

<?php
// <!-- INICIO DA VALIDAÇÃO PHP -->
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {


    // ADD COMPLEMENTO
    if (getGeneralSecurityToken('tokenAddAdditional')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('category', 'description', 'cost-price', 'sale-price');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {

                if (isDatabaseCategoryExistID($_POST['category']) === false) {
                    $errors[] = "Houve um erro ao processar solicitação, categoria é inexistente.";
                }

                if (!empty($_POST['code'])) {
                    if (isDatabaseAdditionalEnabledByCode($_POST['code'])) {
                        $errors[] = "O codigo é existente, preencha com outro ou deixe em branco.";
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
            $complement_add_fields = array(
                'code' => (!empty($_POST['code']) ? $_POST['code'] : NULL),
                'category_id' => $_POST['category'],
                'description' => $_POST['description'],
                'cost_price' => $_POST['cost-price'],
                'sale_price' => $_POST['sale-price'],
                'created' => date('Y-m-d'),
                'created_by' => $in_user_id,
                'status' => 2
            );
            doDatabaseAdditionalInsert($complement_add_fields);
            doAlertSuccess("O adicional foi adicionado com sucesso.");
        }
    }


    if (empty($errors) === false) {
        header("HTTP/1.1 401 Not Found");
        echo doAlertError($errors);
    }
}


// <!-- FINAL DA VALIDAÇÃO PHP -->
?>


<form action="/panel/additionaladd" method="post">

    <div id="product-add-info" class="content">
        <div id="product-info">
            <section id="product-left">
                <div class="form-group">
                    <label for="cod">COD. Pers.</label>
                    <input type="text" name="code" class="form-control" id="cod" value="">
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
                                <option value="<?php echo $category_list_id ?>">
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
                    <input type="text" name="cost-price" class="form-control" id="cost-price" value="">
                </div>
                <div class="form-group">
                    <label for="sale-price">Preço de Venda:</label>
                    <font color="red">*</font>
                    <input type="text" name="sale-price" class="form-control" id="sale-price" value="">
                </div>
            </section>
            <section id="product-left">
                <div class="form-group">
                    <label for="description">Descrição:</label>
                    <font color="red">*</font>
                    <textarea class="form-control" name="description" id="description"
                        aria-label="With textarea"></textarea>
                </div>
            </section>
        </div>
    </div>

    <br>
    <input name="token" type="text" value="<?php echo addGeneralSecurityToken('tokenAddAdditional') ?>" hidden>
    <a href="/panel/additional">
        <button type="button" class="btn btn-secondary">Voltar</button>
    </a>
    <button type="submit" class="btn btn-primary">Adicionar</button>
</form>

<?php
include_once (realpath(__DIR__ . "/layout/php/footer.php"));
?>