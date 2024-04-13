<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseProductsData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `products` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseProductCode($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsData($id_sanitize, 'code');
    return ($query !== false) ? $query['code'] : false;
}

function getDatabaseProductCategoryID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsData($id_sanitize, 'category_id');
    return ($query !== false) ? $query['category_id'] : false;
}

function getDatabaseProductName($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsData($id_sanitize, 'name');
    return ($query !== false) ? $query['name'] : false;
}

function getDatabaseProductDescription($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsData($id_sanitize, 'description');
    return ($query !== false) ? $query['description'] : false;
}

function getDatabaseProductPhotoName($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsData($id_sanitize, 'photo');
    return ($query !== false) ? $query['photo'] : false;
}

function getDatabaseProductCreated($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsData($id_sanitize, 'created');
    return ($query !== false) ? $query['created'] : false;
}

function getDatabaseProductPriceDistinct($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsData($id_sanitize, 'price_distinct');
    return ($query !== false) ? $query['price_distinct'] : false;
}


function getDatabaseProductCreatedBy($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsData($id_sanitize, 'created_by');
    return ($query !== false) ? $query['created_by'] : false;
}

function getDatabaseProductsStatus($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsData($id_sanitize, 'status');
    return ($query !== false) ? $query['status'] : false;
}

function getDatabaseProductsStockStatus($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsData($id_sanitize, 'stock_status');
    return ($query !== false) ? $query['stock_status'] : false;
}

function isDatabaseProductStockEnabled($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `stock_status` FROM `products` WHERE `id`='".$id_sanitize."';");
    return ($query !== false && $query['stock_status'] == 1) ? true : false;
}


function isDatabaseProductExistID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `products` WHERE `id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}

function doDatabaseProductsList($status = false)
{
    
    return doSelectMultiDB("SELECT `id` FROM `products`");
}

function doDatabaseProductInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `products` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseProductDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `products` WHERE `id`='".$id_sanitize."'limit 1;");
}

function doDatabaseProductUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `products` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 

function isDatabaseProductEnabled($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `status` FROM `products` WHERE `id`='".$id_sanitize."';");
    return ($query['status'] == 2) ? true : false;
}


function isDatabaseProductEnabledByCode($code)
{
    
    $code_sanitize = sanitize($code);

    $query = doSelectSingleDB("SELECT `status` FROM `products` WHERE `code`='".$code_sanitize."';");

    return ($query !== false && $query['status'] == 2) ? true : false;
}


function isDatabaseProductValidationCode($code, $id) {
	$code_sanitize = sanitize($code);
	$id_sanitize = $id;
	
	$data = doSelectSingleDB("SELECT `id` FROM `products` WHERE `code`='".$code_sanitize."' AND `id`='".$id_sanitize."';");
	
	return ($data !== false) ? true : false;
}


?>