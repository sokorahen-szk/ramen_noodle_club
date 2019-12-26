<?php

namespace App\Services;

use Noodlehaus\Config;

use App\Lib\SheetSearch;
use App\Lib\SheetAlphabet;
use App\Lib\RamenShopCategories;
use App\Enum\DayOfWeek;

class BusinessHoursService {

    private $config;
    private $sheetSearch;
    private $sheetAlphabet;
    private $ramenShopCategories;

    public function __construct()
    {
        $this->config = new Config("config/config.json");
        $this->sheetSearch = new SheetSearch();
        $this->sheetAlphabet = new SheetAlphabet(2);
        $this->ramenShopCategories = new RamenShopCategories();
    }

    public function run()
    {

        $results = [];

        //アルファベットの文字列 A 〜 AZまでを取得
        $alphabetList = $this->sheetAlphabet->getAllAlphabet();

        //シートのIDを取得（曜日ごとに決まっている）
        //$sheetId = DayOfWeek::getTodayDayOfWeekNumber();
        $sheetId = 0;

        //対象店舗のIDリストを取得
        $shopIdList = $this->getRamenShopIdList($alphabetList, $sheetId);

        //営業中かどうかシート参照しデータ格納
        $currentBusinessHourStatusList = $this->getBusinessHoursStatusList($alphabetList, $sheetId);

        if( !(count($shopIdList) == count($currentBusinessHourStatusList)) ){
            return false;
        }

        $count = 0;

        foreach($shopIdList as $id) {
            $results[] = $this->ramenShopCategories->changeCategoriesStatus(
                $id,
                $currentBusinessHourStatusList[$count++]
            );
        }
        return !in_array(false, $results);
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

    private function getBusinessHoursStatusList($alphabetList, $sheetId)
    {
        $currentTime = explode(':', date("H:i"));
        $beginColPos = $currentTime[0] * 2 + floor($currentTime[1]/30); // 0 - 57 が求まる
        return $this->sheetSearch->search(
            $alphabetList,
            $this->config->get("global.businessHours.searchMoveTarget"),
            $this->config->get("global.businessHours.row"),
            $alphabetList[$beginColPos + $this->sheetAlphabet->getPosAlphabetNumber($this->config->get("global.businessHours.row"))],
            $sheetId
        );
    }

}
