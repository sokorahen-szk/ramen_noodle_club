<?php

use PHPUnit\Framework\TestCase;
use App\Lib\SlackClient;
use Noodlehaus\Config;

class SlackClientTest extends TestCase {
    private $config;

    public function setUp(): void {
        $this->config = $this->config = new Config(dirname(__FILE__) . "/../../config/config.json");
    }

    public function test_pushNotification_it_should_return_success() {
        $instance = new SlackClient($this->config);
        $instance->pushNotification(__FUNCTION__ . "成功テストメッセージ");
    }

    public function test_pushNotification_it_should_return_failed() {
        $instance = new SlackClient($this->config);
        $instance->pushNotification(__FUNCTION__ . "失敗テストメッセージ", "error");
    }
}