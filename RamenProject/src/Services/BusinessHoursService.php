<?php

namespace App\Services;

use Noodlehaus\Config;

use App\Lib\SheetSearch;
use App\Lib\SheetAlphabet;

class BusinessHoursService {

    private $config;
    private $sheetSearch;
    private $sheetAlphabet;

    public function __construct()
    {
        $this->config = new Config("config/config.json");
        $this->sheetSearch = new SheetSearch();
        $this->sheetAlphabet = new SheetAlphabet(2);
    }

    public function run()
    {
        $alphabetList = $this->sheetAlphabet->getAllAlphabet();
        $shopIdList = $this->getRamenShopIdList($alphabetList, 0);
        var_dump($shopIdList);
        //var_dump($alphabetList->getPosAlphabetNumber('AZ'));
    }

    private function getRamenShopIdList($alphabetList, $sheetId)
    {
        return $this->sheetSearch->search(
            $alphabetList,
            $this->config->get("global.ramenShop.searchMoveTarget"),
            $this->config->get("global.ramenShop.row"),
            $this->config->get("global.ramenShop.col"),
            $sheetId
        );

    }

}
