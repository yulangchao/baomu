<?php

namespace addons\xshop\model;

use app\common\library\Auth as User;

class Auth extends Model
{
    public static function instance()
    {
        return User::instance();
    }
}
