<?php

namespace addons\xshop\controller;

use addons\xshop\validate\CartValidate;
use addons\xshop\model\CartModel;
use addons\xshop\model\OrderModel;
use addons\xshop\traits\LoginTrait;

/**
 * 购物车
 */
class Cart extends Base
{
    protected $beforeActionList = [
        '__NeedLogin'
    ];

    use LoginTrait;
    /**
     * 获取购物车商品
     * @ApiHeaders (name=Xshop-Token, type=string, required=true, description="请求的Token")
     */
    public function index()
    {
        $param = $this->request->get();
        if (empty($param)) {
            return $this->success('', CartModel::getList());
        } else {
            $result = $this->validate($param, CartValidate::class . '.buyOneInfo');
            if (true !== $result) {
                return $this->error($result);
            }
            return $this->success('', OrderModel::buyOneInfo($param));
        }
    }

    /**
     * 添加到购物车
     * @ApiSummary 添加到购物车
     * @ApiRoute addons/xshop/cart/add
     * @ApiMethod (POST)
     * @ApiHeaders (name=Xshop-Token, type=string, required=true, description="请求的Token")
     * @ApiParams (name="sku_id", type="integer", required=true, description="商品sku_id")
     * @ApiParams (name="quantity", type="integer", required=true, description="购买数量")
     */
    public function add()
    {
        $param = $this->request->post();
        $result = $this->validate($param, CartValidate::class . '.add');
        if (true !== $result) {
            return $this->error($result);
        }
        return $this->success('', CartModel::add($param));
    }
    /**
     * 更新购物车数量
     * @ApiSummary 添加到购物车
     * @ApiRoute addons/xshop/cart/update
     * @ApiMethod (POST)
     * @ApiHeaders (name=Xshop-Token, type=string, required=true, description="请求的Token")
     * @ApiParams (name="sku_id", type="integer", required=true, description="商品sku_id")
     * @ApiParams (name="quantity", type="integer", required=true, description="购买数量")
     */
    public function edit()
    {
        $param = $this->request->post();
        $result = $this->validate($param, CartValidate::class . '.edit');
        if (true !== $result) {
            return $this->error($result);
        }
        return $this->success('', CartModel::edit($param));
    }
    /**
     * 更新购物车选中状态
     * @ApiSummary 更新购物车选中状态, 用于创建订单
     * @ApiRoute addons/xshop/cart/update
     * @ApiMethod (POST)
     * @ApiHeaders (name=Xshop-Token, type=string, required=true, description="请求的Token")
     * @ApiParams (name="ids", type="array", required=true, description="需要选中的商品项")
     */
    public function updateStatus()
    {
        $param = $this->request->post();
        $result  = $this->validate($param, CartValidate::class . '.update_status');
        if (true !== $result) {
            return $this->error($result);
        }
        return $this->success('', CartModel::updateStatus($param));
    }
    /**
     * 删除购物车商品
     * @ApiSummary 删除购物车商品
     * @ApiRoute addons/xshop/cart/update
     * @ApiMethod (POST)
     * @ApiHeaders (name=Xshop-Token, type=string, required=true, description="请求的Token")
     * @ApiParams (name="id", type="array", required=true, description="")
     */
    public function delete()
    {
        $param = $this->request->post();
        $result  = $this->validate($param, CartValidate::class . '.delete');
        if (true !== $result) {
            return $this->error($result);
        }
        return $this->success('', CartModel::del($param));
    }
    /**
     * 清空购物车
     * @ApiSummary 清空购物车
     * @ApiRoute addons/xshop/cart/update
     * @ApiMethod (POST)
     * @ApiHeaders (name=Xshop-Token, type=string, required=true, description="请求的Token")
     */
    public function clear()
    {
        return $this->success('', CartModel::clear());
    }
}
