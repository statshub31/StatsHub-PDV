<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseProductsQuestionResponsesData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `products_question_reponse` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseProductQuestionResponseQuestionID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsQuestionResponsesData($id_sanitize, 'question_id');
    return ($query !== false) ? $query['question_id'] : false;
}
function getDatabaseProductQuestionResponseResponse($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseProductsQuestionResponsesData($id_sanitize, 'response');
    return ($query !== false) ? $query['response'] : false;
}



function isDatabaseProductQuestionResponseExistID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `products_question_reponse` WHERE `id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}

function doDatabaseProductsQuestionResponsesListByQuestionID($question_id, $status = false)
{
    $sanitize_question_id = sanitize($question_id);

    return doSelectMultiDB("SELECT `id` FROM `products_question_reponse` where `question_id` = '".$sanitize_question_id."' and `deleted`=0");
}



function doDatabaseProductQuestionResponseInsert($import_data_query)
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

    return doInsertDB("INSERT INTO `products_question_reponse` (" . $keys . ") VALUES (" . $values . ")");
}

function doDatabaseProductQuestionResponseDelete($id)
{
    
    $id_sanitize = sanitize($id);

    doDeleteDB("DELETE FROM `products_question_reponse` WHERE `id`='".$id_sanitize."'limit 1;");
}

function doDatabaseProductQuestionResponseUpdate($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `products_question_reponse` SET $query_sql WHERE `id`='" . $id_sanitize . "';");
}

// 
// 
// 
// SPECIFIC
// 
// 
// 

function doDatabaseProductQuestionResponseInsertMultipleRow($import_data_query)
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
    $query = "INSERT INTO `products_question_reponse` ($keys) VALUES $values";
    doInsertDB($query);
}


function doDatabaseProductQuestionResponseTruncateByProductID($product_id)
{
    
    $product_id_sanitize = sanitize($product_id);

    doDeleteDB("DELETE FROM `products_question_reponse` WHERE `question_id`='".$product_id_sanitize."';");
}


function getDatabaseProductQuestionResponseExistByQuestionIDAndResponse($question_id, $text)
{
    $text_sanitize = sanitize($text);
    $question_sanitize = sanitize($question_id);

    $query = doSelectSingleDB("SELECT `id` FROM `products_question_reponse` WHERE `question_id`='".$question_sanitize."' and `response`='".$text_sanitize."' and `deleted`=0;");
    return ($query !== false) ? $query['id'] : false;
}


function doDatabaseProductQuestionResponseUpdateByQuestionID($question_id, $import_data_query, $empty = true)
{

    
    $question_id_sanitize = sanitize($question_id);
    
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
    doUpdateDB("UPDATE `products_question_reponse` SET $query_sql WHERE `question_id`='" . $question_id_sanitize . "';");
}


function doDatabaseProductQuestionResponseUpdateByEnabled($id, $import_data_query, $empty = true)
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
    doUpdateDB("UPDATE `products_question_reponse` SET $query_sql WHERE `id`='" . $id_sanitize . "' and `deleted` = 0;");
}


function isDatabaseProductQuestionResponseValidation($question_id, $id) {
	$question_id_sanitize = sanitize($question_id);
	$id_sanitize = $id;
	
	$data = doSelectSingleDB("SELECT `id` FROM `products_question_reponse` WHERE `question_id`='".$question_id_sanitize."' AND `id`='".$id_sanitize."';");
	
	return ($data !== false) ? true : false;
}
?>