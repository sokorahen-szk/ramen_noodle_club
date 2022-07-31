<?php

use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;
use App\Exceptions\BaseException;
use \Mockery as m;
use GuzzleHttp\ClientInterface as IHttpClient;

class BaseExceptionTest extends TestCase {
    private $dotenv;

    public function setUp(): void {
        $this->dotenv = Dotenv::createImmutable(dirname(__DIR__) . "../../");
        $this->dotenv->load();
    }

    public function test_base_exception() {
        $this->markTestSkipped("skip");
        $exception = new BaseException("message", 200);
        $this->assertInstanceOf(BaseException::class, $exception);
    }

    public function test_base_exception_by_mock() {
        $payload = [
            "username" => "エラー通知",
            "avatar_url" => "https://cdn.pixabay.com/photo/2014/04/02/10/26/attention-303861_1280.png",
            "content" => "error message",
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

        $exception = new BaseException("error message", 500, $mockHttpClient);
        $this->assertInstanceOf(BaseException::class, $exception);
    }
}