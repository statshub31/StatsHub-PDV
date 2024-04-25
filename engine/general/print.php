<?php

require $_SERVER['DOCUMENT_ROOT'] . '/engine/api/print/autoload.php';

use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

// Função para imprimir
function imprimirPedido($conteudo)
{
    try {
        // Conecta à impressora
        $connector = new WindowsPrintConnector("USB001://192.168.1.2/POS-80 11.3.0.0"); // Substitua pelo número da porta USB da sua impressora
        $printer = new Printer($connector);

        // Imprime o conteúdo
        $printer->text($conteudo);

        // Corta o papel (apenas para impressoras que suportam)
        $printer->cut();

        // Fecha a conexão com a impressora
        $printer->close();
    } catch (Exception $e) {
        echo "Erro ao imprimir: " . $e->getMessage();
    }
}



function doListPrints()
{
    // Comando para listar impressoras no Windows
    $cmd = 'wmic printer get name';

    // Executar o comando e capturar a saída
    exec($cmd, $output, $return_var);

    // Verificar se o comando foi executado com sucesso
    if ($return_var === 0) {
        echo "Impressoras disponíveis:\n";
        foreach ($output as $linha) {
            if (!empty(trim($linha))) {
                echo "- " . trim($linha) . "<br>";
            }
        }
    } else {
        echo "Erro ao listar impressoras.";
    }
}