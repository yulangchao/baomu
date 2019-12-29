<?php
namespace addons\xshop\validate;
use think\Validate;
class AppSourceValidate extends Validate {
    protected $rule = [
        'version' => 'require',
        'platform' => 'require'
    ];

    protected $message = [
        
    ];

    protected $scene = [
        'check' => ['version', 'platform']
    ];
}