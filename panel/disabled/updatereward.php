<?php
include_once(realpath(__DIR__ . "/front/php/header.php"));
getAdminAccess();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {

    if (getToken('confirmUpdateReward' . $_POST['reward_id'])) {
        if (empty($_POST) === false) {
            $required_fields = array('name', 'level', 'imagem');
            if (validateRequiredFields($_POST, $required_fields) === false) {
                $errors[] = "Obrigatório o preenchimento de todos os campos.";
            }

            if (is_numeric($_POST["level"]) === false) {
                $errors[] = "É obrigatório que o nivel seja um número.";
            }

            if (isRewardExist($_POST['reward_id']) === false) {
                $errors[] = 'Ocorreu um erro, tente novamente.';
            }
            if (is_numeric($_POST["price"]) === false) {
                $errors[] = "É obrigatório que o nivel seja um valor.";
            }

            if (isset($_FILES['imagem']) && $_FILES['imagem']['size'] > 0) {
                // Verifica se o arquivo foi enviado sem erros
                if ($_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
                    $errors[] = 'Erro no upload do arquivo.';
                }

                $imageInfo = getimagesize($_FILES['imagem']['tmp_name']);
                if (!$imageInfo || !in_array($imageInfo['mime'], array('image/png', 'image/jpeg', 'image/gif'))) {
                    $errors[] = 'O arquivo não é uma imagem válida.';
                } elseif ($imageInfo[0] > 1500 || $imageInfo[1] > 1500) {
                    $errors[] = 'A imagem excede o tamanho máximo permitido (1500x1500 pixels).';
                }
            }
        }


        if (empty($errors)) {
            $image = true;
            $uid = getRewardPhoto($_POST['reward_id']);
            $targetPath = __DIR__ . '/../front/images/rewards';

            if (isset($_FILES['imagem']) && $_FILES['imagem']['size'] > 0) {
                removerArquivos($targetPath.'/', $uid);
                $fileInfo = pathinfo($_FILES['imagem']['name']);
                $fileExtension = $fileInfo['extension'];

                // Criar um nome de arquivo único com a extensão original
                $uniqueFileName = $uid . '.' . $fileExtension;

                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $targetPath . '/' . $uniqueFileName)) {
                    // A movimentação do arquivo foi bem-sucedida
                    // $uniqueFileName contém o nome do arquivo com a extensão original
                } else {
                    $errors[] = 'Falha ao mover o arquivo para o diretório de destino.';
                    $image = false;
                }
            }

            if ($image) {
                $import_data_query = array(
                    'name' => sanitizeSpecial($_POST['name']),
                    'level' => (int) ($_POST['level']),
                    'photo' => $uid,
                    'price' => (float) ($_POST['price'])
                );
                doUpdateRewardsRow($_POST['reward_id'], $import_data_query);
                echo alertSuccess("O premio foi adicionado com sucesso.", '/admin/rewards');
            }
        }


        if (empty($errors) === false) {
            header("HTTP/1.1 401 Not Found");
            echo alertError($errors);
        }
    }

    if (getToken('updateReward' . $_POST['reward_id'])) {
        $reward_id = $_POST['reward_id'];
        ?>
        <h1 class="h3 mb-0 text-gray-800">Premios</h1>
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
        <form action="/admin/updatereward" method="post" enctype="multipart/form-data">

            <div class="form-imgs-container">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Imagem<font color="red">*</font></span>
                        <div id="previewImagem" style="width: 100px; height: 100px; overflow: hidden; border: 1px solid #ccc;">
                            <img id="imagemSelecionada" style="width: 100%; height: auto;">
                        </div>
                    </div>
                    <div class="custom-file">
                        <input type="file" name="imagem" class="custom-file-input" id="inputGroupFile01" accept="image/*">
                        <label class="custom-file-label" for="inputGroupFile01">Escolha sua imagem.</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputfname">Nome:</label><font color="red">*</font>
                <input type="text" name="name" class="form-control" id="inputfname" placeholder="Chaveiro"
                    value="<?php echo getRewardName($reward_id) ?>">
            </div>
            <div class="form-group">
                <label for="inputlname">Nível:</label><font color="red">*</font>
                <input type="text" name="level" class="form-control" id="inputlname" placeholder="10"
                    value="<?php echo getRewardLevel($reward_id) ?>">
            </div>
            <div class="form-group">
                <label for="price">Preço:</label><font color="red">*</font>
                <input type="text" name="price" class="form-control" id="price" placeholder="10.00"
                    value="<?php echo getRewardPrice($reward_id) ?>">
            </div>

            <input type="hidden" name="reward_id" value="<?php echo $_POST['reward_id'] ?>" />
            <input type="hidden" name="token" value="<?php echo addToken('confirmUpdateReward' . $_POST['reward_id']) ?>" />
            <a href="/admin/rewards">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
            </a>
            <button type="submit" class="btn btn-primary">Aprovar</button>
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

            $(document).ready(function () {
                <?php
                $dir = '/front/images/rewards/';
                $img = getRewardPhoto($reward_id);
                $format = getPathImageFormat($dir, $img);
                ?>

                var dir = '<?php echo $dir; ?>';
                var format = '<?php echo $format; ?>';
                var img = '<?php echo $img; ?>';

                verificarImagem(dir + img + '.' + format, '#imagemSelecionada');

            });

            $(document).ready(function () {
                $('#inputGroupFile01').change(function () {
                    exibirImagem(this);
                });
            });

            function exibirImagem(input) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#imagemSelecionada').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        </script>
        <?php
    }
}
include_once(realpath(__DIR__ . "/front/php/footer.php"));
?>