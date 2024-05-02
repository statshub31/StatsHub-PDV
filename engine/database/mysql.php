<?php

$time = time();
if (!function_exists("elapsedTime")) {
	function elapsedTime($l_start = false, $l_time = false)
	{
		if ($l_start === false)
			global $l_start;
		if ($l_time === false)
			global $l_time;

		$l_time = explode(' ', microtime());
		$l_finish = $l_time[1] + $l_time[0];
		return round(($l_finish - $l_start), 4);
	}
}

// $connect = new mysqli($config['sqlHost'], $config['sqlUser'], $config['sqlPassword'], $config['sqlDatabase']);
$connect = new mysqli('localhost', 'root', '', 'stpdv_teste');

function doTruncateTableDB($table)
{
	global $connect;
	mysqli_query($connect, 'TRUNCATE ' . $table . ';') or die(var_dump($table) . "<br>(query - <font color='red'>SQL error</font>) <br><br><br>" . mysqli_error($connect));
}

function doEscapeStringDB($escapestr)
{
    global $connect;

    // Use prepared statements para evitar injeções de SQL
    $stmt = $connect->prepare("SELECT ?");
    
    // Verifique se a preparação da declaração foi bem-sucedida
    if ($stmt) {
        // Vincule o parâmetro à declaração
        $stmt->bind_param("s", $escapestr);
        
        // Execute a declaração
        $stmt->execute();
        
        // Obtenha o resultado, se necessário
        $stmt->bind_result($result);
        $stmt->fetch();
        
        // Feche a declaração
        $stmt->close();
        
        // Retorne o valor escapado
        return $result;
    } else {
        // Lidar com o erro na preparação da declaração
        // Você pode querer logar o erro ou retornar um valor padrão, dependendo dos requisitos da sua aplicação
        return false;
    }
}

function doInsertMultiDB($query)
{
	global $connect;
	mysqli_multi_query($connect, $query) or die(var_dump($query) . "<br>(query - <font color='red'>SQL error</font>) <br><br><br>" . mysqli_error($connect));
}

function getLastInsertDB()
{

	global $connect;
	global $aacQueries;
	$aacQueries++;
	global $accQueriesData;

	$query = "SELECT  LAST_INSERT_ID();";

	$accQueriesData[] = "[" . elapsedTime() . "] " . $query;
	$result = mysqli_query($connect, $query) or die(var_dump($query) . "<br>(query - <font color='red'>SQL error</font>) <br>Type: <b>select_single</b> (select single row from database)<br><br>" . mysqli_error($connect));
	$row = mysqli_fetch_assoc($result);


	return ($row != 0) ? $row['LAST_INSERT_ID()'] : false;
}

function doSelectSingleDB($query)
{
	global $connect;
	global $aacQueries;
	$aacQueries++;

	global $accQueriesData;
	$accQueriesData[] = "[" . elapsedTime() . "] " . $query;
	$result = mysqli_query($connect, $query) or die(var_dump($query) . "<br>(query - <font color='red'>SQL error</font>) <br>Type: <b>select_single</b> (select single row from database)<br><br>" . mysqli_error($connect));
	$row = mysqli_fetch_assoc($result);
	return !empty($row) ? $row : false;
}

function doSelectMultiDB($query)
{
	global $connect;
	global $aacQueries;
	$aacQueries++;
	global $accQueriesData;
	$accQueriesData[] = "[" . elapsedTime() . "] " . $query;
	$array = array();
	$results = mysqli_query($connect, $query) or die(var_dump($query) . "<br>(query - <font color='red'>SQL error</font>) <br>Type: <b>select_multi</b> (select multiple rows from database)<br><br>" . mysqli_error($connect));
	while ($row = mysqli_fetch_assoc($results)) {
		$array[] = $row;
	}
	return !empty($array) ? $array : false;
}

function doResetDB()
{
	doUpdateDB("SET foreign_key_checks = 0;");
	doTruncateTableDB('accounts');
	doTruncateTableDB('accounts_cb');
	doTruncateTableDB('games');
	doTruncateTableDB('games_rewards');
	doTruncateTableDB('games_scratch_card');
	doTruncateTableDB('games_telesena');
	doTruncateTableDB('products_purchase');
	doTruncateTableDB('rewards');
	doTruncateTableDB('rewards_check');
	doTruncateTableDB('settings');
	doTruncateTableDB('users');
	doTruncateTableDB('users_social');
	doUpdateDB("SET foreign_key_checks = 1;");
	doInsertDB("
	INSERT INTO settings (
		`site_title`,
	`site_description`,
	`password`,
	`cpf`,
	`address`,
	`baseboard`,
	`baseboard_text`,
	`game_register`,
	`reward_per_product`,
	`reward_per_totalpurchase`,
	`value_for_exp`,
	`value_from_exp`,
	`url`,`n_email`,
	`b_email`,
	`b_password`,
	`whatsapp_status`,
	`whatsapp_contact`,
	`whatsapp_message`,
	`facebook_status`,
	`facebook_contact`,
	`instagram_status`,
	`instagram_contact`,
	`pg_title`,
	`pg_subtitle`,
	`pg_about`,
	`pg_about_tp1`,
	`pg_about_dp1`,
	`pg_about_ip1`,
	`pg_about_tp2`,
	`pg_about_dp2`,
	`pg_about_ip2`,
	`pg_about_tp3`,
	`pg_about_dp3`,
	`pg_about_ip3`,
	`pg_nvl`,
	`pg_clientD`,
	`pg_addressURL`
	
	) VALUES (
		'Init Title','Init Desription',0,0,0,0,'',0,0,1,'30.00',5,'localhost', 'usuario@gmail.com', 'usuario@gmail.com', '1213121', 0, '', 
		'', 0, '', 0, '', 
		'Seja bem vindo a uma nova aventura!', 
		'ESTE É O COMEÇO DE UMA LONGA PARCERIA', 
		'Somos uma empresa que vai além de simplesmente oferecer um produto ao cliente; nosso objetivo é transformar cada compra em uma experiência única e memorável.', 
		'Produto',
		'Seu apoio ao escolher nossos produtos é crucial. A cada R$ 30.00 em compras, ganhe 5% de experiência para tornar sua jornada de compras ainda mais gratificante.',
		'fa-fa-teste1', 
		'Produto',
		'Seu apoio ao escolher nossos produtos é crucial. A cada R$ 30.00 em compras, ganhe 5% de experiência para tornar sua jornada de compras ainda mais gratificante.',
		'fa-fa-teste1', 
		'Produto',
		'Seu apoio ao escolher nossos produtos é crucial. A cada R$ 30.00 em compras, ganhe 5% de experiência para tornar sua jornada de compras ainda mais gratificante.',
		'fa-fa-teste1',
		'As recompensas são reservadas aos clientes que fazem suas compras na loja física. Não se esqueça de conferir sempre os termos de participação.',
		'Nossos clientes mais fiéis brilham no topo, destacando-se como os três principais apoiadores da loja.',
		''
		);
	");
	doInsertDB("INSERT INTO `accounts`(`id`, `username`, `password`, `email`, `group_id`, `rules`, `created`) VALUES (1, 'moratech', 'eacd55f94ae1b2956a05eb1d21c6e335', 'moratech@gmail.com', 4, 1, '2024-01-01');");
	doInsertDB("INSERT INTO `users`(`id`, `account_id`, `fname`, `lname`, `phone`) VALUES (1, 1, 'Administrador', 'Moratech', '11994489463');");
	doInsertDB("INSERT INTO `users_social`(`user_id`, `inst_status`, `inst_data`, `whats_status`, `whats_data`, `face_status`, `face_data`) VALUES (1, 0, '', 0, '', 0, '');");
}

function doUpdateDB($query)
{
	voidQuery($query);
}

function doInsertDB($query)
{
	voidQuery($query);

	// Obter o ID gerado após a inserção
	global $connect;
	$insertedID = mysqli_insert_id($connect);

	return $insertedID;
}


function doDeleteDB($query)
{
	voidQuery($query);
}

// Send a void query
function voidQuery($query)
{
	global $connect;
	global $aacQueries;
	$aacQueries++;
	global $accQueriesData;
	$accQueriesData[] = "[" . elapsedTime() . "] " . $query;
	mysqli_query($connect, $query) or die(var_dump($query) . "<br>(query - <font color='red'>SQL error</font>) <br>Type: <b>voidQuery</b> (voidQuery is used for update, insert or delete from database)<br><br>" . mysqli_error($connect));
}

function doMysqlConvertArrayKey($inputArray)
{
	$resultString = implode(', ', array_map(function ($item) {
		return "`$item`";
	}, $inputArray));

	return $resultString;
}

function doMysqlConvertArrayValue($inputArray)
{
	$resultString = implode(', ', array_map(function ($item) {
		return "'" . $item . "'";
	}, $inputArray));

	return $resultString;
}
function getColumnsWithoutId($tableName)
{
	global $connect;

	// Consulta para obter todas as colunas
	$query = "SHOW COLUMNS FROM $tableName";
	$result = mysqli_query($connect, $query) or die(var_dump($query) . "<br>(query - <font color='red'>SQL error</font>) <br>Type: <b>select_single</b> (select single row from database)<br><br>" . mysqli_error($connect));

	// Processa os resultados
	$columns = array();
	while ($row = mysqli_fetch_assoc($result)) {
		// Ignora a coluna "id"
		if ($row["Field"] !== "id") {
			$columns[] = $row["Field"];
		}
	}

	// Retorna os nomes das colunas
	return $columns;
}

function doMysqlConvertUpdateArray($keys, $values, $NULL = false)
{
	$formattedString = '';

	// Verifica se os arrays têm o mesmo número de elementos
	if (count($keys) == count($values)) {
		// Cria a string formatada
		for ($i = 0; $i < count($keys); $i++) {
			$formattedString .= $keys[$i] . " = '" . $values[$i] . "', ";
		}

		// Remove a vírgula extra no final
		$formattedString = rtrim($formattedString, ', ');
	} else {
		// Trata o caso em que os arrays têm tamanhos diferentes
		echo "Erro: Os arrays têm tamanhos diferentes.";
	}

	if($NULL)
		return str_replace("''", "NULL", $formattedString);
	else 
		return $formattedString;
}


?>