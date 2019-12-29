<?php

namespace addons\xshop\exception;

use addons\xshop\controller\Base;

class NotLoginException extends Exception
{
    public function __construct($msg = '您未登录')
    {
        parent::__construct($msg, self::NOT_LOGIN);
    }
}
