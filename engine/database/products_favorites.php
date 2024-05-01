<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseProductsFavoritesData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `products_favorites` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseProductFavoriteProductID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsFavoritesData($id_sanitize, 'product_id');
    return ($query !== false) ? $query['product_id'] : false;
}

function getDatabaseProductFavoriteUserID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsFavoritesData($id_sanitize, 'user_id');
    return ($query !== false) ? $query['user_id'] : false;
}

function getDatabaseProductFavoriteExistIDByUserAndProductID($product_id, $user_id)
{
    $product_id_sanitize = sanitize($product_id);
    $user_id_sanitize = sanitize($user_id);

    $query = doSelectSingleDB("SELECT `id` FROM `products_favorites` WHERE `product_id`='".$product_id_sanitize."' and `user_id`='".$user_id_sanitize."';");
    return ($query !== false) ? $query['id'] : false;
}


function isDatabaseProductFavoriteExistIDByUserAndProductID($product_id, $user_id)
{
    $product_id_sanitize = sanitize($product_id);
    $user_id_sanitize = sanitize($user_id);

    $query = doSelectSingleDB("SELECT `id` FROM `products_favorites` WHERE `product_id`='".$product_id_sanitize."' and `user_id`='".$user_id_sanitize."';");
    return ($query !== false) ? true : false;
}

function doDatabaseProductsFavoritesListByUserID($user_id)
{
    $user_id_sanitize = $user_id;
    return doSelectMultiDB("SELECT `id` FROM `products_favorites` where `user_id` = $user_id_sanitize");
}

function doDatabaseProductFavoriteInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `products_favorites` (" . $keys . ") VALUES (" . $values . ")");
}
function doDatabaseProductFavoriteDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `products_favorites` WHERE `id`='".$id_sanitize."' limit 1;");
}

function doDatabaseProductFavoriteUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `products_favorites` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 

function isDatabaseProductFavoriteExistTitle($title)
{
    
    $title_sanitize = sanitize($title);

    $query = doSelectSingleDB("SELECT `id` FROM `products_favorites` WHERE `title`='".$title_sanitize."';");
    return ($query !== false) ? true : false;
}

function isDatabaseProductFavorite($title, $id) {
	$title_sanitize = sanitize($title);
	$id_sanitize = $id;
	
	$data = doSelectSingleDB("SELECT `id` FROM `products_favorites` WHERE `title`='".$title_sanitize."' AND `id`='".$id_sanitize."';");
	
	return ($data !== false) ? true : false;
}
?>