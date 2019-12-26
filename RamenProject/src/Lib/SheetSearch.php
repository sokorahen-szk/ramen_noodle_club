<?php

namespace App\Lib;

use App\Lib\SheetAlphabet;

class SheetSearch {

    private $sheetId;
    private $pointer;
    private $row;
    private $col;
    private $alphabetList;

    public function search($alphabetList, $move, $beginRowPos, $beginColPos)
    {
        $this->row = $beginRowPos;
        $this->col = $beginColPos;

        $this->move($move);

        var_dump($this->$pointer);

        //$result = self::$loop();

    }

    private static function move($move) :void
    {
        if($move === 'y') {
            $this->$pointer = &$this->$row;
        } else if($move === 'x'){
            $this->$pointer = &$this->$col;
        } else {
            $this->$pointer = null;
        }
    }

    private function loop()
    {

    }

}

?>
