<?php

namespace addons\xshop\traits;

use addons\xshop\model\UserModel;
use addons\xshop\exception\IgnoreException;

trait LoginTrait
{
    protected function __NeedLogin()
    {
        if (!UserModel::info()->isLogin()) {
            throw new IgnoreException("您未登录");
        }
    }
}
