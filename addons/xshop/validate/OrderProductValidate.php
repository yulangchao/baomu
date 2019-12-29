<?php
namespace addons\xshop\validate;
class OrderProductValidate extends Validate {
    protected $rule = [
        'id' => 'require|number',
        'star' => 'require|number',
        'content' => 'max:250'
    ];

    protected $message = [
    ];

    protected $scene = [
        'review' => ['id', 'star', 'content']
    ];
}