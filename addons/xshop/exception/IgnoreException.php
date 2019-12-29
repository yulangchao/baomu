<?php

namespace addons\xshop\exception;

use addons\xshop\controller\Base;

class IgnoreException extends Exception
{
    public function __construct($msg = '')
    {
        parent::__construct($msg, self::IGNORE);
    }
}
