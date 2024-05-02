<?php

# get + Pasta + Arquivo + Função + Dependencia
function getDatabaseSettingsHorarysData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `settings_horary` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseSettingsHoraryDayEnabled($id, $n)
{
    $day = array(
        1 => 'monday',
        2 => 'tuesday',
        3 => 'wednesday',
        4 => 'thursday',
        5 => 'friday',
        6 => 'saturday',
        7 => 'sunday'
    );

    $id_sanitize = sanitize($id);
    $string = $day[$n].'_status';

    $query = getDatabaseSettingsHorarysData($id_sanitize, $string);

    return ($query !== false && $query[$string] == 1) ? true : false;
}

function getDatabaseSettingsHoraryDayStart($id, $n)
{
    $day = array(
        1 => 'monday',
        2 => 'tuesday',
        3 => 'wednesday',
        4 => 'thursday',
        5 => 'friday',
        6 => 'saturday',
        7 => 'sunday'
    ); 

    $id_sanitize = sanitize($id);
    $string = $day[$n].'_start';

    $query = getDatabaseSettingsHorarysData($id_sanitize, $string);
    return ($query !== false) ? $query[$string] : false;
}

function getDatabaseSettingsHoraryDayEnd($id, $n)
{
    $day = array(
        1 => 'monday',
        2 => 'tuesday',
        3 => 'wednesday',
        4 => 'thursday',
        5 => 'friday',
        6 => 'saturday',
        7 => 'sunday'
    );

    $id_sanitize = sanitize($id);

    $string = $day[$n].'_end';
    $query = getDatabaseSettingsHorarysData($id_sanitize, $string);
    return ($query !== false) ? $query[$string] : false;
}


function getDatabaseSettingsHoraryDescription($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsHorarysData($id_sanitize, 'description');
    return ($query !== false) ? $query['description'] : false;
}

function doDatabaseSettingsHoraryInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `settings_horary` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseSettingsHoraryDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `settings_horary` WHERE `id`='".$id_sanitize."' limit 1;");
}

function doDatabaseSettingsHoraryUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `settings_horary` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}


function getDatabaseSettingsHoraryRowCount()
{

    $result = doSelectSingleDB("SELECT COUNT(*) AS rowCount FROM `settings_horary`;");

    return ($result !== false) ? $result['rowCount'] : 0;
}
?>