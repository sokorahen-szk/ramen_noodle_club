<?php

namespace App\Lib;

use App\Interfaces\INotificationClient;
use Noodlehaus\Config;
use GuzzleHttp\Client as HttpClient;

class SlackClient extends Notification implements INotificationClient {
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
        $this->push($payload, getenv("SLACK_WEBHOOK_ERROR_PUSH_CHANNEL_URL"));
    }

    /**
     * Slack webhookのPOST用Payloadを作る
     * @param  String $message メッセージ
     * @return Array  payload配列 [ "payload" => <JSON形式> ]
     */
    private function payload(string $message) :array
    {
        $slackConfig = [];
        if($this->notificationType == 'success') {
            $slackConfig = (Object) [
                "username"      => $this->config->get("global.slack.successfulNotifyChannel.username"),
                "iconEmoji"     => $this->config->get("global.slack.successfulNotifyChannel.iconEmoji")
            ];
        }

        if ($this->notificationType == 'error') {
            $slackConfig = (Object) [
                "username"      => $this->config->get("global.slack.errorNotifyChannel.username"),
                "iconEmoji"     => $this->config->get("global.slack.errorNotifyChannel.iconEmoji")
            ];
        }

        $payload = json_encode([
            "username"      => $slackConfig->username,
            "text"          => $message,
            "icon_emoji"    => $slackConfig->iconEmoji
        ]);
        return [ "payload" => $payload ];
    }

    public function setWebhookUrl(string $webhookUrl): void
    {
        parent::setWebhookUrl($webhookUrl);
    }
}
