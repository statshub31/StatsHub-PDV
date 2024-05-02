<?php


function doDatabaseRemoveProduct($product_id)
{
    $product_id_sanitize = $product_id;
    $cart_product_list = doDatabaseCartProductsListByProductID($product_id_sanitize);

    if ($cart_product_list) {
        foreach ($cart_product_list as $data) {
            $cart_product_id = $data['id'];
            $cart_id = getDatabaseCartProductCartID($cart_product_id);
            $request_order_id = getDatabaseRequestOrderCartID($cart_id);


            ## REMOVER PEDIDOS ##
            doDatabaseRequestOrderAvailableTruncateByRequestOrderID($request_order_id);
            doDatabaseRequestOrderLogsTruncateByRequestOrderID($request_order_id);
            doDatabaseRequestOrderDelete($request_order_id);

            ## REMOVER CARRINHO ##

            $cart_product_complement_id = getDatabaseCartProductComplementByCartProductID($cart_product_id);
            doDatabaseCartProductComplementDelete($cart_product_complement_id);
            doDatabaseCartProductAdditionalDeleteByCartProductUnlimited($cart_product_id);

            // Lista de todas as perguntas
            $list_question = doDatabaseProductsQuestionsListByProductID(getDatabaseCartProductProductID($cart_product_id));

            if ($list_question !== false) {
                foreach ($list_question as $data) {
                    $question_id = $data['id'];
                    $question_remove_id = getDatabaseCartProductQuestionIDByCartAndQuestID($cart_product_id, $question_id);

                    doDatabaseCartProductQuestionResponseDeleteByQuestionIDUnlimited($question_remove_id);
                    doDatabaseCartProductQuestionDeleteUnlimited($question_remove_id);
                }
            }

            doDatabaseCartProductDelete($cart_product_id);
        }

    }
    doDatabaseTruncateProductTables($product_id);
}

// Removo um produto por id 
function doDatabaseTruncateProductTables($product_id)
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

// Removo todos os produtos por id 
function doDatabaseRemoveProducts($products)
{
    global $image_product_dir;

    foreach ($products as $product_id) {
        doDatabaseRemoveProduct($product_id);
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

        doDatabaseProductQuestionResponseInsertMultipleRow($response_fields[$count]);
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
    if (isDatabaseProductPromotionEnabledByProductID($product_id)) {
        $price = doCalcDiscountPromotion($product_id, $price_id);
    } else {
        $price = getDatabaseProductPrice($price_id);
    }

    $total_product_price = ($price * $amount);

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

    if (doGeneralValidationPriceType($price_discount_t)) {
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

    // Lista de todas as perguntas
    $list_question = doDatabaseProductsQuestionsListByProductID(getDatabaseCartProductProductID($cart_product_id_sanitize));

    if ($list_question !== false) {
        foreach ($list_question as $data) {
            $question_id = $data['id'];
            $question_remove_id = getDatabaseCartProductQuestionIDByCartAndQuestID($cart_product_id_sanitize, $question_id);

            doDatabaseCartProductQuestionResponseDeleteByQuestionIDUnlimited($question_remove_id);
            doDatabaseCartProductQuestionDeleteUnlimited($question_remove_id);
        }
    }
    doDatabaseCartProductComplementDelete($cart_product_complement_id);
    doDatabaseCartProductAdditionalDeleteByCartProductUnlimited($cart_product_id);
    doDatabaseCartProductDelete($cart_product_id_sanitize);
}


function doRemoveCartAllProduct($cart_id)
{
    $cart_product_list = doDatabaseCartProductsListByCartID($cart_id);

    if ($cart_product_list) {
        foreach ($cart_product_list as $data) {
            $cart_product_id = $data['id'];
            doRemoveCartProductID($cart_product_id);
        }
    }
}


function doRemoveCartsProductID($product_id)
{
    $carts = doDatabaseCartsListEnabled();

    if ($carts) {
        foreach ($carts as $data) {
            $cart_id = $data['id'];
            $cart_product_id = getDatabaseCartProductExistIDByCartAndProductID($cart_id, $product_id);
            doRemoveCartProductID($cart_product_id);
        }
    }

}


function doRequestOrderLogInsert($order_id, $status)
{

    $request_order__logs_insert_fields = array(
        'request_order_id' => $order_id,
        'status_delivery' => $status,
        'created' => date('Y-m-d H:i:s')
    );

    doDatabaseRequestOrderLogInsert($request_order__logs_insert_fields);
}

function getMinTimeOrderDelivery($order_id)
{
    $order_log_id = getDatabaseRequestOrderLogIDByOrderIDAndStatusID($order_id, 2);
    $created = getDatabaseRequestOrderLogCreated($order_log_id);
    return date("H:i", strtotime($created . " + " . getDatabaseSettingsDeliveryTimeMin(1) . " minutes"));
}
function getMaxTimeOrderDelivery($order_id)
{
    $order_log_id = getDatabaseRequestOrderLogIDByOrderIDAndStatusID($order_id, 2);
    $created = getDatabaseRequestOrderLogCreated($order_log_id);
    return date("H:i", strtotime($created . " + " . getDatabaseSettingsDeliveryTimeMax(1) . " minutes"));
}


function getOrderProgressBarValue($order_id)
{
    $order_first_log_id = doDatabaseRequestOrderLogsFirstLogByOrderID($order_id);

    if ($order_first_log_id == 1) {
        $order_first_log_id = getDatabaseRequestOrderLogIDByOrderIDAndStatusID($order_id, 2);
    }

    $created = getDatabaseRequestOrderLogCreated($order_first_log_id);

    $time_min_strtotime = strtotime($created . " + " . getDatabaseSettingsDeliveryTimeMin(1) . " minutes");
    $time_max_strtotime = strtotime($created . " + " . getDatabaseSettingsDeliveryTimeMax(1) . " minutes");
    $date_actual_strtotime = strtotime(date("Y-m-d H:i:s"));

    $time_min_restant = ($time_min_strtotime - $date_actual_strtotime) / 60;
    $time_max_restant = ($time_max_strtotime - $date_actual_strtotime) / 60;
    $percentual_min = ((getDatabaseSettingsDeliveryTimeMin(1) - $time_min_restant) / getDatabaseSettingsDeliveryTimeMin(1)) * 100;
    $percentual_max = ((getDatabaseSettingsDeliveryTimeMax(1) - $time_max_restant) / getDatabaseSettingsDeliveryTimeMax(1)) * 100;


    return array(
        'min' => number_format((($percentual_min > 100) ? 100 : $percentual_min), 0),
        'max' => number_format((($percentual_max > 100) ? 100 : $percentual_max), 0),
        'minutes_min' => ($time_min_restant < 0) ? 0 : $time_min_restant,
        'minutes_max' => ($time_max_restant < 0) ? 0 : $time_max_restant,
    );

}


function doDeliveryManList()
{

    return doSelectMultiDB("
    SELECT u.id FROM users AS u 
    INNER JOIN accounts AS a ON a.id = u.account_id 
    WHERE a.group_id = 2;
    ");
}

function isOpen()
{
    $date_actual = date('N');
    $time_actual = date('H:i:s');

    if (getDatabaseSettingsHoraryDayEnabled(1, $date_actual) == 0)
        return false;

    if (getDatabaseSettingsHoraryDayEnabled(1, $date_actual) == 1) {
        if ($time_actual < getDatabaseSettingsHoraryDayStart(1, $date_actual) || $time_actual > getDatabaseSettingsHoraryDayEnd(1, $date_actual))
            return false;
    }

    return true;
}

function isOpenByDay($date_actual)
{
    $time_actual = date('H:i:s');

    if (getDatabaseSettingsHoraryDayEnabled(1, $date_actual) == 0)
        return false;

    return true;
}


function doAvailableGeneral()
{
    $food_sum = doSelectSingleDB("SELECT sum(`food`) as total FROM request_order_available");
    $food_count = doSelectSingleDB("SELECT count(`food`) as total FROM request_order_available");

    $box_sum = doSelectSingleDB("SELECT sum(`box`) as total FROM request_order_available");
    $box_count = doSelectSingleDB("SELECT count(`box`) as total FROM request_order_available");

    $deliverytime_sum = doSelectSingleDB("SELECT sum(`deliverytime`) as total FROM request_order_available");
    $deliverytime_count = doSelectSingleDB("SELECT count(`deliverytime`) as total FROM request_order_available");

    $costbenefit_sum = doSelectSingleDB("SELECT sum(`costbenefit`) as total FROM request_order_available");
    $costbenefit_count = doSelectSingleDB("SELECT count(`costbenefit`) as total FROM request_order_available");

    $food = $food_sum['total'] / ($food_count['total'] !== false) ? $food_count['total'] : 0;
    $box = $box_sum['total'] / ($box_count['total'] !== false) ? $box_count['total'] : 0;
    $deliverytime = $deliverytime_sum['total'] / ($deliverytime_count['total'] !== false) ? $deliverytime_count['total'] : 0;
    $costbenefit = $costbenefit_sum['total'] / ($costbenefit_count['total'] !== false) ? $costbenefit_count['total'] : 0;
    $general = ($food + $box + $deliverytime + $costbenefit) / 4;

    return array(
        'general' => round($general, 2),
        'food' => round($food, 2),
        'box' => round($box, 2),
        'deliverytime' => round($deliverytime, 2),
        'costbenefit' => round($costbenefit, 2)
    );

}


function doPrintOrder($order_id)
{

    $html = '<style>
    #content-header {
        text-align: center;
    }

    #name {
        font-size: 15px;
        font-weight: bold;
        font-family: monospace;
    }

    #cnpj {
        font-size: 12px;
        font-weight: bold;
        font-family: monospace;
    }

    #type_delivery {
        text-align: center;
    }

    #type_delivery {
        font-size: 14px;
    }

    hr {
        margin: 10px 0px;
    }

    #date {
        font-family: monospace;
        font-size: 14px;
        text-align: center;
    }

    #number-delivery {
        font-family: "Franklin Gothic Medium", "Arial Narrow", Arial, sans-serif;
        font-size: 15px;
        text-align: center;
    }

    .title {
        font-family: "Franklin Gothic Medium", "Arial Narrow", Arial, sans-serif;
        font-size: 15px;
        text-align: center;
    }

    .order {
        font-size: 15px;
    }

    .value {
        position: relative;
        float: right;
        font-weight: bold;
        font-family: monospace;
    }

    .subvalue {
        position: relative;
        float: right;
        font-weight: bold;
        font-family: monospace;
        font-size: 10px !important;
    }

    li,
    ul,
    ol {
        margin: 1px;
    }

    li {
        width: 100%;
    }

    .subtopic {
        font-weight: 600;
    }
</style>';

    $first_log = doDatabaseRequestOrderLogsFirstLogByOrderID($order_id);

    $html .= '<div id="content">';
    $html .= '<div id="content-header">';
    $html .= '<label id="name">' . getDatabaseSettingsInfoTitle(1) . '</label><br>';
    $cnpj = getDatabaseSettingsInfoCNPJ(1);
    if ($cnpj !== false) {
        $html .= '<small id="cnpj">CNPJ: ' . $cnpj . '</small>';
    }
    $html .= '</div><hr>';

    $html .= '<div id="type_delivery">' . getDatabaseDeliveryTitle(1) . '</div><hr>';

    $html .= '<div id="date">';
    $html .= doDate(getDatabaseRequestOrderLogCreated($first_log)) . ' às ' . doTime(getDatabaseRequestOrderLogCreated($first_log));
    $html .= '</div><hr>';

    $html .= '<div id="number-delivery">';
    $html .= 'PEDIDO #' . $order_id;
    $html .= '</div><hr>';

    $html .= '<label class="title">Itens:</label><br><hr>';

    $cart_id = getDatabaseRequestOrderCartID($order_id);
    $main_address_id = getDatabaseRequestOrderAddressIDSelect($order_id);
    $cart_list = doDatabaseCartProductsListByCartID($cart_id);
    $user_id = getDatabaseCartUserID($cart_id);
    $pay_id = getDatabaseRequestOrderPayIDSelect($order_id);

    $itens_count = 0;
    if ($cart_list) {
        foreach ($cart_list as $data) {
            $cart_product_list_id = $data['id']; // PRODUTO CART
            $cart_product_id = getDatabaseCartProductProductID($cart_product_list_id); // PRODUTO_ID
            $obs = getDatabaseCartProductObservation($cart_product_list_id);
            $size_id = getDatabaseCartProductPriceID($cart_product_list_id);
            $measure_id = getDatabaseProductSizeMeasureID($size_id);
            $itens_count += getDatabaseCartProductAmount($cart_product_list_id);
            $discount = getDatabaseTicketValue(getDatabaseCartTicketSelectTicketID(getDatabaseCartTicketSelectByCartID($cart_id)));

            $html .= '<label class="order">';
            $html .= '(' . getDatabaseCartProductAmount($cart_product_list_id) . ')';
            $html .= getDatabaseProductName($cart_product_id) . ' - ';
            $html .= getDatabaseProductPriceSize($size_id);
            $html .= getDatabaseMeasureTitle($measure_id);
            $html .= '<nav><ul class="subtopic">Complementos</ul><ol>';

            $complement_list = doDatabaseProductsComplementsListByProductID($cart_product_id);
            $product_complement_select = getDatabaseCartProductComplementByCartProductID($cart_product_list_id);
            $complement_select = getDatabaseCartProductComplementComplementID($product_complement_select);

            if ($complement_list) {
                foreach ($complement_list as $dataComplement) {
                    $product_complement_id = $dataComplement['id'];
                    $complement_id = getDatabaseProductComplementComplementID($product_complement_id);
                    $html .= ($complement_select == $complement_id) ? '<li>' . getDatabaseComplementDescription($complement_id) . '</li>' : '';
                }
            }

            $html .= '</ol></nav>';

            $html .= '<nav><ul class="subtopic">Adicionais</ul><ol>';

            $additional_list = doDatabaseProductsAdditionalListByProductID($cart_product_id);
            if ($additional_list) {
                foreach ($additional_list as $dataAdditional) {
                    $product_additional_id = $dataAdditional['id'];
                    $additional_id = getDatabaseProductAdditionalAdditionalID($product_additional_id);
                    $html .= (isDatabaseCartProductAdditionalExistIDByCartAndAdditionalID($cart_product_list_id, $additional_id) == 1) ? '<li>' . getDatabaseAdditionalDescription($additional_id) . '
                                 <span class="subvalue">R$ ' . sprintf("%.2f", getDatabaseAdditionalTotalPrice($additional_id)) . '</span>
                                 </li>' : '';
                }
            }
            $html .= '</ol></nav>';
            $html .= '<span class="subtopic">Observações:</span><br>' . getDatabaseCartProductObservation($cart_product_list_id);
            $html .= '</label><br>';
            $html .= '<span class="value">R$ ' . sprintf("%.2f", (doCartTotalPriceProduct($cart_product_list_id))) . '</span><br><hr>';
        }
    }

    $html .= '<label class="title">Dados do Cliente:</label><br>';
    $html .= '<label><span class="subtopic">Nome:</span>' . getDatabaseUserName($user_id) . '<br></label>';
    $html .= '<label><span class="subtopic">Telefone:</span>' . getDatabaseUserPhone($user_id) . '<br></label>';
    $html .= '<label><span class="subtopic">Quantidade de Itens:</span>' . $itens_count . '<br></label>';
    $html .= '<label><span class="subtopic">Entrega:</span>';
    if (isDatabaseRequestOrderSelectAddress($order_id)) {
        $html .= getDatabaseAddressPublicPlace($main_address_id) . ', ';
        $html .= getDatabaseAddressNumber($main_address_id) . '(';
        $html .= getDatabaseAddressComplement($main_address_id) . '), ';
        $html .= getDatabaseAddressNeighborhood($main_address_id) . ', ';
        $html .= getDatabaseAddressCity($main_address_id) . ' - ';
        $html .= getDatabaseAddressState($main_address_id);
    } else {
        $html .= 'Retirada no Local';
    }
    $html .= '<br></label><hr>';

    $html .= '<label class="title">Pagamento:</label><br>';
    $html .= '<label><span class="subtopic">Forma de Pagamento:</span>' . getDatabaseSettingsPayType($pay_id) . '<br></label>';
    $html .= '<label><span class="subtopic">Desconto: </span><br><span class="value">';
    $html .= getDatabaseTicketCode(getDatabaseCartTicketSelectTicketID(getDatabaseCartTicketSelectCartID($cart_id)));
    $html .= '<small>' . (($discount !== false) ? 'Cliente teve um desconto de [' . doTypeDiscount($discount) . ']' : 'Cliente não selecionou nenhum cupom.') . '</small>';
    $html .= '</span></label><br>';
    $html .= '<label><span class="subtopic">Taxa de Entrega: </span><span class="value">R$ ';
    $html .= sprintf("%.2f", (getDatabaseSettingsDeliveryFee(1))) . '</span></label><br>';

    $html .= '<label><span class="subtopic">Total: </span><span class="value">R$ ';
    $html .= sprintf("%.2f", (doCartTotalPrice($order_id) - doCartTotalPriceDiscount($order_id))) . '</span></label><br><hr>';

    if ($pay_id == getDatabaseSettingsPayMoney()) {
        $html .= '<label>Troco para: <span class="value">R$ ' . getDatabaseRequestOrderChangeOf($order_id) . '</span></label><br>';
        $html .= '<label>Troco: <span class="value">R$ ' . sprintf("%.2f", (getDatabaseRequestOrderChangeOf($order_id) - (doCartTotalPrice($order_id) - doCartTotalPriceDiscount($order_id)))) . '</span></label><br>';
    }

    $html .= '</div>'; // Fechando a div "content"

    $html .= '<script>
    window.print();
    window.close();
</script>';

    // Escapando o conteúdo usando json_encode
    $conteudoEscapado = json_encode($html);

    // Criando o script JavaScript para abrir a nova janela com o conteúdo
    echo "<script>";
    echo "var novaJanela = window.open('', '_blank');";
    echo "novaJanela.document.write($conteudoEscapado);";
    echo "</script>";
}



function getProductInStock($product_id, $quantity)
{
    $product_id_sanitize = sanitize($product_id);
    $product_stock_status = getDatabaseProductsStockStatus($product_id_sanitize);

    if ($product_stock_status == 1) {
        $stock_id = getDatabaseStockIDByProductID($product_id_sanitize);
        $stock_amount = (getDatabaseStockActual($stock_id) - $quantity);

        if ($stock_amount < 0)
            return false;

    }

    return true;
}

function isProductInStock($product_id)
{
    $product_id_sanitize = sanitize($product_id);
    $product_stock_status = getDatabaseProductsStockStatus($product_id_sanitize);

    if ($product_stock_status == 1) {
        $stock_id = getDatabaseStockIDByProductID($product_id_sanitize);
        $stock_amount = getDatabaseStockActual($stock_id);

        if ($stock_amount <= 0)
            return false;

    }

    return true;
}

function doDecreaseStock($order_id)
{
    $order_id_sanitize = $order_id;
    $cart_id = getDatabaseRequestOrderCartID($order_id_sanitize);

    $cart_products_list = doDatabaseCartProductsListByCartID($cart_id);

    if ($cart_products_list) {
        foreach ($cart_products_list as $data) {
            // CARRINHO
            $cart_product_list_id = $data['id'];
            $product_id = getDatabaseCartProductProductID($cart_product_list_id);
            $product_amount = getDatabaseCartProductAmount($cart_product_list_id);

            // STOCK
            $stock_id = getDatabaseStockIDByProductID($product_id);
            $product_stock_status = getDatabaseProductsStockStatus($product_id);
            $stock_amount = getDatabaseStockActual($stock_id);
            $new_stock_amount = ($stock_amount - $product_amount);

            if ($product_stock_status == 1) {
                $stock_update_fields = array(
                    'actual' => $new_stock_amount
                );

                doDatabaseStockUpdate($stock_id, $stock_update_fields);

                if ($new_stock_amount <= 0) {
                    doRemoveCartsProductID($product_id);
                }
            }
        }
    }
}


function doIncreaseStock($order_id)
{
    $order_id_sanitize = $order_id;
    $cart_id = getDatabaseRequestOrderCartID($order_id_sanitize);

    $cart_products_list = doDatabaseCartProductsListByCartID($cart_id);

    if ($cart_products_list) {
        foreach ($cart_products_list as $data) {
            // CARRINHO
            $cart_product_list_id = $data['id'];
            $product_id = getDatabaseCartProductProductID($cart_product_list_id);
            $product_amount = getDatabaseCartProductAmount($cart_product_list_id);

            // STOCK
            $stock_id = getDatabaseStockIDByProductID($product_id);
            $product_stock_status = getDatabaseProductsStockStatus($product_id);
            $stock_amount = getDatabaseStockActual($stock_id);
            $new_stock_amount = ($stock_amount + $product_amount);

            if ($product_stock_status == 1) {
                $stock_update_fields = array(
                    'actual' => $new_stock_amount
                );

                doDatabaseStockUpdate($stock_id, $stock_update_fields);
            }
        }
    }
}


function doCalcDiscountPromotion($product_id, $size_id)
{
    $product_id_sanitize = sanitize($product_id);
    $size_id_sanitize = sanitize($size_id);
    $promotion_id = getDatabaseProductPromotionByProductID($product_id_sanitize);
    $discount = getDatabaseProductPromotionType($promotion_id);
    $total = 0;

    if ($promotion_id !== false) {
        $price = getDatabaseProductPrice($size_id);
        $discount_value = getDatabaseProductPromotionValue($promotion_id);

        if (isDatabasePromotionPercentual($discount)) {
            $discount_amount = ($price * $discount_value) / 100;
            $total = $price - $discount_amount;
        }

        if (isDatabasePromotionReais($discount)) {
            $total = $price - $discount_value;
        }
    }

    return $total;
}

function isProductPromotionCumulative($cart_id)
{
    $cart_list = doDatabaseCartProductsListByCartID($cart_id);

    foreach ($cart_list as $data) {
        $cart_product_list_id = $data['id'];
        $product_id = getDatabaseCartProductProductID($cart_product_list_id);
        $promotion_id = getDatabaseProductPromotionByProductID($product_id);
        if ($promotion_id !== false && !isDatabaseProductPromotionCumulativeEnabled($promotion_id)) {
            return false;
        }
    }

    return $cart_list ? true : false;
}

function isProductUnblocked($cart_id)
{
    $cart_list = doDatabaseCartProductsListByCartID($cart_id);

    foreach ($cart_list as $data) {
        $cart_product_list_id = $data['id'];
        $product_id = getDatabaseCartProductProductID($cart_product_list_id);
        if (isDatabaseProductBlocked($product_id)) {
            return false;
        }
    }

    return $cart_list ? true : false;
}

function isCartProductValidationUser($user_id, $cart_product_id)
{
    $user_id_sanitize = sanitize($user_id);
    $cart_product_id_sanitize = sanitize($cart_product_id);
    $cart_id = getDatabaseCartProductCartID($cart_product_id_sanitize);
    $user_id_in_cart = getDatabaseCartUserID($cart_id);

    return ($cart_id !== false && $user_id_in_cart == $user_id) ? true : false;
}



function echoModelDelivery($user_id)
{
    if (isDatabaseUserSelectAddressByUserID($user_id)) {
        echo 'Pagamento na Entrega';
    } else {
        echo 'Retirada no Local';
    }
}

function doUpdateOrderDeliveryStatus($order_id, $deliverman_id = false)
{
    $order_id_sanitize = sanitize($order_id);
    $deliverman_id_sanitize = sanitize($deliverman_id);
    $order_last_log_id = doDatabaseRequestOrderLogsLastLogByOrderID($order_id_sanitize);

    $actual_order_status_id = getDatabaseRequestOrderLogStatusDelivery($order_last_log_id);

    if ($actual_order_status_id == 1) {
        doRequestOrderLogInsert($order_id, 2);
    }

    if ($actual_order_status_id == 2) {
        doPrintOrder($order_id);
        doRequestOrderLogInsert($order_id, 3);
    }

    if ($actual_order_status_id == 3) {
        if (isDatabaseRequestOrderSelectAddress($order_id)) {
            $order_update_fields = array(
                'deliveryman' => $deliverman_id_sanitize
            );
            doDatabaseRequestOrderUpdate($order_id, $order_update_fields);
            doRequestOrderLogInsert($order_id, 4);
        } else {
            doRequestOrderLogInsert($order_id, 7);
        }
    }


    if (($actual_order_status_id == 4) || ($actual_order_status_id == 7)) {
        doRequestOrderLogInsert($order_id, 5);

        $order_update_fields = array(
            'status' => 7
        );
        doDatabaseRequestOrderUpdate($_POST['order_id'], $order_update_fields);
    }



}