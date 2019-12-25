<?php declare(strict_types = 1);

namespace App\Services;

use Noodlehaus\Config;

class BusinessHoursService {

    private static $config;

    public static function run() :void
    {
        self::$config = new Config("config/config.json");
    }

}
