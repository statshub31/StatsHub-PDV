<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));
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


<form action="/panel/useradd" method="post">

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
                    <input type="file" name="product" class="custom-file-input" id="inputProduct" accept="image/*">
                    <label class="custom-file-label" for="inputProduct">Escolha sua imagem.</label>
                </div>
            </div>
        </div>
        <div id="product-info">
            <section id="product-left">
                <div class="form-group">
                    <label for="cod">COD. Pers.</label>
                    <font color="red">*</font>
                    <input type="text" name="cod" class="form-control" id="cod" value="">
                </div>
                <div class="form-group">
                    <label for="category">Categoria:</label>
                    <font color="red">*</font>
                    <select class="custom-select" name="category" id="category">
                        <option selected>Choose...</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
            </section>

            <section id="product-left">
                <div class="form-group">
                    <label for="measure">Medida:</label>
                    <font color="red">*</font>
                    <select class="custom-select" name="measure" id="measure">
                        <option selected>Choose...</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">Descrição:</label>
                    <font color="red">*</font>
                    <textarea class="form-control" id="description" aria-label="With textarea"></textarea>
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
                    <label for="stock-min">Mínimo:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top"
                                title="Mínimo para manter em estoque. Não poderá ser menor que o atual."></i></small>
                    </label>
                    <input name="stock-min" type="text" class="form-control w-50" id="stock-min" value="">
                </div>
                <div class="form-group">
                    <label for="stock-actual">Atual:
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
                <label for="size-p">Tamanho:
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
                <label for="price-p">Valor:
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
                    <label for="size-m">Tamanho:
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
                    <label for="price-m">Valor:
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
                    <th>Adicional</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Desconto</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Marcar</th>
                    <th>Adicional</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Desconto</th>
                    <th>Total</th>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td>
                        <input type="checkbox">
                    </td>
                    <td>
                        <section class="product_photo">
                            <img src="/../../../layout/images/additional/1.jpeg"></img>
                        </section>
                        <label>Tomate</label>
                    </td>
                    <td>
                        Sabor Calabresa
                    </td>
                    <td>R$ 20.00</td>
                    <td>R$ 5.00</td>
                    <td>R$ 15.00</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="complement" class="content">
        <table class="table table-bordered" id="dataTableComplement" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Marcar</th>
                    <th>Complemento</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Marcar</th>
                    <th>Complemento</th>
                    <th>Descrição</th>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td>
                        <input type="checkbox">
                    </td>
                    <td>
                        <section class="product_photo">
                            <img src="/../../../layout/images/additional/1.jpeg"></img>
                        </section>
                        <label>Tomate</label>
                    </td>
                    <td>
                        Sabor Calabresa
                    </td>
                </tr>
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
                    <input type="checkbox" name="price-size-status" id="quest-status" class="vc-switch-input">
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

                    <div id="collapseOne1" class="collapse show" aria-labelledby="headingOne1" data-parent="#accordion">
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
                            <input name="response1[]" type="text" class="form-control w-100" value="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>












    <br>
    <input type="hidden" name="user_id" value="" />
    <input type="hidden" name="token" value="" />
    <button type="submit" class="btn btn-primary">Atualizar</button>
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
        let currentIndex = 1; // índice do campo atual

        $('body').on('input', 'input[name^="response"]', function () {
            let currentValue = $(this).val().trim();
            let $nextInput = $(this).closest('.card-body').next().find('input[name^="response"]');

            // Verifica se o campo está preenchido
            if (currentValue !== '' && $nextInput.length === 0) {
                // Cria um novo campo de entrada
                let newInput = $('<input>').attr({
                    type: 'text',
                    name: 'response' + (++currentIndex) + '[]',
                    class: 'form-control w-100',
                    value: ''
                });

                // Adiciona o novo campo de entrada após o campo atual
                $(this).closest('.card-body').after($('<div class="card-body"></div>').append(newInput));
            }
        });

        var contador = 2;

        // Função para adicionar um novo campo
        $('#addCampo').click(function () {
            // Criar uma cópia do modelo de campo
            var novoCampo = $('.card').first().clone();

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

            // Atribuir IDs únicos aos novos elementos clonados
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

            // Incrementar o contador para o próximo campo
            contador++;
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