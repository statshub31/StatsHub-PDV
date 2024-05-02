<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseAddressData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `address` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseAddressUserID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAddressData($id_sanitize, 'user_id');
    return ($query !== false) ? $query['user_id'] : false;
}

function getDatabaseAddressZipCode($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAddressData($id_sanitize, 'zip_code');
    return ($query !== false) ? $query['zip_code'] : false;
}

function getDatabaseAddressPublicPlace($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAddressData($id_sanitize, 'publicplace');
    return ($query !== false) ? $query['publicplace'] : false;
}
function getDatabaseAddressNeighborhood($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAddressData($id_sanitize, 'neighborhood');
    return ($query !== false) ? $query['neighborhood'] : false;
}

function getDatabaseAddressNumber($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAddressData($id_sanitize, 'number');
    return ($query !== false) ? $query['number'] : false;
}

function getDatabaseAddressComplement($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAddressData($id_sanitize, 'complement');
    return ($query !== false) ? $query['complement'] : false;
}
function getDatabaseAddressCity($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAddressData($id_sanitize, 'city');
    return ($query !== false) ? $query['city'] : false;
}

function getDatabaseAddressState($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAddressData($id_sanitize, 'state');
    return ($query !== false) ? $query['state'] : false;
}

function isDatabaseAddressExistID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `address` WHERE `id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}

function doDatabaseAddressValidateUser($user_id, $id)
{
    $id_sanitize = sanitize($id);
    $user_id_sanitize = sanitize($user_id);

    $query = doSelectSingleDB("SELECT `id` FROM `address` WHERE `id`='".$id_sanitize."' and `user_id`='".$user_id_sanitize."';");
    return ($query !== false) ? true : false;
}

function doDatabaseAddressListByUserID($user_id)
{
    
    return doSelectMultiDB("SELECT `id` FROM `address` where `user_id`='".$user_id."'");
}

function doDatabaseAddressInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `address` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseAddressDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `address` WHERE `id`='".$id_sanitize."'limit 1;");
}

function doDatabaseAddressUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `address` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 

function getDatabaseAddressIDByEmail($email)
{
    
    $email_sanitize = sanitize($email);

    $query = doSelectSingleDB("SELECT `id` FROM `address` WHERE `email`='".$email_sanitize."';");
    return ($query !== false) ? $query['id'] : false;
}


function isDatabaseAddressByEmail($email)
{
    
    $email_sanitize = sanitize($email);

    $query = doSelectSingleDB("SELECT `id` FROM `address` WHERE `email`='".$email_sanitize."';");
    return ($query !== false) ? true : false;
}

function isDatabaseAddressByUsername($username)
{
    
    $username_sanitize = sanitize($username);

    $query = doSelectSingleDB("SELECT `id` FROM `address` WHERE `username`='".$username_sanitize."';");
    return ($query !== false) ? true : false;
}



function getDatabaseAddressIDByUserName($username)
{
    
    $username_sanitize = sanitize($username);

    $query = doSelectSingleDB("SELECT `id` FROM `address` WHERE `username`='".$username_sanitize."';");
    return ($query !== false) ? $query['id'] : false;
}

function getDatabaseAddressCoutRowByUserID($user_id)
{

    $result = doSelectSingleDB("SELECT COUNT(*) AS rowCount FROM `address` where `user_id`='".$user_id."'");

    return ($result !== false) ? $result['rowCount'] : 0;
}


?>