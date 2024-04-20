<?php

# get + Pasta + Arquivo + Função + Dependencia
function getDatabaseSettingsSocialsData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `settings_social` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseSettingsSocialWhatsappStatus($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsSocialsData($id_sanitize, 'whatsapp_status');
    return ($query !== false) ? $query['whatsapp_status'] : false;
}

function getDatabaseSettingsSocialWhatsappInfo($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsSocialsData($id_sanitize, 'whatsapp_contact');
    return ($query !== false) ? $query['whatsapp_contact'] : false;
}


function isDatabaseSettingsSocialWhatsappEnabled($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsSocialsData($id_sanitize, 'whatsapp_status');
    return ($query !== false && $query == 1) ? true : false;
}


function getDatabaseSettingsSocialInstagramStatus($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsSocialsData($id_sanitize, 'instagram_status');
    return ($query !== false) ? $query['instagram_status'] : false;
}


function getDatabaseSettingsSocialInstagramInfo($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsSocialsData($id_sanitize, 'instagram_contact');
    return ($query !== false) ? $query['instagram_contact'] : false;
}

function isDatabaseSettingsSocialInstagramEnabled($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsSocialsData($id_sanitize, 'instagram_status');
    return ($query !== false && $query == 1) ? true : false;
}

function getDatabaseSettingsSocialFacebookStatus($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsSocialsData($id_sanitize, 'facebook_status');
    return ($query !== false) ? $query['facebook_status'] : false;
}
function getDatabaseSettingsSocialFacebookInfo($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsSocialsData($id_sanitize, 'facebook_contact');
    return ($query !== false) ? $query['facebook_contact'] : false;
}


function isDatabaseSettingsSocialFacebookEnabled($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseSettingsSocialsData($id_sanitize, 'facebook_status');
    return ($query !== false && $query == 1) ? true : false;
}



function doDatabaseSettingsSocialInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `settings_social` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseSettingsSocialDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `settings_social` WHERE `id`='".$id_sanitize."' limit 1;");
}

function doDatabaseSettingsSocialUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `settings_social` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}


function getDatabaseSettingsSocialRowCount()
{

    $result = doSelectSingleDB("SELECT COUNT(*) AS rowCount FROM `settings_social`;");

    return ($result !== false) ? $result['rowCount'] : 0;
}
?>