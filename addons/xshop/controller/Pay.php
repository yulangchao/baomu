<?php

namespace addons\xshop\controller;

use addons\xshop\model\PayModel;

/**
 * 支付
 */
class Pay extends Base
{
    protected $needLogin = ['index'];
    /**
     * 获取购物车商品
     * @ApiMethod (POST)
     * @ApiHeaders (name=Xshop-Token, type=string, required=true, description="请求的Token")
     * @ApiParams (name="order_sn", type="string", required=true, description="订单号")
     * @ApiParams (name="pay_type", type="string", required=true, description="支付方式 alipay,wechat")
     * @ApiParams (name="pay_method", type="integer", required=true, description="支付类型")
     */
    public function index()
    {
        $param = $this->request->post();
        $result = $this->validate($param, [
            'order_sn' => 'require',
            'pay_type' => 'require',
            'pay_method' => 'require'
        ]);
        if (true != $result) {
            return $this->error($result);
        }
        return $this->success('', PayModel::submit($param));
    }
    
    /** 支付回调 */
    public function notify()
    {
        PayModel::notify();
    }

    /** 支付返回 */
    public function returnx()
    {
        PayModel::returnx();
    }
}
