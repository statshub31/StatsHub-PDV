<?php
// VALIDADORES


function doGeneralValidationNameFormat($string)
{
    return preg_match('/^[a-zA-Z ]*$/', $string);
}
function doGeneralValidationProductNameFormat($string)
{
    return preg_match('/^[a-zA-Z0-9 ]*$/', $string);
}

function doGeneralValidationProductAlphaNumericFormat($string)
{
    return preg_match('/^[a-zA-Z0-9 ]*$/', $string);
}
function doGeneralValidationUserNameFormat($string)
{
    return preg_match('/^[a-zA-Z0-9]*$/', $string);
}
function doGeneralValidationPasswordFormat($string)
{
    return preg_match('/^[a-zA-Z!@#$%&0-9]*$/', $string);
}
function doGeneralValidationCodeFormat($string)
{
    return preg_match('/^[a-zA-Z0-9]*$/', $string);
}

function doGeneralValidationDiscountFormat($string)
{
    return preg_match('/^[0-9%]*$/', $string);
}

function doGeneralValidationPhoneFormat($string)
{
    return preg_match('/^[0-9]*$/', $string);
}

function doGeneralValidationNumberFormat($string)
{
    return preg_match('/^[0-9]*$/', $string);
}

function doGeneralValidationPriceFormat($string)
{
    return preg_match('/^[0-9.,]*$/', $string);
}

function doGeneralValidationEmailFormat($string)
{
    return filter_var($string, FILTER_VALIDATE_EMAIL);
}

function doGeneralCategoryNameFormat($string)
{
    return preg_match('/^[a-zA-Z ]*$/', $string);
}

function doGeneralValidationDescriptionFormat($string)
{
    return preg_match('/^[0-9a-zA-Záàãâäéèêëíìîïóòõôöúùûüç ,.(),;:!"#$%&=?~^ªº-]*$/', $string);
}
function doGeneralValidationPriceType($string)
{
    return preg_match('/^.*%$/', $string);
}

function isCampanhaInURL($param)
{
    // Obtém a parte da URL após o nome do domínio
    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Divide a URL em segmentos
    $parts = explode('/', $url);

    // Verifica se algum dos segmentos é "campanha"
    foreach ($parts as $part) {
        if ($part === $param) {
            return true;
        }
    }

    return false;
}


function getURLLastParam()
{
    // Obtém a parte da URL após "campanha/"
    $url = $_SERVER['REQUEST_URI'];
    $parts = explode('/', $url);
    $parameter = end($parts);
    // Retorna o valor do parâmetro
    return $parameter;
}








// ARQUIVOS

function getPathImageFormat($dir, $img)
{

    if (empty($img))
        return false;

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

function getPathAvatarImage($img)
{
    global $image_user_dir;
    global $image_model_dir;

    $format = getPathImageFormat($image_user_dir, $img);

    if ($format !== false) {
        return $image_user_dir . $img . '.' . $format;
    } else {
        return $image_model_dir . 'avatar.jpeg';
    }
}

function getPathProductImage($img)
{
    global $image_product_dir;
    global $image_model_dir;

    $format = getPathImageFormat($image_product_dir, $img);

    if ($format !== false) {
        return $image_product_dir . $img . '.' . $format;
    } else {
        return $image_model_dir . 'product.png';
    }
}

function getPathModelImage($img)
{
    global $image_model_dir;

    $format = getPathImageFormat($image_model_dir, $img);

    if ($format !== false) {
        return $image_model_dir . $img . '.' . $format;
    } else {
        return $image_model_dir . 'no-image.png';
    }
}

function doGeneralRemoveArchives($caminho_pasta, $nome_arquivo)
{
    // Obtém uma lista de todos os arquivos no diretório
    $arquivos = scandir($caminho_pasta);

    // Variável para armazenar o número de arquivos removidos
    $num_arquivos_removidos = 0;

    // Percorre todos os arquivos encontrados
    foreach ($arquivos as $arquivo) {
        // Verifica se o arquivo corresponde ao nome conhecido
        if (strpos($arquivo, $nome_arquivo) !== false) {
            // Remove o arquivo
            if (unlink($caminho_pasta . $arquivo)) {
                $num_arquivos_removidos++;
            }
        }
    }

    // Se nenhum arquivo correspondente for encontrado
    if ($num_arquivos_removidos === 0) {
        return 'Nenhum arquivo correspondente encontrado.';
    } else {
        return $num_arquivos_removidos . ' arquivo(s) removido(s) com sucesso.';
    }
}

function doGeneralRemoveArchive($caminho_pasta, $nome_arquivo)
{
    // Caminho completo para o arquivo

    $format = getPathImageFormat($caminho_pasta, $nome_arquivo);
    $caminho_arquivo = $_SERVER['DOCUMENT_ROOT'] . $caminho_pasta . $nome_arquivo . '.' . $format;

    if ($format == false)
        return false;

    // Verifica se o arquivo existe
    if (file_exists($caminho_arquivo)) {
        // Tenta remover o arquivo
        if (unlink($caminho_arquivo)) {
            return 'Arquivo removido com sucesso.';
        } else {
            return 'Falha ao remover o arquivo.';
        }
    } else {
        return 'Arquivo não encontrado.';
    }
}



// 



function generateRandomString($numbers = false, $specials = false, $letters = false, $length = 16)
{

    if ($numbers || ($numbers === false && $specials === false && $letters === false))
        $result[] = '123456789';

    if ($letters || ($numbers === false && $specials === false && $letters === false))
        $result[] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    if ($specials || ($numbers === false && $specials === false && $letters === false))
        $result[] = '!@#$%&*';

    $characters = implode('', $result);

    $charactersLength = strlen($characters);

    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


// LIMPADORES

function sanitize($data)
{
    // Verificar se a extensão Filter está habilitada
    if (extension_loaded('filter')) {
        // Usar filter_var com o filtro FILTER_UNSAFE_RAW
        return filter_var($data, FILTER_UNSAFE_RAW, FILTER_FLAG_NO_ENCODE_QUOTES);
    } else {
        // Em caso de a extensão Filter não estar disponível
        // Fallback para htmlentities e strip_tags
        return htmlentities(strip_tags($data), ENT_QUOTES, 'UTF-8');
    }
}

function sanitizeSpecial($str)
{
    $str = preg_replace('/[,.\/|"$%&=?~^><ªº-]/', '', $str);
    return $str;
}

function sanitizeString($str)
{
    $str = preg_replace('/[áàãâä]/ui', 'a', $str);
    $str = preg_replace('/[éèêë]/ui', 'e', $str);
    $str = preg_replace('/[íìîï]/ui', 'i', $str);
    $str = preg_replace('/[óòõôö]/ui', 'o', $str);
    $str = preg_replace('/[úùûü]/ui', 'u', $str);
    $str = preg_replace('/[ç]/ui', 'c', $str);
    $str = preg_replace('/[,.\/(),;:|!"#$%&=?~^><ªº-]/', '', $str);
    //    $str = preg_replace('/[^a-z0-9]/i', '_', $str);
    $str = preg_replace('/_+/', '_', $str); // ideia do Bacco :)
    $str = str_replace(' ', '', $str);
    $str = preg_replace('/[+*-]+/', '', $str);

    return $str;
}








// FACILITADORES

function removeEmptyValues(&$array)
{
    $array = array_filter($array, function ($value) {
        return $value !== '' && $value !== null;
    });
}
function doGeneralCreateArrayFromKeys($array)
{
    $newArray = array_keys($array);
    return $newArray;
}
function doGeneralCreateArrayFromValues($array)
{
    $newArray = array_values($array);
    return $newArray;
}

function validateRequiredFields($postData, $requiredFields)
{
    foreach ($postData as $key => $value) {
        if (empty($value) && in_array($key, $requiredFields)) {
            return false; // Pelo menos um campo obrigatório não está preenchido
        }
    }
    return true; // Todos os campos obrigatórios estão preenchidos
}

function validateRequiredFilesFields($postData, $requiredFields)
{

    foreach ($postData as $key => $value) {
        if (in_array($key, $requiredFields) && $value['size'] <= 0) {
            return false; // Pelo menos um campo obrigatório não está preenchido
        }
    }
    return true; // Todos os campos obrigatórios estão preenchidos

}


function data_dump($print = false, $var = false, $title = false)
{
    if ($title !== false)
        echo "<pre><font color='red' size='5'>$title</font><br>";
    else
        echo '<pre>';
    if ($print !== false) {
        echo 'Print: - ';
        print_r($print);
        echo "<br>";
    }
    if ($var !== false) {
        echo 'Var_dump: - ';
        var_dump($var);
    }
    echo '</pre><br>';
}

function removerArquivos($caminho_pasta, $nome_arquivo)
{
    // Obtém uma lista de todos os arquivos no diretório
    $arquivos = scandir($caminho_pasta);
    // Variável para armazenar o número de arquivos removidos
    $num_arquivos_removidos = 0;

    // Percorre todos os arquivos encontrados
    foreach ($arquivos as $arquivo) {
        // Verifica se o arquivo corresponde ao nome conhecido
        if (strpos($arquivo, $nome_arquivo) !== false) {
            // Remove o arquivo
            if (unlink($caminho_pasta . '/' . $arquivo)) {
                $num_arquivos_removidos++;
            }
        }
    }

    // Se nenhum arquivo correspondente for encontrado
    if ($num_arquivos_removidos === 0) {
        return 'Nenhum arquivo correspondente encontrado.';
    } else {
        return $num_arquivos_removidos . ' arquivo(s) removido(s) com sucesso.';
    }
}

function doSelect($f, $d)
{
    return ($f == $d) ? 'selected' : '';
}
function doCheck($f, $d)
{
    return ($f == $d) ? 'checked' : '';
}

function doYN($f)
{
    return ($f == 1) ? 'Sim' : 'Não';
}

function isGeneralExistProduct($table, $n)
{
    return isset($table[$n]);
}

function doTime($time)
{
    return date("H:i", strtotime($time));
}

function doDate($date)
{
    return date("d/m/Y", strtotime($date));
}


function doStyleProgress($n)
{
    $style = array(
        1 => '#ff5722',
        2 => '#e1d702',
        3 => '#4caf50',
        4 => '#009688',
        5 => '#8bc34a',
        6 => '#f44336',
        7 => '#009688'
    );

    return (isset($style[$n])) ? $style[$n] : $style[1];
}


function doBRDateTime($date)
{
    return date('d/m/Y H:i', strtotime($date));
}


function doTypeDiscount($discount)
{

    return (doGeneralValidationPriceType($discount)) ? $discount : 'R$ ' . sprintf("%.2f", (int) $discount);
}