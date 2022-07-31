<?php

use PHPUnit\Framework\TestCase;
use App\Lib\RamenShopCategories;
use App\Enum\BusinessHourStatus;
use App\Exceptions\BusinessHourStatusChangeFailureException;

// 擬似関数を使うためにrequire_onceする
require_once dirname(__DIR__) . "../../common.php";

class RamenShopCategoriesTest extends TestCase {
    public function setUp(): void {}

    public function test_changeCategoriesStatus_shop_open_business_hours_shop_id_0() {
        $instance = new RamenShopCategories();
        $expected = [
            "isSuccess" => true,
            "shopId" => 0,
            "status" => 1,
            "changed" => false,
        ];
        $actual = $instance->changeCategoriesStatus(0, BusinessHourStatus::OPEN_BUSINESS_HOURS);
        $this->assertSame($expected, $actual);
    }

    public function test_changeCategoriesStatus_shop_open_business_hours_shop_id_1() {
        $instance = new RamenShopCategories();
        $expected = [
            "isSuccess" => true,
            "shopId" => 1,
            "status" => 1,
            "changed" => true,
        ];
        $actual = $instance->changeCategoriesStatus(1, BusinessHourStatus::OPEN_BUSINESS_HOURS);
        $this->assertSame($expected, $actual);
    }

    public function test_changeCategoriesStatus_close_business_hours_shop_id_1() {
        $instance = new RamenShopCategories();
        $expected = [
            "isSuccess" => true,
            "shopId" => 1,
            "status" => 0,
            "changed" => true,
        ];
        $actual = $instance->changeCategoriesStatus(1, BusinessHourStatus::CLOSE_BUSINESS_HOURS);
        $this->assertSame($expected, $actual);
    }

    public function test_changeCategoriesStatus_not_exists_status_code_it_should_return_exception_error() {
        $this->markTestSkipped("skip");
        $instance = new RamenShopCategories();

        try {
            $instance->changeCategoriesStatus(1, 3);

            $this->assertTrue(false);
        } catch (BusinessHourStatusChangeFailureException $e) {
            $this->assertTrue(true);
        }
    }

    public function test_changeCategoriesStatus_failed_to_wp_set_post_categories_it_should_return_exception_error() {
        $this->markTestSkipped("skip");
        $instance = new RamenShopCategories();

        try {
            $instance->changeCategoriesStatus(998, BusinessHourStatus::OPEN_BUSINESS_HOURS);

            $this->assertTrue(false);
        } catch (BusinessHourStatusChangeFailureException $e) {
            $this->assertTrue(true);
        }
    }

    public function test_changeCategoriesStatus_failed_to_is_wp_error_it_should_return_exception_error() {
        $this->markTestSkipped("skip");
        $instance = new RamenShopCategories();

        try {
            $instance->changeCategoriesStatus(999, BusinessHourStatus::OPEN_BUSINESS_HOURS);

            $this->assertTrue(false);
        } catch (BusinessHourStatusChangeFailureException $e) {
            $this->assertTrue(true);
        }
    }
}