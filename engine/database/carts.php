<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseCartsData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `carts` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseCartUserID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseCartsData($id_sanitize, 'user_id');
    return ($query !== false) ? $query['user_id'] : false;
}

function getDatabaseCartStatus($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseCartsData($id_sanitize, 'status');
    return ($query !== false) ? $query['status'] : false;
}

function getDatabaseCartCreated($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseCartsData($id_sanitize, 'created');
    return ($query !== false) ? $query['created'] : false;
}

function getDatabaseCartExistIDByUserID($user_id)
{
    
    $user_id_sanitize = sanitize($user_id);

    $query = doSelectSingleDB("SELECT `id` FROM `carts` WHERE `user_id`='".$user_id_sanitize."' and `status`=2;");
    return ($query !== false) ? $query['id'] : false;
}


function isDatabaseCartExistIDByUserID($user_id)
{
    
    $user_id_sanitize = sanitize($user_id);

    $query = doSelectSingleDB("SELECT `id` FROM `carts` WHERE `user_id`='".$user_id_sanitize."' and `status`=2;");
    return ($query !== false) ? true : false;
}

function isDatabaseCartEnabled($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `carts` WHERE `id`='".$id_sanitize."' and `status`=2;");
    return ($query !== false) ? true : false;
}


function doDatabaseCartsListEnabled()
{
    
    return doSelectMultiDB("SELECT `id` FROM `carts` where `status`=2");
}

function doDatabaseCartsListByUserID($user_id)
{
    $user_id_sanitize = sanitize($user_id);
    
    return doSelectMultiDB("SELECT `id` FROM `carts` where `user_id` = '".$user_id_sanitize."' and `status`=2;");
}

function doDatabaseCartsListByUserIDAllStatus($user_id)
{
    $user_id_sanitize = $user_id;
    return doSelectMultiDB("SELECT `id` FROM `carts` where `user_id`='".$user_id_sanitize."' order by `id` desc;");
}

function doDatabaseCartInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `carts` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseCartDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `carts` WHERE `id`='".$id_sanitize."'limit 1;");
}

function doDatabaseCartUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `carts` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 

function isDatabaseCartExistTitle($title)
{
    
    $title_sanitize = sanitize($title);

    $query = doSelectSingleDB("SELECT `id` FROM `carts` WHERE `title`='".$title_sanitize."';");
    return ($query !== false) ? true : false;
}

function isDatabaseCartTitleValidation($title, $id) {
	$title_sanitize = sanitize($title);
	$id_sanitize = $id;
	
	$data = doSelectSingleDB("SELECT `id` FROM `carts` WHERE `title`='".$title_sanitize."' AND `id`='".$id_sanitize."';");
	
	return ($data !== false) ? true : false;
}

function isDatabaseCartUserValidation($user_id, $id) {
	$user_id_sanitize = sanitize($user_id);
	$id_sanitize = $id;
	
	$data = doSelectSingleDB("SELECT `id` FROM `carts` WHERE `user_id`='".$user_id_sanitize."' AND `id`='".$id_sanitize."';");
	
	return ($data !== false) ? true : false;
}
?>