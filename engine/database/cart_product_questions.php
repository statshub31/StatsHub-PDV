<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseCartProductQuestionsData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `cart_product_questions` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseCartProductQuestionCartID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseCartProductQuestionsData($id_sanitize, 'cart_id');
    return ($query !== false) ? $query['cart_id'] : false;
}

function getDatabaseCartProductQuestionProductID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseCartProductQuestionsData($id_sanitize, 'product_id');
    return ($query !== false) ? $query['product_id'] : false;
}


function getDatabaseCartProductQuestionAmount($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseCartProductQuestionsData($id_sanitize, 'amount');
    return ($query !== false) ? $query['amount'] : false;
}


function isDatabaseCartProductQuestionExistIDByUserID($user_id)
{
    
    $user_id_sanitize = sanitize($user_id);

    $query = doSelectSingleDB("SELECT `id` FROM `cart_product_questions` WHERE `user_id`='".$user_id_sanitize."';");
    return ($query !== false) ? true : false;
}

function doDatabaseCartProductQuestionsList($status = false)
{
    
    return doSelectMultiDB("SELECT `id` FROM `cart_product_questions`");
}

function doDatabaseCartProductQuestionInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `cart_product_questions` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseCartProductQuestionDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `cart_product_questions` WHERE `id`='".$id_sanitize."'limit 1;");
}

function doDatabaseCartProductQuestionUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `cart_product_questions` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 

?>