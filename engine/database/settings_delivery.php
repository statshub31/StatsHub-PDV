<?php

# get + Pasta + Arquivo + Função + Dependencia
function getDatabaseSettingsDeliverysData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `settings_delivery` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseSettingsDeliveryOrderWithdrawal($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsDeliverysData($id_sanitize, 'order_withdrawal');
    return ($query !== false) ? $query['order_withdrawal'] : false;
}

function getDatabaseSettingsDeliveryAddressAPI($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsDeliverysData($id_sanitize, 'address_api');
    return ($query !== false) ? $query['address_api'] : false;
}
function getDatabaseSettingsDeliveryOrderMin($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsDeliverysData($id_sanitize, 'order_min');
    return ($query !== false) ? $query['order_min'] : false;
}
function getDatabaseSettingsDeliveryFee($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsDeliverysData($id_sanitize, 'fee');
    return ($query !== false) ? $query['fee'] : false;
}
function getDatabaseSettingsDeliveryTimeMin($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsDeliverysData($id_sanitize, 'time_min');
    return ($query !== false) ? $query['time_min'] : false;
}
function getDatabaseSettingsDeliveryTimeMax($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsDeliverysData($id_sanitize, 'time_max');
    return ($query !== false) ? $query['time_max'] : false;
}

function doDatabaseSettingsDeliveryInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `settings_delivery` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseSettingsDeliveryDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `settings_delivery` WHERE `id`='".$id_sanitize."' limit 1;");
}

function doDatabaseSettingsDeliveryUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `settings_delivery` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}


function getDatabaseSettingsDeliveryRowCount()
{

    $result = doSelectSingleDB("SELECT COUNT(*) AS rowCount FROM `settings_delivery`;");

    return ($result !== false) ? $result['rowCount'] : 0;
}
?>