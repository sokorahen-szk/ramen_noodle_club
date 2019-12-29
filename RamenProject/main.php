<?php

include_once VENDOR_DIRECTORY_FULL_PATH_NAME;

use App\Services\BusinessHoursService;

use App\Exceptions\BusinessHourSheetEmptyException;
use App\Exceptions\ResultResponseMismatchException;
use App\Exceptions\BusinessHourStatusChangeFailureException;
use Dotenv\Dotenv;

define("END_OF_CELL", "No value");
define("DEFAULT_TIMEZONE", date_default_timezone_get());

$dotenv = Dotenv::createImmutable(__DIR__); //.envのパスが異なる場合は変更する
$dotenv->load();

function env($EnvPathName, $defaultValue = null) {
    global $dotenv;
    return @getenv($EnvPathName) ?: $defaultValue;
}

function changeTimeZone($timezone) {
    date_default_timezone_set($timezone);
}

$businessHoursService = new BusinessHoursService();
    try {
        changeTimeZone("Asia/Tokyo");
        if($businessHoursService->run()) {
            //
        }
        changeTimeZone(DEFAULT_TIMEZONE);
    } catch(BusinessHourSheetEmptyException $e) {
        //
    } catch(ResultResponseMismatchException $e) {
        //
    } catch(BusinessHourStatusChangeFailureException $e) {
        //
    }
?>
