<?php

namespace addons\xshop\model;

use addons\xshop\exception\Exception;
use addons\xshop\exception\NotFoundException;
use addons\xshop\exception\NotLoginException;
use app\admin\library\xshop\Tools;

class AppSourceModel extends Model
{
    protected $name = 'xshop_app_update';
    protected $visible = [
    ];
    
    /**
     * 获取APP更新包
     * @param String $platform android ios
     * @param String $version 客户端当前版本
     */
    public static function checkUpdate($attributes)
    {
        extract($attributes);
        $model = self::where('platform', $platform)->where('status', 1)->order('id', 'DESC')->find();
        if (empty($model) || $model->version == $version) {
            return [
            'update' => 0
        ];
        }
        return [
            'update' => 1,
            'description' => $model->description,
            'wgtUrl' => cdnurl($model->source_file, true)
        ];
    }
}
