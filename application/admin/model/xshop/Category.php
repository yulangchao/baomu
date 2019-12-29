<?php

namespace app\admin\model\xshop;

use think\Model;
use fast\Tree;

class Category extends Model
{   

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'xshop_category';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    // protected $createTime = false;
    // protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'pid'
    ];

    public static function getTreeArray() {
        $tree = Tree::instance();
        $tree->init(collection(self::select())->toArray(), 'pid');
        return $tree->getTreeArray(0);
    }

    public static function getTreeList() {
        $tree = Tree::instance();
        $tree->init(collection(self::select())->toArray(), 'pid');
        return $tree->getTreeList($tree->getTreeArray(0), 'name');
    }

    public function getPidAttr($value, $data) {
        return $data['parent_id'];
    }

    public function parent()
    {
        return $this->belongsTo('Category', 'parent_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    







}
