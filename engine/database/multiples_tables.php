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

    $first_log = doDatabaseRequestOrderLogsFirstLogByOrderID($order_id); // Função fictícia para obter o primeiro log do pedido

    // Escapando as aspas para que o conteúdo seja incluído corretamente no JavaScript
    $conteudo = '

    <style>
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

    li, ul, ol {
        margin: 1px;
    }

    li {
        width: 100%;
    }

    .subtopic {
        font-weight: 600;
    }
</style>
        <div id="content">
            <div id="content-header">
                <label id="name">
                    ' . getDatabaseSettingsInfoTitle(1) . '
                </label><br>
                <small id="cnpj">CNPJ: 10.0.0.0.0.0.0.0.</small>
            </div>
            <hr>
            <div id="type_delivery">
                ' . getDatabaseDeliveryTitle(1) . '
            </div>
            <hr>
            <div id="date">
                ' . doDate(getDatabaseRequestOrderLogCreated($first_log)) . ' às ' . doTime(getDatabaseRequestOrderLogCreated($first_log)) . '
            </div>
            <hr>
            <div id="number-delivery">
                PEDIDO #' . $order_id . '
            </div>
            <hr>
            <label class="title">Itens:</label><br>
            <hr>';
    // Lista de itens do carrinho
    $cart_id = getDatabaseRequestOrderCartID($order_id);
    $cart_list = doDatabaseCartProductsListByCartID($cart_id);
    $itens_count = 0;
    if ($cart_list) {
        foreach ($cart_list as $data) {
            $cart_product_list_id = $data['id']; // ID do produto no carrinho
            $cart_product_id = getDatabaseCartProductProductID($cart_product_list_id); // ID do produto
            $obs = getDatabaseCartProductObservation($cart_product_list_id); // Observação do produto
            $size_id = getDatabaseCartProductPriceID($cart_product_list_id); // ID do preço
            $measure_id = getDatabaseProductSizeMeasureID($size_id); // ID da medida
            $user_id = getDatabaseCartUserID($cart_product_list_id); // ID do usuário
            $itens_count += getDatabaseCartProductAmount($cart_product_list_id); // Contador de itens
            $main_address_id = getDatabaseRequestOrderAddressIDSelect($order_id); // ID do endereço principal
            $discount = getDatabaseTicketValue(getDatabaseCartTicketSelectTicketID(getDatabaseCartTicketSelectByCartID($cart_product_list_id))); // Desconto
            $conteudo .= '
                    <label class="order">
                        (' . getDatabaseCartProductAmount($cart_product_list_id) . ')
                        ' . getDatabaseProductName($cart_product_id) . ' -
                        ' . getDatabaseProductPriceSize($size_id) . '
                        ' . getDatabaseMeasureTitle($measure_id) . '
                        <span class="value">R$ ' . doCartTotalPriceProduct($cart_product_list_id) . '</span>
                    </label><br>
                    <hr>';
        }
    }
    $conteudo .= '
            <label class="title">Dados do Cliente:</label><br>
            <label>
                <span class="subtopic">Nome:</span>
                ' . getDatabaseUserName($user_id) . '<br>
            </label>
            <label>
                <span class="subtopic">Telefone:</span>
                ' . getDatabaseUserPhone($user_id) . '<br>
            </label>
            <label>
                <span class="subtopic">Quantidade de Itens:</span>
                ' . $itens_count . '<br>
            </label>
            <label>
                <span class="subtopic">Entrega:</span><br>
                ' . getDatabaseAddressPublicPlace($main_address_id) . ',
                ' . getDatabaseAddressNumber($main_address_id) . ', (
                ' . getDatabaseAddressComplement($main_address_id) . ')
                ' . getDatabaseAddressNeighborhood($main_address_id) . ',
                ' . getDatabaseAddressCity($main_address_id) . ' -
                ' . getDatabaseAddressState($main_address_id) . '.
                <br>
            </label>
            <hr>
            <label class="title">Pagamento:</label><br>
            <label>
                <span class="subtopic">Forma de Pagamento:</span><br>
            </label>
            <label>Desconto: <span class="value">
            <small>Usado o cupom [<?php echo getDatabaseTicketCode(getDatabaseCartTicketSelectTicketID(getDatabaseRequestOrderTicketID($cart_id))) ?>] e obtido o desconto de
                ['.($discount !== false) ? $discount : 'Nenhum desconto selecionado.'.']</small>

        </span>
            </label><br>
            <label>Total: <span class="value">R$ ' . (doCartTotalPrice($order_id) - doCartTotalPriceDiscount($order_id)) . '</span>
            </label><br>
        </div>
        <script>
            window.print();
            window.close();
        </script>';

    // Escapando o conteúdo usando json_encode
    $conteudoEscapado = json_encode($conteudo);

    // Criando o script JavaScript para abrir a nova janela com o conteúdo
    echo "<script>";
    echo "var novaJanela = window.open('', '_blank');";
    echo "novaJanela.document.write($conteudoEscapado);";
    echo "</script>";
}