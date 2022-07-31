<?php

namespace App\Lib;

class SheetAlphabet {

    private $alphabetList;

    public function __construct(int $length = 1)
    {
        $this->length = $length;
        $this->alphabetList = $this->generate();
    }

    /**
     * アルファベットリストを返す
     * @return Array アルファベット配列を返す
     */
    public function getAllAlphabet() :Array
    {
        return $this->alphabetList;
    }

    /**
     * 特定のアルファベット文字（キー）から、アルファベット配列の自身が入っている位置情報を取得する
     * @param  String $alphabet アルファベットの文字（キー）
     * @return Int|Null         見つかれば位置を数値で返す。見つからない場合は、Nullを返す。
     */
    public function getPosAlphabetNumber(String $alphabet) :?Int
    {
        if($this->length * 26 >= strlen($alphabet)) {
            $pos = array_search($alphabet, $this->alphabetList);
            if($pos !== false) {
                return $pos + 1;
            }
        }
        return null;
    }


    /*
        PRIVATE METHODS
    */

    /**
     * アルファベット配列を作る
     * @return Array アルファベット配列 Ex: ”A" 〜 "AZ" の情報が配列として入る
     */
    private function generate() :Array
    {
        $list = [];
        $maxLimit = $this->length * 26;
        $count = 0;
        for($a = 'A'; $count++ < $maxLimit ; $a++) {
            $list[] = $a;
        }
        return $list;
    }

}
