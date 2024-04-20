<?php

# get + Pasta + Arquivo + Função + Dependencia
function getDatabaseSettingsInfosData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `settings_info` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseSettingsInfoTitle($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsInfosData($id_sanitize, 'title');
    return ($query !== false) ? $query['title'] : false;
}

function getDatabaseSettingsInfoDescription($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsInfosData($id_sanitize, 'description');
    return ($query !== false) ? $query['description'] : false;
}

function doDatabaseSettingsInfoInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `settings_info` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseSettingsInfoDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `settings_info` WHERE `id`='".$id_sanitize."' limit 1;");
}

function doDatabaseSettingsInfoUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `settings_info` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}


function getDatabaseSettingsInfoRowCount()
{

    $result = doSelectSingleDB("SELECT COUNT(*) AS rowCount FROM `settings_info`;");

    return ($result !== false) ? $result['rowCount'] : 0;
}
?>