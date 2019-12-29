<?php

namespace App\Exceptions;
use App\Exceptions\BaseException;
class BusinessHourStatusChangeFailureException extends BaseException {
    public function __construct($response = null)
    {
        $appendMessage = '';

        if(is_array($response)) {
            $appendMessage = "```" . json_encode($response) . "```";
        } else if($response !== null){
            $appendMessage = $response;
        }

        parent::__construct("カテゴリーの取り外しの時にエラーが発生して正常に取り外しできませんでした。\n" . $appendMessage);
    }
}
