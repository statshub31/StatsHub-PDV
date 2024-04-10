<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));
?>

<form action="/panel/userupdate" method="post">

    <div class="form-group">
        <label for="name">Nome:</label>
        <font color="red">*</font>
        <input type="text" name="name" class="form-control" id="name" value="">
    </div>
    <div class="form-group">
        <label for="password">Senha:</label>
        <font color="red">*</font>
        <input type="password" name="password" class="form-control" id="password" value="Senha">
    </div>

    <div class="form-group">
        <label for="phone">Telefone:</label>
        <font color="red">*</font>
        <input type="text" name="phone" class="form-control" id="phone" value="">
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <font color="red">*</font>
        <input type="email" name="email" class="form-control" id="email" value="">
    </div>
    <br>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <label class="input-group-text" for="raffle_type">Cargo <small><i class="fa fa-question-circle"
                        aria-hidden="true" data-toggle="tooltip" data-placement="top"
                        title="Função ou posição que ele ocupará."></i></small></label>
        </div>
        <select name="group_id" class="custom-select" id="raffle_type">
            <option value="1" >Teste
            </option>
        </select>
    </div>
    <br>
    <input type="hidden" name="user_id" value="" />
    <input type="hidden" name="token" value="" />
    <button type="submit" class="btn btn-primary">Atualizar</button>
</form>
<?php
include_once (realpath(__DIR__ . "/layout/php/footer.php"));
?>