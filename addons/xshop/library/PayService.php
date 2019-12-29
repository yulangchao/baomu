<?php

namespace addons\xshop\library;

use addons\epay\library\Service;
use Yansongda\Pay\Pay;
use think\Response;
use think\Session;

class PayService extends Service
{
    public static function submitOrder($amount, $orderid = null, $type = null, $title = null, $notifyurl = null, $returnurl = null, $method = null)
    {
        if (!is_array($amount)) {
            $params = [
                'amount'    => $amount,
                'orderid'   => $orderid,
                'type'      => $type,
                'title'     => $title,
                'notifyurl' => $notifyurl,
                'returnurl' => $returnurl,
                'method'    => $method,
            ];
        } else {
            $params = $amount;
        }
        $type = isset($params['type']) && in_array($params['type'], ['alipay', 'wechat']) ? $params['type'] : 'wechat';
        $method = isset($params['method']) ? $params['method'] : 'web';
        $orderid = isset($params['orderid']) ? $params['orderid'] : date("YmdHis") . mt_rand(100000, 999999);
        $amount = isset($params['amount']) ? $params['amount'] : 1;
        $title = isset($params['title']) ? $params['title'] : "支付";
        $auth_code = isset($params['auth_code']) ? $params['auth_code'] : '';
        $openid = isset($params['openid']) ? $params['openid'] : '';

        $request = request();
        $notifyurl = isset($params['notifyurl']) ? $params['notifyurl'] : $request->root(true) . '/addons/epay/index/' . $type . 'notify';
        $returnurl = isset($params['returnurl']) ? $params['returnurl'] : $request->root(true) . '/addons/epay/index/' . $type . 'return/out_trade_no/' . $orderid;
        $html = '';
        $config = Service::getConfig($type);
        $config[$type]['notify_url'] = $notifyurl;
        $config[$type]['return_url'] = $returnurl;

        if ($type == 'alipay') {
            //创建支付对象
            $pay = new Pay($config);
            //支付宝支付,请根据你的需求,仅选择你所需要的即可
            $params = [
                'out_trade_no' => $orderid,//你的订单号
                'total_amount' => $amount,//单位元
                'subject'      => $title,
            ];
            //如果是移动端自动切换为wap
            $method = $method == 'web' ? ($request->isMobile() ? 'wap' : $method) : $method;

            switch ($method) {
                case 'web':
                    //电脑支付,跳转
                    $html = $pay->driver($type)->gateway('web')->pay($params);
                    Response::create($html)->send();
                    break;
                case 'wap':
                    //手机网页支付,跳转
                    $html = $pay->driver($type)->gateway('wap')->pay($params);
                    Response::create($html)->send();
                    break;
                case 'mp':
                case 'app':
                    //APP支付,直接返回字符串
                    $html = $pay->driver($type)->gateway('app')->pay($params);
                    break;
                case 'tt':
                    $url = $pay->driver($type)->gateway('app')->pay($params);
                    $ttPay = self::createTTPay();
                    $params['openid'] = $openid;
                    $params['url'] = $url;
                    $params['notifyurl'] = $notifyurl;
                    $html = $ttPay->pay($params);
                    break;
                case 'scan':
                    //扫码支付,直接返回字符串
                    $html = $pay->driver($type)->gateway('scan')->pay($params);
                    break;
                case 'pos':
                    //刷卡支付,直接返回字符串
                    //刷卡支付必须要有auth_code
                    $params['auth_code'] = $auth_code;
                    $html = $pay->driver($type)->gateway('pos')->pay($params);
                    break;
                default:
                    //其它支付类型请参考：https://docs.pay.yansongda.cn/alipay
            }
        } else {
            //如果是PC支付,判断当前环境,进行跳转
            if ($method == 'web') {
                if ((strpos($request->server('HTTP_USER_AGENT'), 'MicroMessenger') !== false)) {
                    Session::delete("openid");
                    Session::set("wechatorderdata", $params);
                    $url = addon_url('epay/api/wechat', [], true, true);
                    header("location:{$url}");
                    exit;
                } elseif ($request->isMobile()) {
                    $method = 'wap';
                }
            }

            //创建支付对象
            $pay = new Pay($config);
            $params = [
                'out_trade_no' => $orderid,//你的订单号
                'body'         => $title,
                'total_fee'    => $amount * 100, //单位分
            ];
            switch ($method) {
                case 'web':
                    //电脑支付,跳转到自定义展示页面(FastAdmin独有)
                    $html = $pay->driver($type)->gateway('web')->pay($params);
                    Response::create($html)->send();
                    break;
                case 'mp':
                    //公众号支付
                    //公众号支付必须有openid
                    $params['openid'] = $openid;
                    $html = $pay->driver($type)->gateway('mp')->pay($params);
                    break;
                case 'wap':
                    //手机网页支付,跳转
                    $params['spbill_create_ip'] = $request->ip(0, false);
                    $html = $pay->driver($type)->gateway('wap')->pay($params);
                    header("location:{$html}");
                    exit;
                    break;
                case 'app':
                    //APP支付,直接返回字符串
                    $html = $pay->driver($type)->gateway('app')->pay($params);
                    break;
                case 'scan':
                    //扫码支付,直接返回字符串
                    $html = $pay->driver($type)->gateway('scan')->pay($params);
                    break;
                case 'pos':
                    //刷卡支付,直接返回字符串
                    //刷卡支付必须要有auth_code
                    $params['auth_code'] = $auth_code;
                    $html = $pay->driver($type)->gateway('pos')->pay($params);
                    break;
                case 'miniapp':
                    //小程序支付,直接返回字符串
                    //小程序支付必须要有openid
                    $params['openid'] = $openid;
                    $html = $pay->driver($type)->gateway('miniapp')->pay($params);
                    break;
                default:
            }
        }
        //返回字符串
        $html = is_array($html) ? json_encode($html) : $html;
        return $html;
    }

    /**
     * 创建头条支付对象
     */
    public static function createTTPay()
    {
        $config = \addons\xshop\model\ConfigModel::getByCodes(['xshop_tt_mp_appid', 'xshop_tt_mp_AppSecret', 'xshop_tt_mp_mchid', 'xshop_tt_mp_app_id', 'xshop_tt_mp_pay_secret']);
        $config = [
            'appid' => $config['xshop_tt_mp_appid'],
            'secret' => $config['xshop_tt_mp_AppSecret'],
            'merchant_id' => $config['xshop_tt_mp_mchid'],
            'pay_app_id' => $config['xshop_tt_mp_app_id'],
            'pay_app_secret' => $config['xshop_tt_mp_pay_secret']
        ];
        return TT::instance($config);
    }
}
