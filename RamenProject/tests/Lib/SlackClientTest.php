<?php

use PHPUnit\Framework\TestCase;
use App\Lib\SlackClient;
use Noodlehaus\Config;
use \Mockery;
use GuzzleHttp\ClientInterface as IHttpClient;

class SlackClientTest extends TestCase {
    private $config;

    public function setUp(): void {
        $this->config = $this->config = new Config(dirname(__FILE__) . "/../../config/config.json");
    }

    public function test_slack_pushNotification_type_success() {
        $this->markTestSkipped("skip");
        $instance = new SlackClient($this->config);
        $instance->pushNotification(__FUNCTION__ . "成功テストメッセージ");
    }

    public function test_slack_pushNotification_type_success_by_mock() {
        $payload = json_encode([
            "username" => "プロセス正常終了通知",
            "text" => "成功テストメッセージ",
            "icon_emoji" => ":ok:",
        ]);

        $expected = [
            "form_params" => ["payload" => $payload],
            "headers" => ['Content-Type' => 'application/x-www-form-urlencoded'],
        ];

        $mockHttpClient = Mockery::mock(IHttpClient::class);
        $mockHttpClient->shouldReceive("request")
            ->once()
            ->with("POST", Mockery::any(), Mockery::on(function($actual) use ($expected) {
                    $this->assertSame($expected, $actual);
                    return true;
                })
            );

        $instance = new SlackClient($this->config, $mockHttpClient);
        $instance->pushNotification("成功テストメッセージ");
    }

    public function test_slack_pushNotification_type_error() {
        $this->markTestSkipped("skip");
        $instance = new SlackClient($this->config);
        $instance->pushNotification(__FUNCTION__ . "失敗テストメッセージ", "error");
    }

    public function test_slack_pushNotification_type_error_by_mock() {
        $payload = json_encode([
            "username" => "エラー通知",
            "text" => "失敗テストメッセージ",
            "icon_emoji" => ":alert:",
        ]);

        $expected = [
            "form_params" => ["payload" => $payload],
            "headers" => ['Content-Type' => 'application/x-www-form-urlencoded'],
        ];

        $mockHttpClient = Mockery::mock(IHttpClient::class);
        $mockHttpClient->shouldReceive("request")
            ->once()
            ->with("POST", Mockery::any(), Mockery::on(function($actual) use ($expected) {
                    $this->assertSame($expected, $actual);
                    return true;
                })
            );

        $instance = new SlackClient($this->config, $mockHttpClient);
        $instance->pushNotification("失敗テストメッセージ", "error");
    }
}