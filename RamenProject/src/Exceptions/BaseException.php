<?php

namespace App\Exceptions;

use App\Lib\DiscordClient;
use App\Lib\SlackClient;
use App\Interfaces\INotificationClient;
use Noodlehaus\Config;
use GuzzleHttp\ClientInterface as IHttpClient;
use GuzzleHttp\Client as HttpClient;

class BaseException extends \Exception {

    /**
     * @var \Noodlehaus\Config
     */
    private $config;

    public function __construct($message, $code = 0, ?IHttpClient $httpClient = null)
    {
        $this->config = new Config(dirname(__FILE__) . "/../../config/config.json");
        $httpClient = $httpClient ?: new HttpClient();

        parent::__construct($message, $code);

        $this->sendMessageExternalService($message, $httpClient);
    }

    private function sendMessageExternalService(string $message, IHttpClient $httpClient) :void
    {
        // ローカル環境では、通知させたくないためpush通知させたくない。
        //if (getenv("APP_ENV") === "local") {
        //    return;
        //}

        /**
         * @var App\Interfaces\INotificationClient
         */
        $notificationClient = null;
        switch (getenv("NOTIFICATION_SERVICE_NAME")) {
            case "slack":
                $notificationClient = new SlackClient($this->config, $httpClient);
                break;
            case "discord":
                $notificationClient = new DiscordClient($this->config, $httpClient);
                break;
            default:
                throw new \Exception("NOTIFICATION_SERVICE_NAME slack or discord を設定してください。");
        }

        $notificationClient->pushNotification($message, "error");
    }
}
