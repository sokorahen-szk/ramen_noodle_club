<?php

use PHPUnit\Framework\TestCase;
use App\Lib\DiscordClient;
use Noodlehaus\Config;
use Dotenv\Dotenv;
use \Mockery as m;
use GuzzleHttp\ClientInterface as IHttpClient;

class DiscordClientTest extends TestCase {
    private $config;
    private $dotenv;

    public function setUp(): void {
        $this->config = $this->config = new Config(dirname(__FILE__) . "/../../config/config.json");
        $this->dotenv = Dotenv::createImmutable(dirname(__DIR__) . "../../");
        $this->dotenv->load();
    }

    public function test_discord_pushNotification_type_success()
    {
        $this->markTestSkipped("skip");
        $instance = new DiscordClient($this->config);
        $instance->pushNotification(__FUNCTION__ . "成功テストメッセージ");
    }

    public function test_discord_pushNotification_type_success_by_mock()
    {
        $payload = [
            "username" => "プロセス正常終了通知",
            "avatar_url" => "https://cdn.pixabay.com/photo/2015/06/09/16/12/icon-803718_1280.png",
            "content" => "成功テストメッセージ",
        ];

        $expected = [
            "form_params" => $payload,
            "headers" => [],
        ];

        $mockHttpClient = m::mock(IHttpClient::class);
        $mockHttpClient->shouldReceive("request")
            ->once()
            ->with("POST", m::any(), m::on(function($actual) use ($expected) {
                    $this->assertSame($expected, $actual);
                    return true;
                })
            );

        $instance = new DiscordClient($this->config, $mockHttpClient);
        $actual = $instance->pushNotification("成功テストメッセージ");
        $this->assertTrue($actual);
    }

    public function test_discord_pushNotification_type_error()
    {
        $this->markTestSkipped("skip");
        $instance = new DiscordClient($this->config);
        $instance->pushNotification(__FUNCTION__ . "失敗テストメッセージ", "error");
    }

    public function test_discord_pushNotification_type_error_by_mock()
    {
        $payload = [
            "username" => "エラー通知",
            "avatar_url" => "https://cdn.pixabay.com/photo/2014/04/02/10/26/attention-303861_1280.png",
            "content" => "失敗テストメッセージ",
        ];

        $expected = [
            "form_params" => $payload,
            "headers" => [],
        ];

        $mockHttpClient = m::mock(IHttpClient::class);
        $mockHttpClient->shouldReceive("request")
            ->once()
            ->with("POST", m::any(), m::on(function($actual) use ($expected) {
                    $this->assertSame($expected, $actual);
                    return true;
                })
            );

        $instance = new DiscordClient($this->config, $mockHttpClient);
        $actual = $instance->pushNotification("失敗テストメッセージ", "error");
        $this->assertTrue($actual);
    }
}