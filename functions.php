<?php

define("RAMEN_PROJECT_INCLUDE_SERVICE_STATUS", class_exists('BusinessHoursService', false) );
define("VENDOR_PATH", "/RamenProject/vendor/autoload.php");

try {
    define("VENDOR_DIRECTORY_FULL_PATH_NAME", checkVendorPath() );
    require_once dirname(__FILE__) . "/RamenProject/main.php";
} catch (Exception $e) {
    //Autoloadが読み込みできない場合、例外処理として対応し、継続処理は続ける。システム上影響はでない
}
function checkVendorPath() {
    $pathName = null;
    if( file_exists(dirname(dirname(__FILE__)).VENDOR_PATH) ) {
         return dirname( dirname(__FILE__).VENDOR_PATH );
    } else if( file_exists(dirname(__FILE__).VENDOR_PATH) ) {
         return dirname(__FILE__).VENDOR_PATH;
    }
    throw new Exception('Include file does not exists or is not readable.');
}
?>
