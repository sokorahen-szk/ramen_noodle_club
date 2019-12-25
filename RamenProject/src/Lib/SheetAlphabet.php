<?php

namespace App\Lib;

class SheetAlphabet {

    private $alphabetList;

    public function __construct(int $length = 1)
    {
        $this->length = $length;
        $this->alphabetList = $this->generate();
    }

    public function getAllAlphabet()
    {
        return $this->alphabetList;
    }

    public function getPosAlphabetNumber(String $alphabet)
    {
        if($this->length * 26 >= strlen($alphabet)) {
            $pos = array_search($alphabet, $this->alphabetList);
            if($pos !== false) {
                return $pos + 1;
            }
        }
        return null;
    }

    private function generate()
    {
        $list = [];
        $maxLimit = $this->length * 26;
        $count = 1;
        for($a = 'A' ; ; $a++) {
            $list[] = $a;
            $count++;
            if($maxLimit < $count) break;
        }
        return $list;
    }

}
