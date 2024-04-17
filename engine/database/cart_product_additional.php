<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseCartProductAdditionalData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `cart_product_additional` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseCartProductAdditionalCartID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseCartProductAdditionalData($id_sanitize, 'cart_id');
    return ($query !== false) ? $query['cart_id'] : false;
}

function getDatabaseCartProductAdditionalCartProductID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseCartProductAdditionalData($id_sanitize, 'cart_product_id');
    return ($query !== false) ? $query['cart_product_id'] : false;
}


function getDatabaseCartProductAdditionalAmount($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseCartProductAdditionalData($id_sanitize, 'amount');
    return ($query !== false) ? $query['amount'] : false;
}


function isDatabaseCartProductAdditionalExistIDByUserID($user_id)
{
    
    $user_id_sanitize = sanitize($user_id);

    $query = doSelectSingleDB("SELECT `id` FROM `cart_product_additional` WHERE `user_id`='".$user_id_sanitize."';");
    return ($query !== false) ? true : false;
}

function doDatabaseCartProductAdditionalList($status = false)
{
    
    return doSelectMultiDB("SELECT `id` FROM `cart_product_additional`");
}

function doDatabaseCartProductAdditionalInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `cart_product_additional` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseCartProductAdditionalDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `cart_product_additional` WHERE `id`='".$id_sanitize."'limit 1;");
}

function doDatabaseCartProductAdditionalUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `cart_product_additional` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 


function doDatabaseCartProductAdditionalInsertMultipleRow($import_data_query)
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
    $query = "INSERT INTO `cart_product_additional` ($keys) VALUES $values";
    doInsertDB($query);
}
?>