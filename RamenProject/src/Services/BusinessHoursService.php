<?php

namespace App\Services;

use Noodlehaus\Config;

//Libs
use App\Lib\SheetSearch;
use App\Lib\SheetAlphabet;
use App\Lib\RamenShopCategories;

//Enums
use App\Enum\DayOfWeek;

//Exceptions
use App\Exceptions\ResultResponseMismatchException;

use App\Lib\SlackClient;

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
        $count = 0;

        //アルファベットの文字列 A 〜 AZまでを取得
        $alphabetList = $this->sheetAlphabet->getAllAlphabet();

        //シートのIDを取得（曜日ごとに決まっている）
        $sheetId = DayOfWeek::getTodayDayOfWeekNumber();

        //対象店舗のIDリストを取得
        $shopIdList = $this->getRamenShopIdList($alphabetList, $sheetId);

        //営業中かどうかシート参照しデータ格納
        $currentBusinessHourStatusList = $this->getBusinessHoursStatusList($alphabetList, $sheetId);

        $t = new SlackClient();
        $t->pushMessage("A");

        if( !(count($shopIdList) == count($currentBusinessHourStatusList)) ){
            throw new ResultResponseMismatchException();
        }

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
        $currentTime = explode(':', date("H:i")); //HH:MM

        //スプレットシートの営業時間 00:00 - 23:30　を現在の位置と合わせて座標取得するための位置情報を数値で取得する
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
