<?php
namespace addons\xshop\exception;

use think\Exception as Base;
use think\Response;

class Exception
{
    protected $msg = '异常消息';
    protected $code = 10000;
    const NOT_FOUND = 404;
    const NOT_LOGIN = 401;
    const NOT_AUTHORIZE = 403;
    const IGNORE = 9999;
    public function __construct($msg, $code = 10000, $status_code = 200)
    {
        $this->msg = $msg;
        $this->code = $code;
        $this->send($status_code);
    }
    protected function send($code = 200)
    {
        $header = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET,POST,PUT,PATCH,DELETE',
            'Access-Control-Allow-Headers' => 'Content-Type,Xshop-Token,platform',
            'Access-Control-Max-Age' => 86400
        ];
        $data = [
            'code' => $this->code,
            'msg' => $this->msg,
            'data' => null,
            'time' => time()
        ];
        Response::create($data, 'json', $code, $header)->send();
        die;
    }
}
