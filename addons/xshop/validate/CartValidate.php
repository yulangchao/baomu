<?php
namespace addons\xshop\validate;
class CartValidate extends Validate {
    protected $rule = [
        'sku_id' => 'require|number',
        'quantity' => 'require|number',
        'ids' => 'require|array'
    ];

    protected $message = [
        'ids.require' => '没有选择商品'
    ];

    protected $scene = [
        'add' => ['sku_id', 'quantity'],
        'edit' => ['sku_id', 'quantity'],
        'update_status' => ['ids'],
        'delete' => ['ids'],
        'buyOneInfo' => ['sku_id', 'quantity']
    ];
}