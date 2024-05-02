<?php
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
ini_set('memory_limit', '-1');

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
require_once (__DIR__ . "/general/general.php");
require_once (__DIR__ . "/general/security.php");
require_once (__DIR__ . "/general/alerts.php");

// DATABASE FUNCTIONS


require_once (__DIR__ . "/database/mysql.php");
require_once (__DIR__ . "/database/groups.php");
require_once (__DIR__ . "/database/users.php");
require_once (__DIR__ . "/database/accounts.php");
require_once (__DIR__ . "/database/address.php");
require_once (__DIR__ . "/database/tickets.php");
require_once (__DIR__ . "/database/status.php");
require_once (__DIR__ . "/database/status_delivery.php");
require_once (__DIR__ . "/database/measure.php");
require_once (__DIR__ . "/database/categorys.php");
require_once (__DIR__ . "/database/complements.php");
require_once (__DIR__ . "/database/additional.php");
require_once (__DIR__ . "/database/products.php");
require_once (__DIR__ . "/database/products_price.php");
require_once (__DIR__ . "/database/stock.php");
require_once (__DIR__ . "/database/products_additional.php");
require_once (__DIR__ . "/database/products_complements.php");
require_once (__DIR__ . "/database/products_question.php");
require_once (__DIR__ . "/database/products_question_response.php");
require_once (__DIR__ . "/database/stock_actions.php");
require_once (__DIR__ . "/database/logs_stock.php");
require_once (__DIR__ . "/database/promotions.php");
require_once (__DIR__ . "/database/product_promotion.php");
require_once (__DIR__ . "/database/products_favorites.php");
require_once (__DIR__ . "/database/product_fee_exemption .php");
require_once (__DIR__ . "/database/icons.php");


require_once (__DIR__ . "/database/delivery.php");
require_once (__DIR__ . "/database/carts.php");
require_once (__DIR__ . "/database/cart_products.php");
require_once (__DIR__ . "/database/cart_product_complements.php");
require_once (__DIR__ . "/database/cart_product_additional.php");
require_once (__DIR__ . "/database/cart_product_questions.php");
require_once (__DIR__ . "/database/cart_product_question_responses.php");
require_once (__DIR__ . "/database/address_user_select.php");
require_once (__DIR__ . "/database/ticket_select.php");

require_once (__DIR__ . "/database/request_order.php");
require_once (__DIR__ . "/database/request_order_logs.php");
require_once (__DIR__ . "/database/request_order_available.php");

require_once (__DIR__ . "/database/settings_images.php");
require_once (__DIR__ . "/database/settings_info.php");
require_once (__DIR__ . "/database/settings_pay.php");
require_once (__DIR__ . "/database/settings_delivery.php");
require_once (__DIR__ . "/database/settings_social.php");
require_once (__DIR__ . "/database/settings_horary.php");

require_once (__DIR__ . "/database/multiples_tables.php");



if (getGeneralSecurityLoggedIn() === true) {

    $session_account_id = getGeneralSecuritySession('account_id');
    $in_account_data = getDatabaseAccountsData($session_account_id, 'id');
    $in_account_id = $in_account_data['id'];
    $in_user_data = getDatabaseUsersData(getDatabaseUserIDByAccountID($in_account_id), 'id');
    $in_user_id = $in_user_data['id'];


}
?>
<style>
    :root {
        --primary-color: <?php echo getDatabaseSettingsInfoMainColor(1); ?>;
        --secondary-color: #e9e9e9;
        --first-color: <?php echo getDatabaseSettingsInfoMainColor(1); ?>;
        --first-color-dark: #23004D;
        --first-color-light: #A49EAC;
        --first-color-lighten: #F2F2F2;
        --body-font: 'Open Sans', sans-serif;
        --h1-font-size: 1.5rem;
        --normal-font-size: .938rem;
        --small-font-size: .813rem;
        --border-color: #ced4da;
        --blue: #4e73df;
        --indigo: #6610f2;
        --purple: #6f42c1;
        --pink: #e83e8c;
        --red: #e74a3b;
        --orange: #fd7e14;
        --yellow: #f6c23e;
        --green: #1cc88a;
        --teal: #20c9a6;
        --cyan: #36b9cc;
        --white: #fff;
        --gray: #858796;
        --gray-dark: #5a5c69;
        --primary: #4e73df;
        --secondary: #858796;
        --success: #1cc88a;
        --info: #36b9cc;
        --warning: #f6c23e;
        --danger: #e74a3b;
        --light: #f8f9fc;
        --dark: #5a5c69;
        --breakpoint-xs: 0;
        --breakpoint-sm: 576px;
        --breakpoint-md: 768px;
        --breakpoint-lg: 992px;
        --breakpoint-xl: 1200px;
        --border-color: #ced4da;
        --font-family-sans-serif: "Nunito", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        --font-family-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace
    }
</style>