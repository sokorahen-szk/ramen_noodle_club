<?php

namespace App\Lib;

use App\Enum\BusinessHourStatus;

use App\Exceptions\BusinessHourStatusChangeFailureException;

class RamenShopCategories {

    public function __construct(){}

    public function changeCategoriesStatus($id, $status)
    {
        try {
            $result = null;
            if($status == BusinessHourStatus::LAST_ORDER_BUSINESS_HOURS || $status == BusinessHourStatus::OPEN_BUSINESS_HOURS) {
                $result = wp_set_post_categories(0, $id, true);
            } else {
                $result = wp_set_post_categories(0, $id, false);
            }

            if( is_wp_error($result) || $result == false || $result == '' ) {
                throw new BusinessHourStatusChangeFailureException();
            }
            return true;
        } catch (BusinessHourStatusChangeFailureException $e) {
            return false;
        }
    }

}
