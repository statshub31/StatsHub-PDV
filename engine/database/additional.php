<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseAdditionalData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `additional` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseAdditionalCode($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAdditionalData($id_sanitize, 'code');
    return ($query !== false) ? $query['code'] : false;
}

function getDatabaseAdditionalCategoryID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAdditionalData($id_sanitize, 'category_id');
    return ($query !== false) ? $query['category_id'] : false;
}

function getDatabaseAdditionalCostPrice($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAdditionalData($id_sanitize, 'cost_price');
    return ($query !== false) ? $query['cost_price'] : false;
}
function getDatabaseAdditionalSalePrice($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAdditionalData($id_sanitize, 'sale_price');
    return ($query !== false) ? $query['sale_price'] : false;
}


function getDatabaseAdditionalDescription($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAdditionalData($id_sanitize, 'description');
    return ($query !== false) ? $query['description'] : false;
}

function getDatabaseAdditionalCreated($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAdditionalData($id_sanitize, 'created');
    return ($query !== false) ? $query['created'] : false;
}

function getDatabaseAdditionalCreatedBy($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAdditionalData($id_sanitize, 'created_by');
    return ($query !== false) ? $query['created_by'] : false;
}

function getDatabaseAdditionalStatus($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAdditionalData($id_sanitize, 'status');
    return ($query !== false) ? $query['status'] : false;
}

function isDatabaseAdditionalExistID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `additional` WHERE `id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}

function doDatabaseAdditionalList($status = false)
{
    
    return doSelectMultiDB("SELECT `id` FROM `additional`");
}

function doDatabaseAdditionalInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `additional` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseAdditionalDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `additional` WHERE `id`='".$id_sanitize."'limit 1;");
}

function doDatabaseAdditionalUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `additional` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 

function isDatabaseAdditionalEnabled($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `status` FROM `additional` WHERE `id`='".$id_sanitize."';");
    return ($query['status'] == 2) ? true : false;
}


function isDatabaseAdditionalEnabledByCode($code)
{
    
    $code_sanitize = sanitize($code);

    $query = doSelectSingleDB("SELECT `status` FROM `additional` WHERE `code`='".$code_sanitize."';");

    return ($query !== false && $query['status'] == 2) ? true : false;
}


function isDatabaseAdditionalValidationCode($code, $id) {
	$code_sanitize = sanitize($code);
	$id_sanitize = $id;
	
	$data = doSelectSingleDB("SELECT `id` FROM `additional` WHERE `code`='".$code_sanitize."' AND `id`='".$id_sanitize."';");
	
	return ($data !== false) ? true : false;
}

?>