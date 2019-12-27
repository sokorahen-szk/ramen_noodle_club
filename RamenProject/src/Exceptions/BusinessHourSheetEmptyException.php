<?php

namespace App\Exceptions;
use App\Exceptions\BaseException;
class BusinessHourSheetEmptyException extends BaseException {
    public function __construct()
    {
        parent::__construct("データ取得できませんでした。営業時間の情報が書かれた「supsystic-tables」の情報を確認してください。");
    }
}
