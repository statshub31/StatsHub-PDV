<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));
?>



<?php
// <!-- INICIO DA VALIDAÇÃO PHP -->
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {


    // ADD PRODUCT
    // if (getGeneralSecurityToken('tokenAddProduct')) {
    if (1 == 1) {
        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('category', 'name', 'description', 'size-p', 'size-p-description', 'size-p-price');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                // $errors[] = "Obrigatório o preenchimento de todos os campos.";
                // $required_fields_status = false;
            }

            if ($required_fields_status) {

                // INFORMAÇÕES
                if (isset($_FILES['productImage']) && $_FILES['productImage']['size'] > 0) {
                    // Verifica se o arquivo foi enviado sem erros
                    if ($_FILES['productImage']['error'] !== UPLOAD_ERR_OK) {
                        $errors[] = 'Erro no upload do arquivo.';
                    }

                    // Verifica se é uma imagem válida
                    $imageInfo = getimagesize($_FILES['productImage']['tmp_name']);
                    if (!$imageInfo || !in_array($imageInfo['mime'], array('image/jpeg', 'image/png'))) {
                        $errors[] = 'O arquivo enviado para a foto de perfil não é uma imagem válida.';
                    } elseif ($imageInfo[0] > 1500 || $imageInfo[1] > 1500) {
                        $errors[] = 'A imagem para foto precisa ser menor que 1500x1500.';
                    }
                }

                if (isDatabaseCategoryExistID($_POST['category']) === false) {
                    $errors[] = "Houve um erro ao processar solicitação, categoria é inexistente.";
                }

                if (!empty($_POST['code'])) {
                    if (isDatabaseProductEnabledByCode($_POST['code'])) {
                        $errors[] = "O codigo é existente, preencha com outro ou deixe em branco.";
                    }
                }
                if (doGeneralValidationProductNameFormat($_POST['name']) == false) {
                    $errors[] = "Escolha outro nome, somente é aceito caracteres alfanumérico.";
                }

                if (isGeneralSecurityManagerAccess() === false) {
                    $errors[] = "É obrigatório ter um cargo igual ou superior ao de gestor, para executar está ação.";
                }

                // PREÇOS
                if (isDatabaseMeasureExistID($_POST['measure']) === false) {
                    $errors[] = "A unidade de medida selecionada, não é existente.";
                }

                if (doGeneralValidationPriceFormat($_POST['price-p']) == false) {
                    $errors[] = "É obrigatório preencher com valores númerico, o campo de valor do tamanho 1.";
                }

                // KILOGRAMA
                if ($_POST['measure'] == 1) {

                    if (doGeneralValidationNumberFormat($_POST['size-p']) == false) {
                        $errors[] = "Você selecionou kilograma como unidade de medida, é obrigatório digitar um valor númerico no tamanho  de tamanho 1.";
                    }

                    if (isset($_POST['price-size-status'])) {
                        if (doGeneralValidationNumberFormat($_POST['size-m']) == false) {
                            $errors[] = "Você selecionou kilograma como unidade de medida, é obrigatório digitar um valor númerico no tamanho  de tamanho 2.";
                        }

                        if (!empty($_POST['size-g'])) {
                            if (doGeneralValidationNumberFormat($_POST['size-g']) == false) {
                                $errors[] = "Você selecionou kilograma como unidade de medida, é obrigatório digitar um valor númerico no tamanho  de tamanho 3.";
                            }
                        }

                        if (!empty($_POST['size-xg'])) {
                            if (doGeneralValidationNumberFormat($_POST['size-xg']) == false) {
                                $errors[] = "Você selecionou kilograma como unidade de medida, é obrigatório digitar um valor númerico no tamanho  de tamanho 4.";
                            }
                        }
                    }
                }

                // GRAMA
                if ($_POST['measure'] == 2) {

                    if (doGeneralValidationNumberFormat($_POST['size-p']) == false) {
                        $errors[] = "Você selecionou grama como unidade de medida, é obrigatório digitar um valor númerico no tamanho  de tamanho 1.";
                    }

                    if (isset($_POST['price-size-status'])) {
                        if (doGeneralValidationNumberFormat($_POST['size-m']) == false) {
                            $errors[] = "Você selecionou grama como unidade de medida, é obrigatório digitar um valor númerico no tamanho  de tamanho 2.";
                        }

                        if (!empty($_POST['size-g'])) {
                            if (doGeneralValidationNumberFormat($_POST['size-g']) == false) {
                                $errors[] = "Você selecionou grama como unidade de medida, é obrigatório digitar um valor númerico no tamanho  de tamanho 3.";
                            }
                        }

                        if (!empty($_POST['size-g'])) {
                            if (doGeneralValidationNumberFormat($_POST['size-xg']) == false) {
                                $errors[] = "Você selecionou grama como unidade de medida, é obrigatório digitar um valor númerico no tamanho  de tamanho 4.";
                            }
                        }
                    }
                }

                // PORÇÃO
                if ($_POST['measure'] == 3) {

                    if (doGeneralValidationNumberFormat($_POST['size-p']) == false) {
                        $errors[] = "Você selecionou porção como unidade de medida, é obrigatório digitar um valor númerico no tamanho  de tamanho 1.";
                    }

                    if (isset($_POST['price-size-status'])) {
                        if (doGeneralValidationNumberFormat($_POST['size-m']) == false) {
                            $errors[] = "Você selecionou porção como unidade de medida, é obrigatório digitar um valor númerico no tamanho  de tamanho 2.";
                        }

                        if (!empty($_POST['size-g'])) {
                            if (doGeneralValidationNumberFormat($_POST['size-g']) == false) {
                                $errors[] = "Você selecionou porção como unidade de medida, é obrigatório digitar um valor númerico no tamanho  de tamanho 3.";
                            }
                        }
                        if (!empty($_POST['size-xg'])) {
                            if (doGeneralValidationNumberFormat($_POST['size-xg']) == false) {
                                $errors[] = "Você selecionou porção como unidade de medida, é obrigatório digitar um valor númerico no tamanho  de tamanho 4.";
                            }
                        }
                    }
                }

                // UNIDADE
                if ($_POST['measure'] == 4) {

                    if (doGeneralValidationNumberFormat($_POST['size-p']) == false) {
                        $errors[] = "Você selecionou unidade como unidade de medida, é obrigatório digitar um valor númerico no tamanho  de tamanho 1.";
                    }

                    if (isset($_POST['price-size-status'])) {
                        if (doGeneralValidationNumberFormat($_POST['size-m']) == false) {
                            $errors[] = "Você selecionou unidade como unidade de medida, é obrigatório digitar um valor númerico no tamanho  de tamanho 2.";
                        }

                        if (!empty($_POST['size-g'])) {
                            if (doGeneralValidationNumberFormat($_POST['size-g']) == false) {
                                $errors[] = "Você selecionou unidade como unidade de medida, é obrigatório digitar um valor númerico no tamanho  de tamanho 3.";
                            }
                        }

                        if (!empty($_POST['size-xg'])) {
                            if (doGeneralValidationNumberFormat($_POST['size-xg']) == false) {
                                $errors[] = "Você selecionou unidade como unidade de medida, é obrigatório digitar um valor númerico no tamanho  de tamanho 4.";
                            }
                        }
                    }
                }

                // CENTIMETRO
                if ($_POST['measure'] == 5) {

                    if (doGeneralValidationNumberFormat($_POST['size-p']) == false) {
                        $errors[] = "Você selecionou centimetro como unidade de medida, é obrigatório digitar um valor númerico no tamanho  de tamanho 1.";
                    }

                    if (isset($_POST['price-size-status'])) {
                        if (doGeneralValidationNumberFormat($_POST['size-m']) == false) {
                            $errors[] = "Você selecionou centimetro como unidade de medida, é obrigatório digitar um valor númerico no tamanho  de tamanho 2.";
                        }

                        if (!empty($_POST['size-g'])) {
                            if (doGeneralValidationNumberFormat($_POST['size-g']) == false) {
                                $errors[] = "Você selecionou centimetro como unidade de medida, é obrigatório digitar um valor númerico no tamanho  de tamanho 3.";
                            }
                        }
                        if (!empty($_POST['size-xg'])) {
                            if (doGeneralValidationNumberFormat($_POST['size-xg']) == false) {
                                $errors[] = "Você selecionou centimetro como unidade de medida, é obrigatório digitar um valor númerico no tamanho  de tamanho 4.";
                            }
                        }
                    }
                }

                // MEDIDA
                if ($_POST['measure'] == 6) {

                    if (doGeneralValidationNumberFormat($_POST['size-p']) == false) {
                        $errors[] = "Você selecionou porção como unidade de medida, é obrigatório digitar um caracter alfabetico no tamanho  de tamanho 1.";
                    }

                    if (isset($_POST['price-size-status'])) {
                        if (doGeneralValidationNumberFormat($_POST['size-m']) == false) {
                            $errors[] = "Você selecionou porção como unidade de medida, é obrigatório digitar um caracter alfabetico no tamanho  de tamanho 2.";
                        }

                        if (!empty($_POST['size-g'])) {
                            if (doGeneralValidationNumberFormat($_POST['size-g']) == false) {
                                $errors[] = "Você selecionou porção como unidade de medida, é obrigatório digitar um caracter alfabetico no tamanho  de tamanho 3.";
                            }
                        }

                        if (!empty($_POST['size-xg'])) {
                            if (doGeneralValidationNumberFormat($_POST['size-xg']) == false) {
                                $errors[] = "Você selecionou porção como unidade de medida, é obrigatório digitar um caracter alfabetico no tamanho  de tamanho 4.";
                            }
                        }
                    }
                }

                $required_price_fields[] = array(
                    'size-p',
                    'price-p',
                );

                if (isset($_POST['price-size-status'])) {
                    $required_price_fields[] = array(
                        'size-m',
                        'price-m'
                    );

                    if (doGeneralValidationPriceFormat($_POST['price-m']) == false) {
                        $errors[] = "É obrigatório preencher com valores númerico, o campo de valor do tamanho 2.";
                    }

                    if (!empty($_POST['price-g'])) {
                        if (doGeneralValidationPriceFormat($_POST['price-g']) == false) {
                            $errors[] = "É obrigatório preencher com valores númerico, o campo de valor do tamanho 3.";
                        }
                    }

                    if (!empty($_POST['price-xg'])) {
                        if (doGeneralValidationPriceFormat($_POST['price-xg']) == false) {
                            $errors[] = "É obrigatório preencher com valores númerico, o campo de valor do tamanho 4.";
                        }
                    }

                    $required_price_fields = array_merge($required_price_fields[0], $required_price_fields[1]);
                } else {
                    $required_price_fields = array_merge($required_price_fields[0]);
                }

                if (validateRequiredFields($_POST, $required_price_fields) === false) {
                    $errors[] = "É obrigatório preencher todos os campos de tamanhos liberado.";
                }

                // STOCK
                if (isset($_POST['stock-status'])) {
                    $required_stock_fields = array('stock-min', 'stock-actual');

                    if (validateRequiredFields($_POST, $required_stock_fields) === false) {
                        $errors[] = "É obrigatório preencher todos os campos do estoque.";
                    }

                    if (doGeneralValidationNumberFormat($_POST['stock-min']) == false) {
                        $errors[] = "Estoque mínimo precisa ser um valor numérico.";
                    }

                    if (doGeneralValidationNumberFormat($_POST['stock-actual']) == false) {
                        $errors[] = "Estoque atual precisa ser um valor numérico.";
                    }
                }

                // ADDITIONAL
                if (isset($_POST['additional'])) {
                    foreach ($_POST['additional'] as $additional) {
                        if (isDatabaseAdditionalExistID($additional) === false) {
                            $errors[] = "Um ou mais adicional, não existe.";
                        }
                    }
                }

                // Complemento
                if (isset($_POST['complements'])) {
                    foreach ($_POST['complements'] as $complements) {
                        if (isDatabaseComplementExistID($complements) === false) {
                            $errors[] = "Um ou mais complemento, não existe.";
                        }
                    }
                }

                // QUESTION
                if (isset($_POST['questions-status'])) {
                    $count = 1;
                    while (isset($_POST['question' . $count])) {

                        if (empty($_POST['question' . $count])) {
                            $errors[] = "Você habilitou o questionário, é obrigatório o preenchimento de todas as perguntas criadas.";
                        }

                        if (!isset($_POST['response-free' . $count])) {
                            if (empty($_POST['response' . $count][0])) {
                                $errors[] = "Você precisa inserir ao menos uma resposta, para as perguntas.";
                            }
                        }

                        $count++;
                    }
                }
            }

        }


        if (empty($errors)) {

            $image = True;

            if (isset($_FILES['productImage']) && $_FILES['productImage']['size'] > 0) {
                $newName = md5(date("Y_m_d_H:i:s"));
                $fileInfo = pathinfo($_FILES['productImage']['name']);
                $fileExtension = $fileInfo['extension'];

                if (move_uploaded_file($_FILES['productImage']['tmp_name'], __DIR__ . '/..' . $image_product_dir . $newName . '.' . $fileExtension) === false) {
                    $errors[] = 'Falha ao mover o arquivo para o diretório de destino.';
                    $image = false;
                }
            }


            if ($image) {

                // INFORMAÇÕES
                $product_add_fields = array(
                    'code' => (!empty($_POST['code']) ? $_POST['code'] : NULL),
                    'name' => $_POST['name'],
                    'category_id' => $_POST['category'],
                    'description' => $_POST['description'],
                    'photo' => $newName,
                    'created' => date('Y-m-d'),
                    'stock_status' => (isset($_POST['stock-status']) ? 1 : 0),
                    'price_distinct' => (isset($_POST['price-size-status']) ? 1 : 0),
                    'created_by' => $in_user_id,
                    'status' => 2
                );

                $product_insert_id = doDatabaseProductInsert($product_add_fields);

                // PREÇO
                // TAM 1
                if ((!empty($_POST['size-p'])) && (!empty($_POST['price-p']))) {
                    $price_filled_fields[] = array(
                        'product_id' => $product_insert_id,
                        'size_measure_id' => $_POST['measure'],
                        'size' => $_POST['size-p'],
                        'description' => (!empty($_POST['size-p-description']) ? $_POST['size-p-description'] : NULL),
                        'price' => $_POST['price-p']
                    );
                }

                // TAM 2
                if ((!empty($_POST['size-m'])) && (!empty($_POST['price-m']))) {
                    $price_filled_fields[] = array(
                        'product_id' => $product_insert_id,
                        'size_measure_id' => $_POST['measure'],
                        'size' => $_POST['size-m'],
                        'description' => (!empty($_POST['size-m-description']) ? $_POST['size-m-description'] : NULL),
                        'price' => $_POST['price-m']
                    );
                }

                // TAM 3
                if ((!empty($_POST['size-g'])) && (!empty($_POST['price-g']))) {
                    $price_filled_fields[] = array(
                        'product_id' => $product_insert_id,
                        'size_measure_id' => $_POST['measure'],
                        'size' => $_POST['size-g'],
                        'description' => (!empty($_POST['size-g-description']) ? $_POST['size-g-description'] : NULL),
                        'price' => $_POST['price-g']
                    );
                }

                // TAM 4
                if ((!empty($_POST['size-xg'])) && (!empty($_POST['price-xg']))) {
                    $price_filled_fields[] = array(
                        'product_id' => $product_insert_id,
                        'size_measure_id' => $_POST['measure'],
                        'size' => $_POST['size-p'],
                        'description' => (!empty($_POST['size-xg-description']) ? $_POST['size-xg-description'] : NULL),
                        'price' => $_POST['price-xg']
                    );
                }

                doDatabaseProductPriceInsertMultipleRow($price_filled_fields);

                // STOCK
                if (isset($_POST['stock-status'])) {
                    $product_stock_fields = array(
                        'product_id' => $product_insert_id,
                        'min' => $_POST['stock-min'],
                        'actual' => $_POST['stock-actual']
                    );
                    doDatabaseStockInsert($product_stock_fields);
                }


                // ADDITIONAL
                if (isset($_POST['additional'])) {
                    foreach ($_POST['additional'] as $additional_id) {
                        $product_additional_fields[] = array(
                            'product_id' => $product_insert_id,
                            'additional_id' => $additional_id
                        );
                    }

                    doDatabaseProductAdditionalInsertMultipleRow($product_additional_fields);
                }

                // COMPLEMENTS
                if (isset($_POST['complements'])) {
                    foreach ($_POST['complements'] as $complement_id) {
                        $product_complements_fields[] = array(
                            'product_id' => $product_insert_id,
                            'complement_id' => $complement_id
                        );
                    }

                    doDatabaseProductComplementInsertMultipleRow($product_complements_fields);
                }


                if (isset($_POST['questions-status'])) {
                    $count = 1;
                    while (isset($_POST['question' . $count])) {
                        $questions_fields = array(
                            'product_id' => $product_insert_id,
                            'question' => $_POST['question' . $count],
                            'multiple_response' => (isset($_POST['multiple-response' . $count]) ? 1 : 0),
                            'response_free' => (isset($_POST['response-free' . $count]) ? 1 : 0)
                        );
                        $question_insert_id = doDatabaseProductQuestionInsert($questions_fields);

                        if (!isset($_POST['response-free' . $count])) {
                            foreach ($_POST['response' . $count] as $response) {
                                if (!empty($response)) {
                                    $response_fields[$count][] = array(
                                        'question_id' => $question_insert_id,
                                        'response' => $response
                                    );
                                }
                            }

                            doDatabaseProductQuestionResponseInsertMultipleRow($response_fields[$count]);
                        }
                        $count++;
                    }

                }

                // QUESTIONS



                doAlertSuccess("O produto foi adicionado com sucesso.");
            } else {
                $errors[] = "Houve um erro ao encaminhar a imagem para o servidor, tente novamente.";
            }
        }
    }


    if (empty($errors) === false) {
        header("HTTP/1.1 401 Not Found");
        echo doAlertError($errors);
    }
}


// <!-- FINAL DA VALIDAÇÃO PHP -->
?>





<script>
    $(document).ready(function () {
        window.checkboxToggle = function (checkboxId, responseId, className) {
            $(className).change(function () {
                $(className).not(this).prop('checked', false);
            });

            $(checkboxId).change(function () {
                // Toggle (mostrar ou esconder) a div com base no estado do checkbox
                $(responseId).show();
            });
        }
    });
</script>
<link href="/layout/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

<div class="menu-configs-container">
    <ul class="menu-configs-nav">
        <li class="button menu-config-select" data-filter="product-add-info">Informações</li>
        <li class="button" data-filter="price">Preço</li>
        <li class="button" data-filter="stock">Estoque</li>
        <li class="button" data-filter="additional">Adicional</li>
        <li class="button" data-filter="complement">Complemento</li>
        <li class="button" data-filter="questions">Perguntas</li>
    </ul>
</div>


<form action="/panel/productadd" method="post" enctype="multipart/form-data">

    <div id="product-add-info" class="content">
        <div class="form-imgs-container">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Imagem
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top"
                                title="Formatos aceito: PNG, JPG, JPEG.Tamanho Máximo: 512x512."></i></small>
                    </span>
                    <div id="previewImage"
                        style="width: 100px; height: 100px; overflow: hidden; border: 1px solid #ccc;">
                        <img id="productSelect" src="/layout/images/model/no-image.png"
                            style="width: 100%; height: 100%;">
                    </div>
                </div>
                <div class="custom-file">
                    <input type="file" name="productImage" class="custom-file-input" id="inputProduct" accept="image/*">
                    <label class="custom-file-label" for="inputProduct">Escolha sua imagem.</label>
                </div>
            </div>
        </div>
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
            </section>

            <section id="product-left">
                <div class="form-group">
                    <label for="name">Nome</label>
                    <font color="red">*</font>:
                    <input type="text" name="name" class="form-control" id="name" value="">
                </div>
                <div class="form-group">
                    <label for="description">Descrição
                        <font color="red">*</font>:
                    </label>
                    <textarea class="form-control" name="description" id="description"
                        aria-label="With textarea"></textarea>
                </div>
            </section>
        </div>
    </div>



    <div id="stock" class="content">
        <div class="form-group">
            <label for="stock-status">Deseja habilitar o controle de estoque?</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, você precisará definir o valor minimo de estoque."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input type="checkbox" name="stock-status" id="stock-status" class="vc-switch-input">
                    <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                    <span class="vc-handle"></span>
                </label>
            </div>
        </div>

        <div id="stock-status-container">
            <fieldset style="display: flex;">
                <legend>Estoque</legend>
                <div class="form-group">
                    <label for="stock-min">Mínimo
                        <font color="red">*</font>:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top"
                                title="Mínimo para manter em estoque. Não poderá ser menor que o atual."></i></small>
                    </label>
                    <input name="stock-min" type="text" class="form-control w-50" id="stock-min" value="">
                </div>
                <div class="form-group">
                    <label for="stock-actual">Atual
                        <font color="red">*</font>:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top"
                                title="Estoque atual. Não poderá ser menor que o mínimo."></i></small>
                    </label>
                    <input name="stock-actual" type="text" class="form-control w-50" id="stock-actual" value="">
                </div>
            </fieldset>
        </div>
    </div>

    <div id="price" class="content">

        <div class="form-group">
            <label for="measure">Medida:</label>
            <font color="red">*</font>
            <select class="custom-select" name="measure" id="measure">
                <option selected>Escolha...</option>
                <!-- TAMANHOS LISTA START -->
                <?php
                $list_measure = doDatabaseMeasureList();
                if ($list_measure) {
                    foreach ($list_measure as $data) {
                        $measure_list_id = $data['id'];
                        ?>
                        <option value="<?php echo $measure_list_id ?>">
                            <?php echo getDatabaseMeasureTitle($measure_list_id) ?>
                        </option>
                        <?php
                    }
                }
                ?>
                <!-- TAMANHOS LISTA FIM -->

            </select>
        </div>
        <div class="form-group">
            <label for="price-size-status">Deseja habilitar a distinção por tamanho?</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, você poderá definir preço por tamanho P, M, G..."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input type="checkbox" name="price-size-status" id="price-size-status" class="vc-switch-input">
                    <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                    <span class="vc-handle"></span>
                </label>
            </div>
        </div>

        <fieldset style="display: flex;">
            <legend>Tamanho 1</legend>
            <div class="form-group">
                <label for="size-p">Tamanho
                    <font color="red">*</font>:
                    <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                            data-placement="top"
                            title="Somente será aceito caracteres alfanúmericos, por exemplo: P, M, G, 200g, 400g, 1kg..."></i></small>
                </label>
                <input name="size-p" type="text" class="form-control w-50" id="size-p" value="">
            </div>
            <div class="form-group" style="width: 100%">
                <label for="size-p-description">Descrição:
                    <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                            data-placement="top"
                            title="Descrição sobre o tamanho, caso for diferenciado por P, M, G, você poderá inserir a descrição em gramagem."></i></small>
                </label>
                <input name="size-p-description" type="text" class="form-control w-50" id="size-p-description" value="">
            </div>
            <div class="form-group">
                <label for="price-p">Valor
                    <font color="red">*</font>:
                    <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                            data-placement="top"
                            title="Somente será aceito caracteres númerico e virgula, por exemplo: 20,00 | 30,00..."></i></small>
                </label>
                <input name="price-p" type="text" class="form-control w-50" id="price-p" value="">
            </div>
        </fieldset>
        <div id="price-size-status-container">
            <div class="alert alert-info" role="alert">
                Os campos que estiverem vazio, serão considerados como inexistente.
            </div>
            <fieldset style="display: flex;">
                <legend>Tamanho 2</legend>
                <div class="form-group">
                    <label for="size-m">Tamanho
                        <font color="red">*</font>:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top"
                                title="Somente será aceito caracteres alfanúmericos, por exemplo: P, M, G, 200g, 400g, 1kg..."></i></small>
                    </label>
                    <input name="size-m" type="text" class="form-control w-50" id="size-m" value="">
                </div>
                <div class="form-group" style="width: 100%">
                    <label for="size-m-description">Descrição:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top"
                                title="Descrição sobre o tamanho, caso for diferenciado por P, M, G, você poderá inserir a descrição em gramagem."></i></small>
                    </label>
                    <input name="size-m-description" type="text" class="form-control w-50" id="size-m-description"
                        value="">
                </div>
                <div class="form-group">
                    <label for="price-m">Valor
                        <font color="red">*</font>:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top"
                                title="Somente será aceito caracteres númerico e virgula, por exemplo: 20,00 | 30,00..."></i></small>
                    </label>
                    <input name="price-m" type="text" class="form-control w-50" id="price-m" value="">
                </div>
            </fieldset>
            <fieldset style="display: flex;">
                <legend>Tamanho 3</legend>
                <div class="form-group">
                    <label for="size-g">Tamanho:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top"
                                title="Somente será aceito caracteres alfanúmericos, por exemplo: P, M, G, 200g, 400g, 1kg..."></i></small>
                    </label>
                    <input name="size-g" type="text" class="form-control w-50" id="size-g" value="">
                </div>
                <div class="form-group" style="width: 100%">
                    <label for="size-g-description">Descrição:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top"
                                title="Descrição sobre o tamanho, caso for diferenciado por P, M, G, você poderá inserir a descrição em gramagem."></i></small>
                    </label>
                    <input name="size-g-description" type="text" class="form-control w-50" id="size-g-description"
                        value="">
                </div>
                <div class="form-group">
                    <label for="price-g">Valor:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top"
                                title="Somente será aceito caracteres númerico e virgula, por exemplo: 20,00 | 30,00..."></i></small>
                    </label>
                    <input name="price-g" type="text" class="form-control w-50" id="price-g" value="">
                </div>
            </fieldset>
            <fieldset style="display: flex;">
                <legend>Tamanho 4</legend>
                <div class="form-group">
                    <label for="size-xg">Tamanho:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top"
                                title="Somente será aceito caracteres alfanúmericos, por exemplo: P, M, G, 200g, 400g, 1kg..."></i></small>
                    </label>
                    <input name="size-xg" type="text" class="form-control w-50" id="size-xg" value="">
                </div>
                <div class="form-group" style="width: 100%">
                    <label for="size-xg-description">Descrição:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top"
                                title="Descrição sobre o tamanho, caso for diferenciado por P, M, G, você poderá inserir a descrição em gramagem."></i></small>
                    </label>
                    <input name="size-xg-description" type="text" class="form-control w-50" id="size-xg-description"
                        value="">
                </div>
                <div class="form-group">
                    <label for="price-xg">Valor:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top"
                                title="Somente será aceito caracteres númerico e virgula, por exemplo: 20,00 | 30,00..."></i></small>
                    </label>
                    <input name="price-xg" type="text" class="form-control w-50" id="price-xg" value="">
                </div>
            </fieldset>
        </div>
    </div>

    <div id="additional" class="content">
        <table class="table table-bordered" id="dataTableAdditional" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Marcar</th>
                    <th>Categoria</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Desconto</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Marcar</th>
                    <th>Categoria</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Desconto</th>
                    <th>Total</th>
                </tr>
            </tfoot>
            <tbody>
                <!-- ADICIONAL LISTA START -->
                <?php
                $additional_list = doDatabaseAdditionalList();
                if ($additional_list) {
                    foreach ($additional_list as $data) {
                        $additional_list_id = $data['id'];
                        ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="additional[]" value="<?php echo $additional_list_id ?>">
                            </td>

                            <td>
                                <label><?php echo getDatabaseCategoryTitle(getDatabaseAdditionalCategoryID($additional_list_id)) ?></label>
                            </td>
                            <td>
                                <label><?php echo getDatabaseAdditionalDescription($additional_list_id) ?></label>
                            </td>
                            <td>R$ <?php echo getDatabaseAdditionalCostPrice($additional_list_id) ?></td>
                            <td>R$ <?php echo getDatabaseAdditionalSalePrice($additional_list_id) ?></td>
                            <td>R$ <?php echo getDatabaseAdditionalTotalPrice($additional_list_id) ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td>Não existe nenhum adicional cadastrado, para cadastrar <a href="/panel/addicional">clica
                                aqui</a>
                        </td>
                    </tr>


                    <?php
                }
                ?>
                <!-- ADICIONAL LISTA FIM -->
            </tbody>
        </table>
    </div>

    <div id="complement" class="content">
        <table class="table table-bordered" id="dataTableComplement" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Marcar</th>
                    <th>Categoria</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Marcar</th>
                    <th>Categoria</th>
                    <th>Descrição</th>
                </tr>
            </tfoot>
            <tbody>
                <!-- COMPLEMENTO LISTA START -->
                <?php
                $complement_list = doDatabaseComplementsList();
                if ($complement_list) {
                    foreach ($complement_list as $data) {
                        $complement_list_id = $data['id'];
                        ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="complements[]" value="<?php echo $complement_list_id ?>">
                            </td>
                            <td>
                                <label><?php echo getDatabaseCategoryTitle(getDatabaseComplementCategoryID($complement_list_id)) ?></label>
                            </td>
                            <td><?php echo getDatabaseComplementDescription($complement_list_id) ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td>Não existe nenhum complemento cadastrado, para cadastrar <a href="/panel/complements">clica
                                aqui</a>
                        </td>
                    </tr>


                    <?php
                }
                ?>
                <!-- COMPLEMENTO LISTA FIM -->
            </tbody>
        </table>
    </div>


    <div id="questions" class="content">
        <div class="form-group">
            <label for="quest-status">Deseja habilitar o questionário?</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, você poderá definir perguntas para o usuário."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input type="checkbox" name="questions-status" id="quest-status" class="vc-switch-input">
                    <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                    <span class="vc-handle"></span>
                </label>
            </div>
        </div>

        <div id="quest-status-container">
            <div class="alert alert-info" role="alert">
                Os campos que estiverem vazio, serão considerados como inexistente.
            </div>
            <button id="addCampo" type="button" class="btn btn-primary mt-3">Adicionar Campo</button><br><br>
            <div id="accordion">
                <div class="card" style="margin-bottom: 10px;">
                    <div class="card-header" id="headingOne1">
                        <h5 class="mb-0">
                            <a class="btn btn-link" data-toggle="collapse" data-target="#collapseOne1"
                                aria-expanded="true" aria-controls="collapseOne1">
                                <input name="question1" type="text" class="form-control w-100" value="">
                            </a>
                        </h5>
                    </div>

                    <div id="collapseOne1" class="collapse" aria-labelledby="headingOne1" data-parent="#accordion">
                        <div class="form-group">
                            <label for="multiple-response1">Multipla resposta</label>
                            <small>
                                <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                    data-placement="top"
                                    title="Caso habilite está função,  usuário poderá escolher +1 resposta.">
                                </i>
                            </small>
                            <div class="vc-toggle-container">
                                <label class="vc-switch">
                                    <input type="checkbox" name="multiple-response1" id="multiple-response1"
                                        class="vc-switch-input checkbox-toggle1"
                                        onclick="checkboxToggle('#multiple-response1', '#response1', '.checkbox-toggle1');">
                                    <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                                    <span class="vc-handle"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="response-free1">Resposta Livre</label>
                            <small>
                                <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                    data-placement="top"
                                    title="Caso habilite está função,  usuário poderá responder o que quiser.">
                                </i>
                            </small>
                            <div class="vc-toggle-container">
                                <label class="vc-switch">
                                    <input type="checkbox" name="response-free1" id="response-free1"
                                        class="vc-switch-input checkbox-toggle1"
                                        onclick="checkboxToggle('#multiple-response1', '#response1', '.checkbox-toggle1');">
                                    <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                                    <span class="vc-handle"></span>
                                </label>
                            </div>
                        </div>
                        <div class="card-body" id="response1">
                            <input name="response1[]" type="text" class="form-control w-100 response" value="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>












    <br>
    <input name="token" type="text" value="<?php echo addGeneralSecurityToken('tokenAddProduct') ?>" hidden>
    <a href="/panel/products">
        <button type="button" class="btn btn-secondary">Voltar</button>
    </a>
    <button type="submit" class="btn btn-primary">Adicionar</button>
</form>


<script>

    function verificarImagem(caminhoDaImagem, $id) {
        $.ajax({
            url: caminhoDaImagem,
            type: 'HEAD',
            cache: false, // Desativa o cache
            success: function () {
                // Adiciona uma query string única à URL da imagem
                var novaUrl = caminhoDaImagem + '?' + new Date().getTime();
                $($id).attr('src', novaUrl);
            }
        });
    }

    function exibirIMG(input, id) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(id).attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }


    $(document).ready(function () {
        const dir = '<?php echo $image_config_dir; ?>';

        // FAVICON
        // const faviconFormat = '<?php echo getPathImageFormat($image_config_dir, "favicon") ?>';
        // verificarImagem(`${dir}favicon.${faviconFormat}`, '#faviconSelect');

        $('#inputProduct').change(function () {
            exibirIMG(this, '#productSelect');
        });


        // HORARY
        if ($('#stock-status').is(':checked')) {
            $('#stock-status-container').show();
        } else {
            $('#stock-status-container').hide();
        }

        if ($('#price-size-status').is(':checked')) {
            $('#price-size-status-container').show();
        } else {
            $('#price-size-status-container').hide();
        }

        if ($('#quest-status').is(':checked')) {
            $('#quest-status-container').show();
        } else {
            $('#quest-status-container').hide();
        }


        // HORARY
        // Adicionar um ouvinte de evento para o checkbox
        $('#stock-status').change(function () {
            // Toggle (mostrar ou esconder) a div com base no estado do checkbox
            $('#stock-status-container').toggle();
        });
        // Adicionar um ouvinte de evento para o checkbox
        $('#price-size-status').change(function () {
            // Toggle (mostrar ou esconder) a div com base no estado do checkbox
            $('#price-size-status-container').toggle();
        });
        // Adicionar um ouvinte de evento para o checkbox
        $('#quest-status').change(function () {
            // Toggle (mostrar ou esconder) a div com base no estado do checkbox
            $('#quest-status-container').toggle();
        });

        // Adicionar um ouvinte de evento para o checkbox
        $('#response-free1').change(function () {
            // Toggle (mostrar ou esconder) a div com base no estado do checkbox
            $('#response1').toggle();
        });

        // QUESTIONARIO
        // let currentIndex = 1; // índice do campo atual
        // $('body').on('input', 'input[name^="response"]:last', function () {
        //     // Verifica se o campo atual está preenchido
        //     if ($(this).val().trim() !== '') {
        //         // Obtém o nome do último campo existente
        //         let currentName = $(this).attr('name');

        //         // Cria um novo campo de entrada com o mesmo nome
        //         let newInput = $('<input>').attr({
        //             type: 'text',
        //             name: currentName,
        //             class: 'form-control w-100',
        //             value: ''
        //         });

        //         // Adiciona o novo campo de entrada após o último campo atual
        //         $(this).closest('.card-body').after($('<div class="card-body"></div>').append(newInput));
        //     }
        // });


        // Função para adicionar um novo input quando o atual for preenchido
        function adicionarNovoInput(idDoCard) {
            $('#' + idDoCard).on('input', 'input[name^="response"]:last', function () {
                // Verifica se o campo atual está preenchido
                if ($(this).val().trim() !== '') {
                    // Verifica se o próximo input já existe
                    if ($(this).next().length === 0) {
                        // Obtém o nome do próximo campo
                        let currentName = $(this).attr('name');
                        let nextIndex = parseInt(currentName.match(/\d+/)[0]) + 1;
                        let nextName = currentName;

                        // Cria um novo campo de entrada com o próximo nome
                        let newInput = $('<input>').attr({
                            type: 'text',
                            name: nextName,
                            class: 'form-control w-100 response',
                            value: ''
                        });

                        // Adiciona o novo campo de entrada após o atual
                        $(this).after(newInput);
                    }
                }
            });
        }


        var contador = 1;
        // Função para adicionar um novo campo quando o botão #addCampo for clicado
        $('#addCampo').click(function () {
            // Criar uma cópia do modelo de campo
            var novoCampo = $('.card').first().clone();

            // Definir valores vazios para os campos de entrada
            novoCampo.find('input').val('');

            // Incrementar o contador para o próximo campo
            contador++;

            // Atribuir IDs únicos aos elementos clonados
            var headingId = 'headingOne' + contador;
            var collapseId = 'collapseOne' + contador;
            var multipleResponseId = 'multiple-response' + contador;
            var responseId = 'response' + contador;
            var checkboxToggleId = 'checkbox-toggle' + contador;
            var responseFreeId = 'response-free' + contador;

            // Definição da função checkboxToggle para este novo campo
            window['checkboxToggle' + contador] = function (checkboxId, responseId, className) {
                $(className).change(function () {
                    $(className).not(this).prop('checked', false);
                });

                $(checkboxId).change(function () {
                    // Toggle (mostrar ou esconder) a div com base no estado do checkbox
                    $(responseId).show();
                });
            };

            // Atualizar os atributos dos elementos clonados com os novos IDs
            novoCampo.find('.card-header').attr('id', headingId);
            novoCampo.find('.card-header a').attr({
                'data-toggle': 'collapse',
                'data-target': '#' + collapseId,
                'aria-expanded': 'true',
                'aria-controls': collapseId
            });
            novoCampo.find('.collapse').attr({
                'id': collapseId,
                'aria-labelledby': headingId
            });
            novoCampo.find('input[name="question1"]').attr('name', 'question' + contador);
            novoCampo.find('input[name="multiple-response1"]').attr({
                'name': 'multiple-response' + contador,
                'id': multipleResponseId,
                'onclick': 'checkboxToggle' + contador + '("#' + multipleResponseId + '", "#' + responseId + '", ".checkbox-toggle' + contador + '")'
            });
            novoCampo.find('input[name="response-free1"]').attr({
                'name': responseFreeId,
                'id': responseFreeId
            });
            novoCampo.find('.checkbox-toggle1').removeClass('checkbox-toggle1').addClass(checkboxToggleId);
            novoCampo.find('.card-body').attr('id', responseId);
            novoCampo.find('input[name="response1[]"]').attr('name', responseId + '[]');

            // Adicionar checkboxToggle para o novo campo
            window['checkboxToggle' + contador]('#' + multipleResponseId, '#' + responseId, '.' + checkboxToggleId);

            // Adicionar ouvinte de evento para o checkbox de resposta livre
            novoCampo.find('input[name="' + responseFreeId + '"]').change(function () {
                // Toggle (mostrar ou esconder) a div com base no estado do checkbox
                $('#' + responseId).toggle();
            });

            // Adicionar o novo campo ao final do accordion
            $('#accordion').append(novoCampo);

            // Chamar adicionarNovoInput para o novo campo
            adicionarNovoInput(responseId);
        });

        // Chamar adicionarNovoInput para cada card existente
        $('[id^="response"]').each(function () {
            adicionarNovoInput($(this).attr('id'));
        });


        // QUESTIONARIO

        $('.button').click(function () {
            // Remova a classe 'menu-config-select' de todos os botões
            $('.button').removeClass('menu-config-select');

            // Adicione a classe 'menu-config-select' ao botão clicado
            $(this).addClass('menu-config-select');

            // Obtenha o valor do atributo 'data-filter'
            var filter = $(this).data('filter');

            // Esconda todas as divs com a classe 'content'
            $('.content').hide();

            // Mostre a div correspondente ao filtro clicado
            $('#' + filter).show();
        });
    });
</script>
<?php
include_once (realpath(__DIR__ . "/layout/php/footer.php"));
?>