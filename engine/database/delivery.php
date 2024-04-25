<?php

# get + Pasta + Arquivo + Função + Dependencia
function getDatabaseDeliverysData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `delivery` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseDeliveryTitle($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseDeliverysData($id_sanitize, 'title');
    return ($query !== false) ? $query['title'] : false;
}

function getDatabaseDeliveryAccess($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseDeliverysData($id_sanitize, 'access');
    return ($query !== false) ? $query['access'] : false;
}


function isDatabaseDeliveryExistID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `delivery` WHERE `id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}

function doDatabaseDeliveryList($status = false)
{
    
    return doSelectMultiDB("SELECT `id` FROM `delivery`");
}

function doDatabaseDeliveryInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `delivery` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseDeliveryDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `delivery` WHERE `id`='".$id_sanitize."' limit 1;");
}

function doDatabaseDeliveryUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `delivery` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

?>