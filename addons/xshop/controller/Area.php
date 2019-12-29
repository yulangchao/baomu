<?php

namespace addons\xshop\controller;

use think\addons\Controller;
use think\Config;
use addons\xshop\model\AreaModel;

/**
 * 地区
 * @ApiWeigh (10)
 */
class Area extends Base
{
    protected function _initialize()
    {
        parent::_initialize();
    }
    /**
     * 区域列表
     */
    public function index()
    {
        $this->success('', AreaModel::getList());
    }
}
