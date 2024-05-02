<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseLogsStockData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `logs_stock` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseLogStockProductID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseLogsStockData($id_sanitize, 'product_id');
    return ($query !== false) ? $query['product_id'] : false;
}

function getDatabaseLogStockActionID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseLogsStockData($id_sanitize, 'action_id');
    return ($query !== false) ? $query['action_id'] : false;
}

function getDatabaseLogStockAmount($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseLogsStockData($id_sanitize, 'amount');
    return ($query !== false) ? $query['amount'] : false;
}

function getDatabaseLogStockAmountDate($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseLogsStockData($id_sanitize, 'date');
    return ($query !== false) ? $query['date'] : false;
}



function getDatabaseLogStockUserID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseLogsStockData($id_sanitize, 'user_id');
    return ($query !== false) ? $query['user_id'] : false;
}

function getDatabaseLogStockReason($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseLogsStockData($id_sanitize, 'reason');
    return ($query !== false) ? $query['reason'] : false;
}

function isDatabaseLogStockExistID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `logs_stock` WHERE `id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}

function doDatabaseLogsStockList($status = false)
{
    
    return doSelectMultiDB("SELECT `id` FROM `logs_stock`");
}

function doDatabaseLogStockInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `logs_stock` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseLogStockDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `logs_stock` WHERE `id`='".$id_sanitize."'limit 1;");
}

function doDatabaseLogStockUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `logs_stock` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 

function isDatabaseLogStockExistTitle($title)
{
    
    $title_sanitize = sanitize($title);

    $query = doSelectSingleDB("SELECT `id` FROM `logs_stock` WHERE `title`='".$title_sanitize."';");
    return ($query !== false) ? true : false;
}


function doDatabaseLogsStockTruncateByProductID($product_id)
{
    
    $product_id_sanitize = sanitize($product_id);

    doDeleteDB("DELETE FROM `logs_stock` WHERE `product_id`='".$product_id_sanitize."';");
}

?>