<?php

namespace addons\xshop\controller;

use addons\xshop\validate\NavValidate;
use addons\xshop\model\NavModel;
use addons\xshop\traits\LoginTrait;

/**
 * 导航
 * @weigh(10)
 */
class Nav extends Base
{
    public function __contruct()
    {
        parent::__contruct();
    }

    /**
     * 获取导航数据
     * @ApiParams (name="nav_type", type="integer", required=true, description="导航分类 0：首页轮播，1：首页分类")
     */
    public function index()
    {
        $params = $this->request->post();
        $result = $this->validate($params, NavValidate::class . '.getList');
        if (true !== $result) {
            return $this->error($result);
        }
        return $this->success('', NavModel::getList($params));
    }
}
