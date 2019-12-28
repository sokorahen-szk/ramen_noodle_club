<?php

require_once VENDOR_DIRECTORY_FULL_PATH_NAME;

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
            //Success
        }
    } catch(BusinessHourSheetEmptyException $e) {
        echo $e->getMessage();
    } catch(ResultResponseMismatchException $e) {
        echo $e->getMessage();
    } catch(BusinessHourStatusChangeFailureException $e) {
        echo $e->getMessage();
    }
?>
