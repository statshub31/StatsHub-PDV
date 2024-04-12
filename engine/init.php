<?php

// Inicia a sessão e o buffer de saída
session_start();
ob_start();


global $image_config_dir;
global $image_user_dir;
global $image_model_dir;

$image_config_dir = '/layout/images/config/';
$image_user_dir = '/layout/images/users/';
$image_model_dir = '/layout/images/model/';

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
require_once(__DIR__ . "/database/measure.php");
require_once(__DIR__ . "/database/categorys.php");


if (getGeneralSecurityLoggedIn() === true) {

    $session_account_id = getGeneralSecuritySession('account_id');
    $in_account_data = getDatabaseAccountsData($session_account_id, 'id');
    $in_account_id = $in_account_data['id'];
    $in_user_data = getDatabaseUsersData(getDatabaseUserIDByAccountID($in_account_id), 'id');
    $in_user_id = $in_user_data['id'];
}
