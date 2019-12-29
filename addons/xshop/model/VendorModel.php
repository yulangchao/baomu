<?php

namespace addons\xshop\model;

use app\common\library\Auth;
use app\common\library\Sms;
use addons\xshop\exception\Exception;
use addons\xshop\exception\NotFoundExceptionException;
use addons\xshop\library\Weixin;
use addons\xshop\library\TT;

class VendorModel extends Model
{
    protected $name = "xshop_vendor";

    /**
     * 微信小程序登录
     */
    public static function wxMPLogin($code)
    {
        $config = ConfigModel::getByCodes(['xshop_wx_mp_appid', 'xshop_wx_mp_AppSecret']);
        $config = [
            'appid' => $config['xshop_wx_mp_appid'],
            'secret' => $config['xshop_wx_mp_AppSecret']
        ];
        $wx = Weixin::instance($config);
        $res = $wx->loginByCode($code);
        if (!$res['ret']) {
            throw new Exception($res['msg']);
        }
        $data = json_decode($res['msg'], 1);
        if (!empty($data['errcode'])) {
            throw new Exception($data['errmsg']);
        }
        return $data;
    }

    /**
     * 头条小程序登录
     */
    public static function ttMPLogin($code)
    {
        $config = ConfigModel::getByCodes(['xshop_tt_mp_appid', 'xshop_tt_mp_AppSecret']);
        $config = [
            'appid' => $config['xshop_tt_mp_appid'],
            'secret' => $config['xshop_tt_mp_AppSecret']
        ];
        $tt = TT::instance($config);
        $res = $tt->loginByCode($code);
        if (!$res['ret']) {
            throw new Exception($res['msg']);
        }
        $data = json_decode($res['msg'], 1);
        if (!empty($data['error'])) {
            throw new Exception($data['message']);
        }
        return $data;
    }
    
    /**
     * 第三方账户登录 与原账户系统关联
     * $openid $session_key $unionid
     */
    public static function login($attributes)
    {
        // todo
    }
}
