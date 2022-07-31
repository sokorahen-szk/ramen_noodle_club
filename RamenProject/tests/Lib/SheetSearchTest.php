<?php

use App\Exceptions\LoopLimitException;
use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;
use App\Lib\SheetSearch;
use App\Lib\SheetAlphabet;

// 擬似関数を使うためにrequire_onceする
require_once dirname(__DIR__) . "../../common.php";

class SheetSearchTest extends TestCase {
    private $dotenv;
    private $sheetAlphabet;

    public function setUp(): void {
        $this->dotenv = Dotenv::createImmutable(dirname(__DIR__) . "../../");
        $this->dotenv->load();

        $this->sheetAlphabet = new SheetAlphabet(2);
    }

    public function test_search_all_get_ramen_shop_ids() {
        $instance = new SheetSearch();

        $expected = [
            "16",
            "16",
            "56",
            "57",
            "58",
            "59",
            "60",
            "61",
            "62",
            "63",
            "64",
            "65",
            "66",
            "67",
            "68",
            "69",
            "70",
            "71",
            "72",
            "73",
            "74",
            "75",
            "76",
            "77",
            "78",
            "79",
            "80",
            "81",
            "82",
            "83",
            "84",
            "85",
            "86",
            "87",
            "88",
            "89",
            "90",
            "91",
            "92",
            "93",
            "94",
            "95",
            "96",
            "97",
            "98",
            "99",
            "100",
            "101",
            "102",
            "103",
            "104",
            "105",
            "106",
            "107",
        ];
        $actual = $instance->search(
            $this->sheetAlphabet->getAllAlphabet(),
            "y",
            2,
            "B",
            1,
        );
        $this->assertCount(54, $actual);
        $this->assertSame($expected, $actual);
    }

    public function test_search_specific_get_ramen_shop_ids() {
        $instance = new SheetSearch();

        $expected = [
            "105",
            "106",
            "107",
        ];
        $actual = $instance->search(
            $this->sheetAlphabet->getAllAlphabet(),
            "y",
            53,
            "B",
            2,
        );
        $this->assertCount(3, $actual);
        $this->assertSame($expected, $actual);
    }

    public function test_search_specific_get_business_hours_values() {
        $instance = new SheetSearch();

        $expected = [
            "1",
            "0",
            "0",
            "0",
            "0",
            "0",
        ];
        $actual = $instance->search(
            $this->sheetAlphabet->getAllAlphabet(),
            "y",
            50,
            "C",
            3,
        );
        $this->assertCount(6, $actual);
        $this->assertSame($expected, $actual);
    }

    public function test_search_it_should_return_exception_error() {
        $this->markTestSkipped("skip");
        $instance = new SheetSearch();

        try {
            $instance->search(
                $this->sheetAlphabet->getAllAlphabet(),
                "y",
                2,
                "B",
                999,
            );

            $this->assertTrue(false);
        } catch (LoopLimitException $e) {
            $this->assertTrue(true);
        }
    }
}