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
                    $errors[] = "Houve um erro ao processar a solicitação. A categoria é inexistente.";
                }

                if (!empty($_POST['code'])) {
                    if (isDatabaseAdditionalEnabledByCode($_POST['code'])) {
                        $errors[] = "O código já existe. Preencha com outro ou deixe em branco.";
                    }
                }

                if($_POST['cost-price'] > $_POST['sale-price']) {
                    $errors[] = "Deve haver algum engano, o preço de custo está maior que o de venda.";
                }

                if (doGeneralValidationPriceFormat($_POST['cost-price']) == false) {
                    $errors[] = "No campo de custo, apenas são aceitos valores numéricos.";
                }

                if (doGeneralValidationPriceFormat($_POST['sale-price']) == false) {
                    $errors[] = "No campo de desconto, apenas são aceitos valores numéricos.";
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
                    <input type="text" name="cost-price" class="form-control priceFormat" id="cost-price" value="">
                </div>
                <div class="form-group">
                    <label for="sale-price">Preço de Venda:</label>
                    <font color="red">*</font>
                    <input type="text" name="sale-price" class="form-control priceFormat" id="sale-price" value="">
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


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/0.9.0/jquery.mask.min.js"
    integrity="sha512-oJCa6FS2+zO3EitUSj+xeiEN9UTr+AjqlBZO58OPadb2RfqwxHpjTU8ckIC8F4nKvom7iru2s8Jwdo+Z8zm0Vg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function () {
        // Adiciona um evento de input ao campo
        $(".priceFormat").on('input', function () {
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
<?php
include_once (realpath(__DIR__ . "/layout/php/footer.php"));
?>