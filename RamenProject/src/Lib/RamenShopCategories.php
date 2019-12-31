<?php

namespace App\Lib;

use App\Enum\BusinessHourStatus;

use App\Exceptions\BusinessHourStatusChangeFailureException;

class RamenShopCategories {

    public function __construct(){}

    /**
     * WordPressの営業中かどうかを判別しているカテゴリーを変更する
     * @param  Int $id        店舗ID
     * @param  Int $status    営業中かどうかのステータス
     * @return Array          変更に関わるデータを配列で返す
     */
    public function changeCategoriesStatus($id, $status) :Array
    {
        try {

            $result = null;
            $currentCategories = wp_get_post_categories($id);

            $inValue = array_search($status, $currentCategories);

            $response = [
                "isSuccess"  => false,
                "shopId"     => $id,
                "status"     => $status,
                "changed"    => false
            ];

            //IDが 0 である場合、スキップさせる。
            if($id === 0) {
                $response["isSuccess"] = true;
                return $response;
            }

            if($status === BusinessHourStatus::LAST_ORDER_BUSINESS_HOURS || $status === BusinessHourStatus::OPEN_BUSINESS_HOURS) {
                $result = wp_set_post_categories($id, $this->addCategoriesId($currentCategories, 1), true);
            } else {
                $result = wp_set_post_categories($id, $this->removeCategoriesId($currentCategories, 1), false);
            }

            //書き換え後が正常であるかどうか
            if( is_wp_error($result) || $result == false || $result == '' ) {
                throw new BusinessHourStatusChangeFailureException([
                    "ramenShopId"           => $id,
                    "business_hours_status" => $status
                ]);
            }

            $response["isSuccess"] = true;
            $response["changed"] = true;
            return $response;
        } catch (BusinessHourStatusChangeFailureException $e) {
            return $response;
        }
    }

    private function removeCategoriesId($array, $ignoreValue)
    {
        $pos = array_search($ignoreValue, $array);
        $results = [];
        if($pos !== false) {
            $array[$pos] = null;
        }
        foreach($array as $value) {
            if($value) $results[] = $value;

        }
        return $results;
    }

    private function addCategoriesId($array, $addValue)
    {
        $pos = array_search($addValue, $array);
        if($pos === false) {
            $array[] = $addValue;
        }
        return $array;
    }
}
