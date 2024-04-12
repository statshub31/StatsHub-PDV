<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseProductsPriceData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `products_price` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseProductPriceProductID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsPriceData($id_sanitize, 'product_id');
    return ($query !== false) ? $query['product_id'] : false;
}

function getDatabaseProductSizeMeasureID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsPriceData($id_sanitize, 'size_measure_id');
    return ($query !== false) ? $query['size_measure_id'] : false;
}

function getDatabaseProductPriceSize($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsPriceData($id_sanitize, 'size');
    return ($query !== false) ? $query['size'] : false;
}

function getDatabaseProductPriceDescription($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsPriceData($id_sanitize, 'description');
    return ($query !== false) ? $query['description'] : false;
}

function getDatabaseProductPrice($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsPriceData($id_sanitize, 'price');
    return ($query !== false) ? $query['price'] : false;
}

function isDatabaseProductPriceExistID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `products_price` WHERE `id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}

function doDatabaseProductPricesPriceList($status = false)
{
    
    return doSelectMultiDB("SELECT `id` FROM `products_price`");
}

function doDatabaseProductPriceInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `products_price` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseProductPriceDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `products_price` WHERE `id`='".$id_sanitize."'limit 1;");
}

function doDatabaseProductPriceUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `products_price` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 


function doDatabaseProductPriceInsertMultipleRow($import_data_query)
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
    $query = "INSERT INTO `products_price` ($keys) VALUES $values";
    doInsertDB($query);
}

?>