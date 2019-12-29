<?php
namespace addons\xshop\validate;
class NavValidate extends Validate {
    protected $rule = [
        'nav_type' => 'require|array',
    ];

    protected $message = [
        
    ];

    protected $scene = [
        'getList' => ['nav_type'],
    ];
}