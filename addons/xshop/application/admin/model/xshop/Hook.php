<?php

namespace app\admin\model\xshop;

use think\Model;


class Hook extends Model
{

    

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'xshop_hook';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];

    public function hookAddon() {
        return $this->hasMany('HookAddon', 'hook', 'hook');
    }
    

    







}
