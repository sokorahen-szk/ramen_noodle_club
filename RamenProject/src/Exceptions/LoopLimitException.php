<?php

namespace App\Exceptions;

use App\Exceptions\BaseException;
class LoopLimitException extends BaseException {
    public function __construct($response = null)
    {
        $appendMessage = '';

        if(is_array($response)) {
            $appendMessage = json_encode($response, true);
        } else if($response !== null){
            $appendMessage = $response;
        }
        parent::__construct("反復処理が制限されている回数を超えたため、エラーが発生しました。\n" . $response);
    }
}
