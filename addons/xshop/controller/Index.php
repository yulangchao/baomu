<?php

namespace addons\xshop\controller;

use think\addons\Controller;
use think\Config;
use app\common\library\Auth;
use addons\xshop\validate\UserValidate;
use addons\xshop\validate\AppSourceValidate;
use addons\xshop\model\AppSourceModel;
use addons\xshop\model\UserModel;
use addons\xshop\model\ConfigModel;
use app\common\library\Sms;
use addons\xshop\library\Weixin;
use addons\xshop\model\VendorModel;
use addons\xshop\model\LaunchLogModel;
use addons\xshop\model\HookAddonsModel;

/**
 * 首页
 * @ApiWeigh (99999)
 */
class Index extends Base
{
    protected function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 测试
     */
    public function index()
    {
        $this->success('Hi');
    }

    /**
     * 应用启动
     */
    public function launch()
    {
        $params = $this->request->post();
        $params['platform'] = $this->request->header('platform');
        $rules = [
            'platform' => 'require|in:H5,WX-H5,MP-WEIXIN,MP-ALIPAY,MP-BAIDU,MP-QQ,other,android,ios',
            'systeminfo' => 'require'
        ];
        $msg = [
            'platform' => '平台不正确'
        ];
        $result = $this->validate($params, $rules, $msg);
        if ($result !== true) {
            return $this->error($result, null, 9999);
        }
        return $this->success(LaunchLogModel::addLog($params));
    }

    /**
     * 获取用户信息
     * @ApiHeaders (name=Xshop-Token, type=string, required=true, description="请求的Token")
     */
    public function getUserInfo()
    {
        if ($this->auth->isLogin()) {
            return $this->success('', $this->auth->getUserInfo());
        }
        return $this->error('未登录');
    }

    /**
     * 会员登录
     * @ApiMethod (POST)
     * @ApiParams (name="username", type="integer", required=false, description="账号 0")
     * @ApiParams (name="password", type="integer", required=false, description="密码 0")
     * @ApiParams (name="mobile", type="string", required=false, description="手机号码 1")
     * @ApiParams (name="code", type="string", required=false, description="验证码 1")
     * @ApiParams (name="loginWay", type="integer", required=true, description="0 账号密码登录 1：手机验证码登录")
     */
    public function login()
    {
        $params = $this->request->post();
        $loginWay = isset($params['loginWay']) ? intval($params['loginWay']) : 0;
        if ($loginWay == 0) {
            $result = $this->validate($params, UserValidate::class . '.login');
            if (true !== $result) {
                return $this->error($result);
            }
            $auth = Auth::instance();
            if (true === $auth->login($params['username'], $params['password'])) {
                return $this->success('', $auth->getUserInfo());
            }
            return $this->error($auth->getError());
        } else {
            $result = $this->validate($params, [
                'mobile' => 'require',
                'code' => 'require'
            ]);
            if (true !== $result) {
                return $this->error($result);
            }
            return $this->success('', UserModel::registerOrLogin($params));
        }
    }

    public function appInfo() {
        $config = [
            'plugins' => HookAddonsModel::getAddons()
        ];
        return $this->success('', $config);
    }

    /**
     * 获取手机验证码
     * @ApiParams (name="mobile", type="string", required=true, description="手机号码")
     */
    public function getVerifyCode()
    {
        $params = $this->request->post();
        $result = $this->validate($params, [
            'mobile' => 'require'
        ]);
        if (true !== $result) {
            return $this->error($result);
        }
        if (true === Sms::send($params['mobile'], null, 'XShopRegister')) {
            return $this->success();
        }
        return $this->error("短信发送失败");
    }


    /**
     * 注销登录
     * @ApiHeaders (name=Xshop-Token, type=string, required=true, description="请求的Token")
     */
    public function logout()
    {
        if ($this->auth->isLogin()) {
            $this->auth->logout();
            return $this->success();
        }
        return $this->error("您未登录");
    }

    /** APP更新 */
    public function update()
    {
        $params = $this->request->post();
        $result = $this->validate($params, AppSourceValidate::class . '.check');
        if (true !== $result) {
            return $this->error($result);
        }
        return $this->success('', AppSourceModel::checkUpdate($params));
    }

    /** 获取配置 */
    public function getConfig()
    {
        $config = [
            'h5_appId' => 'xshop_h5_appid',
            'wx_mp_appid' => 'xshop_wx_mp_appid'
        ];
        $params = $this->request->post();
        $result = $this->validate($params, [
            'code' => 'require|array'
        ]);
        if (true !== $result) {
            return $this->error($result);
        }
        $codes = [];
        foreach ($params['code'] as $item) {
            if (isset($config[$item])) {
                $codes[] = $config[$item];
            }
        }
        return $this->success('', ConfigModel::getByCodes($codes));
    }

    /** 获取openId */
    public function getOpenId()
    {
        $params = $this->request->post();
        $result = $this->validate($params, [
            'code' => 'require'
        ]);
        if (true !== $result) {
            return $this->error($result);
        }
        $config = ConfigModel::getByCodes(['xshop_h5_appid', 'xshop_h5_AppSecret']);
        $appid = $config['xshop_h5_appid'];
        $secret = $config['xshop_h5_AppSecret'];
        $code = $params['code'];
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$secret}&code={$code}&grant_type=authorization_code";
        $res = \fast\Http::sendRequest($url, [], 'GET');
        if (!$res['ret']) {
            return $this->error($res['msg']);
        }
        $data = json_decode($res['msg'], 1);
        if (isset($data['errcode'])) {
            return $this->error($data['errmsg']);
        }
        return $this->success('', $data['openid']);
    }


    /**
     * 微信小程序登录
     */
    public function wxMPLogin()
    {
        $code = $this->request->post('code');
        $data = VendorModel::wxMPLogin($code);
        return $this->success('', $data['openid']);
    }

    /**
     * 头条小程序登录
     */

    public function ttMPLogin()
    {
        $code = $this->request->post('code');
        $data = VendorModel::ttMPLogin($code);
        return $this->success('', $data['openid']);
    }
}
