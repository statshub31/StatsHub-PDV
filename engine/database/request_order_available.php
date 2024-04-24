<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseRequestOrderAvailableData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `request_order_available` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseRequestOrderAvailableRequestID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseRequestOrderAvailableData($id_sanitize, 'request_order_id');
    return ($query !== false) ? $query['request_order_id'] : false;
}

function getDatabaseRequestOrderAvailableCreated($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseRequestOrderAvailableData($id_sanitize, 'created');
    return ($query !== false) ? $query['created'] : false;
}

function getDatabaseRequestOrderAvailableFoodAvailable($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseRequestOrderAvailableData($id_sanitize, 'food');
    return ($query !== false) ? (int)$query['food'] : false;
}

function isDatabaseRequestOrderAvailableFoodAvailable($id, $n)
{
    
    $id_sanitize = sanitize($id);
    $query = getDatabaseRequestOrderAvailableData($id_sanitize, 'food');
    return ($query !== false && $query['food'] >= $n) ? true : false;
}


function getDatabaseRequestOrderAvailableBoxAvailable($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseRequestOrderAvailableData($id_sanitize, 'box');
    return ($query !== false) ? (int)$query['box'] : false;
}

function isDatabaseRequestOrderAvailableBoxAvailable($id, $n)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseRequestOrderAvailableData($id_sanitize, 'box');
    return ($query !== false && $query['box'] >= $n) ? true : false;
}

function getDatabaseRequestOrderAvailableCostBenefitAvailable($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseRequestOrderAvailableData($id_sanitize, 'costbenefit');
    return ($query !== false) ? (int)$query['costbenefit'] : false;
}

function isDatabaseRequestOrderAvailableCostBenefitAvailable($id, $n)
{
    $id_sanitize = sanitize($id);

    $query = getDatabaseRequestOrderAvailableData($id_sanitize, 'costbenefit');
    return ($query !== false && $query['costbenefit'] >= $n) ? true : false;
}

function getDatabaseRequestOrderAvailableDeliveryTimeAvailable($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseRequestOrderAvailableData($id_sanitize, 'deliverytime');
    return ($query !== false) ? (int)$query['deliverytime'] : false;
}

function isDatabaseRequestOrderAvailableDeliveryTimeAvailable($id, $n)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseRequestOrderAvailableData($id_sanitize, 'deliverytime');
    return ($query !== false && $query['deliverytime'] >= $n) ? true : false;
}
function getDatabaseRequestOrderAvailableComment($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseRequestOrderAvailableData($id_sanitize, 'comment');
    return ($query !== false) ? $query['comment'] : false;
}


function isDatabaseRequestOrderAvailableExistID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `request_order_available` WHERE `id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}

function doDatabaseRequestOrderAvailableList()
{
    return doSelectMultiDB("SELECT id FROM request_order_available ORDER BY id DESC");
}

function doDatabaseRequestOrderAvailableFirstAvailableByOrderID($order_id)
{
    $order_id_sanitize = $order_id;
    return doSelectSingleDB("SELECT id FROM request_order_available where `request_order_id`='".$order_id_sanitize."' ORDER BY id asc LIMIT 1")['id'];
}

function doDatabaseRequestOrderAvailableLastAvailableByOrderID($order_id)
{
    $order_id_sanitize = $order_id;
    return doSelectSingleDB("SELECT id FROM request_order_available where `request_order_id`='".$order_id_sanitize."' ORDER BY id DESC LIMIT 1")['id'];
}

function doDatabaseRequestOrderAvailableInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `request_order_available` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseRequestOrderAvailableDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `request_order_available` WHERE `id`='".$id_sanitize."'limit 1;");
}

function doDatabaseRequestOrderAvailableUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `request_order_available` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 

function isDatabaseRequestOrderAvailableExistByOrderID($order_id)
{
    
    $order_id_sanitize = sanitize($order_id);

    $query = doSelectSingleDB("SELECT `id` FROM `request_order_available` WHERE `request_order_id`='".$order_id_sanitize."';");
    return ($query !== false) ? true : false;
}

function getDatabaseRequestOrderAvailableExistByOrderID($order_id)
{
    
    $order_id_sanitize = sanitize($order_id);

    $query = doSelectSingleDB("SELECT `id` FROM `request_order_available` WHERE `request_order_id`='".$order_id_sanitize."';");
    return ($query !== false) ? $query['id'] : false;
}

?>