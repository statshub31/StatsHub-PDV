<?php

# get + Pasta + Arquivo + Função + Dependencia
function getDatabasePromotionsData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `promotions` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabasePromotionTitle($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabasePromotionsData($id_sanitize, 'title');
    return ($query !== false) ? $query['title'] : false;
}

function isDatabasePromotionExistID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `promotions` WHERE `id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}


function isDatabasePromotionPercentual($id)
{
    
    $id_sanitize = sanitize($id);
    $exist = isDatabasePromotionExistID($id_sanitize);
    $type = getDatabasePromotionTitle($id_sanitize);

    return ($exist && $type === 'Percentual') ? true : false;
}

function isDatabasePromotionReais($id)
{
    
    $id_sanitize = sanitize($id);
    $exist = isDatabasePromotionExistID($id_sanitize);
    $type = getDatabasePromotionTitle($id_sanitize);

    return ($exist && $type === 'Reais') ? true : false;
}


function doDatabasePromotionList($status = false)
{
    
    return doSelectMultiDB("SELECT `id` FROM `promotions`");
}

?>