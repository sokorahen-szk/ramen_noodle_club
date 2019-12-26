<?php

namespace App\Services;

use Noodlehaus\Config;

use App\Lib\SheetSearch;


class BusinessHoursService {

    private static $config;

    public static function run()
    {
        self::$config = new Config("config/config.json");
        $alphabetList = new SheetAlphabet(3);

        var_dump(self::getRamenShopIdList($alphabetList));

        //var_dump($alphabetList->getPosAlphabetNumber('AZ'));
        //var_dump(do_shortcode("[supsystic-tables-cell id=0 row=1 col=C]"));
    }

    private static function getRamenShopIdList($alphabetList)
    {
        $move = self::$config->get("global.ramenShop.searchMoveTarget");
        $beginRowPos = self::$config->get("global.ramenShop.row");
        $beginColPos = self::$config->get("global.ramenShop.col");

        return SheetSearch::search($alphabetList, $move, $beginRowPos, $beginColPos);

    }

}
