<?php

namespace App\Lib;

use App\Exceptions\BusinessHourSheetEmptyException;

class SheetSearch {

    private $sheetId;
    private $pointer;
    private $row;
    private $col;
    private $alphabetList;
    private $id;
    private $sheetAlphabet;

    public function __construct(){}

    public function search($alphabetList, $move, $beginRowPos, $beginColPos, $sheetId) :Array
    {

        $this->clearValues();

        $this->row = (int)$beginRowPos;
        $this->col = $beginColPos;
        $this->id = $sheetId;
        $this->setMovePointer($move);

        $result = $this->loop();

        if($result == null || !is_array($result) ) {
            throw new BusinessHourSheetEmptyException();
        }

        return $result;
    }

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

    private function loop() :Array
    {
        $response = [];
        while(($res = do_shortcode(
            "[supsystic-tables-cell id=".$this->id." row=".$this->row." col=".$this->col."]"
        )) != '') {
            $response[] = $res;
            $this->pointer++;
        }
        return $response;
    }

    private function clearValues() :void
    {
        $this->pointer = null;
        $this->row = null;
        $this->col = null;
    }

    private function convertToNumber($alphabetList, $beginColPos) :?Int
    {
        return array_search($beginColPos, $alphabetList) ?: null;
    }

}

?>
