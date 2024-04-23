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

function doDatabaseRemoveProducts($products)
{
    global $image_product_dir;

    foreach ($products as $product_id) {
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
}

function doDatabaseBlockProducts($products)
{
    $update_field = array('status' => 3);

    foreach ($products as $product_id) {
        doDatabaseProductUpdate($product_id, $update_field);
    }
}

function doDatabaseUnblockProducts($products)
{
    $update_field = array('status' => 2);

    foreach ($products as $product_id) {
        doDatabaseProductUpdate($product_id, $update_field);
    }
}

function doDatabaseDepromotionProducts($products, $user_id)
{
    $update_field = array(
        'end' => date("Y-m-d H:i:s"),
        'finished_by' => $user_id,
        'status' => 3,
    );

    foreach ($products as $product_id) {
        doDatabaseProductPromotionUpdateByProductID($product_id, $update_field);
    }
}

function doDatabaseExceptionProducts($products, $user_id)
{
    $promotion = false;

    foreach ($products as $product_promotion_id) {

        if (isDatabaseProductFeeExemptionEnabledByProductID($product_promotion_id)) {
            doDatabaseProductFeeExemptionDeleteByProductID($product_promotion_id);
        } else {
            $promotion_products_fields[] = array(
                'product_id' => $product_promotion_id,
                'created' => date("Y-m-d H:i:s"),
                'created_by' => $user_id
            );
            $promotion = true;
        }
    }

    if ($promotion)
        doDatabaseProductFeeExemptionInsertMultipleRow($promotion_products_fields);

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

function doProcessUpdateResponse($response_id, $text)
{
    $response_fields = array(
        'response' => $text
    );

    doDatabaseProductQuestionResponseUpdate($response_id, $response_fields);
}

function doProcessNewResponse($question_id, $response)
{

    $response_fields = array(
        'question_id' => $question_id,
        'response' => $response
    );

    doDatabaseProductQuestionResponseInsert($response_fields);
}


function doProcessDisabledResponse($response_id)
{

    $response_fields = array(
        'deleted' => 1
    );

    doDatabaseProductQuestionResponseUpdate($response_id, $response_fields);
}


function doProcessResponseQuestion($count, $question_id)
{
    $count_response = 0;
    // VARREDURA EM TODAS AS RESPOSTA
    while (isset($_POST['response' . $count][$count_response])) {
        $response_old = isset($_POST['old_response' . $count][$count_response]);
        // VERIFICA SE É UMA RESPOSTA ALTERADA
        if ($response_old) {
            $empty_response = (empty($_POST['response' . $count][$count_response]));
            $response_id = getDatabaseProductQuestionResponseExistByQuestionIDAndResponse($question_id, $_POST['old_response' . $count][$count_response]);

            // ELA ESTÁ VAZIA
            if ($empty_response === false) {
                doProcessUpdateResponse($response_id, $_POST['response' . $count][$count_response]);
            } else {
                // data_dump('Q'.$count.'=R'.$count_response);
                doProcessDisabledResponse($response_id);
            }
        } else {
            $empty_response = (empty($_POST['response' . $count][$count_response]));

            if ($empty_response === false) {
                doProcessNewResponse($question_id, $_POST['response' . $count][$count_response]);
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


function doCartTotalPriceProduct($cart_product_id)
{
    $cart_product_id_sanitize = sanitize($cart_product_id);
    $product_id = getDatabaseCartProductProductID($cart_product_id_sanitize);
    $amount = getDatabaseCartProductAmount($cart_product_id_sanitize);
    $price_id = getDatabaseCartProductPriceID($cart_product_id_sanitize);

    $total_product_price = (getDatabaseProductPrice($price_id) * $amount);

    $list_additional = doDatabaseCartProductAdditionalListByCart($cart_product_id);
    $additional_total = 0;

    if ($list_additional) {
        foreach ($list_additional as $data) {
            $additional_cart_id = $data['id'];
            $additional_id = getDatabaseCartProductAdditionalAdditionalID($additional_cart_id);
            $additional_total += (getDatabaseAdditionalTotalPrice($additional_id) * $amount);
        }
    }

    return ($total_product_price + $additional_total);
}

function doCartTotalPrice($cart_id)
{
    $total = 0.0;
    $list_cart = doDatabaseCartProductsListByCartID($cart_id);
    if ($list_cart) {
        foreach ($list_cart as $data) {
            $cart_product_id = $data['id'];
            $total += doCartTotalPriceProduct($cart_product_id);
        }
    }

    return ($total + getDatabaseSettingsDeliveryFee(1));
}

function doCartTotalPriceDiscount($cart_id)
{
    $cart_id_sanitize = $cart_id;
    $id_cart_ticket = getDatabaseCartTicketSelectByCartID($cart_id_sanitize);
    $id_ticket = getDatabaseCartTicketSelectTicketID($id_cart_ticket);
    $price_discount_t = getDatabaseTicketValue($id_ticket);
    $total = doCartTotalPrice($cart_id_sanitize);
    
    if(doGeneralValidationPriceType($price_discount_t)) {
        $discount_percentage = (float) rtrim($price_discount_t, '%') / 100;
        $price_discount = $total * $discount_percentage;
    } else {
        $price_discount = (float) $price_discount_t;
    }

    $total_discount = $total - $price_discount;

    return ($total - $total_discount);
}

function doCartProductIDIsUserID($cart_product_id, $user_id)
{
    $cart_id = getDatabaseCartProductCartID($cart_product_id);
    $user_cart_id = getDatabaseCartUserID($cart_id);

    return ($user_cart_id == $user_id) ? true : false;
}


function doRemoveCartProductID($cart_product_id)
{
    $cart_id = getDatabaseCartProductCartID($cart_product_id);
    $cart_product_id_sanitize = sanitize($cart_product_id);
    $cart_product_complement_id = getDatabaseCartProductComplementByCartProductID($cart_product_id_sanitize);
    // $cart_question = 

    // Lista de todas as perguntas
    $list_question = doDatabaseProductsQuestionsListByProductID(getDatabaseCartProductProductID($cart_product_id_sanitize));

    foreach ($list_question as $data) {
        $question_id = $data['id'];
        $question_remove_id = getDatabaseCartProductQuestionIDByCartAndQuestID($cart_product_id_sanitize, $question_id);

        doDatabaseCartProductQuestionResponseDeleteByQuestionIDUnlimited($question_remove_id);
        doDatabaseCartProductQuestionDeleteUnlimited($question_remove_id);
    }
    doDatabaseCartProductComplementDelete($cart_product_complement_id);
    doDatabaseCartProductAdditionalDeleteByCartProductUnlimited($cart_product_id);
    doDatabaseCartProductDelete($cart_product_id_sanitize);
}


function doRequestOrderLogInsert($order_id, $status) {
    
    $request_order__logs_insert_fields = array(
        'request_order_id' => $order_id,
        'status_delivery' => $status,
        'created' => date('Y-m-d H:i:s')
    );

    doDatabaseRequestOrderLogInsert($request_order__logs_insert_fields);
}