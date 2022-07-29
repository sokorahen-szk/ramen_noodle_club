<?php

namespace App\Lib;

use App\Exceptions\BusinessHourSheetEmptyException;
use App\Excepitons\LoopLimitException;

class SheetSearch {

    private $pointer;
    private $row;
    private $col;
    private $id;

    const LOOP_LIMIT_NUMBER = 200;

    public function __construct(){}

    /**
     * 探索する
     * @param  Array    $alphabetList  アルファベット配列 Ex: ”A" 〜 "AZ" の情報が配列として入る
     * @param  String   $move          [description]
     * @param  Int      $beginRowPos   Sheet（列）の開始位置の数字
     * @param  String   $beginColPos   Sheet（行）の開始位置のアルファベット
     * @param  Int      $sheetId       参照するSheetのId
     * @return Array                   探索して見つけたセルのValueを配列で返す
     */
    public function search($alphabetList, $move, $beginRowPos, $beginColPos, $sheetId) :Array
    {
        $this->clearValues();

        $this->row = (int)$beginRowPos;
        $this->col = $beginColPos;
        $this->id = $sheetId;
        $this->setMovePointer($move);

        $result = $this->loop();

        if($result == null || !is_array($result) ) {
            throw new BusinessHourSheetEmptyException([
                "row"       => $this->row,
                "col"       => $this->col,
                "sheet_id"  => $this->id
            ]);
        }

        return $result;
    }

    /**
     * Sheetの移動するセルの向きをpointerに入れる
     * @param String $move 'Y' or 'X'
     */
    private function setMovePointer($move) :void
    {
        if($move === 'y') {
            $this->pointer = &$this->row;
        } else if($move === 'x'){
            $this->pointer = &$this->col;
        } else {
            $this->pointer = null;
        }
    }

    /**
     * セルを移動する
     * @return Array 移動して取得したデータを配列で返す
     */
    private function loop() :Array
    {
        $response = [];
        $count = 0;
        while(($res = do_shortcode(
            "[supsystic-tables-cell id=".$this->id." row=".$this->row." col=".$this->col."]"
        )) != getenv("END_OF_CELL")) {
            if(self::LOOP_LIMIT_NUMBER <= $count) {
                throw new LoopLimitException();
            }
            $response[] = $res;
            $this->pointer++;
            $count++;
        }
        return $response;
    }

    /**
     * 主要プロパティの初期化
     */
    private function clearValues() :void
    {
        $this->pointer = null;
        $this->row = null;
        $this->col = null;
    }
}

?>
