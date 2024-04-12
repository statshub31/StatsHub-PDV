<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseComplementsData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `complements` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseComplementCode($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseComplementsData($id_sanitize, 'code');
    return ($query !== false) ? $query['code'] : false;
}

function getDatabaseComplementCategoryID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseComplementsData($id_sanitize, 'category_id');
    return ($query !== false) ? $query['category_id'] : false;
}

function getDatabaseComplementDescription($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseComplementsData($id_sanitize, 'description');
    return ($query !== false) ? $query['description'] : false;
}

function getDatabaseComplementCreated($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseComplementsData($id_sanitize, 'created');
    return ($query !== false) ? $query['created'] : false;
}

function getDatabaseComplementCreatedBy($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseComplementsData($id_sanitize, 'created_by');
    return ($query !== false) ? $query['created_by'] : false;
}

function getDatabaseComplementstatus($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseComplementsData($id_sanitize, 'status');
    return ($query !== false) ? $query['status'] : false;
}

function isDatabaseComplementExistID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `complements` WHERE `id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}

function doDatabaseComplementsList($status = false)
{
    
    return doSelectMultiDB("SELECT `id` FROM `complements`");
}

function doDatabaseComplementInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `complements` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseComplementDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `complements` WHERE `id`='".$id_sanitize."'limit 1;");
}

function doDatabaseComplementUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `complements` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 

function isDatabaseComplementEnabled($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `status` FROM `complements` WHERE `id`='".$id_sanitize."';");
    return ($query['status'] == 2) ? true : false;
}


function isDatabaseComplementEnabledByCode($code)
{
    
    $code_sanitize = sanitize($code);

    $query = doSelectSingleDB("SELECT `status` FROM `complements` WHERE `code`='".$code_sanitize."';");

    return ($query !== false && $query['status'] == 2) ? true : false;
}


function isDatabaseComplementValidationCode($code, $id) {
	$code_sanitize = sanitize($code);
	$id_sanitize = $id;
	
	$data = doSelectSingleDB("SELECT `id` FROM `complements` WHERE `code`='".$code_sanitize."' AND `id`='".$id_sanitize."';");
	
	return ($data !== false) ? true : false;
}

?>