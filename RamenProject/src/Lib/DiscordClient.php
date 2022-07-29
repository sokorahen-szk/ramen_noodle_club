<?php

namespace App\Lib;

use App\Interfaces\INotificationClient;
use Noodlehaus\Config;
use GuzzleHttp\Client as HttpClient;

class DiscordClient extends Notification implements INotificationClient {
    private $config;

    public function __construct(Config $config, ?HttpClient $httpClient = null){
        parent::__construct($httpClient);
        $this->config = $config;
    }

    public function pushNotification(string $message, ?string $type = null)
    {
        if ($type) {
            $this->notificationType = $type;
        }

        $payload = $this->payload($message);
        $this->push($payload, getenv("DISCORD_WEBHOOK_ERROR_PUSH_CHANNEL_URL"));
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