<?php

include_once VENDOR_DIRECTORY_FULL_PATH_NAME;

use App\Services\BusinessHoursService;

use App\Exceptions\BusinessHourSheetEmptyException;
use App\Exceptions\ResultResponseMismatchException;
use App\Exceptions\BusinessHourStatusChangeFailureException;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__); //.envのパスが異なる場合は変更する
$dotenv->load();

function env($EnvPathName, $defaultValue = null) {
    global $dotenv;
    return @getenv($EnvPathName) ?: $defaultValue;
}

$businessHoursService = new BusinessHoursService();
    try {
        if($businessHoursService->run()) {
            //
        }
    } catch(BusinessHourSheetEmptyException $e) {
        //
    } catch(ResultResponseMismatchException $e) {
        //
    } catch(BusinessHourStatusChangeFailureException $e) {
        //
    }
?>
