<?php

namespace addons\xshop\model;

use addons\xshop\exception\Exception;
use addons\xshop\exception\NotFoundException;
use addons\xshop\exception\NotLoginException;
use app\admin\library\xshop\Tools;

class LaunchLogModel extends Model
{
    protected $name = 'xshop_launch_log';
    protected $visible = [
    ];
    
    /**
     * 记录app启动日志
     * header
     */
    public static function addLog($attributes)
    {
        extract($attributes);
        $user = UserModel::info();
        $data = [
            'user_id' => empty($user) ? '' : $user->id,
            'platform' => $platform,
            'systeminfo' => isset($systeminfo) ? json_encode($systeminfo, 1) : '',
            'ip' => request()->ip(),
            'create_time' => time()
        ];
        return self::insert($data);
    }
}
