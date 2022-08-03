<?php

use PHPUnit\Framework\TestCase;
use App\Lib\Notification;

use Dotenv\Dotenv;
use \Mockery as m;
use GuzzleHttp\ClientInterface as IHttpClient;

class NotificationTest extends TestCase {
    private $dotenv;

    public function setUp(): void {
        $this->dotenv = Dotenv::createImmutable(dirname(__DIR__) . "../../");
        $this->dotenv->load();
    }

    public function test_pushNotification_for_slack() {
        $this->markTestSkipped("skip");
        $instance = new Notification();

        $payload = json_encode([
            "username"      => "テスト通知",
            "text"          => "テストメッセージ",
            "icon_emoji"    => ":ok:",
        ]);

        $actual = $instance->push(["payload" => $payload], getenv("SLACK_WEBHOOK_ERROR_PUSH_CHANNEL_URL"));
        $this->assertTrue($actual);
    }

    public function test_pushNotification_for_slack_by_mock() {
        $payload = json_encode([
            "username"      => "テスト通知",
            "text"          => "テストメッセージ",
            "icon_emoji"    => ":ok:",
        ]);

        $expected = [
            "form_params" => ["payload" => $payload],
            "headers" => [],
        ];

        $mockHttpClient = m::mock(IHttpClient::class);
        $mockHttpClient->shouldReceive("request")
            ->once()
            ->with("POST", "https://webhookurl/slack", m::on(function($actual) use ($expected) {
                    $this->assertSame($expected, $actual);
                    return true;
                })
            );
        $instance = new Notification($mockHttpClient);

        $actual = $instance->push(["payload" => $payload], "https://webhookurl/slack");
        $this->assertTrue($actual);
    }

    public function test_pushNotification_for_discord() {
        $this->markTestSkipped("skip");
        $instance = new Notification();

        $payload = [
            "username" => "テスト通知",
            "avatar_url" => "https://cdn.wikiwiki.jp/to/w/nijisanji/%E5%8B%87%E6%B0%97%E3%81%A1%E3%81%B2%E3%82%8D/::ref/face_orig.png",
            "content" => "テストメッセージ",
        ];

        $actual = $instance->push($payload, getenv("DISCORD_WEBHOOK_ERROR_PUSH_CHANNEL_URL"));
        $this->assertTrue($actual);
    }

    public function test_pushNotification_for_discord_by_mock() {
        $payload = [
            "username" => "テスト通知",
            "avatar_url" => "https://avator_url",
            "content" => "テストメッセージ",
        ];

        $expected = [
            "form_params" => $payload,
            "headers" => [],
        ];

        $mockHttpClient = m::mock(IHttpClient::class);
        $mockHttpClient->shouldReceive("request")
            ->once()
            ->with("POST", "https://webhookurl/discord", m::on(function($actual) use ($expected) {
                    $this->assertSame($expected, $actual);
                    return true;
                })
            );
        $instance = new Notification($mockHttpClient);

        $actual = $instance->push($payload, "https://webhookurl/discord");
        $this->assertTrue($actual);
    }
}