<?php

namespace App\Lib;

use App\Interfaces\INotificationClient;
use Noodlehaus\Config;
use GuzzleHttp\ClientInterface as IHttpClient;

class DiscordClient extends Notification implements INotificationClient {
    private $config;

    public function __construct(Config $config, ?IHttpClient $httpClient = null){
        parent::__construct($httpClient);
        $this->config = $config;
    }

    public function pushNotification(string $message, ?string $type = null): bool
    {
        if ($type) {
            $this->notificationType = $type;
        }

        $payload = $this->payload($message);
        return $this->push($payload, getenv("DISCORD_WEBHOOK_ERROR_PUSH_CHANNEL_URL"));
    }

    public function setWebhookUrl(string $webhookUrl): void
    {
        parent::setWebhookUrl($webhookUrl);
    }

    private function payload($message): array
    {
        $payload = [];
        if($this->notificationType == 'success') {
            $payload += [
                "username"      => $this->config->get("global.discord.successfulNotifyChannel.username"),
                "avatar_url"     => $this->config->get("global.discord.successfulNotifyChannel.avatarUrl")
            ];
        }

        if ($this->notificationType == 'error') {
            $payload += [
                "username"      => $this->config->get("global.discord.errorNotifyChannel.username"),
                "avatar_url"     => $this->config->get("global.discord.errorNotifyChannel.avatarUrl")
            ];
        }

        $payload["content"] = $message;
        return $payload;
    }

}