<?php

namespace App\Enum;

class BusinessHourStatus {

    //営業時間外
    const CLOSE_BUSINESS_HOURS = 0;

    //営業中
    const OPEN_BUSINESS_HOURS = 1;

    //ラストオーダー
    const LAST_ORDER_BUSINESS_HOURS = 2;

}
