<?php



function doDatabaseRemoveProduct($product_id)
{
    global $image_product_dir;
    $product_id_sanitize = sanitize($product_id);

    $photo_name = getDatabaseProductPhotoName($product_id_sanitize);
    $question_id = getDatabaseProductQuestionIDByProductID($product_id_sanitize);

    doGeneralRemoveArchive($image_product_dir, $photo_name);
    doDatabaseLogsStockTruncateByProductID($product_id_sanitize);
    doDatabaseStockTruncateByProductID($product_id_sanitize);
    doDatabaseProductQuestionResponseTruncateByProductID($question_id);
    doDatabaseProductQuestionTruncateByProductID($product_id_sanitize);
    doDatabaseProductPriceTruncateByProductID($product_id_sanitize);
    doDatabaseProductComplementTruncateByProductID($product_id_sanitize);
    doDatabaseProductAdditionalTruncateByProductID($product_id_sanitize);
    doDatabaseProductDelete($product_id_sanitize);


}


function doProcessNewQuestion($count)
{
    $questions_fields = array(
        'product_id' => $_POST['product_select_id'],
        'question' => $_POST['question' . $count],
        'multiple_response' => (isset($_POST['multiple-response' . $count]) ? 1 : 0),
        'response_free' => (isset($_POST['response-free' . $count]) ? 1 : 0)
    );

    $question_insert_id = doDatabaseProductQuestionInsert($questions_fields);

    if (!isset($_POST['response-free' . $count])) {
        foreach ($_POST['response' . $count] as $response) {
            if (!empty($response)) {
                $response_fields[$count][] = array(
                    'question_id' => $question_insert_id,
                    'response' => $response
                );
            }
        }

        // doDatabaseProductQuestionResponseInsertMultipleRow($response_fields[$count]);
    }
}

function doProcessDisabledQuestion($count)
{
    $question_update_id = getDatabaseProductQuestionExistByQuestion($_POST['old_question' . $count]);

    $question_update_fields = array(
        'deleted' => 1
    );

    doDatabaseProductQuestionUpdate($question_update_id, $question_update_fields); // DESABILITO A PERGUNTA
    doDatabaseProductQuestionResponseUpdateByQuestionID($question_update_id, $question_update_fields);  // DESABILITO AS RESPOSTAS 
}

function doDisabledResponsesQuestion($count)
{
    $question_update_id = getDatabaseProductQuestionExistByQuestion($_POST['old_question' . $count]);

    $question_update_fields = array(
        'deleted' => 1
    );
    doDatabaseProductQuestionResponseUpdateByQuestionID($question_update_id, $question_update_fields);  // DESABILITO AS RESPOSTAS 
}

function doProcessUpdateResponse($response_id, $text) {
    $response_fields = array(
        'response' => $text
    );

    doDatabaseProductQuestionResponseUpdate($response_id, $response_fields);
}

function doProcessNewResponse($question_id, $response) {

    $response_fields = array(
        'question_id' => $question_id,
        'response' => $response
    );

    doDatabaseProductQuestionResponseInsert($response_fields);
}


function doProcessDisabledResponse($response_id) {

    $response_fields = array(
        'deleted' => 1
    );

    doDatabaseProductQuestionResponseUpdate($response_id, $response_fields);
}


function doProcessResponseQuestion($count, $question_id) {
    $count_response = 0;
    // VARREDURA EM TODAS AS RESPOSTA
    while(isset($_POST['response'. $count][$count_response])) {
        $response_old = isset($_POST['old_response'. $count][$count_response]);
        // VERIFICA SE É UMA RESPOSTA ALTERADA
        if($response_old) {
            $empty_response = (empty($_POST['response'. $count][$count_response]));
            $response_id = getDatabaseProductQuestionResponseExistByQuestionIDAndResponse($question_id, $_POST['old_response'. $count][$count_response]);                

            // ELA ESTÁ VAZIA
            if($empty_response === false) {
                doProcessUpdateResponse($response_id, $_POST['response'. $count][$count_response]);
            } else {
                // data_dump('Q'.$count.'=R'.$count_response);
                doProcessDisabledResponse($response_id);
            }
        } else {
            $empty_response = (empty($_POST['response'. $count][$count_response]));
            
            if($empty_response === false) {
                doProcessNewResponse($question_id, $_POST['response'. $count][$count_response]);
            }
        }

        ++$count_response;
    }
}

function doProcessUpdateQuestion($count)
{
    $checked_question = ($_POST['old_question' . $count] === $_POST['question' . $count]);

    // PERGUNTA DE INICIO É IGUAL A DE FIM?
    if ($checked_question === false) {
        $empty = (empty($_POST['question' . $count]));

        doProcessDisabledQuestion($count);

        // A PERGUNTA É DIFERENTE, MAS ESTÁ VAZIO?
        if ($empty === false) {
            doProcessNewQuestion($count);
        }
    } else {
        $question_update_id = getDatabaseProductQuestionExistByQuestion($_POST['old_question' . $count]);
        $response_free_enabled = (getDatabaseProductQuestionResponseFree($question_update_id) == 0) && (isset($_POST['response-free' . $count]));

        $question_update_fields = array(
            'multiple_response' => (isset($_POST['multiple-response' . $count]) ? 1 : 0),
            'response_free' => (isset($_POST['response-free' . $count]) ? 1 : 0)
        );

        doDatabaseProductQuestionUpdate($question_update_id, $question_update_fields);
        
        if ($response_free_enabled) {
            doDisabledResponsesQuestion($count);
        } else {
            doProcessResponseQuestion($count, $question_update_id);
        }


    }
}


function doProcessProductEdit($count)
{
    $question_old = isset($_POST['old_question' . $count]);
    // PERGUNTA ALTERADA?
    if ($question_old) {
        doProcessUpdateQuestion($count);
    } else {
        doProcessNewQuestion($count);
    }
}