<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseCartProductsData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `cart_products` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseCartProductCartID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseCartProductsData($id_sanitize, 'cart_id');
    return ($query !== false) ? $query['cart_id'] : false;
}

function getDatabaseCartProductProductID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseCartProductsData($id_sanitize, 'product_id');
    return ($query !== false) ? $query['product_id'] : false;
}


function getDatabaseCartProductAmount($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseCartProductsData($id_sanitize, 'amount');
    return ($query !== false) ? $query['amount'] : false;
}


function getDatabaseCartProductPriceID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseCartProductsData($id_sanitize, 'product_price_id');
    return ($query !== false) ? $query['product_price_id'] : false;
}


function getDatabaseCartProductObservation($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseCartProductsData($id_sanitize, 'observation');
    return ($query !== false) ? $query['observation'] : false;
}


function isDatabaseCartProductExistIDByUserID($user_id)
{
    
    $user_id_sanitize = sanitize($user_id);

    $query = doSelectSingleDB("SELECT `id` FROM `cart_products` WHERE `user_id`='".$user_id_sanitize."';");
    return ($query !== false) ? true : false;
}

function isDatabaseCartProductExistID($id)
{
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `cart_products` WHERE `id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}

function doDatabaseCartProductsListByCartID($cart_id)
{
    $cart_id_sanitize = $cart_id;
    return doSelectMultiDB("SELECT `id` FROM `cart_products` where `cart_id` = '".$cart_id_sanitize."'");
}

function doDatabaseCartProductInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `cart_products` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseCartProductDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `cart_products` WHERE `id`='".$id_sanitize."'limit 1;");
}

function doDatabaseCartProductUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `cart_products` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 

function getDatabaseCartProductRowCountByCartID($cart_id)
{
    $cart_id_sanitize = sanitize($cart_id);

    $result = doSelectSingleDB("SELECT COUNT(*) AS rowCount FROM `cart_products` where `cart_id` = '".$cart_id_sanitize."';");

    return ($result !== false) ? $result['rowCount'] : 0;
}


function getDatabaseCartProductExistIDByCartAndProductID($cart_product_id, $product_id)
{
    
    $cart_product_id_sanitize = sanitize($cart_product_id);
    $product_id_sanitize = sanitize($product_id);

    $query = doSelectSingleDB("SELECT `id` FROM `cart_products` WHERE `id`='".$cart_product_id_sanitize."' and `product_id`='".$product_id_sanitize."';");
    return ($query !== false) ? $query['id'] : false;
}

?>