<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseCartTicketSelectsData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `cart_ticket_select` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseCartTicketSelectTicketID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseCartTicketSelectsData($id_sanitize, 'ticket_id');
    return ($query !== false) ? $query['ticket_id'] : false;
}

function getDatabaseCartTicketSelectCartID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseCartTicketSelectsData($id_sanitize, 'cart_id');
    return ($query !== false) ? $query['cart_id'] : false;
}

function isDatabaseCartTicketSelectUsed($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseCartTicketSelectsData($id_sanitize, 'used');
    return ($query !== false && $query['used'] == 1) ? true : false;
}

function isDatabaseCartTicketSelectNotUsed($ticket_id, $cart_id)
{
    
    $ticket_id_sanitize = sanitize($ticket_id);
    $cart_id_sanitize = sanitize($cart_id);

    $query = doSelectSingleDB("SELECT `id` FROM `cart_ticket_select` WHERE `cart_id`='".$cart_id_sanitize."' and `ticket_id`='".$ticket_id_sanitize."' and `used`=0;");
    
    return ($query !== false) ? true : false;
}

function getDatabaseCartTicketSelectByCartID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `cart_ticket_select` WHERE `cart_id`='".$id_sanitize."';");
    return ($query !== false) ? $query['id'] : false;
}

function isDatabaseCartTicketSelectByCartID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `cart_ticket_select` WHERE `cart_id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}


function doDatabaseCartTicketSelectsList($status = false)
{
    
    return doSelectMultiDB("SELECT `id` FROM `cart_ticket_select`");
}

function doDatabaseCartTicketSelectInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `cart_ticket_select` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseCartTicketSelectDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `cart_ticket_select` WHERE `id`='".$id_sanitize."'limit 1;");
}

function doDatabaseCartTicketSelectUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `cart_ticket_select` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 

function isDatabaseCartTicketSelectExistTitle($title)
{
    
    $title_sanitize = sanitize($title);

    $query = doSelectSingleDB("SELECT `id` FROM `cart_ticket_select` WHERE `title`='".$title_sanitize."';");
    return ($query !== false) ? true : false;
}

function isDatabaseCartTicketSelectTitleValidation($title, $id) {
	$title_sanitize = sanitize($title);
	$id_sanitize = $id;
	
	$data = doSelectSingleDB("SELECT `id` FROM `cart_ticket_select` WHERE `title`='".$title_sanitize."' AND `id`='".$id_sanitize."';");
	
	return ($data !== false) ? true : false;
}
?>