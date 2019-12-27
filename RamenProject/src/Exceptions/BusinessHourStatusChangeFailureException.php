<?php

namespace App\Exceptions;
use App\Exceptions\BaseException;
class BusinessHourStatusChangeFailureException extends BaseException {
    public function __construct()
    {
        parent::__construct("カテゴリーの取り外しの時にエラーが発生して正常に取り外しできませんでした。");
    }
}
