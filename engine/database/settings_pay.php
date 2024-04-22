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

function getDatabaseSettingsPayType($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsPaysData($id_sanitize, 'type');
    return ($query !== false) ? $query['type'] : false;
}

function getDatabaseSettingsPayKey($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsPaysData($id_sanitize, 'pay_key');
    return ($query !== false) ? $query['pay_key'] : false;
}
function getDatabaseSettingsPayDisabled($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsPaysData($id_sanitize, 'disabled');
    return ($query !== false) ? $query['disabled'] : false;
}


function getDatabaseSettingsPayByType($type)
{
    
    $type_sanitize = sanitize($type);

    $query = doSelectSingleDB("SELECT `id` FROM `settings_pay` WHERE `type`='".$type_sanitize."';");

    return ($query !== false) ? $query['id'] : false;
}


function getDatabaseSettingsPayMoney()
{
    $query = getDatabaseSettingsPayByType('Dinheiro');

    return ($query !== false) ? $query : false;
}

function isDatabaseSettingsPayMoneyEnabled()
{
    $type_id = (int)getDatabaseSettingsPayMoney();
    
    $query = doSelectSingleDB("SELECT `disabled` FROM `settings_pay` WHERE `id`=$type_id;");
    return ($query !== false && $query['disabled'] == 0) ? true : false;
}

function getDatabaseSettingsPayCredit()
{
    
    $query = getDatabaseSettingsPayByType('Crédito');

    return ($query !== false) ? $query : false;
}
function isDatabaseSettingsPayCreditEnabled()
{
    
    $type_id = getDatabaseSettingsPayCredit();

    $query = doSelectSingleDB("SELECT `disabled` FROM `settings_pay` WHERE `id`=$type_id;");
    return ($query !== false && $query['disabled'] == 0) ? true : false;
}
function getDatabaseSettingsPayDebit()
{
    $query = getDatabaseSettingsPayByType('Débito');

    return ($query !== false) ? $query : false;
}

function isDatabaseSettingsPayDebitEnabled()
{
    
    $type_id = getDatabaseSettingsPayDebit();

    $query = doSelectSingleDB("SELECT `disabled` FROM `settings_pay` WHERE `id`=$type_id;");
    return ($query !== false && $query['disabled'] == 0) ? true : false;
}

function getDatabaseSettingsPayPix()
{
    $query = getDatabaseSettingsPayByType('Pix');

    return ($query !== false) ? $query : false;
}

function isDatabaseSettingsPayPixEnabled()
{
    $type_id = getDatabaseSettingsPayPix();

    $query = doSelectSingleDB("SELECT `disabled` FROM `settings_pay` WHERE `id`=$type_id;");
    return ($query !== false && $query['disabled'] == 0) ? true : false;
}

function isDatabaseSettingsPayExist($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `settings_pay` WHERE `id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}


function doDatabaseSettingsPayListByStatus($status = 0)
{
    
    return doSelectMultiDB("SELECT `id` FROM `settings_pay`");
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