<?php

namespace addons\xshop\controller;

use addons\xshop\validate\OrderValidate;
use addons\xshop\validate\OrderProductValidate;
use addons\xshop\model\OrderModel;
use addons\xshop\model\OrderProductModel;
use addons\xshop\traits\LoginTrait;

/**
 * 订单
 */
class Order extends Base
{
    protected $beforeActionList = [
        '__NeedLogin'
    ];
    function __contruct() {
        parent::__contruct();
    }

    use LoginTrait;

    /**
     * 获取订单
     * @ApiHeaders (name=Xshop-Token, type=string, required=true, description="请求的Token")
     * @ApiParams (name="state", type="array", required=false, description="订单状态")
     */
    public function index() {
        $params = $this->request->get();
        $result = $this->validate($params, OrderValidate::class . '.state');
        if (true !== $result) {
            return $this->error($result);
        }
        return $this->success('', OrderModel::getList($params));
    }

    /**
     * 获取订单信息
     * @ApiHeaders (name=Xshop-Token, type=string, required=true, description="请求的Token")
     * @ApiParams (name="order_sn", type="array", required=true, description="订单sn")
     */
    public function info() {
        $params = $this->request->get();
        $result = $this->validate($params, OrderValidate::class . '.info');
        if (true !== $result) {
            return $this->error($result);
        }
        return $this->success('', OrderModel::info($params));
    }
    
    /**
     * 创建订单
     * @ApiHeaders (name=Xshop-Token, type=string, required=true, description="请求的Token")
     * @ApiParams (name="address_id", type="integer", required=true, description="收货地址Id")
     * @ApiParams (name="coupon_id", type="integer", required=false, description="使用优惠券Id")
     */
    public function add() {
        $params = $this->request->post();
        if (!empty($params['sku_id'])) { // 立即购买
            $result = $this->validate($params, OrderValidate::class . '.addOne');
            if (true !== $result) {
                return $this->error($result);
            }
            return $this->success('', OrderModel::addOne($params));
        } else {
            $result = $this->validate($params, OrderValidate::class . '.add');
            if (true !== $result) {
                return $this->error($result);
            }
            return $this->success('', OrderModel::add($params));
        }
    }

    /**
     * 订单收货
     * @ApiHeaders (name=Xshop-Token, type=string, required=true, description="请求的Token")
     * @ApiParams (name="order_sn", type="integer", required=true, description="订单号")
     */
    public function receive() {
        $params = $this->request->post();
        $result = $this->validate($params, OrderValidate::class . '.sn');
        if (true !== $result) {
            return $this->error($result);
        }
        return $this->success('', OrderModel::receive($params));
    }

    /**
     * 订单取消
     * @ApiHeaders (name=Xshop-Token, type=string, required=true, description="请求的Token")
     * @ApiParams (name="order_sn", type="integer", required=true, description="订单号")
     */
    public function cancel() {
        $params = $this->request->post();
        $result = $this->validate($params, OrderValidate::class . '.sn');
        if (true !== $result) {
            return $this->error($result);
        }
        return $this->success('', OrderModel::cancel($params));
    }

    /**
     * 订单删除
     * @ApiHeaders (name=Xshop-Token, type=string, required=true, description="请求的Token")
     * @ApiParams (name="order_sn", type="integer", required=true, description="订单号")
     */
    public function del() {
        $params = $this->request->post();
        $result = $this->validate($params, OrderValidate::class . '.sn');
        if (true !== $result) {
            return $this->error($result);
        }
        return $this->success('', OrderModel::del($params));
    }

    /**
     * 评价订单商品
     * @ApiHeaders (name=Xshop-Token, type=string, required=true, description="请求的Token")
     * @ApiParams (name="id", type="integer", required=true, description="订单商品ID")
     * @ApiParams (name="star", type="integer", required=true, description="评价星级")
     * @ApiParams (name="content", type="string", required=false, description="评价内容")
     */
    public function review() {
        $params = $this->request->post();
        $result = $this->validate($params, OrderProductValidate::class . '.review');
        if (true !== $result) {
            return $this->error($result);
        }
        return $this->success('', OrderProductModel::review($params));
    }

    public function getPrice() {
        $params = $this->request->post();
        $rules = [
            'address_id' => 'require'
        ];
        $msg = [
            'address_id' => '请选择收货地址'
        ];
        $result = $this->validate($params, $rules, $msg);
        if ($result !== true) {
            return $this->error($result);
        }
        return $this->success('', OrderModel::getPrice($params));
    }
}