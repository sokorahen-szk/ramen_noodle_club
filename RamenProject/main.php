<?php

require_once VENDOR_DIRECTORY_FULL_PATH_NAME;

use App\Services\BusinessHoursService;

$businessHoursService = new BusinessHoursService();

    if($businessHoursService->run()) {
        //Success
    }

?>
