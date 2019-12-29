<?php

namespace addons\xshop\model;

use think\Model;
use app\admin\library\xshop\Tools;
use addons\xshop\exception\NotFoundException;
use fast\Tree;

class CategoryModel extends Model
{
    protected $name = "xshop_category";
    protected $visible = [];
    protected $hidden = [
        'create_time', 'update_time', 'sort'
    ];
    protected $append = [
        'image'
    ];

    public static function getTreeArray()
    {
        $tree = Tree::instance();
        $tree->init(collection(self::select())->toArray(), 'parent_id');
        return $tree->getTreeArray(0);
    }

    public static function getTreeList()
    {
        $tree = Tree::instance();
        return $tree->getTreeList(self::getTreeArray());
    }

    
    public static function getChildren($myid, $withself = false)
    {
        $tree = Tree::instance();
        $list = collection(self::select())->toArray();
        $tree->init($list, 'parent_id');
        return $tree->getChildren($myid, $withself);
    }
    
    public function products()
    {
        return $this->hasMany('ProductModel', 'category_id', 'id');
    }

    public function getImageAttr($value, $data)
    {
        return cdnurl($data['image'], true);
    }
}
