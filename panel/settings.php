<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));
// getMasterAdminAccess();

// if ($_SERVER['REQUEST_METHOD'] === 'POST' && getToken('settingsSave')) {
//     if (empty($_POST) === false) {
//         $required_fields = array('title', 'url', 'valueForExp', 'valueFromExp', 'b_email', 'b_password');
//         if (validateRequiredFields($_POST, $required_fields) === false) {
//             $errors[] = "Obrigatório o preenchimento de todos os campos.";
//         }

//         if (getSizeString($_POST['title'], 2, 20) !== true) {
//             $errors[] = "O título inserido é inválido. Certifique-se de que ele tenha entre 2 e 20 caracteres.";
//         }

//         if (getSizeString($_POST['pg_title'], 5, 40) !== true) {
//             $errors[] = "O título inserido é inválido. Certifique-se de que ele tenha entre 5 e 40 caracteres.";
//         }

//         if (getSizeString($_POST['pg_subtitle'], 5, 40) !== true) {
//             $errors[] = "O título inserido é inválido. Certifique-se de que ele tenha entre 5 e 40 caracteres.";
//         }

//         if (getSizeString($_POST['pg_about'], 5, 170) !== true) {
//             $errors[] = "O título inserido é inválido. Certifique-se de que ele tenha entre 5 e 170 caracteres.";
//         }

//         if (isPGIconExist($_POST['pg_about_ip1']) !== true) {
//             $errors[] = "O primeiro icone selecionado não é existente.";
//         }

//         if (getSizeString($_POST['pg_about_tp1'], 3, 15) !== true) {
//             $errors[] = "O primeiro título inserido é inválido. Certifique-se de que ele tenha entre 3 e 15 caracteres.";
//         }

//         if (getSizeString($_POST['pg_about_dp1'], 5, 170) !== true) {
//             $errors[] = "A primeira descrição inserido é inválido. Certifique-se de que ele tenha entre 5 e 170 caracteres.";
//         }

//         if (isPGIconExist($_POST['pg_about_ip2']) !== true) {
//             $errors[] = "O segundo icone selecionado não é existente.";
//         }

//         if (getSizeString($_POST['pg_about_tp2'], 3, 15) !== true) {
//             $errors[] = "O segundo titulo inserido é inválido. Certifique-se de que ele tenha entre 3 e 15 caracteres.";
//         }

//         if (getSizeString($_POST['pg_about_dp2'], 5, 170) !== true) {
//             $errors[] = "A segunda descrição inserido é inválido. Certifique-se de que ele tenha entre 5 e 170 caracteres.";
//         }

//         if (isPGIconExist($_POST['pg_about_ip3']) !== true) {
//             $errors[] = "O primeiro icone selecionado não é existente.";
//         }

//         if (getSizeString($_POST['pg_about_tp3'], 3, 15) !== true) {
//             $errors[] = "O terceiro título inserido é inválido. Certifique-se de que ele tenha entre 3 e 15 caracteres.";
//         }

//         if (getSizeString($_POST['pg_about_dp3'], 5, 170) !== true) {
//             $errors[] = "A terceira descrição inserido é inválido. Certifique-se de que ele tenha entre 5 e 170 caracteres.";
//         }

//         if (getSizeString($_POST['pg_nvl'], 5, 170) !== true) {
//             $errors[] = "A descrição de nível inserido é inválido. Certifique-se de que ele tenha entre 5 e 170 caracteres.";
//         }

//         if (getSizeString($_POST['pg_clientD'], 5, 170) !== true) {
//             $errors[] = "A descrição de cliente destaque inserido é inválido. Certifique-se de que ele tenha entre 5 e 170 caracteres.";
//         }


//         if (isset($_FILES['rules']) && $_FILES['rules']['size'] > 0) {
//             // Verifica se o arquivo foi enviado sem erros
//             if ($_FILES['rules']['error'] !== UPLOAD_ERR_OK) {
//                 $errors[] = 'Erro no upload do arquivo.';
//             }

//             // Verifica se é uma imagem válida
//             $pdfMimeTypes = ['application/pdf'];
//             $fileType = mime_content_type($_FILES['rules']['tmp_name']);

//             if (!in_array($fileType, $pdfMimeTypes)) {
//                 $errors[] = 'O arquivo enviado para as regras de privacidade não é um PDF válido.';
//             }
//         }
//         if (isset($_FILES['privacy']) && $_FILES['privacy']['size'] > 0) {
//             // Verifica se o arquivo foi enviado sem erros
//             if ($_FILES['privacy']['error'] !== UPLOAD_ERR_OK) {
//                 $errors[] = 'Erro no upload do arquivo.';
//             }

//             // Verifica se é uma imagem válida
//             $pdfMimeTypes = ['application/pdf'];
//             $fileType = mime_content_type($_FILES['privacy']['tmp_name']);

//             if (!in_array($fileType, $pdfMimeTypes)) {
//                 $errors[] = 'O arquivo enviado para as regras de privacidade não é um PDF válido.';
//             }
//         }

//         if (isset($_FILES['telesenaImage']) && $_FILES['telesenaImage']['size'] > 0) {
//             // Verifica se o arquivo foi enviado sem erros
//             if ($_FILES['telesenaImage']['error'] !== UPLOAD_ERR_OK) {
//                 $errors[] = 'Erro no upload do arquivo.';
//             }

//             // Verifica se é uma imagem válida
//             $imageInfo = getimagesize($_FILES['telesenaImage']['tmp_name']);
//             if (!$imageInfo || !in_array($imageInfo['mime'], array('image/jpeg', 'image/png'))) {
//                 $errors[] = 'O arquivo enviado para background da telesena não é uma imagem válida.';
//             } elseif ($imageInfo[0] != 1280 || $imageInfo[1] != 740) {
//                 $errors[] = 'A imagem para backgrounda da telesena precisa ter exatamente 1280x740.';
//             }
//         }

//         if (isset($_FILES['scratchcardImage']) && $_FILES['scratchcardImage']['size'] > 0) {
//             // Verifica se o arquivo foi enviado sem erros
//             if ($_FILES['scratchcardImage']['error'] !== UPLOAD_ERR_OK) {
//                 $errors[] = 'Erro no upload do arquivo.';
//             }

//             // Verifica se é uma imagem válida
//             $imageInfo = getimagesize($_FILES['scratchcardImage']['tmp_name']);
//             if (!$imageInfo || !in_array($imageInfo['mime'], array('image/png'))) {
//                 $errors[] = 'O arquivo enviado para background da raspadinha não é uma imagem válida.';
//             } elseif ($imageInfo[0] != 502 || $imageInfo[1] != 500) {
//                 $errors[] = 'A imagem para backgrounda da raspadinha precisa ter exatamente 502x500.';
//             }
//         }


//         if (isset($_FILES['mainImage']) && $_FILES['mainImage']['size'] > 0) {
//             // Verifica se o arquivo foi enviado sem erros
//             if ($_FILES['mainImage']['error'] !== UPLOAD_ERR_OK) {
//                 $errors[] = 'Erro no upload do arquivo.';
//             }

//             // Verifica se é uma imagem válida
//             $imageInfo = getimagesize($_FILES['mainImage']['tmp_name']);
//             if (!$imageInfo || !in_array($imageInfo['mime'], array('image/jpeg'))) {
//                 $errors[] = 'O arquivo enviado na tela principal não é uma imagem válida.';
//             } elseif ($imageInfo[0] > 2500 || $imageInfo[1] > 2500) {
//                 $errors[] = 'A imagem excede o tamanho máximo permitido (2500x2500 pixels).';
//             }
//         }

//         if (isset($_FILES['codeImage']) && $_FILES['codeImage']['size'] > 0) {
//             // Verifica se o arquivo foi enviado sem erros
//             if ($_FILES['codeImage']['error'] !== UPLOAD_ERR_OK) {
//                 $errors[] = 'Erro no upload do arquivo.';
//             }

//             // Verifica se é uma imagem válida
//             $imageInfo = getimagesize($_FILES['codeImage']['tmp_name']);
//             if (!$imageInfo || !in_array($imageInfo['mime'], array('image/png'))) {
//                 $errors[] = 'O arquivo enviado na imagem de código não é uma imagem válida.';
//             } elseif ($imageInfo[0] < 502 || $imageInfo[1] < 500) {
//                 $errors[] = 'A imagem é menor do que o tamanho mínimo permitido (502x500 pixels).';
//             } elseif ($imageInfo[0] > 502 || $imageInfo[1] > 500) {
//                 $errors[] = 'A imagem excede o tamanho máximo permitido (502x500 pixels).';
//             }

//         }


//         if (isset($_FILES['favicon']) && $_FILES['favicon']['size'] > 0) {
//             // Verifica se o arquivo foi enviado sem erros
//             if ($_FILES['favicon']['error'] !== UPLOAD_ERR_OK) {
//                 $errors[] = 'Erro no upload do arquivo.';
//             }

//             // Verifica se é uma imagem válida
//             $imageInfo = getimagesize($_FILES['favicon']['tmp_name']);
//             if (!$imageInfo || !in_array($imageInfo['mime'], array('image/png', 'image/jpeg', 'image/gif'))) {
//                 $errors[] = 'O arquivo não é uma imagem válida.';
//             } elseif ($imageInfo[0] > 700 || $imageInfo[1] > 700) {
//                 $errors[] = 'A imagem excede o tamanho máximo permitido (700x700 pixels).';
//             }
//         }

//         if (isset($_FILES['logo']) && $_FILES['logo']['size'] > 0) {
//             // Verifica se o arquivo foi enviado sem erros
//             if ($_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
//                 $errors[] = 'Erro no upload do arquivo.';
//             }

//             $imageInfo = getimagesize($_FILES['logo']['tmp_name']);
//             if (!$imageInfo || !in_array($imageInfo['mime'], array('image/png', 'image/jpeg', 'image/gif'))) {
//                 $errors[] = 'O arquivo não é uma imagem válida.';
//             } elseif ($imageInfo[0] > 1500 || $imageInfo[1] > 1500) {
//                 $errors[] = 'A imagem excede o tamanho máximo permitido (1500x1500 pixels).';
//             }
//         }

//         if (isset($_POST['rperproduct']) && isset($_POST['rpertotalpurchase'])) {
//             $errors[] = 'Você não pode ter 02 métodos para ganho de experiência.';
//         }

//         if (is_numeric($_POST['valueForExp']) === false) {
//             $errors[] = 'Você precisa prencher o valor para experiência, com um número.';
//         }

//         if (is_numeric($_POST['valueFromExp']) === false) {
//             $errors[] = 'Você precisa prencher o valor da experiência, com um número.';
//         }

//         if (isset($_POST['whatsStatus'])) {
//             if (is_numeric($_POST['whatsContact']) === false) {
//                 $errors[] = 'No campo Contato Whatsapp só é aceito números.';
//             }

//             $whats_fields = array('whatsContact', 'whatsMessage');
//             if (validateRequiredFields($_POST, $whats_fields) === false) {
//                 $errors[] = "É indispensável preencher todos os campos referentes às informações do WhatsApp.";
//             }
//         }

//         if (isset($_POST['instStatus'])) {
//             $inst_fields = array('instContact');
//             if (validateRequiredFields($_POST, $inst_fields) === false) {
//                 $errors[] = "É indispensável preencher todos os campos referentes às informações do Instagram.";
//             }
//         }

//         if (isset($_POST['faceStatus'])) {
//             $face_fields = array('faceContact');
//             if (validateRequiredFields($_POST, $face_fields) === false) {
//                 $errors[] = "É indispensável preencher todos os campos referentes às informações do Facebook.";
//             }
//         }

//         if (isset($_POST['baseboard'])) {
//             $face_fields = array('baseboard_description');
//             if (validateRequiredFields($_POST, $face_fields) === false) {
//                 $errors[] = "É indispensável preencher todos os campos referentes às informações do Rodapé.";
//             }
//         }

//     }


//     if (empty($errors)) {

//         $image = True;
//         $targetPath = __DIR__ . '/../front/images/config';
//         $targetGamePath = __DIR__ . '/../front/images/games_config';
//         $targetRulesPath = __DIR__ . '/../rules';

//         if (isset($_FILES['favicon']) && $_FILES['favicon']['size'] > 0) {
//             if (move_uploaded_file($_FILES['favicon']['tmp_name'], $targetPath . '/favicon.png') === false) {
//                 $errors[] = 'Falha ao mover o arquivo para o diretório de destino.';
//                 $image = false;
//             }
//         }


//         if (isset($_FILES['codeImage']) && $_FILES['codeImage']['size'] > 0) {
//             removerArquivos($targetPath . '/', 'key');
//             $fileInfo = pathinfo($_FILES['codeImage']['name']);
//             $fileExtension = $fileInfo['extension'];


//             if (move_uploaded_file($_FILES['codeImage']['tmp_name'], $targetPath . '/key.' . $fileExtension) === False) {
//                 $errors[] = 'Falha ao mover o arquivo para o diretório de destino.';
//                 $image = False;
//             }
//         }

//         if (isset($_FILES['rules']) && $_FILES['rules']['size'] > 0) {
//             removerArquivos($targetRulesPath . '/', 'rules');

//             if (move_uploaded_file($_FILES['rules']['tmp_name'], $targetRulesPath . '/rules.pdf') === False) {
//                 $errors[] = 'Falha ao mover o arquivo para o diretório de destino.';
//                 $image = False;
//             }
//         }
//         if (isset($_FILES['privacy']) && $_FILES['privacy']['size'] > 0) {
//             removerArquivos($targetRulesPath . '/', 'privacy');

//             if (move_uploaded_file($_FILES['privacy']['tmp_name'], $targetRulesPath . '/privacy.pdf') === False) {
//                 $errors[] = 'Falha ao mover o arquivo para o diretório de destino.';
//                 $image = False;
//             }
//         }

//         if (isset($_FILES['telesenaImage']) && $_FILES['telesenaImage']['size'] > 0) {
//             removerArquivos($targetGamePath . '/', 'background-tele');
//             $fileInfo = pathinfo($_FILES['telesenaImage']['name']);
//             $fileExtension = $fileInfo['extension'];


//             if (move_uploaded_file($_FILES['telesenaImage']['tmp_name'], $targetGamePath . '/background-tele.' . $fileExtension) === False) {
//                 $errors[] = 'Falha ao mover o arquivo para o diretório de destino.';
//                 $image = False;
//             }
//         }

//         if (isset($_FILES['scratchcardImage']) && $_FILES['scratchcardImage']['size'] > 0) {
//             removerArquivos($targetGamePath . '/', 'background-scrachcard');
//             $fileInfo = pathinfo($_FILES['scratchcardImage']['name']);
//             $fileExtension = $fileInfo['extension'];


//             if (move_uploaded_file($_FILES['scratchcardImage']['tmp_name'], $targetGamePath . '/background-scrachcard.' . $fileExtension) === False) {
//                 $errors[] = 'Falha ao mover o arquivo para o diretório de destino.';
//                 $image = False;
//             }
//         }

//         if (isset($_FILES['mainImage']) && $_FILES['mainImage']['size'] > 0) {
//             removerArquivos($targetPath . '/', 'header-bg');
//             $fileInfo = pathinfo($_FILES['mainImage']['name']);
//             $fileExtension = $fileInfo['extension'];


//             if (move_uploaded_file($_FILES['mainImage']['tmp_name'], $targetPath . '/header-bg.' . $fileExtension) === False) {
//                 $errors[] = 'Falha ao mover o arquivo para o diretório de destino.';
//                 $image = False;
//             }
//         }

//         if (isset($_FILES['logo']) && $_FILES['logo']['size'] > 0) {
//             if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetPath . '/logo.png') === False) {
//                 $errors[] = 'Falha ao mover o arquivo para o diretório de destino.';
//                 $image = False;
//             }
//         }
//         $import_data_query = array(
//             'site_title' => (!empty($_POST['title']) ? $_POST['title'] : NULL),
//             'site_description' => (!empty($_POST['description']) ? $_POST['description'] : NULL),
//             'password' => (isset($_POST['password']) ? 1 : 0),
//             'cpf' => (isset($_POST['cpf']) ? 1 : 0),
//             'address' => (isset($_POST['address']) ? 1 : 0),
//             'game_register' => (isset($_POST['registerRewardStatus']) ? 1 : 0),
//             'reward_per_product' => (isset($_POST['rperproduct']) ? 1 : 0),
//             'reward_per_totalpurchase' => (isset($_POST['rpertotalpurchase']) ? 1 : 0),
//             'value_for_exp' => (!empty($_POST['valueForExp']) ? $_POST['valueForExp'] : 30),
//             'value_from_exp' => (!empty($_POST['valueFromExp']) ? $_POST['valueFromExp'] : 1),
//             'baseboard' => (isset($_POST['baseboard']) ? 1 : 0),
//             'baseboard_text' => (!empty($_POST['baseboard_description']) ? $_POST['baseboard_description'] : NULL),
//             'url' => (!empty($_POST['url']) ? $_POST['url'] : NULL),
//             'whatsapp_status' => (isset($_POST['whatsStatus']) ? 1 : 0),
//             'whatsapp_contact' => (!empty($_POST['whatsContact']) ? $_POST['whatsContact'] : NULL),
//             'whatsapp_message' => (!empty($_POST['whatsMessage']) ? $_POST['whatsMessage'] : NULL),
//             'facebook_status' => (isset($_POST['faceStatus']) ? 1 : 0),
//             'facebook_contact' => (!empty($_POST['faceContact']) ? $_POST['faceContact'] : NULL),
//             'instagram_status' => (isset($_POST['instStatus']) ? 1 : 0),
//             'instagram_contact' => (!empty($_POST['instContact']) ? $_POST['instContact'] : NULL),
//             'b_email' => (!empty($_POST['b_email']) ? $_POST['b_email'] : NULL),
//             'n_email' => (!empty($_POST['n_email']) ? $_POST['n_email'] : NULL),
//             'b_password' => (!empty($_POST['b_password']) ? base64_encode($_POST['b_password']) : NULL),

//             'pg_title' => (!empty($_POST['pg_title']) ? $_POST['pg_title'] : NULL),
//             'pg_subtitle' => (!empty($_POST['pg_subtitle']) ? $_POST['pg_subtitle'] : NULL),
//             'pg_about' => (!empty($_POST['pg_about']) ? $_POST['pg_about'] : NULL),
//             'pg_about_ip1' => (!empty($_POST['pg_about_ip1']) ? $_POST['pg_about_ip1'] : NULL),
//             'pg_about_tp1' => (!empty($_POST['pg_about_tp1']) ? $_POST['pg_about_tp1'] : NULL),
//             'pg_about_dp1' => (!empty($_POST['pg_about_dp1']) ? $_POST['pg_about_dp1'] : NULL),
//             'pg_about_ip2' => (!empty($_POST['pg_about_ip2']) ? $_POST['pg_about_ip2'] : NULL),
//             'pg_about_tp2' => (!empty($_POST['pg_about_tp2']) ? $_POST['pg_about_tp2'] : NULL),
//             'pg_about_dp2' => (!empty($_POST['pg_about_dp2']) ? $_POST['pg_about_dp2'] : NULL),
//             'pg_about_ip3' => (!empty($_POST['pg_about_ip3']) ? $_POST['pg_about_ip3'] : NULL),
//             'pg_about_tp3' => (!empty($_POST['pg_about_tp3']) ? $_POST['pg_about_tp3'] : NULL),
//             'pg_about_dp3' => (!empty($_POST['pg_about_dp3']) ? $_POST['pg_about_dp3'] : NULL),
//             'pg_nvl' => (!empty($_POST['pg_nvl']) ? $_POST['pg_nvl'] : NULL),
//             'pg_clientD' => (!empty($_POST['pg_clientD']) ? $_POST['pg_clientD'] : NULL),
//             'pg_addressURL' => (!empty($_POST['pg_address']) ? $_POST['pg_address'] : NULL),
//         );

//         if ($image) {
//             if (getDatabaseSettingsRowCount() > 0) {
//                 doUpdateDatabaseSettingsRow(1, $import_data_query);
//             } else {
//                 doInsertDatabaseSettingsRow($import_data_query);
//             }
//             echo alertSuccess("As alterações foram salvas com sucesso!!");
//         }

//     }


//     if (empty($errors) === false) {
//         header("HTTP/1.1 401 Not Found");
//         echo alertError($errors);
//     }
// }

?>

<div class="menu-configs-container">
    <ul class="menu-configs-nav">
        <li class="button menu-config-select" data-filter="images">Imagens</li>
        <li class="button" data-filter="informations">Informações</li>
        <li class="button" data-filter="delivery">Pedido</li>
        <li class="button" data-filter="pay">Pagamento</li>
        <li class="button" data-filter="social">Social</li>
        <li class="button" data-filter="horarys">Horários</li>
    </ul>
</div>

<form action="/panel/settings" method="post" enctype="multipart/form-data">
    <div id="images" class="content">

        <div class="form-imgs-container">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Icone
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top"
                                title="Formatos aceito: PNG, JPG, JPEG.Tamanho Máximo: 512x512."></i></small>
                    </span>
                    <div id="previewIcon"
                        style="width: 100px; height: 100px; overflow: hidden; border: 1px solid #ccc;">
                        <img id="faviconSelect" style="width: 100%; height: 100%;">
                    </div>
                </div>
                <div class="custom-file">
                    <input type="file" name="favicon" class="custom-file-input" id="inputFavicon" accept="image/*">
                    <label class="custom-file-label" for="inputFavicon">Escolha sua imagem.</label>
                </div>
            </div>
        </div>

        <div class="form-imgs-container">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Background
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top"
                                title="Formatos aceito: PNG, JPG, JPEG, AVIF.Tamanho Máximo: 2500x2500."></i></small>
                    </span>
                    <div id="previewBackground"
                        style="width: 100px; height: 100px; overflow: hidden; border: 1px solid #ccc;">
                        <img id="backgroundSelect" style="width: 100%; height: 100%;">
                    </div>
                </div>
                <div class="custom-file">
                    <input type="file" name="background" class="custom-file-input" id="inputBackground"
                        accept="image/*">
                    <label class="custom-file-label" for="inputBackground">Escolha sua imagem.</label>
                </div>
            </div>
        </div>
        <div class="form-imgs-container">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Logo
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top"
                                title="Formatos aceito: PNG, JPG, JPEG.Tamanho Máximo: 600x600."></i></small></span>
                    <div id="previewLogo"
                        style="width: 100px; height: 100px; overflow: hidden; border: 1px solid #ccc;">
                        <img id="logoSelect" style="width: 100%; height: 100%;">
                    </div>
                </div>
                <div class="custom-file">
                    <input type="file" name="logo" class="custom-file-input" id="inputLogo" accept="image/*">
                    <label class="custom-file-label" for="inputLogo">Escolha sua imagem.</label>
                </div>
            </div>
        </div>
        <div class="form-imgs-container">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Login
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top"
                                title="Formatos aceito: PNG, JPG, JPEG.Tamanho Máximo: 1564x1194."></i></small>
                    </span>
                    <div id="previewLogin"
                        style="width: 100px; height: 100px; overflow: hidden; border: 1px solid #ccc;">
                        <img id="loginSelect" style="width: 100%; height: 100%;">
                    </div>
                </div>
                <div class="custom-file">
                    <input type="file" name="login" class="custom-file-input" id="inputLogin" accept="image/*">
                    <label class="custom-file-label" for="inputLogin">Escolha sua imagem.</label>
                </div>
            </div>
        </div>

    </div>

    <div id="informations" class="content">

        <div class="form-group">
            <label for="title">Título
                <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                        title="Nome da sua loja."></i></small>
            </label>
            <input name="title" type="text" class="form-control" id="title" value="">
        </div>


        <div class="form-group">
            <label for="description">Descrição
                <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                        title="Tipo de produtos vendido, descrição do que fazem..."></i></small>
            </label>
            <input name="description" type="text" class="form-control" id="description" value="">
        </div>

    </div>

    <div id="delivery" class="content">

        <div class="form-group">
            <label for="order-withdrawal">Aceita retirada de pedido?</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, será mostrado para o cliente a opção para retirar pedido. Isentando da taxa de entrega."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input type="checkbox" name="order-withdrawal" id="order-withdrawal" class="vc-switch-input">
                    <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                    <span class="vc-handle"></span>
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="restaurant-delivery-api">Endereço Google
                <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                        title="Necessário a URL do google"></i></small>
            </label>
            <input name="restaurant-delivery-api" type="text" class="form-control" id="restaurant-delivery-api"
                value="">
        </div>
        <div class="form-group">
            <label for="min-delivery">Pedido Mínimo
                <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                        title="Insira o valor mínimo para compra, caso não tenha, deixe em 0,00."></i></small>
            </label>
            <input name="min-delivery" type="text" class="form-control" id="min-delivery" value="">
        </div>

        <div class="form-group">
            <label for="delivery-fee">Taxa de Entrega
                <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                        title="Insira o valor para a taxa de entrega."></i></small>
            </label>
            <input name="delivery-fee" type="text" class="form-control" id="delivery-fee" value="">
        </div>


        <div class="form-group">
            <label for="time-min">Tempo Mínimo
                <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                        title="Tempo mínimo que o cliente deverá esperar."></i></small>
            </label>
            <input name="time-min" type="text" class="form-control" id="time-min" value="">
        </div>

        <div class="form-group">
            <label for="time-max">Tempo Máximo
                <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                        title="Tempo mínimo que o cliente deverá esperar."></i></small>
            </label>
            <input name="time-max" type="text" class="form-control" id="time-max" value="">
        </div>
    </div>

    <div id="social" class="content">
        <div class="form-group">
            <label for="whatsapp-status">Deseja habilitar o WhatsApp?</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, será mostrado o icone do whatsapp no rodapé."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input type="checkbox" name="whatsapp-status" id="whatsapp-status" class="vc-switch-input">
                    <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                    <span class="vc-handle"></span>
                </label>
            </div>
        </div>

        <div class="form-group" id="whatsapp-status-container">
            <label for="whatsapp-number">WhatsApp:
                <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                        title="Número do WhatsApp."></i></small>
            </label>
            <input name="whatsapp-number" type="text" class="form-control" id="whatsapp-number" value="">
        </div>

        <div class="form-group">
            <label for="instagram-status">Deseja habilitar o Instagram?</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, será mostrado o icone do instagram no rodapé."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input type="checkbox" name="instagram-status" id="instagram-status" class="vc-switch-input">
                    <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                    <span class="vc-handle"></span>
                </label>
            </div>
        </div>

        <div class="form-group" id="instagram-status-container">
            <label for="profile-instagram">Instagram
                <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                        title="@ do Instagram."></i></small>
            </label>
            <input name="profile-instagram" type="text" class="form-control" id="profile-instagram" value="">
        </div>

        <div class="form-group">
            <label for="facebook-status">Deseja habilitar o Facebook?</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, será mostrado o icone do facebook no rodapé."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input type="checkbox" name="facebook-status" id="facebook-status" class="vc-switch-input">
                    <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                    <span class="vc-handle"></span>
                </label>
            </div>
        </div>

        <div class="form-group" id="facebook-status-container">
            <label for="profile-facebook">Facebook
                <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                        title="@ do facebook."></i></small>
            </label>
            <input name="profile-facebook" type="text" class="form-control" id="profile-facebook" value="">
        </div>
    </div>

    <div id="pay" class="content">
        <div class="form-group">
            <label for="pay-money">Dinheiro</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, será mostrado para o cliente a opção de pagamento em dinheiro."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input type="checkbox" name="pay-money" id="pay-money" class="vc-switch-input">
                    <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                    <span class="vc-handle"></span>
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="pay-credit">Crédito</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, será mostrado para o cliente a opção de pagamento com cartão de crédito."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input type="checkbox" name="pay-credit" id="pay-credit" class="vc-switch-input">
                    <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                    <span class="vc-handle"></span>
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="pay-debit">Débito</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, será mostrado para o cliente a opção de pagamento com cartão de debito."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input type="checkbox" name="pay-debit" id="pay-debit" class="vc-switch-input">
                    <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                    <span class="vc-handle"></span>
                </label>
            </div>
        </div>


        <div class="form-group">
            <label for="pay-pix">Pix</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, será mostrado para o cliente a opção de pagamento com pix."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input type="checkbox" name="pay-pix" id="pay-pix" class="vc-switch-input">
                    <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                    <span class="vc-handle"></span>
                </label>
            </div>
        </div>

        <div class="form-group" id="pix-info-container">
            <label for="pix-key">Pix:
                <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                        title="Chave Pix."></i></small>
            </label>
            <input name="pix-key" type="text" class="form-control" id="pix-key" value="">
        </div>
    </div>


    <div id="horarys" class="content">
        <div class="form-group">
            <label for="monday-status">Estabelecimento é aberto de Segunda-Feira?</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, o cliente poderá fazer pedido nas terça-feira."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input type="checkbox" name="monday-status" id="monday-status" class="vc-switch-input">
                    <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                    <span class="vc-handle"></span>
                </label>
            </div>
        </div>

        <div class="form-group">
            <label for="tuesday-status">Estabelecimento é aberto de Terça-Feira?</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, o cliente poderá fazer pedido nas terça-feira."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input type="checkbox" name="tuesday-status" id="tuesday-status" class="vc-switch-input">
                    <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                    <span class="vc-handle"></span>
                </label>
            </div>
        </div>


        <div class="form-group">
            <label for="wednesday-status">Estabelecimento é aberto de Quarta-Feira?</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, o cliente poderá fazer pedido nas quarta-feira."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input type="checkbox" name="wednesday-status" id="wednesday-status" class="vc-switch-input">
                    <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                    <span class="vc-handle"></span>
                </label>
            </div>
        </div>


        <div class="form-group">
            <label for="thursday-status">Estabelecimento é aberto de Quinta-Feira?</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, o cliente poderá fazer pedido nas quinta-feira."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input type="checkbox" name="thursday-status" id="thursday-status" class="vc-switch-input">
                    <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                    <span class="vc-handle"></span>
                </label>
            </div>
        </div>


        <div class="form-group">
            <label for="friday-status">Estabelecimento é aberto de Sexta-Feira?</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, o cliente poderá fazer pedido nas sexta-feira."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input type="checkbox" name="friday-status" id="friday-status" class="vc-switch-input">
                    <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                    <span class="vc-handle"></span>
                </label>
            </div>
        </div>


        <div class="form-group">
            <label for="saturday-status">Estabelecimento é aberto de Sabado?</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, o cliente poderá fazer pedido nas sabado."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input type="checkbox" name="saturday-status" id="saturday-status" class="vc-switch-input">
                    <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                    <span class="vc-handle"></span>
                </label>
            </div>
        </div>

        <div class="form-group">
            <label for="sunday-status">Estabelecimento é aberto de Domingo?</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, o cliente poderá fazer pedido nos domingo."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input type="checkbox" name="sunday-status" id="sunday-status" class="vc-switch-input">
                    <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                    <span class="vc-handle"></span>
                </label>
            </div>
        </div>

        <div id="monday-status-container">
            <fieldset style="display: flex;">
                <legend>Segunda-Feira</legend>
                <div class="form-group">
                    <label for="monday-start">Ínicio:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top" title="Ínicio de expediente."></i></small>
                    </label>
                    <input name="monday-start" type="text" class="form-control w-50" id="monday-start" value="">
                </div>
                <div class="form-group">
                    <label for="monday-end">Fim:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top" title="Fim de expediente."></i></small>
                    </label>
                    <input name="monday-end" type="text" class="form-control w-50" id="monday-end" value="">
                </div>
            </fieldset>
        </div>

        <div id="tuesday-status-container">
            <fieldset style="display: flex;">
                <legend>Terça-Feira</legend>
                <div class="form-group">
                    <label for="tuesday-start">Ínicio:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top" title="Ínicio de expediente."></i></small>
                    </label>
                    <input name="tuesday-start" type="text" class="form-control w-50" id="tuesday-start" value="">
                </div>
                <div class="form-group">
                    <label for="tuesday-end">Fim:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top" title="Fim de expediente."></i></small>
                    </label>
                    <input name="tuesday-end" type="text" class="form-control w-50" id="tuesday-end" value="">
                </div>
            </fieldset>
        </div>
        <div id="wednesday-status-container">
            <fieldset style="display: flex;">
                <legend>Quarta-Feira</legend>
                <div class="form-group">
                    <label for="wednesday-start">Ínicio:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top" title="Ínicio de expediente."></i></small>
                    </label>
                    <input name="wednesday-start" type="text" class="form-control w-50" id="wednesday-start" value="">
                </div>
                <div class="form-group">
                    <label for="wednesday-end">Fim:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top" title="Fim de expediente."></i></small>
                    </label>
                    <input name="wednesday-end" type="text" class="form-control w-50" id="wednesday-end" value="">
                </div>
            </fieldset>
        </div>
        <div id="thursday-status-container">
            <fieldset style="display: flex;">
                <legend>Quinta-Feira</legend>
                <div class="form-group">
                    <label for="thursday-start">Ínicio:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top" title="Ínicio de expediente."></i></small>
                    </label>
                    <input name="thursday-start" type="text" class="form-control w-50" id="thursday-start" value="">
                </div>
                <div class="form-group">
                    <label for="thursday-end">Fim:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top" title="Fim de expediente."></i></small>
                    </label>
                    <input name="thursday-end" type="text" class="form-control w-50" id="thursday-end" value="">
                </div>
            </fieldset>
        </div>
        <div id="friday-status-container">
            <fieldset style="display: flex;">
                <legend>Sexta-Feira</legend>
                <div class="form-group">
                    <label for="friday-start">Ínicio:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top" title="Ínicio de expediente."></i></small>
                    </label>
                    <input name="friday-start" type="text" class="form-control w-50" id="friday-start" value="">
                </div>
                <div class="form-group">
                    <label for="friday-end">Fim:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top" title="Fim de expediente."></i></small>
                    </label>
                    <input name="friday-end" type="text" class="form-control w-50" id="friday-end" value="">
                </div>
            </fieldset>
        </div>
        <div id="saturday-status-container">
            <fieldset style="display: flex;">
                <legend>Sábado</legend>
                <div class="form-group">
                    <label for="saturday-start">Ínicio:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top" title="Ínicio de expediente."></i></small>
                    </label>
                    <input name="saturday-start" type="text" class="form-control w-50" id="saturday-start" value="">
                </div>
                <div class="form-group">
                    <label for="saturday-end">Fim:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top" title="Fim de expediente."></i></small>
                    </label>
                    <input name="saturday-end" type="text" class="form-control w-50" id="saturday-end" value="">
                </div>
            </fieldset>
        </div>
        <div id="sunday-status-container">
            <fieldset style="display: flex;">
                <legend>Domingo</legend>
                <div class="form-group">
                    <label for="sunday-start">Ínicio:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top" title="Ínicio de expediente."></i></small>
                    </label>
                    <input name="sunday-start" type="text" class="form-control w-50" id="sunday-start" value="">
                </div>
                <div class="form-group">
                    <label for="sunday-end">Fim:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top" title="Fim de expediente."></i></small>
                    </label>
                    <input name="sunday-end" type="text" class="form-control w-50" id="sunday-end" value="">
                </div>
            </fieldset>
        </div>
    </div>
    <br>
    <br>
    <input type="hidden" name="token" value="" />
    <button type="submit" class="btn btn-primary btn-user">Salvar</button>
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
        const faviconFormat = '<?php echo getPathImageFormat($image_config_dir, "favicon") ?>';
        verificarImagem(`${dir}favicon.${faviconFormat}`, '#faviconSelect');

        $('#inputFavicon').change(function () {
            exibirIMG(this, '#faviconSelect');
        });

        // BACKGROUND
        const backgroundFormat = '<?php echo getPathImageFormat($image_config_dir, "background") ?>';
        verificarImagem(`${dir}background.${backgroundFormat}`, '#backgroundSelect');

        $('#inputBackground').change(function () {
            exibirIMG(this, '#backgroundSelect');
        });

        // LOGO
        const logoFormat = '<?php echo getPathImageFormat($image_config_dir, "logo") ?>';
        verificarImagem(`${dir}logo.${logoFormat}`, '#logoSelect');

        $('#inputLogo').change(function () {
            exibirIMG(this, '#logoSelect');
        });

        // LOGINBAKGROUND
        const loginFormat = '<?php echo getPathImageFormat($image_config_dir, "login") ?>';
        verificarImagem(`${dir}login.${loginFormat}`, '#loginSelect');

        $('#inputLogin').change(function () {
            exibirIMG(this, '#loginSelect');
        });

        // Verificar o estado do checkbox ao carregar a página
        if ($('#whatsapp-status').is(':checked')) {
            $('#whatsapp-status-container').show();
        } else {
            $('#whatsapp-status-container').hide();
        }
        if ($('#instagram-status').is(':checked')) {
            $('#instagram-status-container').show();
        } else {
            $('#instagram-status-container').hide();
        }
        if ($('#facebook-status').is(':checked')) {
            $('#facebook-status-container').show();
        } else {
            $('#facebook-status-container').hide();
        }

        if ($('#pay-pix').is(':checked')) {
            $('#pix-info-container').show();
        } else {
            $('#pix-info-container').hide();
        }

        // HORARY
        if ($('#monday-status').is(':checked')) {
            $('#monday-status-container').show();
        } else {
            $('#monday-status-container').hide();
        }

        if ($('#tuesday-status').is(':checked')) {
            $('#tuesday-status-container').show();
        } else {
            $('#tuesday-status-container').hide();
        }

        if ($('#wednesday-status').is(':checked')) {
            $('#wednesday-status-container').show();
        } else {
            $('#wednesday-status-container').hide();
        }

        if ($('#thursday-status').is(':checked')) {
            $('#thursday-status-container').show();
        } else {
            $('#thursday-status-container').hide();
        }

        if ($('#friday-status').is(':checked')) {
            $('#friday-status-container').show();
        } else {
            $('#friday-status-container').hide();
        }

        if ($('#saturday-status').is(':checked')) {
            $('#saturday-status-container').show();
        } else {
            $('#saturday-status-container').hide();
        }

        if ($('#sunday-status').is(':checked')) {
            $('#sunday-status-container').show();
        } else {
            $('#sunday-status-container').hide();
        }


        // Adicionar um ouvinte de evento para o checkbox
        $('#whatsapp-status').change(function () {
            // Toggle (mostrar ou esconder) a div com base no estado do checkbox
            $('#whatsapp-status-container').toggle();
        });
        // Adicionar um ouvinte de evento para o checkbox
        $('#instagram-status').change(function () {
            // Toggle (mostrar ou esconder) a div com base no estado do checkbox
            $('#instagram-status-container').toggle();
        });
        // Adicionar um ouvinte de evento para o checkbox
        $('#facebook-status').change(function () {
            // Toggle (mostrar ou esconder) a div com base no estado do checkbox
            $('#facebook-status-container').toggle();
        });
        // Adicionar um ouvinte de evento para o checkbox
        $('#pay-pix').change(function () {
            // Toggle (mostrar ou esconder) a div com base no estado do checkbox
            $('#pix-info-container').toggle();
        });

        // HORARY
        // Adicionar um ouvinte de evento para o checkbox
        $('#monday-status').change(function () {
            // Toggle (mostrar ou esconder) a div com base no estado do checkbox
            $('#monday-status-container').toggle();
        });
        // Adicionar um ouvinte de evento para o checkbox
        $('#tuesday-status').change(function () {
            // Toggle (mostrar ou esconder) a div com base no estado do checkbox
            $('#tuesday-status-container').toggle();
        });
        // Adicionar um ouvinte de evento para o checkbox
        $('#wednesday-status').change(function () {
            // Toggle (mostrar ou esconder) a div com base no estado do checkbox
            $('#wednesday-status-container').toggle();
        });
        // Adicionar um ouvinte de evento para o checkbox
        $('#thursday-status').change(function () {
            // Toggle (mostrar ou esconder) a div com base no estado do checkbox
            $('#thursday-status-container').toggle();
        });
        // Adicionar um ouvinte de evento para o checkbox
        $('#friday-status').change(function () {
            // Toggle (mostrar ou esconder) a div com base no estado do checkbox
            $('#friday-status-container').toggle();
        });
        // Adicionar um ouvinte de evento para o checkbox
        $('#saturday-status').change(function () {
            // Toggle (mostrar ou esconder) a div com base no estado do checkbox
            $('#saturday-status-container').toggle();
        });
        // Adicionar um ouvinte de evento para o checkbox
        $('#sunday-status').change(function () {
            // Toggle (mostrar ou esconder) a div com base no estado do checkbox
            $('#sunday-status-container').toggle();
        });

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