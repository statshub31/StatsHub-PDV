<?php

# get + Pasta + Arquivo + Função + Dependencia
function getDatabaseProductFeeExemptionsData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `product_fee_exemption` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseProductFeeExemptionProductID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductFeeExemptionsData($id_sanitize, 'product_id');
    return ($query !== false) ? $query['product_id'] : false;
}

function getDatabaseProductFeeExemptionCreated($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductFeeExemptionsData($id_sanitize, 'created');
    return ($query !== false) ? $query['created'] : false;
}
function getDatabaseProductFeeExemptionCreatedBY($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductFeeExemptionsData($id_sanitize, 'created_by');
    return ($query !== false) ? $query['created_by'] : false;
}

function isDatabaseProductFeeExemptionExistID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `product_fee_exemption` WHERE `id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}

function isDatabaseProductFeeExemptionEnabledByProductID($product_id)
{
    
    $product_id_sanitize = sanitize($product_id);

    $query = doSelectSingleDB("SELECT `id` FROM `product_fee_exemption` WHERE `product_id`='".$product_id_sanitize."';");
    return ($query !== false) ? true : false;
}

function getDatabaseProductFeeExemptionByProductID($product_id)
{
    
    $product_id_sanitize = sanitize($product_id);

    $query = doSelectSingleDB("SELECT `id` FROM `product_fee_exemption` WHERE `product_id`='".$product_id_sanitize."';");
    return ($query !== false) ? $query['id'] : false;
}


function doDatabaseProductFeeExemptionList($status = false)
{
    
    return doSelectMultiDB("SELECT `id` FROM `product_fee_exemption`");
}

function doDatabaseProductFeeExemptionInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `product_fee_exemption` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseProductFeeExemptionDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `product_fee_exemption` WHERE `id`='".$id_sanitize."' limit 1;");
}

function doDatabaseProductFeeExemptionUpdateByProductID($product_id, $import_data_query, $empty = true)
{

    
    $product_id_sanitize = sanitize($product_id);
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
    doUpdateDB("UPDATE `product_fee_exemption` SET $query_sql WHERE `product_id`='" . $product_id_sanitize . "';");
}


// 
// 
// 
// SPECIFIC
// 
// 
// 


function doDatabaseProductFeeExemptionInsertMultipleRow($import_data_query)
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
    $query = "INSERT INTO `product_fee_exemption` ($keys) VALUES $values";
    doInsertDB($query);
}

function doDatabaseProductFeeExemptionDeleteByProductID($product_id)
{
    
    $product_id_sanitize = sanitize($product_id);

    doDeleteDB("DELETE FROM `product_fee_exemption` WHERE `product_id`='".$product_id_sanitize."' limit 1;");
}

?>