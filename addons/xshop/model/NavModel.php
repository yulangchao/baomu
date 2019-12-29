<?php

namespace addons\xshop\model;

use addons\xshop\exception\Exception;
use addons\xshop\exception\NotFoundException;
use addons\xshop\exception\NotLoginException;
use app\admin\library\xshop\Tools;

class NavModel extends Model
{
    protected $name = 'xshop_nav';
    protected $visible = [
    ];
    protected $hidden = [
        'sort', 'status'
    ];
    protected $append = [
        'image', 'params'
    ];
    /**
     * @param Integer $nav_type 导航分类 0：首页轮播，1：首页分类
     */
    public static function getList($attributes)
    {
        extract($attributes);
        $model = self::where('status', 1)->where('nav_type', "IN", $nav_type)->order('sort', 'DESC');
        $list = $model->select();
        $result = [];
        foreach ($list as $row) {
            $result[$row->nav_type][] = $row;
        }
        return $result;
    }

    public function getImageAttr($value, $data)
    {
        return cdnurl($data['image'], true);
    }

    public function getParamsAttr($value, $data)
    {
        return empty($data['params']) ? null : json_decode($data['params'], true);
    }
}
