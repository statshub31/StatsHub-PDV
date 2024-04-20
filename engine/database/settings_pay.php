<?php

# get + Pasta + Arquivo + Função + Dependencia
function getDatabaseSettingsPaysData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `settings_pay` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseSettingsPayMoney($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsPaysData($id_sanitize, 'money');
    return ($query !== false) ? $query['money'] : false;
}
function isDatabaseSettingsPayMoneyEnabled($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsPaysData($id_sanitize, 'money');
    return ($query !== false && $query['money'] == 1) ? true : false;
}
function getDatabaseSettingsPayCredit($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsPaysData($id_sanitize, 'credit');
    return ($query !== false) ? $query['credit'] : false;
}
function isDatabaseSettingsPayCreditEnabled($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsPaysData($id_sanitize, 'credit');
    return ($query !== false && $query['credit'] == 1) ? true : false;
}
function getDatabaseSettingsPayDebit($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsPaysData($id_sanitize, 'debit');
    return ($query !== false) ? $query['debit'] : false;
}
function isDatabaseSettingsPayDebitEnabled($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsPaysData($id_sanitize, 'debit');
    return ($query !== false && $query['debit'] == 1) ? true : false;
}

function getDatabaseSettingsPayPix($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsPaysData($id_sanitize, 'pix');
    return ($query !== false) ? $query['pix'] : false;
}
function isDatabaseSettingsPayPixEnabled($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsPaysData($id_sanitize, 'pix');
    return ($query !== false && $query['pix'] == 1) ? true : false;
}

function getDatabaseSettingsPayPixKey($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsPaysData($id_sanitize, 'pix_key');
    return ($query !== false) ? $query['pix_key'] : false;
}






function doDatabaseSettingsPayInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `settings_pay` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseSettingsPayDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `settings_pay` WHERE `id`='".$id_sanitize."' limit 1;");
}

function doDatabaseSettingsPayUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `settings_pay` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}


function getDatabaseSettingsPayRowCount()
{

    $result = doSelectSingleDB("SELECT COUNT(*) AS rowCount FROM `settings_pay`;");

    return ($result !== false) ? $result['rowCount'] : 0;
}
?>