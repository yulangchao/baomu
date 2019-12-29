<?php

namespace app\admin\model\xshop;

use think\Model;
use think\Validate;


class DeliveryRule extends Model
{

    

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'xshop_delivery_rule';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];


    public static function add($tpl_id, $attributes) {
        extract($attributes);
        $delivery_rule_data = [];
        foreach ($area_ids as $i => $row) {
            $data = [
                'tpl_id' => $tpl_id,
                'area_ids' => $area_ids[$i],
                'area_names' => $area_names[$i],
                'first_price' => $first_price[$i],
                'rest_price' => $rest_price[$i]
            ];
            $rules = [
                'tpl_id' => 'require|number',
                'area_ids' => 'require',
                'area_names' => 'require',
                'first_price' => 'require|number|min:1',
                'rest_price' => 'require|number|min:1'
            ];
            $msg = [
                'first_price.require' => '价格不能为空',
                'first_price.number' => '价格必须是数字',
                'first_price.min' => '价格不能为负数',
                'rest_price.require' => '价格不能为空',
                'rest_price.number' => '价格必须是数字',
                'rest_price.min' => '价格不能为负数',
            ];
            $validate = new Validate($rules, $msg);
            if (!$validate->check($data)) {
                throw new \think\exception\ValidateException($validate->getError());
            }
            $delivery_rule_data[] = $data;
        }
        if (empty($delivery_rule_data)) throw new \think\Exception("请添加运费规则");
        $deliveryRule = new DeliveryRule;
        $deliveryRule->saveAll($delivery_rule_data);
        return $deliveryRule;
    }
    
}
