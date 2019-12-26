<?php

namespace App\Lib;

use App\Enum\BusinessHourStatus;

class RamenShopCategories {

    public function __construct(){}

    public function changeCategoriesStatus($id, $status)
    {
        $result = null;
        if($status == BusinessHourStatus::LAST_ORDER_BUSINESS_HOURS || $status == BusinessHourStatus::OPEN_BUSINESS_HOURS) {
            $result = wp_set_post_categories(0, $id, true);
        } else {
            $result = wp_set_post_categories(0, $id, false);
        }

        if( !is_wp_error($result) || $result == false ) {
            return true;
        }
        return false;
    }

}
