<?php
namespace addons\xshop\validate;
class ProductValidate extends Validate {
    protected $rule = [
        'id' => 'require|number',
        'state' => 'require|number'
    ];

    protected $message = [
    ];

    protected $scene = [
        'favorite' => ['id', 'state'],
        'id' => ['id']
    ];
}