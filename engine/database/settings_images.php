<?php

# get + Pasta + Arquivo + Função + Dependencia
function getDatabaseSettingsImagesData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `settings_images` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseSettingsImageIconName($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsImagesData($id_sanitize, 'icon_name');
    return ($query !== false) ? $query['icon_name'] : false;
}

function getDatabaseSettingsImageBackgroundName($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsImagesData($id_sanitize, 'background_name');
    return ($query !== false) ? $query['background_name'] : false;
}
function getDatabaseSettingsImageLogoName($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsImagesData($id_sanitize, 'logo_name');
    return ($query !== false) ? $query['logo_name'] : false;
}
function getDatabaseSettingsImageLoginName($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsImagesData($id_sanitize, 'login_name');
    return ($query !== false) ? $query['login_name'] : false;
}

function doDatabaseSettingsImageInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `settings_images` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseSettingsImageDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `settings_images` WHERE `id`='".$id_sanitize."' limit 1;");
}

function doDatabaseSettingsImageUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `settings_images` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}


function getDatabaseSettingsImageRowCount()
{

    $result = doSelectSingleDB("SELECT COUNT(*) AS rowCount FROM `settings_images`;");

    return ($result !== false) ? $result['rowCount'] : 0;
}
?>