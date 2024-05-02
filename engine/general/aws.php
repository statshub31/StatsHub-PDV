<?php

require $_SERVER['DOCUMENT_ROOT'] . '/engine/apis/aws-sdk/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

$s3Client = new S3Client([
    'version' => 'latest',
    'region' => 'sa-east-1',
    'credentials' => [
        'key' => getenv('AWS_ACCESS_KEY_ID'),
        'secret' => getenv('AWS_SECRET_ACCESS_KEY'),
    ],
]);

// This will output the barcode as HTML output to display in the browser
function doAWSS3GetObject($Key, $version = 'latest', $region = 'sa-east-1')
{
    global $s3Client;

    $result = $s3Client->getObject([
        'Bucket' => getenv('BUCKET_NAME'),
        'Key' => $Key,
    ]);

    $iniContents = $result['Body']->getContents();

    // Processar o conteúdo do INI para obter as credenciais
    $result = parse_ini_string($iniContents);
    return $result;
}



// This will output the barcode as HTML output to display in the browser
function getAWSS3ImageURL($key, $version = 'latest', $region = 'sa-east-1')
{
    global $s3Client;

    // Tentativa de obter o URL da imagem
    try {
        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => getenv('BUCKET_NAME'),
            'Key' => $key,
        ]);

        $request = $s3Client->createPresignedRequest($cmd, '+20 minutes');

        // Obtém o URL assinado
        $presignedUrl = (string) $request->getUri();

        // Parse apenas a parte do caminho do URL sem os parâmetros
        $parsedUrl = parse_url($presignedUrl);

        if (isset($parsedUrl['scheme'], $parsedUrl['host'], $parsedUrl['path'])) {
            // Construa o URL final sem os parâmetros
            $imageUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $parsedUrl['path'];

            return $imageUrl;
        }

        return null;
    } catch (AwsException $e) {
        // Em caso de falha, trate a exceção (pode ser ajustado conforme necessário)
        // Aqui, apenas retornamos null em caso de falha
        return null;
    }
}


function doAWSS3PutIMGObject($objectKey, $localImagePath, $version = 'latest', $region = 'sa-east-1')
{
    global $s3Client; 
    try {
        $result = $s3Client->putObject([
            'Bucket' => getenv('BUCKET_NAME'),
            'Key' => $objectKey,
            'SourceFile' => $localImagePath,
            // 'ACL' => 'public-read',
        ]);

        // Retorna a URL pública da imagem em vez de imprimir
        return $result['ObjectURL'];

    } catch (AwsException $e) {
        // Em caso de falha, trate a exceção
        return "Erro ao fazer upload da imagem: " . $e->getMessage();
    }
}
?>