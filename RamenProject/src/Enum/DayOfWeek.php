<?php

namespace App\Enum;

class DayOfWeek {
    const SUNDAY       =   3;
    const MONDAY       =   4;
    const TUESDAY      =   5;
    const WEDNESDAY    =   6;
    const THURSDAY     =   7;
    const FRIDAY       =   8;
    const SATURDAY     =   9;

    private static $dayOfWeekList = [
        "sunday"    => self::SUNDAY,
        "monday"    => self::MONDAY,
        "tuesday"   => self::TUESDAY,
        "wednesday" => self::WEDNESDAY,
        "thursday"  => self::THURSDAY,
        "friday"    => self::FRIDAY,
        "saturday"  => self::SATURDAY,
    ];

    public static function getTodayDayOfWeekNumber()
    {
        $currentDayOfWeek = strtolower(date("l"));
        if(isset(self::$dayOfWeekList[$currentDayOfWeek])) {
            return self::$dayOfWeekList[$currentDayOfWeek];
        };
        return null;
    }
}

 ?>
