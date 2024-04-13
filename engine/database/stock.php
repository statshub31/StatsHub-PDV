<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseStockData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `stock` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseStockProductID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseStockData($id_sanitize, 'product_id');
    return ($query !== false) ? $query['product_id'] : false;
}

function getDatabaseStockMin($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseStockData($id_sanitize, 'min');
    return ($query !== false) ? $query['min'] : false;
}

function getDatabaseStockActual($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseStockData($id_sanitize, 'actual');
    return ($query !== false) ? $query['actual'] : false;
}

function isDatabaseStockExistID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `stock` WHERE `id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}

function doDatabaseStockList($status = false)
{
    
    return doSelectMultiDB("SELECT `id` FROM `stock`");
}

function doDatabaseStockInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `stock` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseStockDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `stock` WHERE `id`='".$id_sanitize."'limit 1;");
}

function doDatabaseStockUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `stock` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 

function getDatabaseStockIDByProductID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `stock` WHERE `product_id`='".$id_sanitize."';");
    return ($query !== false) ? $query['id'] : false;
}


?>