<?php

function doAlertWarning($message, $type = "info")
{
    echo '
        <style>
            .alert-fixed {
                position: fixed;
                top: 30px;
                left: 50%;
                transform: translateX(-50%);
                width: 100%;
                z-index: 9999;
                border-radius: 0px;
            }
        </style>
        <div class="alert alert-danger alert-fixed w-50" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
            <h4 class="alert-heading">AVISO!!</h4>
            ' . $message . '
        </div>
        <script>
            $(".alert").alert()
        </script>
    ';
}
function doAlertSuccess($message, $refresh = false)
{
    if ($refresh !== false) {
        $ex = '
            setTimeout(function() {
                window.location.href = "' . $refresh . '";
            }, 5 * 1000);
        ';
    } else {
        $ex = '';
    }

    echo '
        <style>
            .alert-fixed {
                position: fixed;
                top: 30px;
                left: 50%;
                transform: translateX(-50%);
                width: 100%;
                z-index: 9999;
                border-radius: 0px;
            }
        </style>
        <div class="alert alert-success alert-fixed w-50" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="alert-heading">SUCESSO!!</h4>
            ' . $message . '
        </div>
        <script>
            ' . $ex . '
            $(".alert").alert();
        </script>
    ';
}

function doAlertError($errors)
{
    echo '
        <style>
            .alert-fixed {
                position: fixed;
                top: 30px;
                left: 50%;
                transform: translateX(-50%);
                width: 100%;
                z-index: 9999;
                border-radius: 0px;
            }
        </style>
        <div class="alert alert-danger alert-fixed w-50" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
            <h4 class="alert-heading">ERRO!!</h4>
            ' . implode('</br>', $errors) . '
        </div>
        <script>
            $(".alert").alert()
        </script>
    ';
}
?>