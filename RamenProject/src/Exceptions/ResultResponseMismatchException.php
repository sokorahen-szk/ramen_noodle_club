<?php

namespace App\Exceptions;
use App\Exceptions\BaseException;
class ResultResponseMismatchException extends BaseException {
    public function __construct()
    {
        parent::__construct("取得情報に誤りがあります。");
    }
}
