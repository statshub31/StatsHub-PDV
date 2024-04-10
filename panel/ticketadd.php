<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));
?>

<form action="/panel/useradd" method="post">

    <div id="product-add-info" class="content">
        <div id="product-info">
            <section id="product-left">
                <div class="form-group">
                    <label for="cod">COD. Pers.
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top"
                                title="Caso estiver em branco, será criado um código aleatorio."></i></small>
                    </label>
                    <font color="red">*</font>
                    <input type="text" name="cod" class="form-control" id="cod" value="">
                </div>

                <div class="form-group">
                    <label for="amount">Quantidade:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top" title="Quantidade de cupons. Caso fique em branco, será ilimitado."></i></small>
                    </label>
                    <input type="text" name="amount" class="form-control" id="amount" value="">
                </div>
            </section>

            <section id="product-left">

                <div class="form-group">
                    <label for="discount">Valor de Desconto:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top"
                                title="Caso queira o desconto em porcentagem, adicione o %."></i></small>
                    </label>
                    <font color="red">*</font>
                    <input type="text" name="discount" class="form-control" id="discount" value="">
                </div>

                <div class="form-group">
                    <label for="expiration">Data de Expiração:
                        <small><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip"
                                data-placement="top"
                                title="Após a data mencionada, não poderá mais ser utilizado o cupom. Caso fique em branco, não irá expirar."></i></small>
                    </label>
                    <input type="date" name="expiration" class="form-control" id="expiration" value="">
                </div>
            </section>
        </div>
    </div>

    <br>
    <input type="hidden" name="user_id" value="" />
    <input type="hidden" name="token" value="" />
    <button type="submit" class="btn btn-primary">Atualizar</button>
</form>

<?php
include_once (realpath(__DIR__ . "/layout/php/footer.php"));
?>