<?php

// このcommon.phpはサーバに含めない
include_once dirname(__FILE__) . "/RamenProject/common.php";

/****************************************************************
▼　ここから、 営業時間のカテゴリーを変更する
    functions.php 内で使うコード　▼
****************************************************************/
function hogehogeeigyoutyuu() {
    define("VENDOR_PATH", "/RamenProject/vendor/autoload.php");

    try {
        define("VENDOR_DIRECTORY_FULL_PATH_NAME", checkVendorPath() );
        include_once dirname(__FILE__) . "/RamenProject/main.php";
    } catch (Exception $e) {
        error_log(sprintf("categories change error %s", $e->getMessage()), 0);
    }
}
function checkVendorPath() {
    if( file_exists(dirname(dirname(__FILE__)).VENDOR_PATH) ) {
        return dirname( dirname(__FILE__).VENDOR_PATH );
    } else if( file_exists(dirname(__FILE__).VENDOR_PATH) ) {
        return dirname(__FILE__).VENDOR_PATH;
    }
    throw new Exception('Include file does not exists or is not readable.');
}
add_action('business_hours_status_change', 'hogehogeeigyoutyuu');
/****************************************************************
▲　ここまで、 営業時間のカテゴリーを変更する
    functions.php 内で使うコード　▲
****************************************************************/
