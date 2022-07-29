<?php

use PHPUnit\Framework\TestCase;
use App\Lib\Notification;

use Dotenv\Dotenv;

class NotificationTest extends TestCase {
    private $dotenv;

    public function setUp(): void {
        $this->dotenv = Dotenv::createImmutable(dirname(__DIR__) . "../../");
        $this->dotenv->load();
    }

    public function test_pushNotification_for_slack() {
        $this->markTestSkipped("Slackに通知飛ぶため、Skip入れてます。テストするときはSkipをコメントアウト");
        $instance = new Notification();

        $payload = json_encode([
            "username"      => "テスト通知",
            "text"          => "テストメッセージ",
            "icon_emoji"    => ":ok:",
        ]);

        $instance->push(["payload" => $payload], getenv("SLACK_WEBHOOK_ERROR_PUSH_CHANNEL_URL"));
    }

    public function test_pushNotification_for_discord() {
        // TODO:
    }
}