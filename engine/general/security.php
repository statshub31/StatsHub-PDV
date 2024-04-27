<?php

function setGeneralSecuritySession($key, $data)
{
	global $sessionPrefix;
	//	$_SESSION['cooldownSession'] = time();
	$_SESSION[$sessionPrefix . $key] = $data;
}

function getGeneralSecuritySession($key)
{
	global $sessionPrefix;
	getGeneralSecurityTimeSession();
	return (isset($_SESSION[$sessionPrefix . $key])) ? $_SESSION[$sessionPrefix . $key] : false;
}

function getGeneralSecurityTimeSession()
{
	if (isset($_SESSION['cooldownSession']) && (time() - $_SESSION['cooldownSession'] > config('timeLogout'))) {
		header('Location: /index');
		session_destroy();
	}
}


function getGeneralSecurityLoggedIn()
{
	return (getGeneralSecuritySession('account_id') !== false) ? true : false;
}

function getGeneralSecurityToken($token)
{
	return ((isset($_SESSION[$token]) && isset($_POST['token'])) && ($_SESSION[$token] == $_POST['token'])) ? true : false;
}

function setGeneralSecurityToken($token)
{
	$_SESSION[$token] = generateRandomString(true, false, true, 10);
}

function addGeneralSecurityToken($token)
{
	setGeneralSecurityToken($token);
	return $_SESSION[$token];
}

function destroyGeneralSecurityToken($token)
{
	unset($_SESSION[$token]);
}

function doGeneralSecurityProtect()
{
	if (getGeneralSecurityLoggedIn() === false) {
		header('Location: /login');
		exit();
	}
}

function doGeneralSecurityCart()
{
	$account_id = getGeneralSecuritySession('account_id');
	$user_data = getDatabaseUsersData(getDatabaseUserIDByAccountID($account_id), 'id');
	$user_id = $user_data['id'];

	if (isDatabaseCartExistIDByUserID($user_id) === false) {
		header('Location: /menu');
	} else {
		$cart_id = getDatabaseCartExistIDByUserID($user_id);
		if (getDatabaseCartProductRowCountByCartID($cart_id) <= 0) {
			header('Location: /menu');
		}
	}

	return $cart_id;
}

function doGeneralSecurityOrder($order_id)
{

	$account_id = getGeneralSecuritySession('account_id');
	$user_data = getDatabaseUsersData(getDatabaseUserIDByAccountID($account_id), 'id');
	$user_id = $user_data['id'];

	if (isDatabaseRequestOrderExistID($order_id) === false) {
		header('Location: /cart');
	} else {
		$cart_id = getDatabaseRequestOrderCartID($order_id);

		if (isDatabaseCartUserValidation($user_id, $cart_id) === false) {
			header('Location: /cart');
		}
	}
	
	return doDatabaseRequestOrderLogsLastLogByOrderID($order_id);
}

function doGeneralSecurityLoginRedirect()
{
	if (getGeneralSecurityLoggedIn() === true) {
		header('Location: /myaccount');
	}
}

function getGeneralSecurityPanelAccess()
{
	doGeneralSecurityProtect();

	$query = getDatabaseAccountGroupID(getGeneralSecuritySession('account_id'));

	if ($query < 2) {
		header('Location: /index');
		exit();
	}
}

function getGeneralSecurityDeliveryManAccess()
{
	doGeneralSecurityProtect();

	$query = getDatabaseAccountGroupID(getGeneralSecuritySession('account_id'));

	if ($query < 3) {
		header('Location: /index');
		exit();
	}
}

function isGeneralSecurityDeliveryManAccess()
{
	return (getDatabaseAccountGroupID(getGeneralSecuritySession('account_id')) >= 2) ? true : false;
}

function getGeneralSecurityAttendantAccess()
{
	doGeneralSecurityProtect();

	$query = getDatabaseAccountGroupID(getGeneralSecuritySession('account_id'));

	if ($query < 4) {
		header('Location: /index');
		exit();
	}
}

function isGeneralSecurityAttendantAccess()
{
	return (getDatabaseAccountGroupID(getGeneralSecuritySession('account_id')) >= 3) ? true : false;
}

function getGeneralSecurityManagerAccess()
{
	doGeneralSecurityProtect();

	$query = getDatabaseAccountGroupID(getGeneralSecuritySession('account_id'));

	if ($query < 5) {
		header('Location: /index');
		exit();
	}
}

function isGeneralSecurityManagerAccess()
{
	return (getDatabaseAccountGroupID(getGeneralSecuritySession('account_id')) >= 4) ? true : false;
}


function doGeneralSecurityValidateIP($ip)
{
	$ipL = getGeneralSecuritySafeIPLong($ip);
	$ipR = long2ip($ipL);

	if ($ip === $ipR) {
		return true;
	} elseif ($ip == '::1') {
		return true;
	} else {
		return false;
	}
}

function getGeneralSecurityIP()
{

	$IP = '';
	if (getenv('HTTP_CLIENT_IP')) {
		$IP = getenv('HTTP_CLIENT_IP');
	} elseif (getenv('HTTP_X_FORWARDED_FOR')) {
		$IP = getenv('HTTP_X_FORWARDED_FOR');
	} elseif (getenv('HTTP_X_FORWARDED')) {
		$IP = getenv('HTTP_X_FORWARDED');
	} elseif (getenv('HTTP_FORWARDED_FOR')) {
		$IP = getenv('HTTP_FORWARDED_FOR');
	} elseif (getenv('HTTP_FORWARDED')) {
		$IP = getenv('HTTP_FORWARDED');
	} else {
		// VERIFICA SE CLOUNDFLARE ESTÁ ATIVO
		$IP = (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'];
	}
	return $IP;
}

function getGeneralSecuritySafeIPLong($ip)
{
	return sprintf('%u', ip2long($ip));
}

// Gets you the actual IP address even from users in long type
function getGeneralSecurityIPLong()
{
	return getGeneralSecuritySafeIPLong(getGeneralSecurityIP());
}
