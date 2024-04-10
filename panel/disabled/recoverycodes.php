<?php
require_once(realpath(__DIR__ . "/../engine/init.php"));

    
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST) && getToken('recoveryCode')) {
?>
<style>
    .keyContainer {
        display: table;
        position: relative;
        width: 200px;
        margin: 5px;
        float: left;
    }

    .keyImage img {
        width: 100%;
        max-width: 100%;
        /* Garante que a imagem não ultrapasse a largura da keyContainer */
        height: auto;
    }

    .codebar {
        position: absolute;
        bottom: 11px;
        left: 18px;
        width: 162px;
        height: 47px;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        border: 1px solid #f18030;
        background-color: #ffffff;
    }

    .codebar p {
        position: absolute;
        bottom: 28px;
        font-weight: bold;
        color: white;
        left: 20px;
    }

    .codebar img {
        position: absolute;
        bottom: 7px;
        left: 2px;
        width: calc(100% - 5px);
        height: auto;
    }

    .codebar p {
        background: linear-gradient(181deg, rgb(168 100 50) 0%, rgb(244 150 49) 50%, rgb(245 165 49) 100%);
        color: #fff;
        margin: 0;
        text-align: center;
        font-size: 18px;
        position: absolute;
        left: -15px;
        right: -15px;
        bottom: 45px;
        border-top: 1px solid #f18030;
        border-right: 1px solid #f18030;
        border-left: 1px solid #f18030;
    }

    .codebar p:after,
    .codebar p:before {
        display: block;
        position: absolute;
        width: 0;
        height: 0;
        content: "";
        top: 100%;

        border-style: solid;
        border-color: transparent;
    }

    .codebar p:before {
        left: 0;
        border-width: 0 15px 5px 0;
        border-right-color: #f18030;
    }

    .codebar p:after {
        border-width: 5px 15px 0 0;
        border-top-color: #f18030;
        right: 0;
    }
</style>
<div id="row">
    <?php
    $list = doAccountsCBCodeListByDate($_POST['date']);
    if ($list) {
        foreach ($list as $data) {
            $code = getAccountsCBCode($data['id']);
            ?>
            <div class="keyContainer">
                <div class="keyImage">
                    <img src="\front\images\config\key.png" alt="Imagem">
                    <div class="codebar">
                        <p>
                            <?php echo $code ?>
                        </p>
                        <img src="data:image/png;base64,<?php echo doGenerateCodeBar($code); ?>">
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>


<script>
    function abrirNovaJanela() {
        // URL do conteúdo que você deseja abrir
        var urlConteudo = 'https://www.exemplo.com';

        // Configurações opcionais da janela
        var configuracoes = 'width=600,height=400,toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes';

        // Abre a nova janela
        window.open(urlConteudo, 'NomeDaJanela', configuracoes);
    }
</script>

<?php
destroyToken("recoveryCode");
} else {
    header('Location: /admin/codes', true, 302);
    exit; // Certifique-se de encerrar a execução do script após o redirecionamento
}
?>