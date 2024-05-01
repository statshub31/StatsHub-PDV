<?php
include_once (__DIR__ . "/engine/init.php");
// include_once __DIR__ . '/layout/php/header.php';
?>


<style>
    .me-close:not(:disabled):not(.disabled) {
        cursor: pointer;
    }

    button.me-close {
        padding: 0;
        background-color: transparent;
        border: 0;
        -webkit-appearance: none;
    }

    [type=reset],
    [type=submit],
    button,
    html [type=button] {
        -webkit-appearance: button;
    }

    .me-close {
        float: right;
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
        color: #000;
        text-shadow: 0 1px 0 #fff;
        opacity: .5;
    }

    button,
    select {
        text-transform: none;
    }

    button,
    input {
        overflow: visible;
    }

    button,
    input,
    optgroup,
    select,
    textarea {
        margin: 0;
        font-family: inherit;
        font-size: inherit;
        line-height: inherit;
    }

    button {
        border-radius: 0;
    }

    .alert-fixed {
        position: fixed;
        top: 30px;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        z-index: 9999;
        border-radius: 0px;
    }

    .me-popup-frame {
        display: flex;
        justify-content: center;
    }

    .me-popup {
        width: 2em;
        opacity: 50%;
        text-align: center;
    }

    .me-popup img {
        width: 100%;
        height: 100%;
    }

    .w-50 {
        width: 50% !important;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .alert-warning {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeeba;
    }

    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    .alert {
        position: relative;
        padding: .75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: .25rem;
    }

    *,
    ::after,
    ::before {
        box-sizing: border-box;
    }
</style>
<div class="alert alert-success alert-fixed w-50" role="alert">
    <button type="button" class="me-close">
        <span aria-hidden="true">&times;</span>
    </button>
    <h4 class="me-popup-frame">
        <div class="me-popup">
            <img src="/layout/images/model/success.png" />
        </div>
    </h4>
    ' . $message . '
</div>
<div class="alert alert-warning alert-fixed w-50" role="alert">
    <button type="button" class="me-close">
        <span aria-hidden="true">&times;</span>
    </button>
    <h4 class="me-popup-frame">
        <div class="me-popup">
            <img src="/layout/images/model/warning.png" />
        </div>
    </h4>
    ' . $message . '
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php 
    $alert_id = generateRandomString(false, false, true, 5);
?>
<div id="<?php echo $alert_id ;?>" class="alert alert-danger alert-fixed w-50" role="alert">
    <button type="button" class="me-close" onclick="closeAlert('<?php echo $alert_id ;?>')">
        <span aria-hidden="true">&times;</span>
    </button>
    <h4 class="me-popup-frame">
        <div class="me-popup">
            <img src="/layout/images/model/failed.png" />
        </div>
    </h4>
    ' . $message . '
</div>

<script>
    // Função para esconder o alerta após 5 segundos
    function hideAlert(id) {
        setTimeout(function () {
            $('#'+ id).fadeOut('slow');
        }, 5000);
    }

    function closeAlert(id) {
        $('#'+ id).fadeOut('slow');
    }

    // Chamada da função ao carregar a página
    $(document).ready(function () {
        hideAlert('<?php echo $alert_id ;?>');
    });
</script>