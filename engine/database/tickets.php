<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseTicketsData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `tickets` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseTicketUserName($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseTicketsData($id_sanitize, 'username');
    return ($query !== false) ? $query['username'] : false;
}

function getDatabaseTicketCode($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseTicketsData($id_sanitize, 'code');
    return ($query !== false) ? $query['code'] : false;
}

function getDatabaseTicketAmount($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseTicketsData($id_sanitize, 'amount');
    return ($query !== false) ? $query['amount'] : false;
}

function getDatabaseTicketAmountUsed($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseTicketsData($id_sanitize, 'amount_used');
    return ($query !== false) ? $query['amount_used'] : false;
}

function getDatabaseTicketCreated($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseTicketsData($id_sanitize, 'created');
    return ($query !== false) ? $query['created'] : false;
}

function getDatabaseTicketCreatedBy($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseTicketsData($id_sanitize, 'created_by');
    return ($query !== false) ? $query['created_by'] : false;
}

function getDatabaseTicketExpiration($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseTicketsData($id_sanitize, 'expiration');
    return ($query !== false) ? $query['expiration'] : false;
}

function getDatabaseTicketValue($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseTicketsData($id_sanitize, 'value');
    return ($query !== false) ? $query['value'] : false;
}

function getDatabaseTicketStatus($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseTicketsData($id_sanitize, 'status');
    return ($query !== false) ? $query['status'] : false;
}

function getDatabaseTicketReason($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseTicketsData($id_sanitize, 'reason');
    return ($query !== false) ? $query['reason'] : false;
}

function getDatabaseTicketEnd($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseTicketsData($id_sanitize, 'end');
    return ($query !== false) ? $query['end'] : false;
}
function getDatabaseTicketFinishedBy($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseTicketsData($id_sanitize, 'finished_by');
    return ($query !== false) ? $query['finished_by'] : false;
}


function isDatabaseTicketExistID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `tickets` WHERE `id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}

function doDatabaseTicketsList($status = false)
{
    
    return doSelectMultiDB("SELECT `id` FROM `tickets`");
}


function doDatabaseTicketInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `tickets` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseTicketDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `tickets` WHERE `id`='".$id_sanitize."'limit 1;");
}

function doDatabaseTicketUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `tickets` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 

function isDatabaseTicketEnabled($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `status` FROM `tickets` WHERE `id`='".$id_sanitize."';");
    return ($query['status'] == 2) ? true : false;
}


function isDatabaseTicketEnabledByCode($code)
{
    
    $code_sanitize = sanitize($code);

    $query = doSelectSingleDB("SELECT `status` FROM `tickets` WHERE `code`='".$code_sanitize."';");

    return ($query !== false && $query['status'] == 2) ? true : false;
}

function doDatabaseTicketsListByStatus($status = 2)
{
    $status_sanitize = $status;
    return doSelectMultiDB("SELECT `id` FROM `tickets` where `status` = $status_sanitize");
}

?>