<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseAddressUserSelectsData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `address_user_select` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseAddressUserSelectAddressID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAddressUserSelectsData($id_sanitize, 'address_id');
    return ($query !== false) ? $query['address_id'] : false;
}

function getDatabaseAddressUserSelectUserID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAddressUserSelectsData($id_sanitize, 'user_id');
    return ($query !== false) ? $query['user_id'] : false;
}

function getDatabaseAddressUserSelectAddressByUserID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `address_id` FROM `address_user_select` WHERE `user_id`='".$id_sanitize."';");
    return ($query !== false) ? $query['address_id'] : false;
}

function getDatabaseAddressUserSelectByUserID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `address_user_select` WHERE `user_id`='".$id_sanitize."';");
    return ($query !== false) ? $query['id'] : false;
}

function isDatabaseAddressUserSelectByUserID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `address_user_select` WHERE `user_id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}


function doDatabaseAddressUserSelectsList($status = false)
{
    
    return doSelectMultiDB("SELECT `id` FROM `address_user_select`");
}

function doDatabaseAddressUserSelectInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `address_user_select` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseAddressUserSelectDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `address_user_select` WHERE `id`='".$id_sanitize."'limit 1;");
}

function doDatabaseAddressUserSelectUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `address_user_select` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 

function isDatabaseAddressUserSelectExistTitle($title)
{
    
    $title_sanitize = sanitize($title);

    $query = doSelectSingleDB("SELECT `id` FROM `address_user_select` WHERE `title`='".$title_sanitize."';");
    return ($query !== false) ? true : false;
}

function isDatabaseAddressUserSelectTitleValidation($title, $id) {
	$title_sanitize = sanitize($title);
	$id_sanitize = $id;
	
	$data = doSelectSingleDB("SELECT `id` FROM `address_user_select` WHERE `title`='".$title_sanitize."' AND `id`='".$id_sanitize."';");
	
	return ($data !== false) ? true : false;
}
?>