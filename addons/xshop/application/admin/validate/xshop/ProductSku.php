<?php

namespace app\admin\validate\xshop;

use think\Validate;

class ProductSku extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'product_id' => 'require',
        'keys' => 'require',
        'value' => 'require',
        'price' => 'require|number|min:0.01',
        'stock' => 'require|integer|min:1',
        'weight' => 'require|number'
    ];
    /**
     * 提示消息
     */
    protected $message = [
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => ['product_id', 'keys', 'value', 'price', 'stock', 'weight', 'sn'],
        'edit' => ['product_id', 'keys', 'value', 'price', 'stock', 'weight', 'sn'],
    ];
    
}
