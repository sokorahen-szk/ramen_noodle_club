<?php

namespace App\Exceptions;

use App\Lib\SlackClient;

class BaseException extends \Exception {

    private $slackClient;

    public function __construct($message, $code = 0)
    {
        $this->SlackClient = new SlackClient();

        //Exception（親クラス）にエラーを渡す
        parent::__construct($message, $code);

        //Slackにエラー通知
        $this->sendMessageExternalService($message);

    }

    private function sendMessageExternalService($message) :void
    {
        $this->SlackClient->pushMessage($message);
    }

}
