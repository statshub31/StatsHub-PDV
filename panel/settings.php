<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));
getGeneralSecurityManagerAccess();

?>



<?php
// <!-- INICIO DA VALIDAÇÃO PHP -->
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
    // STOCK PRODUCTS
    if (getGeneralSecurityToken('tokenSettings')) {

        if (empty($_POST) === false) {
            $required_fields_status = true;
            $required_fields = array('title', 'description', 'login', 'time-min', 'time-max');

            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
                $required_fields_status = false;
            }

            if ($required_fields_status) {

                // IMAGES
                if (isset($_FILES['favicon']) && $_FILES['favicon']['size'] > 0) {
                    // Verifica se o arquivo foi enviado sem erros
                    if ($_FILES['favicon']['error'] !== UPLOAD_ERR_OK) {
                        $errors[] = 'Erro no upload do arquivo.';
                    }

                    // Verifica se é uma imagem válida
                    $imageInfo = getimagesize($_FILES['favicon']['tmp_name']);
                    if (!$imageInfo || !in_array($imageInfo['mime'], array('image/jpeg', 'image/png'))) {
                        $errors[] = 'O arquivo enviado para o icone não é uma imagem válida.';
                    } elseif ($imageInfo[0] > 512 || $imageInfo[1] > 512) {
                        $errors[] = 'A imagem para foto precisa ser menor que 512x512.';
                    }
                }

                if (isset($_FILES['background']) && $_FILES['background']['size'] > 0) {
                    // Verifica se o arquivo foi enviado sem erros
                    if ($_FILES['background']['error'] !== UPLOAD_ERR_OK) {
                        $errors[] = 'Erro no upload do arquivo.';
                    }

                    // Verifica se é uma imagem válida
                    $imageInfo = getimagesize($_FILES['background']['tmp_name']);
                    if (!$imageInfo || !in_array($imageInfo['mime'], array('image/jpeg', 'image/png'))) {
                        $errors[] = 'O arquivo enviado para o fundo não é uma imagem válida.';
                    } elseif ($imageInfo[0] > 1500 || $imageInfo[1] > 1500) {
                        $errors[] = 'A imagem para foto precisa ser menor que 1500x1500.';
                    }
                }

                if (isset($_FILES['logo']) && $_FILES['logo']['size'] > 0) {
                    // Verifica se o arquivo foi enviado sem erros
                    if ($_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
                        $errors[] = 'Erro no upload do arquivo.';
                    }

                    // Verifica se é uma imagem válida
                    $imageInfo = getimagesize($_FILES['logo']['tmp_name']);
                    if (!$imageInfo || !in_array($imageInfo['mime'], array('image/jpeg', 'image/png'))) {
                        $errors[] = 'O arquivo de logo enviado não é uma imagem válida.';
                    } elseif ($imageInfo[0] > 600 || $imageInfo[1] > 600) {
                        $errors[] = 'A imagem para foto precisa ser menor que 600x600.';
                    }
                }

                if (isset($_FILES['login']) && $_FILES['login']['size'] > 0) {
                    // Verifica se o arquivo foi enviado sem erros
                    if ($_FILES['login']['error'] !== UPLOAD_ERR_OK) {
                        $errors[] = 'Erro no upload do arquivo.';
                    }

                    // Verifica se é uma imagem válida
                    $imageInfo = getimagesize($_FILES['login']['tmp_name']);
                    if (!$imageInfo || !in_array($imageInfo['mime'], array('image/jpeg', 'image/png'))) {
                        $errors[] = 'O arquivo de login enviado não é uma imagem válida.';
                    } elseif ($imageInfo[0] > 600 || $imageInfo[1] > 600) {
                        $errors[] = 'A imagem para foto precisa ser menor que 600x600.';
                    }
                }

                // INFO
                if (doGeneralValidationNameFormat($_POST['title']) == false) {
                    $errors[] = "Obrigatório preenchimento do titulo com caracteres alfabético";
                }

                if (doGeneralValidationDescriptionFormat($_POST['description']) == false) {
                    $errors[] = "O campo descrição possui caracteres invalido.";
                }

                if(doGeneralValidationNumberFormat($_POST['cnpj']) == false) {
                    $errors[] = "Caracteres invalido no número do CNPJ, é obrigatório o preenchimento de número e 14 digitos.";
                }

                if(strlen($_POST['cnpj']) > 14) {
                    $errors[] = "É obrigatório o preenchimento de número e 14 digitos.";
                }


                // DELIVERY
                if (isset($_POST['order-withdrawal'])) {
                    if (empty($_POST['restaurant-delivery-api'])) {
                        $errors[] = "Você habilitou a retirada, é obrigatório inserir o endereço";
                    }
                }

                if (!empty($_POST['min-delivery'])) {
                    if (doGeneralValidationPriceFormat($_POST['min-delivery']) == false) {
                        $errors[] = "É obrigatório o preenchimento de um valor valido para o valor minimo.";
                    }
                }

                if (!empty($_POST['min-delivery'])) {
                    if (doGeneralValidationPriceFormat($_POST['delivery-fee']) == false) {
                        $errors[] = "É obrigatório o preenchimento de um valor valido para a taxa de entrega.";
                    }
                }

                if (doGeneralValidationNumberFormat($_POST['time-min']) == false) {
                    $errors[] = "É obrigatório o preenchimento de um valor númerico para a o tempo mínimo de entrega.";
                }

                if (doGeneralValidationNumberFormat($_POST['time-max']) == false) {
                    $errors[] = "É obrigatório o preenchimento de um valor númerico para a o tempo máximo de entrega.";
                }

                if ($_POST['time-min'] < 0) {
                    $errors[] = "O tempo mínimo precisa ser um valor positivo.";
                }

                if ($_POST['time-max'] < 0) {
                    $errors[] = "O tempo máximo precisa ser um valor positivo.";
                }

                if (isset($_POST['pay-pix'])) {
                    if (empty($_POST['pix-key'])) {
                        $errors[] = "Você precisa fornecer uma chave pix";
                    }
                }

                // SOCIAL
                if (isset($_POST['whatsapp-status'])) {
                    if (doGeneralValidationNumberFormat($_POST['whatsapp-number']) == false) {
                        $errors[] = "É obrigatório que o número de whatsapp não tenha caracteres ou letras.";
                    }

                    if (empty($_POST['whatsapp-number'])) {
                        $errors[] = "Você precisa fornecer um número de whatsapp.";
                    }
                }

                if (isset($_POST['instagram-status'])) {
                    if (empty($_POST['profile-instagram'])) {
                        $errors[] = "Você precisa fornecer um perfil de instagram.";
                    }
                }

                if (isset($_POST['facebook-status'])) {
                    if (empty($_POST['profile-facebook'])) {
                        $errors[] = "Você precisa fornecer um perfil de facebook.";
                    }
                }

                // SOCIAL
                if (isset($_POST['monday-status'])) {
                    if (empty($_POST['monday-start']) || empty($_POST['monday-end'])) {
                        $errors[] = "Você precisa fornecer um horário de inicio e fim para segunda.";
                    }
                }
                if (isset($_POST['tuesday-status'])) {
                    if (empty($_POST['tuesday-start']) || empty($_POST['tuesday-end'])) {
                        $errors[] = "Você precisa fornecer um horário de inicio e fim para terça.";
                    }
                }
                if (isset($_POST['wednesday-status'])) {
                    if (empty($_POST['wednesday-start']) || empty($_POST['wednesday-end'])) {
                        $errors[] = "Você precisa fornecer um horário de inicio e fim para quarta.";
                    }
                }
                if (isset($_POST['thursday-status'])) {
                    if (empty($_POST['thursday-start']) || empty($_POST['thursday-end'])) {
                        $errors[] = "Você precisa fornecer um horário de inicio e fim para quinta.";
                    }
                }
                if (isset($_POST['friday-status'])) {
                    if (empty($_POST['friday-start']) || empty($_POST['friday-end'])) {
                        $errors[] = "Você precisa fornecer um horário de inicio e fim para sexta.";
                    }
                }
                if (isset($_POST['saturday-status'])) {
                    if (empty($_POST['saturday-start']) || empty($_POST['saturday-end'])) {
                        $errors[] = "Você precisa fornecer um horário de inicio e fim para sabado.";
                    }
                }
                if (isset($_POST['sunday-status'])) {
                    if (empty($_POST['sunday-start']) || empty($_POST['sunday-end'])) {
                        $errors[] = "Você precisa fornecer um horário de inicio e fim para domingo.";
                    }
                }


                if (isGeneralSecurityManagerAccess() === false) {
                    $errors[] = "É obrigatório ter um cargo igual ou superior ao de gestor, para executar está ação.";
                }
            }

        }


        if (empty($errors)) {

            $image = True;

            if (isset($_FILES['favicon']) && $_FILES['favicon']['size'] > 0) {
                $icon_oldName = getDatabaseSettingsImageIconName(1);

                doGeneralRemoveArchive($image_config_dir, $icon_oldName);
                $icon_newName = md5(date("favicon_Y_m_d_H:i:s"));
                $fileInfo = pathinfo($_FILES['favicon']['name']);
                $fileExtension = $fileInfo['extension'];

                if (move_uploaded_file($_FILES['favicon']['tmp_name'], __DIR__ . '/..' . $image_config_dir . $icon_newName . '.' . $fileExtension) === false) {
                    $errors[] = 'Falha ao mover o arquivo para o diretório de destino.';
                    $image = false;
                }
            }

            if (isset($_FILES['background']) && $_FILES['background']['size'] > 0) {
                $background_oldName = getDatabaseSettingsImageBackgroundName(1);

                doGeneralRemoveArchive($image_config_dir, $background_oldName);
                $background_newName = md5(date("background_Y_m_d_H:i:s"));
                $fileInfo = pathinfo($_FILES['background']['name']);
                $fileExtension = $fileInfo['extension'];

                if (move_uploaded_file($_FILES['background']['tmp_name'], __DIR__ . '/..' . $image_config_dir . $background_newName . '.' . $fileExtension) === false) {
                    $errors[] = 'Falha ao mover o arquivo para o diretório de destino.';
                    $image = false;
                }
            }

            if (isset($_FILES['logo']) && $_FILES['logo']['size'] > 0) {
                $logo_oldName = getDatabaseSettingsImageLogoName(1);

                doGeneralRemoveArchive($image_config_dir, $logo_oldName);
                $logo_newName = md5(date("logo_Y_m_d_H:i:s"));
                $fileInfo = pathinfo($_FILES['logo']['name']);
                $fileExtension = $fileInfo['extension'];

                if (move_uploaded_file($_FILES['logo']['tmp_name'], __DIR__ . '/..' . $image_config_dir . $logo_newName . '.' . $fileExtension) === false) {
                    $errors[] = 'Falha ao mover o arquivo para o diretório de destino.';
                    $image = false;
                }
            }

            if (isset($_FILES['login']) && $_FILES['login']['size'] > 0) {
                $login_oldName = getDatabaseSettingsImageLoginName(1);

                doGeneralRemoveArchive($image_config_dir, $login_oldName);
                $login_newName = md5(date("login_Y_m_d_H:i:s"));
                $fileInfo = pathinfo($_FILES['login']['name']);
                $fileExtension = $fileInfo['extension'];

                if (move_uploaded_file($_FILES['login']['tmp_name'], __DIR__ . '/..' . $image_config_dir . $login_newName . '.' . $fileExtension) === false) {
                    $errors[] = 'Falha ao mover o arquivo para o diretório de destino.';
                    $image = false;
                }
            }


            if ($image) {

                $images_fields_insert = array(
                    'icon_name' => (isset($icon_newName)) ? $icon_newName : NULL,
                    'background_name' => (isset($background_newName)) ? $background_newName : NULL,
                    'logo_name' => (isset($logo_newName)) ? $logo_newName : NULL,
                    'login_name' => (isset($login_newName)) ? $login_newName : NULL
                );

                foreach ($images_fields_insert as $image => $value) {
                    if (empty($value))
                        unset($images_fields_insert[$image]);
                }

                if (count($images_fields_insert) > 0) {
                    if (getDatabaseSettingsImageRowCount() > 0) {
                        doDatabaseSettingsImageUpdate(1, $images_fields_insert);
                    } else {
                        doDatabaseSettingsImageInsert($images_fields_insert);
                    }
                }

                // INFO

                $info_fields_insert = array(
                    'title' => $_POST['title'],
                    'description' => (!empty($_POST['description'])) ? $_POST['description'] : NULL,
                    'cnpj' => (!empty($_POST['cnpj'])) ? $_POST['cnpj'] : NULL,
                    'main_color' => (!empty($_POST['main_color'])) ? $_POST['main_color'] : NULL,
                );

                if (getDatabaseSettingsInfoRowCount() > 0) {
                    doDatabaseSettingsInfoUpdate(1, $info_fields_insert);
                } else {
                    doDatabaseSettingsInfoInsert($info_fields_insert);
                }
                // DELIVERY
                $delivery_fields_insert = array(
                    'order_withdrawal' => (isset($_POST['order-withdrawal'])) ? 1 : 0,
                    'address_api' => (!empty($_POST['restaurant-delivery-api'])) ? $_POST['restaurant-delivery-api'] : NULL,
                    'order_min' => (!empty($_POST['min-delivery'])) ? $_POST['min-delivery'] : 0.0,
                    'fee' => (!empty($_POST['delivery-fee'])) ? $_POST['delivery-fee'] : 0.0,
                    'time_min' => $_POST['time-min'],
                    'time_max' => $_POST['time-max'],
                );

                if (getDatabaseSettingsDeliveryRowCount() > 0) {
                    doDatabaseSettingsDeliveryUpdate(1, $delivery_fields_insert);
                } else {
                    doDatabaseSettingsDeliveryInsert($delivery_fields_insert);
                }

                // pay
                $money_field = array(
                    'disabled' => (!isset($_POST['pay-money'])) ? 1 : 0
                );
                $credit_field = array(
                    'disabled' => (!isset($_POST['pay-credit'])) ? 1 : 0
                );
                $debit_field = array(
                    'disabled' => (!isset($_POST['pay-debit'])) ? 1 : 0
                );

                $pix_field = array(
                    'disabled' => (!isset($_POST['pay-pix'])) ? 1 : 0,
                    'pay_key' => (isset($_POST['pay-pix'])) ? $_POST['pix-key'] : NULL,
                );
                $vr_field = array(
                    'disabled' => (!isset($_POST['pay-vr'])) ? 1 : 0
                );

                $va_field = array(
                    'disabled' => (!isset($_POST['pay-va'])) ? 1 : 0
                );


                doDatabaseSettingsPayUpdate(getDatabaseSettingsPayMoney(), $money_field);
                doDatabaseSettingsPayUpdate(getDatabaseSettingsPayCredit(), $credit_field);
                doDatabaseSettingsPayUpdate(getDatabaseSettingsPayDebit(), $debit_field);
                doDatabaseSettingsPayUpdate(getDatabaseSettingsPayPix(), $pix_field);
                doDatabaseSettingsPayUpdate(getDatabaseSettingsPayVR(), $vr_field);
                doDatabaseSettingsPayUpdate(getDatabaseSettingsPayVA(), $va_field);

                // SOCIAL
                $social_fields_insert = array(
                    'whatsapp_status' => (isset($_POST['whatsapp-status'])) ? 1 : 0,
                    'whatsapp_contact' => (isset($_POST['whatsapp-status'])) ? $_POST['whatsapp-number'] : NULL,
                    'facebook_status' => (isset($_POST['facebook-status'])) ? 1 : 0,
                    'facebook_contact' => (isset($_POST['facebook-status'])) ? $_POST['profile-facebook'] : NULL,
                    'instagram_status' => (isset($_POST['instagram-status'])) ? 1 : 0,
                    'instagram_contact' => (isset($_POST['instagram-status'])) ? $_POST['profile-instagram'] : NULL
                );

                if (getDatabaseSettingsSocialRowCount() > 0) {
                    doDatabaseSettingsSocialUpdate(1, $social_fields_insert, true);
                } else {
                    doDatabaseSettingsSocialInsert($social_fields_insert);
                }

                // HORARY
                $horary_fields_insert = array(
                    'monday_status' => (isset($_POST['monday-status'])) ? 1 : 0,
                    'monday_start' => (isset($_POST['monday-status'])) ? $_POST['monday-start'] : NULL,
                    'monday_end' => (isset($_POST['monday-status'])) ? $_POST['monday-end'] : NULL,

                    'tuesday_status' => (isset($_POST['tuesday-status'])) ? 1 : 0,
                    'tuesday_start' => (isset($_POST['tuesday-status'])) ? $_POST['tuesday-start'] : NULL,
                    'tuesday_end' => (isset($_POST['tuesday-status'])) ? $_POST['tuesday-end'] : NULL,

                    'wednesday_status' => (isset($_POST['wednesday-status'])) ? 1 : 0,
                    'wednesday_start' => (isset($_POST['wednesday-status'])) ? $_POST['wednesday-start'] : NULL,
                    'wednesday_end' => (isset($_POST['wednesday-status'])) ? $_POST['wednesday-end'] : NULL,

                    'thursday_status' => (isset($_POST['thursday-status'])) ? 1 : 0,
                    'thursday_start' => (isset($_POST['thursday-status'])) ? $_POST['thursday-start'] : NULL,
                    'thursday_end' => (isset($_POST['thursday-status'])) ? $_POST['thursday-end'] : NULL,

                    'friday_status' => (isset($_POST['friday-status'])) ? 1 : 0,
                    'friday_start' => (isset($_POST['friday-status'])) ? $_POST['friday-start'] : NULL,
                    'friday_end' => (isset($_POST['friday-status'])) ? $_POST['friday-end'] : NULL,

                    'saturday_status' => (isset($_POST['saturday-status'])) ? 1 : 0,
                    'saturday_start' => (isset($_POST['saturday-status'])) ? $_POST['saturday-start'] : NULL,
                    'saturday_end' => (isset($_POST['saturday-status'])) ? $_POST['saturday-end'] : NULL,

                    'sunday_status' => (isset($_POST['sunday-status'])) ? 1 : 0,
                    'sunday_start' => (isset($_POST['sunday-status'])) ? $_POST['sunday-start'] : NULL,
                    'sunday_end' => (isset($_POST['sunday-status'])) ? $_POST['sunday-end'] : NULL,

                );

                if (getDatabaseSettingsHoraryRowCount() > 0) {
                    doDatabaseSettingsHoraryUpdate(1, $horary_fields_insert, true);
                } else {
                    doDatabaseSettingsHoraryInsert($horary_fields_insert);
                }
            }
            doAlertSuccess("O estoque foi ajustado com sucesso.");

        }
    }



    if (empty($errors) === false) {
        header("HTTP/1.1 401 Not Found");
        echo doAlertError($errors);
    }
}


// <!-- FINAL DA VALIDAÇÃO PHP -->
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
            <label for="title">Título:
                <font color="red">*</font>
                <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                        title="Nome da sua loja."></i></small>
            </label>
            <input name="title" type="text" class="form-control" id="title"
                value="<?php echo getDatabaseSettingsInfoTitle(1) ?>" required>
        </div>


        <div class="form-group">
            <label for="description">Descrição:
                <font color="red">*</font>
                <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                        title="Tipo de produtos vendido, descrição do que fazem..."></i></small>
            </label>
            <input name="description" type="text" class="form-control" id="description"
                value="<?php echo getDatabaseSettingsInfoDescription(1) ?>" required>
        </div>


        <div class="form-group">
            <label for="main_color">Cor Primaria
                <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                        title="Cor principal da plataforma"></i></small>
            </label>
            <input name="main_color" type="color" class="form-control w-20" id="main_color" value="<?php echo getDatabaseSettingsInfoMainColor(1) ?>">
        </div>

        <div class="form-group">
            <label for="cnpj">CNPJ
                <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                        title="CNPJ da empresa"></i></small>
            </label>
            <input name="cnpj" type="text" class="form-control" id="cnpj"
                value="<?php echo getDatabaseSettingsInfoCNPJ(1) ?>">
        </div>

    </div>

    <div id="delivery" class="content">

        <div class="form-group">
            <label for="order-withdrawal">Aceita retirada de pedido?</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, será mostrado para o cliente a opção para retirar pedido. Isentando da taxa de entrega."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input <?php echo doCheck(getDatabaseSettingsDeliveryOrderWithdrawal(1), 1) ?> type="checkbox"
                        name="order-withdrawal" id="order-withdrawal" class="vc-switch-input">
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
                value="<?php echo getDatabaseSettingsDeliveryAddressAPI(1) ?>">
        </div>
        <div class="form-group">
            <label for="min-delivery">Pedido Mínimo:
                <font color="red">*</font>
                <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                        title="Insira o valor mínimo para compra, caso não tenha, deixe em 0,00."></i></small>
            </label>
            <input name="min-delivery" type="text" class="form-control priceFormat" id="min-delivery"
                value="<?php echo getDatabaseSettingsDeliveryOrderMin(1) ?>" required>
        </div>

        <div class="form-group">
            <label for="delivery-fee">Taxa de Entrega
                <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                        title="Insira o valor para a taxa de entrega."></i></small>
            </label>
            <input name="delivery-fee" type="text" class="form-control priceFormat" id="delivery-fee"
                value="<?php echo getDatabaseSettingsDeliveryFee(1) ?>">
        </div>


        <div class="form-group">
            <label for="time-min">Tempo Mínimo
                <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                        title="Tempo mínimo que o cliente deverá esperar."></i></small>
            </label>
            <input name="time-min" type="text" class="form-control" id="time-min"
                value="<?php echo getDatabaseSettingsDeliveryTimeMin(1) ?>" required>
        </div>

        <div class="form-group">
            <label for="time-max">Tempo Máximo
                <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                        title="Tempo mínimo que o cliente deverá esperar."></i></small>
            </label>
            <input name="time-max" type="text" class="form-control" id="time-max"
                value="<?php echo getDatabaseSettingsDeliveryTimeMax(1) ?>" required>
        </div>
    </div>

    <div id="social" class="content">
        <div class="form-group">
            <label for="whatsapp-status">Deseja habilitar o WhatsApp?</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, será mostrado o icone do whatsapp no rodapé."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input <?php echo doCheck(getDatabaseSettingsSocialWhatsappStatus(1), 1) ?> type="checkbox"
                        name="whatsapp-status" id="whatsapp-status" class="vc-switch-input">
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
            <input name="whatsapp-number" type="text" class="form-control" id="whatsapp-number"
                value="<?php echo getDatabaseSettingsSocialWhatsappInfo(1) ?>">
        </div>

        <div class="form-group">
            <label for="instagram-status">Deseja habilitar o Instagram?</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, será mostrado o icone do instagram no rodapé."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input <?php echo doCheck(getDatabaseSettingsSocialInstagramStatus(1), 1) ?> type="checkbox"
                        name="instagram-status" id="instagram-status" class="vc-switch-input">
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
            <input name="profile-instagram" type="text" class="form-control" id="profile-instagram"
                value="<?php echo getDatabaseSettingsSocialInstagramInfo(1) ?>">
        </div>

        <div class="form-group">
            <label for="facebook-status">Deseja habilitar o Facebook?</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, será mostrado o icone do facebook no rodapé."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input <?php echo doCheck(getDatabaseSettingsSocialFacebookStatus(1), 1) ?> type="checkbox"
                        name="facebook-status" id="facebook-status" class="vc-switch-input">
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
            <input name="profile-facebook" type="text" class="form-control" id="profile-facebook"
                value="<?php echo getDatabaseSettingsSocialFacebookInfo(1) ?>">
        </div>
    </div>

    <div id="pay" class="content">
        <div class="form-group">
            <label for="pay-money">Dinheiro</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, será mostrado para o cliente a opção de pagamento em dinheiro."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input <?php echo doCheck(isDatabaseSettingsPayMoneyEnabled(), 1) ?> type="checkbox"
                        name="pay-money" id="pay-money" class="vc-switch-input">
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
                    <input <?php echo doCheck(isDatabaseSettingsPayCreditEnabled(), 1) ?> type="checkbox"
                        name="pay-credit" id="pay-credit" class="vc-switch-input">
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
                    <input <?php echo doCheck(isDatabaseSettingsPayDebitEnabled(), 1) ?> type="checkbox"
                        name="pay-debit" id="pay-debit" class="vc-switch-input">
                    <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                    <span class="vc-handle"></span>
                </label>
            </div>
        </div>


        <div class="form-group">
            <label for="pay-vr">VR</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, será mostrado para o cliente a opção de pagamento em VR."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input <?php echo doCheck(isDatabaseSettingsPayVREnabled(), 1) ?> type="checkbox"
                        name="pay-vr" id="pay-vr" class="vc-switch-input">
                    <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                    <span class="vc-handle"></span>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label for="pay-va">VA</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, será mostrado para o cliente a opção de pagamento em VA."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input <?php echo doCheck(isDatabaseSettingsPayVAEnabled(), 1) ?> type="checkbox"
                        name="pay-va" id="pay-vr" class="vc-switch-input">
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
                    <input <?php echo doCheck(isDatabaseSettingsPayPixEnabled(), 1) ?> type="checkbox" name="pay-pix"
                        id="pay-pix" class="vc-switch-input">
                    <span data-on="Sim" data-off="Não" class="vc-switch-label"></span>
                    <span class="vc-handle"></span>
                </label>
            </div>
        </div>

        <div class="form-group" id="pix-info-container">
            <label for="pix-key">Chave Pix:
                <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                        title="Chave Pix."></i></small>
            </label>
            <input name="pix-key" type="text" class="form-control" id="pix-key"
                value="<?php echo getDatabaseSettingsPayKey(getDatabaseSettingsPayPix()) ?>">
        </div>
    </div>


    <div id="horarys" class="content">
        <div class="form-group">
            <label for="monday-status">Estabelecimento é aberto de Segunda-Feira?</label>
            <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                    title="Caso habilite está função, o cliente poderá fazer pedido nas terça-feira."></i></small>
            <div class="vc-toggle-container">
                <label class="vc-switch">
                    <input <?php echo doCheck(getDatabaseSettingsHoraryDayEnabled(1, 1), 1) ?> type="checkbox"
                        name="monday-status" id="monday-status" class="vc-switch-input">
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
                    <input <?php echo doCheck(getDatabaseSettingsHoraryDayEnabled(1, 2), 1) ?> type="checkbox"
                        name="tuesday-status" id="tuesday-status" class="vc-switch-input">
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
                    <input <?php echo doCheck(getDatabaseSettingsHoraryDayEnabled(1, 3), 1) ?> type="checkbox"
                        name="wednesday-status" id="wednesday-status" class="vc-switch-input">
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
                    <input <?php echo doCheck(getDatabaseSettingsHoraryDayEnabled(1, 4), 1) ?> type="checkbox"
                        name="thursday-status" id="thursday-status" class="vc-switch-input">
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
                    <input <?php echo doCheck(getDatabaseSettingsHoraryDayEnabled(1, 5), 1) ?> type="checkbox"
                        name="friday-status" id="friday-status" class="vc-switch-input">
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
                    <input <?php echo doCheck(getDatabaseSettingsHoraryDayEnabled(1, 6), 1) ?> type="checkbox"
                        name="saturday-status" id="saturday-status" class="vc-switch-input">
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
                    <input <?php echo doCheck(getDatabaseSettingsHoraryDayEnabled(1, 7), 1) ?> type="checkbox"
                        name="sunday-status" id="sunday-status" class="vc-switch-input">
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
                    <input name="monday-start" type="time" class="form-control w-100" id="monday-start"
                        value="<?php echo getDatabaseSettingsHoraryDayStart(1, 1) ?>">
                </div>
                <div class="form-group">
                    <label for="monday-end">Fim:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top" title="Fim de expediente."></i></small>
                    </label>
                    <input name="monday-end" type="time" class="form-control w-100" id="monday-end"
                        value="<?php echo getDatabaseSettingsHoraryDayEnd(1, 1) ?>">
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
                    <input name="tuesday-start" type="time" class="form-control w-100" id="tuesday-start"
                        value="<?php echo getDatabaseSettingsHoraryDayStart(1, 2) ?>">
                </div>
                <div class="form-group">
                    <label for="tuesday-end">Fim:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top" title="Fim de expediente."></i></small>
                    </label>
                    <input name="tuesday-end" type="time" class="form-control w-100" id="tuesday-end"
                        value="<?php echo getDatabaseSettingsHoraryDayEnd(1, 2) ?>">
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
                    <input name="wednesday-start" type="time" class="form-control w-100" id="wednesday-start"
                        value="<?php echo getDatabaseSettingsHoraryDayStart(1, 3) ?>">
                </div>
                <div class="form-group">
                    <label for="wednesday-end">Fim:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top" title="Fim de expediente."></i></small>
                    </label>
                    <input name="wednesday-end" type="time" class="form-control w-100" id="wednesday-end"
                        value="<?php echo getDatabaseSettingsHoraryDayEnd(1, 3) ?>">
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
                    <input name="thursday-start" type="time" class="form-control w-100" id="thursday-start"
                        value="<?php echo getDatabaseSettingsHoraryDayStart(1, 4) ?>">
                </div>
                <div class="form-group">
                    <label for="thursday-end">Fim:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top" title="Fim de expediente."></i></small>
                    </label>
                    <input name="thursday-end" type="time" class="form-control w-100" id="thursday-end"
                        value="<?php echo getDatabaseSettingsHoraryDayEnd(1, 4) ?>">
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
                    <input name="friday-start" type="time" class="form-control w-100" id="friday-start"
                        value="<?php echo getDatabaseSettingsHoraryDayStart(1, 5) ?>">
                </div>
                <div class="form-group">
                    <label for="friday-end">Fim:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top" title="Fim de expediente."></i></small>
                    </label>
                    <input name="friday-end" type="time" class="form-control w-100" id="friday-end"
                        value="<?php echo getDatabaseSettingsHoraryDayEnd(1, 5) ?>">
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
                    <input name="saturday-start" type="time" class="form-control w-100" id="saturday-start"
                        value="<?php echo getDatabaseSettingsHoraryDayStart(1, 6) ?>">
                </div>
                <div class="form-group">
                    <label for="saturday-end">Fim:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top" title="Fim de expediente."></i></small>
                    </label>
                    <input name="saturday-end" type="time" class="form-control w-100" id="saturday-end"
                        value="<?php echo getDatabaseSettingsHoraryDayEnd(1, 6) ?>">
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
                    <input name="sunday-start" type="time" class="form-control w-100" id="sunday-start"
                        value="<?php echo getDatabaseSettingsHoraryDayStart(1, 7) ?>">
                </div>
                <div class="form-group">
                    <label for="sunday-end">Fim:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top" title="Fim de expediente."></i></small>
                    </label>
                    <input name="sunday-end" type="time" class="form-control w-100" id="sunday-end"
                        value="<?php echo getDatabaseSettingsHoraryDayEnd(1, 7) ?>">
                </div>
            </fieldset>
        </div>
    </div>
    <br>
    <br>
    <input name="token" type="text" value="<?php echo addGeneralSecurityToken('tokenSettings') ?>" hidden>
    <button type="submit" class="btn btn-primary btn-user">Salvar</button>
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/0.9.0/jquery.mask.min.js"
    integrity="sha512-oJCa6FS2+zO3EitUSj+xeiEN9UTr+AjqlBZO58OPadb2RfqwxHpjTU8ckIC8F4nKvom7iru2s8Jwdo+Z8zm0Vg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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