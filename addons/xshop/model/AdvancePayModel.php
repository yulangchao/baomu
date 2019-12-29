<?php

namespace addons\xshop\model;

use addons\xshop\exception\Exception;
use addons\xshop\exception\NotFoundException;
use addons\xshop\exception\NotLoginException;
use app\admin\library\xshop\Tools;
use addons\xshop\logic\SkuLogic;
use think\Db;
use think\Hook;

class AdvancePayModel extends Model
{
    protected $name = 'xshop_advance_pay';
    protected $autoWriteTimestamp = true;
    protected $create_time = 'create_time';
    protected $update_time = false;
    protected $delete_time = false;

    /**
     * 获取一个不重复提交的订单号
     * $order_sn, $pay_type, $pay_method
     */
    public static function getOutOrderSN($attributes)
    {
        extract($attributes);
        $data = [
            'order_sn' => $order_sn,
            'platform' => $pay_type,
            'pay_method' => $pay_method
        ];
        $model = self::where($data)->find();
        if (empty($model)) {
            $data['order_sn_re'] = $order_sn . "RE" . mt_rand(1000000, 9999999);
            $data['create_time'] = time();
            return $data['order_sn_re'];
        } else {
            return $model->order_sn_re;
        }
    }
}
