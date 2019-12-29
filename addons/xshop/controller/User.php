<?php

namespace addons\xshop\controller;

use think\addons\Controller;
use think\Config;
use app\common\library\Auth;
use addons\xshop\validate\UserValidate;
use addons\xshop\model\AddressModel;
use addons\xshop\traits\LoginTrait;
use addons\xshop\model\HistoryModel;
use addons\xshop\model\FavoriteModel;
use addons\xshop\model\UserModel;

/**
 * 用户
 * @ApiWeigh (10)
 */
class User extends Base
{
    protected $beforeActionList = [
        '__NeedLogin'
    ];

    use LoginTrait;
    /**
     * 获取用户信息
     * @ApiHeaders (name=Xshop-Token, type=string, required=true, description="请求的Token")
     */
    public function index()
    {
        $user = $this->auth->getUserInfo();
        \think\Hook::listen('xshop_get_userinfo', $user);
        return $this->success('', $user);
    }

    /**
     * 修改资料
     * @ApiHeaders (name=Xshop-Token, type=string, required=true, description="请求的Token")
     */
    public function editInfo()
    {
        $this->request->filter(['strip_tags']);
        $params = $this->request->post();
        $result = $this->validate($params, UserValidate::class . '.editInfo');
        if (true !== $result) {
            return $this->error($result);
        }
        return $this->success('', UserModel::editInfo($params));
    }

    /**
     * 新增或修改地址
     * @ApiHeaders (name=Xshop-Token, type=string, required=true, description="请求的Token")
     * @ApiParams (name=address_id, type=integer, required=false, description="address_id")
     * @ApiParams (name=address, type=string, required=true, description="送货地址 省 市 区")
     * @ApiParams (name=street, type=string, required=true, description="街道")
     * @ApiParams (name=is_default, type=integer, required=false, description="是否默认")
     * @ApiParams (name=contactor_name, type=string, required=true, description="联系人")
     * @ApiParams (name=phone, type=string, required=true, description="联系电话")
     */
    public function editAddress()
    {
        $params = $this->request->post();
        $result = $this->validate($params, UserValidate::class . '.edit');
        if (true !== $result) {
            return $this->error($result);
        }
        return $this->success('', AddressModel::edit($params));
    }

    /**
     * 删除地址
     * @ApiHeaders (name=Xshop-Token, type=string, required=true, description="请求的Token")
     * @ApiParams (name=address_id, type=integer, required=false, description="地址Id")
     */
    public function delAddress()
    {
        $params = $this->request->post();
        $result = $this->validate($params, UserValidate::class . '.deleteAddress');
        if (true !== $result) {
            return $this->error($result);
        }
        return $this->success('', AddressModel::del($params));
    }

    /**
     * 获取地址信息
     * @ApiHeaders (name=Xshop-Token, type=string, required=true, description="请求的Token")
     */
    public function getAddress()
    {
        return $this->success('', AddressModel::getList());
    }

    /**
     * 获取浏览历史
     * @ApiHeaders (name=Xshop-Token, type=string, required=true, description="请求的Token")
     */
    public function viewList()
    {
        return $this->success('', HistoryModel::getList());
    }
    /**
     * 获取收藏
     * @ApiHeaders (name=Xshop-Token, type=string, required=true, description="请求的Token")
     */
    public function favorite()
    {
        return $this->success('', FavoriteModel::getList());
    }
}
