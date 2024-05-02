<?php

# get + Pasta + Arquivo + Função + Dependencia

function getDatabaseStatusDeliveryData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `status_delivery` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseStatusDeliveryTitle($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseStatusDeliveryData($id_sanitize, 'title');
    return ($query !== false) ? $query['title'] : false;
}

function isDatabaseStatusDeliveryExistID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `status_delivery` WHERE `id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}

function doDatabaseStatusDeliveryList($status = false)
{
    
    return doSelectMultiDB("SELECT `id` FROM `status_delivery`");
}

?>