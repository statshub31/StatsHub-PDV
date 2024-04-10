<?php
include_once(realpath(__DIR__ . "/front/php/header.php"));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (getToken('generateCode')) {
        $amount = $_POST["amount"];
        header("Location: /admin/gencode?amount=" . urlencode($amount));
        echo alertSuccess("Criado");
    }
}
?>
<div>

    <form action="/admin/gencode" method="get" target="_blank">

        <div class="alert alert-info" role="alert">
            Para cadastrar novos usuários, é indispensável que haja vouchers disponíveis.
        </div>

        <label for="inputfname">Você deseja gerar quantos códigos?</label>
        <div class="input-group mb-3">
            <input name="amount" type="text" class="form-control" placeholder="50" aria-describedby="basic-addon2">
            <div class="input-group-append">

                <input name="token" type="text" value="<?php echo base64_encode(addToken('generateCode')) ?>" hidden />
                <button class="btn btn-outline-secondary" type="submit">Criar</button>
            </div>
        </div>

    </form>

    <a href="/admin/codes">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
    </a>
</div>

<?php

include_once(realpath(__DIR__ . "/front/php/footer.php"));
?>