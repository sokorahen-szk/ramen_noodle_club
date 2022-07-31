<?php

namespace App\Lib;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface as IHttpClient;

class Notification {

    /**
     * @var GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $notificationType = "success";

    /**
     * @var string
     */
    private $webhookUrl;

    /**
     * @var array
     */
    private $headers = [];

    public function __construct(?IHttpClient $httpClient = null)
    {
        $this->httpClient = $httpClient ?: new HttpClient();
    }

    public function push(array $payload, ?string $webhookUrl = null): bool
    {
        if ($webhookUrl) {
            $this->webhookUrl = $webhookUrl;
        }

        $this->httpClient->request(
            "POST",
            $this->webhookUrl,
            [
                "form_params" => $payload,
                "headers" => $this->headers,
            ],
        );

        return true;
    }

    public function setHeaders(string $key, string $value): void
    {
        $this->headers[$key] = $value;
    }

    protected function setWebhookUrl(string $webhookUrl): void
    {
        $this->webhookUrl = $webhookUrl;
    }
}