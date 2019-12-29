<?php

namespace addons\xshop\model;

use addons\xshop\exception\Exception;
use addons\xshop\exception\NotFoundException;
use addons\xshop\exception\NotLoginException;
use app\admin\library\xshop\Tools;

class AreaModel extends Model
{
    protected $name = 'area';
    protected $visible = [
        'id', 'pid', 'shortname', 'mergename', 'name', 'level', 'first'
    ];
    public static function getList()
    {
        return self::all();
    }
}
