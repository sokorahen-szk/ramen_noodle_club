<?php

require_once VENDOR_DIRECTORY_FULL_PATH_NAME;

use App\Services\BusinessHoursService;

use App\Exceptions\BusinessHourSheetEmptyException;
use App\Exceptions\ResultResponseMismatchException;
use App\Exceptions\BusinessHourStatusChangeFailureException;

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
