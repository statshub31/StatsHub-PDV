<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseCartProductComplementsData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `cart_product_complements` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseCartProductComplementCartID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseCartProductComplementsData($id_sanitize, 'cart_id');
    return ($query !== false) ? $query['cart_id'] : false;
}

function getDatabaseCartProductComplementProductID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseCartProductComplementsData($id_sanitize, 'cart_product_id');
    return ($query !== false) ? $query['cart_product_id'] : false;
}


function getDatabaseCartProductComplementComplementID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseCartProductComplementsData($id_sanitize, 'complement_id');
    return ($query !== false) ? $query['complement_id'] : false;
}

function getDatabaseCartProductComplementByCartProductID($cart_product_id)
{
    
    $cart_product_id_sanitize = sanitize($cart_product_id);
    $query = doSelectSingleDB("SELECT `id` FROM `cart_product_complements` WHERE `cart_product_id`='".$cart_product_id_sanitize."';");
    return ($query !== false) ? $query['id'] : false;
}

function doDatabaseCartProductComplementByCartProductID($cart_product_id)
{
    
    $cart_product_id_sanitize = sanitize($cart_product_id);
    $query = doSelectSingleDB("SELECT `id` FROM `cart_product_complements` WHERE `cart_product_id`='".$cart_product_id_sanitize."';");
    return ($query !== false) ? true : false;
}

function doDatabaseCartProductComplementsList($status = false)
{
    
    return doSelectMultiDB("SELECT `id` FROM `cart_product_complements`");
}

function doDatabaseCartProductComplementInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `cart_product_complements` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseCartProductComplementDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `cart_product_complements` WHERE `id`='".$id_sanitize."' limit 1;");
}

function doDatabaseCartProductComplementUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `cart_product_complements` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 

?>