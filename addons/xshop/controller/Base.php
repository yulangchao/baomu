<?php

namespace addons\xshop\controller;

use app\common\controller\Api;
use app\common\library\Auth;
use addons\xshop\Hook;
use addons\xshop\exception\NotLoginException;
use addons\xshop\exception\IgnoreException;

/**
 * 入口
 * ApiInternal
 */
class Base extends Api
{
    protected $noNeedLogin = ['*'];
    protected $needLogin = [];
    protected function _initialize()
    {
        $this->header = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET,POST,PUT,PATCH,DELETE',
            'Access-Control-Allow-Headers' => 'Content-Type,Xshop-Token,platform',
            'Access-Control-Max-Age' => 86400
        ];
        if ($this->request->method() == 'OPTIONS') {
            \think\Response::create(null, null, 200, $this->header)->send();
            die;
        }
        parent::_initialize();
        $token = $this->request->header('Xshop-Token');
        $this->auth->init($token);
        $this->checkNeedLogin();
        $this->hook = Hook::instance();
        $this->initHooks(); // 初始化钩子
    }

    private function checkNeedLogin()
    {
        $actionname = strtolower($this->request->action());
        if ($this->auth->match($this->needLogin)) {
            if (!$this->auth->isLogin()) {
                throw new IgnoreException("您未登录");
            }
        }
    }

    private function initHooks()
    {
        $hooks = cache('xshop_hooks');
        $hooks = $hooks === false ? \addons\xshop\Hook::instance()->setListenersCache() : $hooks;
        $this->hook->bind($hooks);
    }

    protected function success($msg = '操作成功', $data = null, $code = 1, $type = 'json', array $header = [])
    {
        $header = array_merge($header, $this->header);
        return parent::success($msg, $data, $code, $type, $header);
    }

    protected function error($msg = '', $data = null, $code = 10000, $type = 'json', array $header = [])
    {
        $header = array_merge($header, $this->header);
        return parent::error($msg, $data, $code, $type, $header);
    }
}
