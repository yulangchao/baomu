<?php

namespace addons\xshop\exception;

use addons\xshop\controller\Base;

class NotAuthorizeException extends Exception
{
    public function __construct($msg = '没有权限')
    {
        parent::__construct($msg, self::NOT_AUTHORIZE);
    }
}
