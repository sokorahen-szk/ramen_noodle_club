<?php

namespace App\Lib;

use App\Enum\BusinessHourStatus;
use App\Exceptions\BusinessHourStatusChangeFailureException;

class RamenShopCategories {

    private int $defaultCategoryId = 1;

    public function __construct(){}

    /**
     * WordPressの営業中かどうかを判別しているカテゴリーを変更する
     * @param  Int $id        店舗ID
     * @param  Int $status    営業中かどうかのステータス
     * @return Array          変更に関わるデータを配列で返す
     */
    public function changeCategoriesStatus($id, $status) :Array
    {
        $result = null;
        $currentCategories = wp_get_post_categories($id);

        $response = [
            "isSuccess"  => false,
            "shopId"     => $id,
            "status"     => $status,
            "changed"    => false
        ];

        //idが 0 である場合、そのままカテゴリーの付け外しをしない。
        if($id === 0) {
            $response["isSuccess"] = true;
            return $response;
        }

        if ($status === BusinessHourStatus::OPEN_BUSINESS_HOURS) {
            $result = wp_set_post_categories($id, $this->addCategoriesId($currentCategories, $this->defaultCategoryId), true);
        }

        //$status = 0(営業時間外) or 2(ラストーオーダー)は営業中を外す
        if ($status === BusinessHourStatus::CLOSE_BUSINESS_HOURS
        || $status == BusinessHourStatus::LAST_ORDER_BUSINESS_HOURS) {
            $result = wp_set_post_categories($id, $this->removeCategoriesId($currentCategories, $this->defaultCategoryId), false);
        }

        //書き換え後が正常であるかどうか
        if( is_wp_error($result) || $result == false || $result === null ) {
            throw new BusinessHourStatusChangeFailureException([
                "ramenShopId"           => $id,
                "business_hours_status" => $status
            ]);
        }

        $response["isSuccess"] = true;
        $response["changed"] = true;
        return $response;
    }

    /**
     * WordPressのカテゴリを外す
     * @param  Array $array       対象の店舗が持っているカテゴリIDの配列
     * @param  Int   $categoryId  除外するID番号
     * @return Array              除外するIDを取り除いたカテゴリIDの配列を返す
     */
    private function removeCategoriesId($array, $categoryId)
    {
        $pos = array_search($categoryId, $array);
        $results = [];
        if($pos !== false) {
            $array[$pos] = null;
        }
        foreach($array as $value) {
            if($value) $results[] = $value;

        }
        return $results;
    }

    /**
     * WordPressのカテゴリを追加する
     * @param  Array $array       対象の店舗が持っているカテゴリIDの配列
     * @param  Int   $categoryId  追加するカテゴリID
     * @return Array              追加するカテゴリIDの最後尾に追加して配列を返す
     */
    private function addCategoriesId($array, $categoryId)
    {
        $pos = array_search($categoryId, $array);
        if($pos === false) {
            $array[] = $categoryId;
        }
        return $array;
    }
}
