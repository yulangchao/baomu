<?php

namespace app\admin\model\xshop;

use think\Model;


class ServiceTag extends Model
{

    

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'xshop_service_tag';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];
    

    







}
