<?php

# get + Pasta + Arquivo + Função + Dependencia
function getDatabaseIconsData($id)
{
    

    $data = array();
    $id_sanitize = sanitize($id);

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();

    if ($func_num_args > 1) {
        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        return doSelectSingleDB("SELECT $fields FROM `icons` WHERE `id` = '" . $id_sanitize . "' LIMIT 1;");
    } else
        return false;
}

function getDatabaseIconTitle($id)
{
    
    $id_sanitize = sanitize($id);

    $query = getDatabaseIconsData($id_sanitize, 'title');
    return ($query !== false) ? $query['title'] : false;
}

function isDatabaseIconExistID($id)
{
    
    $id_sanitize = sanitize($id);

    $query = doSelectSingleDB("SELECT `id` FROM `icons` WHERE `id`='".$id_sanitize."';");
    return ($query !== false) ? true : false;
}




function doDatabaseIconList($status = false)
{
    
    return doSelectMultiDB("SELECT `id` FROM `icons`");
}

?>