<?php

function doAlertWarning($message, $type = "info")

{
    // Estilos CSS
    $alert = '
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
                width: 3em;
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

            *,
            ::after,
            ::before {
                box-sizing: border-box;
            }
        </style>';

    // Script jQuery
    $alert .= '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';

    // Gera um ID único para o alerta
    $alert_id = generateRandomString(false, false, true, 5);

    // Estrutura do alerta com botão de fechar
    $alert .= '<div id="'.$alert_id.'" class="alert alert-warning alert-fixed w-50" role="alert">
                    <button type="button" class="me-close" onclick="closeAlert(\'' . $alert_id . '\')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="me-popup-frame">
                        <div class="me-popup">
                            <img src="/layout/images/model/warning.png" />
                        </div>
                    </h4>';

    // Adiciona os erros ao alerta
    $alert .= $message;

    $alert .= '</div>';

    // Script JavaScript
    $alert .= "<script>
                    // Função para esconder o alerta após 5 segundos
                    function hideAlert(id) {
                        setTimeout(function () {
                            $('#' + id).fadeOut('slow');
                        }, 5000);
                    }

                    // Função para fechar o alerta
                    function closeAlert(id) {
                        $('#' + id).fadeOut('slow');
                    }

                    // Chamada da função ao carregar a página
                    $(document).ready(function () {
                        hideAlert('".$alert_id."');
                    });
                </script>";

    // Retorna o alerta completo
    echo $alert;
}


function doAlertSuccess($message, $refresh = false)

{
    // Estilos CSS
    $alert = '
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
                width: 3em;
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

            *,
            ::after,
            ::before {
                box-sizing: border-box;
            }
        </style>';

    // Script jQuery
    $alert .= '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';

    // Gera um ID único para o alerta
    $alert_id = generateRandomString(false, false, true, 5);

    // Estrutura do alerta com botão de fechar
    $alert .= '<div id="'.$alert_id.'" class="alert alert-success alert-fixed w-50" role="alert">
                    <button type="button" class="me-close" onclick="closeAlert(\'' . $alert_id . '\')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="me-popup-frame">
                        <div class="me-popup">
                            <img src="/layout/images/model/success.png" />
                        </div>
                    </h4>';

    // Adiciona os erros ao alerta
    $alert .= $message;

    $alert .= '</div>';

    // Script JavaScript
    $alert .= "<script>
                    // Função para esconder o alerta após 5 segundos
                    function hideAlert(id) {
                        setTimeout(function () {
                            $('#' + id).fadeOut('slow');
                        }, 5000);
                    }

                    // Função para fechar o alerta
                    function closeAlert(id) {
                        $('#' + id).fadeOut('slow');
                    }

                    // Chamada da função ao carregar a página
                    $(document).ready(function () {
                        hideAlert('".$alert_id."');
                    });
                </script>";

    // Retorna o alerta completo
    echo $alert;
}


function doAlertError($errors)
{
    // Estilos CSS
    $alert = '
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
                width: 3em;
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

            *,
            ::after,
            ::before {
                box-sizing: border-box;
            }
        </style>';

    // Script jQuery
    $alert .= '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';

    // Gera um ID único para o alerta
    $alert_id = generateRandomString(false, false, true, 5);

    // Estrutura do alerta com botão de fechar
    $alert .= '<div id="'.$alert_id.'" class="alert alert-danger alert-fixed w-50" role="alert">
                    <button type="button" class="me-close" onclick="closeAlert(\'' . $alert_id . '\')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="me-popup-frame">
                        <div class="me-popup">
                            <img src="/layout/images/model/failed.png" />
                        </div>
                    </h4>';

    // Adiciona os erros ao alerta
    foreach ($errors as $error) {
        $alert .= $error.'<br>';
    }

    $alert .= '</div>';

    // Script JavaScript
    $alert .= "<script>
                    // Função para esconder o alerta após 5 segundos
                    function hideAlert(id) {
                        setTimeout(function () {
                            $('#' + id).fadeOut('slow');
                        }, 5000);
                    }

                    // Função para fechar o alerta
                    function closeAlert(id) {
                        $('#' + id).fadeOut('slow');
                    }

                    // Chamada da função ao carregar a página
                    $(document).ready(function () {
                        hideAlert('".$alert_id."');
                    });
                </script>";

    // Retorna o alerta completo
    return $alert;
}

?>