<?php

# get + Pasta + Arquivo + Função + Dependencia
function getDatabaseGroupsData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `groups` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseGroupTitle($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseGroupsData($id_sanitize, 'title');
    return ($query !== false) ? $query['title'] : false;
}

function getDatabaseGroupAccess($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseGroupsData($id_sanitize, 'access');
    return ($query !== false) ? $query['access'] : false;
}


function isDatabaseGroupExistID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `groups` WHERE `id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}

function doDatabaseGroupList($status = false)
{
    
    return doSelectMultiDB("SELECT `id` FROM `groups`");
}

function doDatabaseGroupInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `groups` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseGroupDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `groups` WHERE `id`='".$id_sanitize."' limit 1;");
}

function doDatabaseGroupUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `groups` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

?>