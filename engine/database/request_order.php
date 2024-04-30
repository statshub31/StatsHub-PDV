<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseRequestOrdersData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `request_order` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseRequestOrderCartID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseRequestOrdersData($id_sanitize, 'cart_id');
    return ($query !== false) ? $query['cart_id'] : false;
}
function getDatabaseRequestOrderAddressIDSelect($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseRequestOrdersData($id_sanitize, 'address_id_select');
    return ($query !== false) ? $query['address_id_select'] : false;
}

function getDatabaseRequestOrderPayIDSelect($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseRequestOrdersData($id_sanitize, 'pay_id');
    return ($query !== false) ? $query['pay_id'] : false;
}
function getDatabaseRequestOrderChangeOf($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseRequestOrdersData($id_sanitize, 'change_of');
    return ($query !== false) ? $query['change_of'] : false;
}



function getDatabaseRequestOrderStatus($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseRequestOrdersData($id_sanitize, 'status');
    return ($query !== false) ? $query['status'] : false;
}
function getDatabaseRequestOrderTicketID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseRequestOrdersData($id_sanitize, 'ticket_id_select');
    return ($query !== false) ? $query['ticket_id_select'] : false;
}


function getDatabaseRequestOrderDeliveryManID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseRequestOrdersData($id_sanitize, 'deliveryman');
    return ($query !== false) ? $query['deliveryman'] : false;
}

function isDatabaseRequestOrderExistID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `request_order` WHERE `id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}

function doDatabaseRequestOrdersList()
{
    
    return doSelectMultiDB("SELECT `id` FROM `request_order` where `status`=2 order by `id` asc;");
}

function doDatabaseRequestOrdersListAll()
{
    
    return doSelectMultiDB("SELECT `id` FROM `request_order` order by `id` desc;");
}

function doDatabaseRequestOrderInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `request_order` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseRequestOrderDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `request_order` WHERE `id`='".$id_sanitize."' limit 1;");
}

function doDatabaseRequestOrderUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `request_order` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 

function isDatabaseRequestOrderExistTitle($title)
{
    
    $title_sanitize = sanitize($title);

    $query = doSelectSingleDB("SELECT `id` FROM `request_order` WHERE `title`='".$title_sanitize."';");
    return ($query !== false) ? true : false;
}

function isDatabaseRequestOrderTitleValidation($title, $id) {
	$title_sanitize = sanitize($title);
	$id_sanitize = $id;
	
	$data = doSelectSingleDB("SELECT `id` FROM `request_order` WHERE `title`='".$title_sanitize."' AND `id`='".$id_sanitize."';");
	
	return ($data !== false) ? true : false;
}


function getDatabaseRequestOrderByCartID($cart_id) {
	$cart_id_sanitize = $cart_id;
	
	$data = doSelectSingleDB("SELECT `id` FROM `request_order` WHERE `cart_id`='".$cart_id_sanitize."';");
	
	return ($data !== false) ? $data['id'] : false;
}
?>