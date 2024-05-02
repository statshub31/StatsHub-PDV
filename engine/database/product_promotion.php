<?php

# get + Pasta + Arquivo + Função + Dependencia
function getDatabaseProductPromotionsData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `product_promotion` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseProductPromotionProductID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductPromotionsData($id_sanitize, 'product_id');
    return ($query !== false) ? $query['product_id'] : false;
}

function getDatabaseProductPromotionCumulative($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductPromotionsData($id_sanitize, 'cumulative');
    return ($query !== false) ? $query['cumulative'] : false;
}

function getDatabaseProductPromotionType($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductPromotionsData($id_sanitize, 'promotion_id');
    return ($query !== false) ? $query['promotion_id'] : false;
}

function getDatabaseProductPromotionValue($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductPromotionsData($id_sanitize, 'value');
    return ($query !== false) ? $query['value'] : false;
}


function getDatabaseProductPromotionCreated($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductPromotionsData($id_sanitize, 'created');
    return ($query !== false) ? $query['created'] : false;
}
function getDatabaseProductPromotionCreatedBY($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductPromotionsData($id_sanitize, 'created_by');
    return ($query !== false) ? $query['created_by'] : false;
}

function getDatabaseProductPromotionExpiration($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductPromotionsData($id_sanitize, 'expiration');
    return ($query !== false) ? $query['expiration'] : false;
}


function getDatabaseProductPromotionEnd($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductPromotionsData($id_sanitize, 'end');
    return ($query !== false) ? $query['end'] : false;
}



function getDatabaseProductPromotionFinishedBy($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductPromotionsData($id_sanitize, 'finished_by');
    return ($query !== false) ? $query['finished_by'] : false;
}

function getDatabaseProductPromotionStatus($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductPromotionsData($id_sanitize, 'status');
    return ($query !== false) ? $query['status'] : false;
}



function isDatabaseProductPromotionExistIDByProductID($product_id)
{
    
    $product_id_sanitize = sanitize($product_id);

    $query = doSelectSingleDB("SELECT `id` FROM `product_promotion` WHERE `product_id`='".$product_id_sanitize."' and `status`=2;");
    return ($query !== false) ? true : false;
}

function getDatabaseProductPromotionByProductID($product_id)
{
    
    $product_id_sanitize = sanitize($product_id);

    $query = doSelectSingleDB("SELECT `id` FROM `product_promotion` WHERE `product_id`='".$product_id_sanitize."' and `status`=2;");
    return ($query !== false) ? $query['id'] : false;
}

function isDatabaseProductPromotionEnabledByProductID($product_id)
{
    
    $product_id_sanitize = sanitize($product_id);

    $query = doSelectSingleDB("SELECT `id` FROM `product_promotion` WHERE `product_id`='".$product_id_sanitize."' and `status`=2;");
    return ($query !== false) ? true : false;
}
function isDatabaseProductPromotionCumulativeEnabled($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `cumulative` FROM `product_promotion` WHERE `id`='".$id_sanitize."' and `status`=2;");
    return ($query !== false && $query['cumulative'] == 1) ? true : false;
}

function doDatabaseProductPromotionList($status = false)
{
    
    return doSelectMultiDB("SELECT `id` FROM `product_promotion`");
}

function doDatabaseProductPromotionInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `product_promotion` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseProductPromotionDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `product_promotion` WHERE `id`='".$id_sanitize."' limit 1;");
}

function doDatabaseProductPromotionUpdateByProductID($product_id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `product_promotion` SET $query_sql WHERE `product_id`='" . $product_id_sanitize . "';");
}


// 
// 
// 
// SPECIFIC
// 
// 
// 


function doDatabaseProductPromotionInsertMultipleRow($import_data_query)
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
    $query = "INSERT INTO `product_promotion` ($keys) VALUES $values";
    doInsertDB($query);
}

?>