<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));
?>

<form action="/panel/useradd" method="post">

    <div id="product-add-info" class="content">
        <div id="product-info">
            <section id="product-left">
                <div class="form-group">
                    <label for="cod">COD. Pers.</label>
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
                    <label for="description">Descrição:</label>
                    <font color="red">*</font>
                    <textarea class="form-control" id="description" aria-label="With textarea"></textarea>
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