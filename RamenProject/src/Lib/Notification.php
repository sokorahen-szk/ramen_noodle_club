<?php

namespace App\Lib;
use GuzzleHttp\Client as HttpClient;

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

    public function __construct(HttpClient $httpClient = null)
    {
        $this->httpClient = $httpClient ?: new HttpClient();
    }

    public function push(array $payload, ?string $webhookUrl = null)
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