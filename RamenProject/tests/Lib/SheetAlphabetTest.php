<?php

use PHPUnit\Framework\TestCase;
use App\Lib\SheetAlphabet;

class SheetAlphabetTest extends TestCase {
    public function setUp(): void {
        //
    }

    public function test_getAllAlphabet_give_zero() {
        $instance = new SheetAlphabet(0);
        $this->assertCount(0, $instance->getAllAlphabet());
    }

    public function test_getAllAlphabet_give_one() {
        $instance = new SheetAlphabet(1);
        $this->assertCount(26, $instance->getAllAlphabet());
        $this->assertSame([
            "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M",
            "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
        ], $instance->getAllAlphabet());
    }

    public function test_getAllAlphabet_give_two() {
        $instance = new SheetAlphabet(2);
        $this->assertCount(52, $instance->getAllAlphabet());
        $this->assertSame([
            "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M",
            "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
            "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK",
            "AL", "AM", "AN", "AO", "AP", "AQ", "AR", "AS", "AT", "AU", "AV",
            "AW", "AX", "AY", "AZ",
        ], $instance->getAllAlphabet());
    }

    public function test_getPosAlphabetNumber() {
        $instance = new SheetAlphabet(2);
        $this->assertSame(1, $instance->getPosAlphabetNumber("A"));
        $this->assertSame(26, $instance->getPosAlphabetNumber("Z"));
        $this->assertSame(27, $instance->getPosAlphabetNumber("AA"));
        $this->assertSame(52, $instance->getPosAlphabetNumber("AZ"));

        $this->assertNull($instance->getPosAlphabetNumber("BA"));
    }
}