<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseUserSelectsData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `user_select` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseUserSelectAddressID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseUserSelectsData($id_sanitize, 'address_id');
    return ($query !== false) ? $query['address_id'] : false;
}

function getDatabaseUserSelectUserID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseUserSelectsData($id_sanitize, 'user_id');
    return ($query !== false) ? $query['user_id'] : false;
}

function getDatabaseUserSelectPayID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseUserSelectsData($id_sanitize, 'pay_id');
    return ($query !== false) ? $query['pay_id'] : false;
}

function getDatabaseUserSelectAddressByUserID($user_id)
{
    
    $user_id_sanitize = sanitize($user_id);

    $query = doSelectSingleDB("SELECT `address_id` FROM `user_select` WHERE `user_id`='".$user_id_sanitize."';");
    return ($query !== false && $query['address_id'] != '') ? $query['address_id'] : false;
}
function isDatabaseUserSelectAddressByUserID($user_id)
{
    
    $user_id_sanitize = sanitize($user_id);

    $query = doSelectSingleDB("SELECT `address_id` FROM `user_select` WHERE `user_id`='".$user_id_sanitize."';");
    return ($query !== false && $query['address_id'] != '') ? true : false;
}

function getDatabaseUserSelectByUserID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `user_select` WHERE `user_id`='".$id_sanitize."';");
    return ($query !== false) ? $query['id'] : false;
}

function isDatabaseUserSelectByUserID($user_id)
{
    
    $user_id_sanitize = sanitize($user_id);

    $query = doSelectSingleDB("SELECT `id` FROM `user_select` WHERE `user_id`='".$user_id_sanitize."';");
    return ($query !== false) ? true : false;
}


function doDatabaseUserSelectsList($status = false)
{
    
    return doSelectMultiDB("SELECT `id` FROM `user_select`");
}

function doDatabaseUserSelectInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `user_select` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseUserSelectDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `user_select` WHERE `id`='".$id_sanitize."'limit 1;");
}

function doDatabaseUserSelectUpdate($id, $import_data_query, $empty = true, $null = false)
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
    $query_sql = doMysqlConvertUpdateArray($keyArray, $valueArray, $null);
    
    doUpdateDB("UPDATE `user_select` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 

function isDatabaseUserSelectExistTitle($title)
{
    
    $title_sanitize = sanitize($title);

    $query = doSelectSingleDB("SELECT `id` FROM `user_select` WHERE `title`='".$title_sanitize."';");
    return ($query !== false) ? true : false;
}

function isDatabaseUserSelectTitleValidation($title, $id) {
	$title_sanitize = sanitize($title);
	$id_sanitize = $id;
	
	$data = doSelectSingleDB("SELECT `id` FROM `user_select` WHERE `title`='".$title_sanitize."' AND `id`='".$id_sanitize."';");
	
	return ($data !== false) ? true : false;
}
?>