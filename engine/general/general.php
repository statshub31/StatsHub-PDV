<?php

function getPathImageFormat($dir, $img)
{
    $expr = $_SERVER['DOCUMENT_ROOT'] . $dir . $img . '*';

    // Obtém a lista de arquivos que correspondem ao padrão
    $files = glob($expr);

    // Verifica se a lista de arquivos não está vazia
    if (!empty($files)) {
        // Itera sobre os arquivos encontrados
        foreach ($files as $path) {
            // Verifica se o caminho corresponde a um arquivo (não um diretório)
            if (is_file($path)) {
                // Obtém a extensão do arquivo
                $extensao = pathinfo($path, PATHINFO_EXTENSION);

                // Retorna a extensão (ou tipo) da imagem
                return $extensao;
            }
        }
    }

    // Retorna false se nenhum arquivo for encontrado
    return false;
}