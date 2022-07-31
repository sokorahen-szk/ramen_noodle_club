<?php

namespace App\Exceptions;

use App\Lib\SlackClient;
use Noodlehaus\Config;

class BaseException extends \Exception {

    private $slackClient;
    private $config;

    public function __construct($message, $code = 0)
    {
        $this->config = new Config(dirname(__FILE__) . "/../../config/config.json");
        $this->SlackClient = new SlackClient($this->config);

        //Exception（親クラス）にエラーを渡す
        parent::__construct($message, $code);

        //Slackにエラー通知
        $this->sendMessageExternalService($message);

    }

    private function sendMessageExternalService($message) :void
    {
        $this->SlackClient->pushNotification($message, "error");
    }

}
