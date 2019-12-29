<?php

namespace addons\xshop\model;

use addons\xshop\exception\Exception;
use addons\xshop\exception\NotFoundException;
use addons\xshop\exception\NotLoginException;
use app\admin\library\xshop\Tools;
use think\Hook;

class ConfigModel extends Model
{
    protected $name = 'config';
    protected $autoWriteTimestamp = false;

    public static function getByCodes(array $array)
    {
        $list = self::where('name', 'IN', $array)->select();
        $newList = [];
        foreach ($list as $i => $row) {
            $data = $row->toArray();
            $val = $data['value'];
            
            switch ($data['type']) {
                case 'array': {
                    $val = json_decode($val, 1);
                    break;
                }
                default: {
                    break;
                }
            }
            $newList[$data['name']] = $val;
        }
        return $newList;
    }
}
