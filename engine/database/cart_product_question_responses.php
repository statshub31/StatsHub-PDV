<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseCartProductQuestionResponsesData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `cart_product_question_responses` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseCartProductQuestionResponseCartID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseCartProductQuestionResponsesData($id_sanitize, 'cart_id');
    return ($query !== false) ? $query['cart_id'] : false;
}

function getDatabaseCartProductQuestionResponseProductID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseCartProductQuestionResponsesData($id_sanitize, 'product_id');
    return ($query !== false) ? $query['product_id'] : false;
}


function getDatabaseCartProductQuestionResponseAmount($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseCartProductQuestionResponsesData($id_sanitize, 'amount');
    return ($query !== false) ? $query['amount'] : false;
}


function isDatabaseCartProductQuestionResponseExistIDByUserID($user_id)
{
    
    $user_id_sanitize = sanitize($user_id);

    $query = doSelectSingleDB("SELECT `id` FROM `cart_product_question_responses` WHERE `user_id`='".$user_id_sanitize."';");
    return ($query !== false) ? true : false;
}

function doDatabaseCartProductQuestionResponsesList($status = false)
{
    
    return doSelectMultiDB("SELECT `id` FROM `cart_product_question_responses`");
}

function doDatabaseCartProductQuestionResponseInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `cart_product_question_responses` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseCartProductQuestionResponseDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `cart_product_question_responses` WHERE `id`='".$id_sanitize."'limit 1;");
}

function doDatabaseCartProductQuestionResponseUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `cart_product_question_responses` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 


function doDatabaseCartProductQuestionResponseInsertMultipleRow($import_data_query)
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
    $query = "INSERT INTO `cart_product_question_responses` ($keys) VALUES $values";
    doInsertDB($query);
}


?>