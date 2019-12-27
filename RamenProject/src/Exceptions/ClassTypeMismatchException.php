<?php

namespace App\Exceptions;

class ClassTypeMismatchException extends \Exception {
    public function __construct()
    {
        parent::__construct("クラスの型が一致していません");
    }
}
