<?php

// Inicia a sessão e o buffer de saída
session_start();
ob_start();


global $image_config_dir;
global $image_user_dir;
global $image_model_dir;
global $image_product_dir;

$image_config_dir = '/layout/images/config/';
$image_user_dir = '/layout/images/users/';
$image_model_dir = '/layout/images/model/';
$image_product_dir = '/layout/images/products/';

// PLATFORM FUNCTIONS
require_once(__DIR__ . "/general/general.php");
require_once(__DIR__ . "/general/security.php");
require_once(__DIR__ . "/general/alerts.php");


// DATABASE FUNCTIONS


require_once(__DIR__ . "/database/mysql.php");
require_once(__DIR__ . "/database/groups.php");
require_once(__DIR__ . "/database/users.php");
require_once(__DIR__ . "/database/accounts.php");
require_once(__DIR__ . "/database/address.php");
require_once(__DIR__ . "/database/tickets.php");
require_once(__DIR__ . "/database/status.php");
require_once(__DIR__ . "/database/status_delivery.php");
require_once(__DIR__ . "/database/measure.php");
require_once(__DIR__ . "/database/categorys.php");
require_once(__DIR__ . "/database/complements.php");
require_once(__DIR__ . "/database/additional.php");
require_once(__DIR__ . "/database/products.php");
require_once(__DIR__ . "/database/products_price.php");
require_once(__DIR__ . "/database/stock.php");
require_once(__DIR__ . "/database/products_additional.php");
require_once(__DIR__ . "/database/products_complements.php");
require_once(__DIR__ . "/database/products_question.php");
require_once(__DIR__ . "/database/products_question_response.php");
require_once(__DIR__ . "/database/stock_actions.php");
require_once(__DIR__ . "/database/logs_stock.php");
require_once(__DIR__ . "/database/promotions.php");
require_once(__DIR__ . "/database/product_promotion.php");
require_once(__DIR__ . "/database/product_fee_exemption .php");
require_once(__DIR__ . "/database/icons.php");


require_once(__DIR__ . "/database/carts.php");
require_once(__DIR__ . "/database/cart_products.php");
require_once(__DIR__ . "/database/cart_product_complements.php");
require_once(__DIR__ . "/database/cart_product_additional.php");
require_once(__DIR__ . "/database/cart_product_questions.php");
require_once(__DIR__ . "/database/cart_product_question_responses.php");
require_once(__DIR__ . "/database/address_user_select.php");
require_once(__DIR__ . "/database/ticket_select.php");

require_once(__DIR__ . "/database/request_order.php");
require_once(__DIR__ . "/database/request_order_logs.php");

require_once(__DIR__ . "/database/settings_images.php");
require_once(__DIR__ . "/database/settings_info.php");
require_once(__DIR__ . "/database/settings_pay.php");
require_once(__DIR__ . "/database/settings_delivery.php");
require_once(__DIR__ . "/database/settings_social.php");
require_once(__DIR__ . "/database/settings_horary.php");

require_once(__DIR__ . "/database/multiples_tables.php");



if (getGeneralSecurityLoggedIn() === true) {

    $session_account_id = getGeneralSecuritySession('account_id');
    $in_account_data = getDatabaseAccountsData($session_account_id, 'id');
    $in_account_id = $in_account_data['id'];
    $in_user_data = getDatabaseUsersData(getDatabaseUserIDByAccountID($in_account_id), 'id');
    $in_user_id = $in_user_data['id'];
}
