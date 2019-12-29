<?php
namespace addons\xshop\validate;
class OrderValidate extends Validate {
    protected $rule = [
        'address_id' => 'require|number',
        'coupon_id' => 'number',
        'order_sn' => 'require',
        'sku_id' => 'require|number',
        'quantity' => 'require|number|min:1',
        'remark|备注' => 'max:200'
    ];

    protected $message = [
        'address_id.require' => '请选择送货地址',
    ];

    protected $scene = [
        'add' => ['address_id', 'coupon_id', 'remark'],
        'addOne' => ['address_id', 'sku_id', 'quantity'],
        'info' => ['order_sn'],
        'getList' => [],
        'sn' => ['order_sn'],
        'state' => ['state'],
        'getPrice' => ['address_id', 'sku_id', 'quantity']
    ];
}