<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseAccountsData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `accounts` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseAccountUserName($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAccountsData($id_sanitize, 'username');
    return ($query !== false) ? $query['username'] : false;
}

function getDatabaseAccountEmail($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAccountsData($id_sanitize, 'email');
    return ($query !== false) ? $query['email'] : false;
}

function getDatabaseAccountGroupID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAccountsData($id_sanitize, 'group_id');
    return ($query !== false) ? $query['group_id'] : false;
}
function getDatabaseAccountIP($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAccountsData($id_sanitize, 'ip');
    return ($query !== false) ? $query['ip'] : false;
}

function getDatabaseAccountRules($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAccountsData($id_sanitize, 'rules');
    return ($query !== false) ? $query['rules'] : false;
}

function getDatabaseAccountBlock($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAccountsData($id_sanitize, 'block');
    return ($query !== false) ? $query['block'] : false;
}

function isDatabaseAccountBlock($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAccountsData($id_sanitize, 'block');
    return ($query['block'] == 1) ? true : false;
}

function getDatabaseAccountCreated($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseAccountsData($id_sanitize, 'created');
    return ($query !== false) ? $query['created'] : false;
}

function isDatabaseAccountExistID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `accounts` WHERE `id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}

function doDatabaseAccountList($status = false)
{
    
    return doSelectMultiDB("SELECT `id` FROM `accounts`");
}

function doDatabaseAccountInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `accounts` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseAccountDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `accounts` WHERE `id`='".$id_sanitize."'limit 1;");
}

function doDatabaseAccountUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `accounts` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 

function getDatabaseAccountIDByEmail($email)
{
    
    $email_sanitize = sanitize($email);

    $query = doSelectSingleDB("SELECT `id` FROM `accounts` WHERE `email`='".$email_sanitize."';");
    return ($query !== false) ? $query['id'] : false;
}


function isDatabaseAccountByEmail($email)
{
    
    $email_sanitize = sanitize($email);

    $query = doSelectSingleDB("SELECT `id` FROM `accounts` WHERE `email`='".$email_sanitize."';");
    return ($query !== false) ? true : false;
}

function isDatabaseAccountByUsername($username)
{
    
    $username_sanitize = sanitize($username);

    $query = doSelectSingleDB("SELECT `id` FROM `accounts` WHERE `username`='".$username_sanitize."';");
    return ($query !== false) ? true : false;
}



function getDatabaseAccountIDByUserName($username)
{
    
    $username_sanitize = sanitize($username);

    $query = doSelectSingleDB("SELECT `id` FROM `accounts` WHERE `username`='".$username_sanitize."';");
    return ($query !== false) ? $query['id'] : false;
}

function getDatabaseAccountLoginValidation($username, $password) {
	$username_sanitize = sanitize($username);
	$password = md5($password);
	
	$data = doSelectSingleDB("SELECT `id` FROM `accounts` WHERE `username`='".$username_sanitize."' AND `password`='".$password."';");
	
	return ($data !== false) ? $data['id'] : false;
}


function isDatabaseAccountExistEmail($email)
{
    
    $email_sanitize = sanitize($email);

    $query = doSelectSingleDB("SELECT `id` FROM `accounts` WHERE `email`='".$email_sanitize."';");
    return ($query !== false) ? true : false;
}

function isDatabaseAccountExistUserName($username)
{
    
    $username_sanitize = sanitize($username);

    $query = doSelectSingleDB("SELECT `id` FROM `accounts` WHERE `username`='".$username_sanitize."';");
    return ($query !== false) ? true : false;
}




function isDatabaseAccountEmailValidation($email, $id) {
	$email_sanitize = sanitize($email);
	$id_sanitize = $id;
	
	$data = doSelectSingleDB("SELECT `id` FROM `accounts` WHERE `email`='".$email_sanitize."' AND `id`='".$id_sanitize."';");
	
	return ($data !== false) ? true : false;
}


function isDatabaseAccountUsernameValidation($username, $id) {
	$username_sanitize = sanitize($username);
	$id_sanitize = $id;
	
	$data = doSelectSingleDB("SELECT `id` FROM `accounts` WHERE `username`='".$username_sanitize."' AND `id`='".$id_sanitize."';");
	
	return ($data !== false) ? true : false;
}
?>