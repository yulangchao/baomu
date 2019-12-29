<?php

namespace addons\xshop\library;

use fast\Http;

class Weixin
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
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$code}&grant_type=authorization_code";
        return Http::sendRequest($url, [], 'GET');
    }
}
