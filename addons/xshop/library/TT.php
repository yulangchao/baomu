<?php

namespace addons\xshop\library;

use fast\Http;
use addons\xshop\exception\Exception;

class TT
{
    protected $config = [];
    public function __construct($config = [])
    {
        $this->config = $config;
    }

    public static function instance($config = [])
    {
        return new static($config);
    }

    public function loginByCode($code)
    {
        $appid = $this->config['appid'];
        $secret = $this->config['secret'];
        $url = "https://developer.toutiao.com/api/apps/jscode2session?appid={$appid}&secret={$secret}&code={$code}";
        return Http::sendRequest($url, [], 'GET');
    }

    /**
     * 封装支付数据
     */
    public function pay($params)
    {
        $data = [
            'app_id' => $this->config['pay_app_id'],
            'method' => 'tp.trade.confirm',
            'sign_type' => 'MD5',
            'timestamp' => time() . '',
            'trade_no' => $this->createTrade($params),
            'merchant_id' => $this->config['merchant_id'],
            'uid' => $params['openid'],
            'pay_channel' => 'ALIPAY_NO_SIGN',
            'pay_type' => 'ALIPAY_APP',
            'total_amount' => intval($params['total_amount'] * 100),
            'risk_info' => json_encode([
                'ip' => request()->ip()
            ])
        ];
        $data['params'] = json_encode([
            'url' => $params['url']
        ]);
        $sign_fields = ['app_id', 'sign_type', 'timestamp', 'trade_no', 'merchant_id', 'uid', 'total_amount', 'params'];
        $data['sign'] = $this->sign($data, $sign_fields, $this->config['pay_app_secret']);
        return $data;
    }

    /**
     * 头条预下单
     * @return String trade_no
     */
    public function createTrade($params)
    {
        // 公共参数
        $data = [
            'app_id' => $this->config['pay_app_id'],
            'method' => 'tp.trade.create',
            'charset' => 'utf-8',
            'sign_type' => 'MD5',
            'timestamp' => time() . '',
            'version' => '1.0'
        ];
        // 业务参数
        $bdata = [
            'out_order_no' => $params['out_trade_no'],
            'uid' => $params['openid'],
            'merchant_id' => $this->config['merchant_id'],
            'total_amount' => intval($params['total_amount'] * 100),
            'currency' => 'CNY',
            'subject' => 'order',
            'body' => 'ordertest',
            'trade_time' => time(),
            'valid_time' => '15',
            'notify_url' => $params['notifyurl'],
            'risk_info' => json_encode([
                'ip' => request()->ip()
            ]),
        ];
        $biz_content = json_encode($bdata);
        $data['biz_content'] = $biz_content;
        $sign_fields = ['app_id', 'method', 'charset', 'sign_type', 'timestamp', 'version', 'biz_content'];
        // 签名
        $data['sign'] = $this->sign($data, $sign_fields, $this->config['pay_app_secret']);
        $url = 'https://tp-pay.snssdk.com/gateway?' . http_build_query($data);
        $res = Http::sendRequest($url, [], 'POST');
        if (!$res['ret']) {
            throw new Exception($res['msg']);
        }
        $resData = json_decode($res['msg'], 1);
        $response = $resData['response'];
        if ($response['code'] != 10000) {
            throw new Exception($resData);
        }
        return $response['trade_no'];
    }

    public function sign($data, $sign_fields, $salt = '')
    {
        $new_sign_fields = [];
        foreach ($sign_fields as $v) {
            if (isset($data[$v]) && trim($data[$v]) !== '') {
                $new_sign_fields[] = $v;
            }
        }
        sort($new_sign_fields);
        
        $signStrArr = [];
        foreach ($new_sign_fields as $v) {
            $signStrArr[] = $v . '=' . $data[$v];
        }
        // 待签名字符串
        $signStr = implode('&', $signStrArr);
        return MD5($signStr . $salt);
    }
}
