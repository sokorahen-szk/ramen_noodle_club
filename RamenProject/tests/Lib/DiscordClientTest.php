<?php

use PHPUnit\Framework\TestCase;
use App\Lib\DiscordClient;
use Noodlehaus\Config;
use Dotenv\Dotenv;

class DiscordClientTest extends TestCase {
    private $config;
    private $dotenv;

    public function setUp(): void {
        $this->config = $this->config = new Config(dirname(__FILE__) . "/../../config/config.json");
        $this->dotenv = Dotenv::createImmutable(dirname(__DIR__) . "../../");
        $this->dotenv->load();
    }

    public function test_discord_pushNotification_it_should_return_success()
    {
        $this->markTestSkipped("skip");
        $instance = new DiscordClient($this->config);
        $instance->pushNotification(__FUNCTION__ . "成功テストメッセージ");
    }

    public function test_discord_pushNotification_it_should_return_failed()
    {
        $this->markTestSkipped("skip");
        $instance = new DiscordClient($this->config);
        $instance->pushNotification(__FUNCTION__ . "成功テストメッセージ", "error");
    }
}