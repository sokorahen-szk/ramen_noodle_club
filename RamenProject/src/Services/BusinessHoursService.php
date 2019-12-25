<?php

namespace App\Services;

use Noodlehaus\Config;
use App\Lib\SheetAlphabet;

class BusinessHoursService {

    private static $config;

    public static function run() :void
    {
        self::$config = new Config("config/config.json");
        $alphabetList = new SheetAlphabet(2);
        var_dump($alphabetList->getPosAlphabetNumber('AZ'));
    }

}
