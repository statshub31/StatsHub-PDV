<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseProductsQuestionsData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `products_question` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseProductQuestionProductID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsQuestionsData($id_sanitize, 'product_id');
    return ($query !== false) ? $query['product_id'] : false;
}


function getDatabaseProductQuestionText($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsQuestionsData($id_sanitize, 'question');
    return ($query !== false) ? $query['question'] : false;
}

function getDatabaseProductQuestionMultipleResponse($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsQuestionsData($id_sanitize, 'multiple_response');
    return ($query !== false) ? $query['multiple_response'] : false;
}

function getDatabaseProductQuestionResponseFree($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsQuestionsData($id_sanitize, 'response_free');
    return ($query !== false) ? $query['response_free'] : false;
}


function isDatabaseProductQuestionExistID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `products_question` WHERE `id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}

function doDatabaseProductsQuestionsListByProductID($product_id)
{
    $sanitize_product_id = sanitize($product_id);

    return doSelectMultiDB("SELECT `id` FROM `products_question` where `product_id` = '".$sanitize_product_id."'");
}



function doDatabaseProductQuestionInsert($import_data_query)
{
    

    // Remove todos os campos vazios
    removeEmptyValues($import_data_query);

    // transforma as chaves em um único array
    $keyArray = doGeneralCreateArrayFromKeys($import_data_query);
    // transforma os valores em um único array
    $valueArray = doGeneralCreateArrayFromValues($import_data_query);

    // Converte para o formato Mysql
    $keys = doMysqlConvertArrayKey($keyArray);

    // Converte para o formato Mysql
    $values = doMysqlConvertArrayValue($valueArray);

    return doInsertDB("INSERT INTO `products_question` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseProductQuestionDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `products_question` WHERE `id`='".$id_sanitize."'limit 1;");
}

function doDatabaseProductQuestionUpdate($id, $import_data_query, $empty = true)
{

    
    $id_sanitize = sanitize($id);
    
    if ($empty) {
        // Remove todos os campos vazios
        removeEmptyValues($import_data_query);
    }

    // transforma as chaves em um único array
    $keyArray = doGeneralCreateArrayFromKeys($import_data_query);

    // transforma os valores em um único array
    $valueArray = doGeneralCreateArrayFromValues($import_data_query);

    // Converte para o formato Mysql
    $query_sql = doMysqlConvertUpdateArray($keyArray, $valueArray);
    doUpdateDB("UPDATE `products_question` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 

function doDatabaseProductQuestionInsertMultipleRow($import_data_query)
{
    // Remove todos os campos vazios
    removeEmptyValues($import_data_query);

    // Transforma as chaves em array
    $keys = array_keys($import_data_query[0]);

    // Transforma os valores em arrays separados
    $values = array_map(function ($item) {
        return '(\'' . implode('\',\'', $item) . '\')';
    }, $import_data_query);

    // Converte para o formato MySQL
    $keys = doMysqlConvertArrayKey($keys);
    $values = implode(', ', $values);

    // Monta a query de inserção
    $query = "INSERT INTO `products_question` ($keys) VALUES $values";
    doInsertDB($query);
}


function doDatabaseProductQuestionTruncateByProductID($product_id)
{
    
    $product_id_sanitize = sanitize($product_id);

    doDeleteDB("DELETE FROM `products_question` WHERE `product_id`='".$product_id_sanitize."';");
}


function getDatabaseProductQuestionIDByProductID($product_id)
{
    
    $product_id_sanitize = sanitize($product_id);

    $query = doSelectSingleDB("SELECT `id` FROM `products_question` WHERE `product_id`='".$product_id_sanitize."';");
    return ($query !== false) ? $query['id'] : false;
}

function getDatabaseProductQuestionRowCountByProductID($product_id)
{
    $product_id_sanitize = sanitize($product_id);

    $result = doSelectSingleDB("SELECT COUNT(*) AS rowCount FROM `products_question` where `product_id` = '".$product_id_sanitize."';");

    return ($result !== false) ? $result['rowCount'] : 0;
}

function isDatabaseProductQuestionExistQuestion($product_id) {
    return (getDatabaseProductQuestionRowCountByProductID($product_id) > 0) ? true : false;
}

?>