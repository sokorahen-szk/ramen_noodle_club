<?php

namespace App\Services;

//Vendor
use Noodlehaus\Config;

//Libs
use App\Lib\SheetSearch;
use App\Lib\SheetAlphabet;
use App\Lib\RamenShopCategories;

//Enums
use App\Enum\DayOfWeek;

//Exceptions
use App\Exceptions\ResultResponseMismatchException;

class BusinessHoursService {

    /**
     * @var \Noodlehaus\Config
     */
    private $config;

    /**
     * @var App\Lib\SheetSearch
     */
    private $sheetSearch;

    /**
     * @var App\Lib\SheetAlphabet
     */
    private $sheetAlphabet;

    /**
     * @var App\Lib\RamenShopCategories
     */
    private $ramenShopCategories;

    public function __construct(
        Config $config,
        SheetSearch $sheetSearch,
        SheetAlphabet $sheetAlphabet,
        RamenShopCategories $ramenShopCategories
    )
    {
        $this->config = $config;
        $this->sheetSearch = $sheetSearch;
        $this->sheetAlphabet = $sheetAlphabet;
        $this->ramenShopCategories = $ramenShopCategories;
    }

    /**
     * 営業時間を確認し、変更するプロセスを起動する
     * @return Object 書き換えた店舗のデータやステータス状態をオブジェクトで返す
     */
    public function run() :Object
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

        if(count($shopIdList) != count($currentBusinessHourStatusList)){
            throw new ResultResponseMismatchException([
                "sheet_id"            => $sheetId,
                "alphabet_list"       => $alphabetList,
                "shop_id_list"        => $shopIdList,
                "business_hours_list" => $currentBusinessHourStatusList
            ]);
        }

        foreach($shopIdList as $id) {
            $results[] = $this->ramenShopCategories->changeCategoriesStatus(
                (int) $id,
                (int) $currentBusinessHourStatusList[$count++]
            );
        }

        return (Object) [
            "status"           => !in_array(false, array_column($results, "isSuccess")),
            "data"             => $results
        ];
    }

    /**
     * ラーメン屋さんのIDリストを取得する
     * @param  Array $alphabetList アルファベット配列 Ex: ”A" 〜 "AZ" の情報が配列として入る
     * @param  Int   $sheetId      参照するシートID
     * @return Array               対象のシートを参照し、取得したデータを配列で返す。
     */
    private function getRamenShopIdList($alphabetList, $sheetId) :Array
    {
        return $this->sheetSearch->search(
            $alphabetList,
            $this->config->get("global.ramenShop.searchMoveTarget"),
            $this->config->get("global.ramenShop.row"),
            $this->config->get("global.ramenShop.col"),
            $sheetId
        );
    }

    private function getBusinessHoursStatusList($alphabetList, $sheetId) :Array
    {
        $currentTime = explode(':', date("H:i")); //HH:MM

        //スプレットシートの営業時間 00:00 - 23:30　を現在の位置と合わせて座標取得するための位置情報を数値で取得する
        $beginColPos = ( $currentTime[0] * 2 + floor($currentTime[1]/30) ) - 1; // -1 - 56 が求まる

        return $this->sheetSearch->search(
            $alphabetList,
            $this->config->get("global.businessHours.searchMoveTarget"),
            $this->config->get("global.businessHours.row"),
            $alphabetList[$beginColPos + $this->sheetAlphabet->getPosAlphabetNumber($this->config->get("global.businessHours.col"))],
            $sheetId
        );
    }
}
