<?php

namespace App\Lib;

use Noodlehaus\Config;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class SlackClient {

    private $config;
    private $slackConfig;
    private $httpClient;

    public function __construct(){
        $this->config = new Config("config/config.json");
        $this->httpClient = new Client();
    }

    public function pushMessage($message = null) :void
    {
        $result = null;
        try {
            $this->setConfig();
            $result = $this->httpClient->request(
                "POST",
                $this->slackConfig->hookUrl,
                [
                    "form_params" => $this->payload($message)
                ]
            );

        } catch(GuzzleException $e) {
            //
        }
    }


    /*
        PRIVATE METHODS
    */
    private function setConfig() :void
    {
        $this->slackConfig = (Object) [
            "hookUrl"       => env("SLACK_WEBHOOK_ERROR_PUSH_CHANNEL_URL"),
            "username"      => $this->config->get("global.slack.errorNotifyChannel.username"),
            "iconEmoji"     => $this->config->get("global.slack.errorNotifyChannel.iconEmoji")
        ];
    }

    private function payload($message) :Array
    {
        $payload = json_encode([
            "icon_emoji"    => $this->slackConfig->hookUrl,
            "username"      => $this->slackConfig->username,
            "text"          => $message,
            "icon_emoji"    => $this->slackConfig->iconEmoji
        ]);
        return [ "payload" => $payload ];
    }
}
