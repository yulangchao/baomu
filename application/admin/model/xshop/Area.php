<?php

namespace app\admin\model\xshop;
use fast\Tree;
use think\Model;

class Area extends Model
{

    // 表名
    protected $name = 'area';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];
    
    public static function getTreeArray(Array $levels = []) {
        $tree = Tree::instance();
        $model = new self;
        if (!empty($levels)) $model->where('level', 'IN', $levels);
        $tree->init(collection($model->select())->toArray(), 'pid');
        return $tree->getTreeArray(0);
    }

    public static function getTreeList(Array $levels = []) {
        $tree = Tree::instance();
        $model = new self;
        if (!empty($levels)) $model->where('level', 'IN', $levels);
        $tree->init(collection($model->select())->toArray(), 'pid');
        return $tree->getTreeList($tree->getTreeArray(0), 'name');
    }
    







}
