<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseProductsComplementsData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `products_complements` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseProductComplementProductID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsComplementsData($id_sanitize, 'product_id');
    return ($query !== false) ? $query['product_id'] : false;
}


function getDatabaseProductComplementComplementID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsComplementsData($id_sanitize, 'complement_id');
    return ($query !== false) ? $query['complement_id'] : false;
}


function isDatabaseProductComplementExistID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `products_complements` WHERE `id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}


function doDatabaseProductsComplementsListByProductID($product_id, $status = false)
{
    $sanitize_product_id = sanitize($product_id);

    return doSelectMultiDB("SELECT `id` FROM `products_complements` where `product_id` = '".$sanitize_product_id."'");
}

function doDatabaseProductComplementInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `products_complements` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseProductComplementDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `products_complements` WHERE `id`='".$id_sanitize."'limit 1;");
}

function doDatabaseProductComplementUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `products_complements` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 

function doDatabaseProductComplementInsertMultipleRow($import_data_query)
{
    // Remove todos os campos vazios
    removeEmptyValues($import_data_query);

    // Transforma as chaves em array
    $keys = array_keys($import_data_query[0]);

    // Transforma os valores em arrays separados
    $values = array_map(function ($item) {
        return '(\'' . implode('\',\'', $item) . '\')';
    }, $import_data_query);

    // Converte para o formato MySQL
    $keys = doMysqlConvertArrayKey($keys);
    $values = implode(', ', $values);

    // Monta a query de inserção
    $query = "INSERT INTO `products_complements` ($keys) VALUES $values";
    doInsertDB($query);
}


function doDatabaseProductComplementTruncateByProductID($product_id)
{
    
    $product_id_sanitize = sanitize($product_id);

    doDeleteDB("DELETE FROM `products_complements` WHERE `product_id`='".$product_id_sanitize."';");
}


function isDatabaseProductComplementValidation($product_id, $complement_id) {
	$product_sanitize = sanitize($product_id);
	$complement_sanitize = sanitize($complement_id);
	
	$data = doSelectSingleDB("SELECT `id` FROM `products_complements` WHERE `product_id`='".$product_sanitize."' AND `complement_id`='".$complement_sanitize."';");
	
	return ($data !== false) ? true : false;
}

function getDatabaseProductComplementowCountByProductID($product_id)
{
    $product_id_sanitize = sanitize($product_id);

    $result = doSelectSingleDB("SELECT COUNT(*) AS rowCount FROM `products_complements` where `product_id` = '".$product_id_sanitize."';");

    return ($result !== false) ? $result['rowCount'] : 0;
}
?>