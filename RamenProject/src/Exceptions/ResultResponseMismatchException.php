<?php

namespace App\Exceptions;
use App\Exceptions\BaseException;
class ResultResponseMismatchException extends BaseException {
    public function __construct($response = null)
    {
        $appendMessage = '';
        if(is_array($response)) {
            $appendMessage = "```" . json_encode($response) . "```";
        } else if($response !== null){
            $appendMessage = $response;
        }

        parent::__construct("取得情報に誤りがあります。\n" . $appendMessage);
    }
}
