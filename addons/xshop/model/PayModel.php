<?php

namespace addons\xshop\model;

use addons\xshop\exception\Exception;
use addons\xshop\exception\NotFoundException;
use addons\xshop\exception\NotLoginException;
use app\admin\library\xshop\Tools;
use addons\xshop\library\PayService;
use think\Log;

class PayModel extends Model
{
    protected $name = 'xshop_pay';
    protected $visible = [
    ];
    protected $hidden = [
    ];
    protected $append = [
    ];
    
    /**
     * 发起订单支付
     */
    public static function submit($attributes)
    {
        extract($attributes);
        $user = UserModel::info();
        $order = OrderModel::with(['products' => function ($query) {
            $query->with(['product']);
        }])->where('order_sn', $order_sn)->where('user_id', $user->id)->find();
        $products = $order->products;
        foreach ($products as $item) {
            if (!$item->product || $item->product['on_sale'] == 0) {
                throw new Exception("商品 " . $item['title'] . " 已下架");
            }
        }
        if (empty($order)) {
            throw new NotFoundException("找不到订单");
        }
        if ($order->status != 0) {
            throw new Exception("该订单不可以付款");
        }
        $epay = get_addon_info('epay');
        $request = \think\Request::instance();
        $auth_code = isset($auth_code) ? $auth_code : '';
        if ($epay && $epay['state']) {
            $notifyurl = $request->root(true) . '/addons/xshop/pay/notify/paytype/' . $pay_type . '/' . 'paymethod/' . $pay_method;
            $returnurl = $request->root(true) . '/addons/xshop/pay/returnx/paytype/' . $pay_type . '/' . 'paymethod/' . $pay_method;
            $order_sn_re = AdvancePayModel::getOutOrderSN($attributes);
            $data = [
                'type' => $pay_type,
                'orderid' => $order_sn_re,
                'title' => $order_sn,
                'amount' => $order->order_price,
                'method' => $pay_method,
                'openid' => $openId,
                'auth_code' => $auth_code,
                'notifyurl' => $notifyurl,
                'returnurl' => $returnurl
            ];
            try {
                return PayService::submitOrder($data);
            } catch (\Yansongda\Pay\Exceptions\GatewayException $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            throw new Exception("请先在后台安装并配置微信支付宝整合插件");
        }
    }

    public static function notify()
    {
        $paytype = request()->param('paytype');
        $pay_method = request()->param('paymethod');
        $pay = PayService::checkNotify($paytype);
        Log::info($pay);
        if (!$pay) {
            echo '签名错误';
            return;
        }
        $data = $pay->verify();
        Log::info($data);
        Log::info("abcdef");
        try {
            $payamount = $paytype == 'alipay' ? $data['total_amount'] : $data['total_fee'] / 100;
            $order_sn_re = $data['out_trade_no'];
            if (stripos($order_sn_re, 'RE') !== false) {
                $order_sn = explode('RE', $order_sn_re)[0];
            } else {
                $order_sn = $order_sn_re;
            }
            $order = OrderModel::where(['order_sn' => $order_sn])->find();
            if (!empty($order)) {
                $order->status = 1;
                $order->is_pay = 1;
                $order->payed_price = $payamount;
                $order->pay_type = $paytype;
                $order->pay_method = $pay_method;
                $order->pay_time = time();
                $order->save();
                \think\Hook::listen('xshop_order_pay_ok', $order);
            }
        } catch (Exception $e) {
        }
        echo $pay->success();
    }

    public static function returnx()
    {
        $paytype = request()->param('paytype');
        $pay = PayService::checkReturn($paytype);
        if (!$pay) {
            $this->error('签名错误');
        }
        

        //你可以在这里定义你的提示信息,但切记不可在此编写逻辑
        $this->success("恭喜你！充值成功!", url("user/index"));
    }

    /**
     * 企业支付通知和回调
     * @throws \think\exception\DbException
     */
    public function epay()
    {
        $type = $this->request->param('type');
        $paytype = $this->request->param('paytype');
        if ($type == 'notify') {
            $pay = \addons\epay\library\Service::checkNotify($paytype);
            if (!$pay) {
                echo '签名错误';
                return;
            }
            $data = $pay->verify();
            try {
                $payamount = $paytype == 'alipay' ? $data['total_amount'] : $data['total_fee'] / 100;
                \addons\recharge\model\Order::settle($data['out_trade_no'], $payamount);
            } catch (Exception $e) {
            }
            echo $pay->success();
        } else {
            $pay = \addons\epay\library\Service::checkReturn($paytype);
            if (!$pay) {
                $this->error('签名错误');
            }

            //你可以在这里定义你的提示信息,但切记不可在此编写逻辑
            $this->success("恭喜你！充值成功!", url("user/index"));
        }
        return;
    }
}
