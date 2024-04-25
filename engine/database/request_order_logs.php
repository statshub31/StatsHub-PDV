<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseRequestOrderLogsData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `request_order_logs` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseRequestOrderLogRequestID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseRequestOrderLogsData($id_sanitize, 'request_order_id');
    return ($query !== false) ? $query['request_order_id'] : false;
}

function getDatabaseRequestOrderLogStatusDelivery($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseRequestOrderLogsData($id_sanitize, 'status_delivery');
    return ($query !== false) ? (int)$query['status_delivery'] : false;
}

function getDatabaseRequestOrderLogCreated($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseRequestOrderLogsData($id_sanitize, 'created');
    return ($query !== false) ? $query['created'] : false;
}

function isDatabaseRequestOrderLogExistID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `request_order_logs` WHERE `id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}

function doDatabaseRequestOrderLogsListByOrderID($order_id)
{
    $order_id_sanitize = $order_id;
    return doSelectMultiDB("SELECT id FROM request_order_logs where `request_order_id`='".$order_id_sanitize."' ORDER BY id DESC");
}


function doDatabaseRequestOrderLogCountRowByStatus($equals) {
    return doSelectSingleDB("SELECT COUNT(*) AS total
    FROM (
        SELECT request_order_id
        FROM request_order_logs AS r1
        GROUP BY request_order_id
        HAVING MAX(ID) = (SELECT MAX(ID) FROM request_order_logs WHERE request_order_id = r1.request_order_id)
            AND MAX(status_delivery) = $equals
            AND DATE((SELECT MAX(created) FROM request_order_logs WHERE request_order_id = r1.request_order_id)) = DATE(NOW())
    ) AS pedidos_ultimos_status_2;
    
    
    ")['total'];
}

function doDatabaseRequestOrderLogsFirstLogByOrderID($order_id)
{
    $order_id_sanitize = $order_id;
    return doSelectSingleDB("SELECT id FROM request_order_logs where `request_order_id`='".$order_id_sanitize."' ORDER BY id asc LIMIT 1")['id'];
}

function doDatabaseRequestOrderLogsLastLogByOrderID($order_id)
{
    $order_id_sanitize = $order_id;
    return doSelectSingleDB("SELECT id FROM request_order_logs where `request_order_id`='".$order_id_sanitize."' ORDER BY id DESC LIMIT 1")['id'];
}

function doDatabaseRequestOrderLogInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `request_order_logs` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseRequestOrderLogDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `request_order_logs` WHERE `id`='".$id_sanitize."'limit 1;");
}

function doDatabaseRequestOrderLogUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `request_order_logs` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 

function isDatabaseRequestOrderLogExistTitle($title)
{
    
    $title_sanitize = sanitize($title);

    $query = doSelectSingleDB("SELECT `id` FROM `request_order_logs` WHERE `title`='".$title_sanitize."';");
    return ($query !== false) ? true : false;
}

function isDatabaseRequestOrderLogTitleValidation($title, $id) {
	$title_sanitize = sanitize($title);
	$id_sanitize = $id;
	
	$data = doSelectSingleDB("SELECT `id` FROM `request_order_logs` WHERE `title`='".$title_sanitize."' AND `id`='".$id_sanitize."';");
	
	return ($data !== false) ? true : false;
}


function getDatabaseRequestOrderLogIDByOrderIDAndStatusID($order_id, $status_id) {
	$order_id_sanitize = sanitize($order_id);
	$status_id_sanitize = sanitize($status_id);
	
	$data = doSelectSingleDB("SELECT `id` FROM `request_order_logs` WHERE `request_order_id`='".$order_id_sanitize."' AND `status_delivery`='".$status_id_sanitize."';");
	
	return ($data !== false) ? $data['id'] : false;
}
?>