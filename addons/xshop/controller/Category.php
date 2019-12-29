<?php

namespace addons\xshop\controller;

use think\addons\Controller;
use think\Config;
use addons\xshop\model\CategoryModel;

/**
 * 商品分类
 * @ApiWeigh (10)
 */
class Category extends Base
{
    protected function _initialize()
    {
        parent::_initialize();
    }
    /**
     * 分类列表
     */
    public function index()
    {
        $this->success('', CategoryModel::getTreeArray());
    }
}
