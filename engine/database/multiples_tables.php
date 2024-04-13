<?php



function doDatabaseRemoveProduct($product_id) {
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