<?php

namespace addons\xshop\library;

class Sn
{
    public static function get($prefix = '')
    {
        return $prefix . date('Ymd').substr(implode(null, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
}
