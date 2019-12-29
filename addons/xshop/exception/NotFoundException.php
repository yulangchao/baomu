<?php

namespace addons\xshop\exception;

use addons\xshop\controller\Base;

class NotFoundException extends Exception
{
    public function __construct($msg = '请求的资源不存在')
    {
        parent::__construct($msg, self::NOT_FOUND);
    }
}
