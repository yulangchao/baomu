<?php

namespace addons\xshop\model;

use addons\xshop\exception\Exception;
use addons\xshop\exception\NotFoundException;
use addons\xshop\exception\NotLoginException;
use app\admin\library\xshop\Tools;
use addons\xshop\logic\SkuLogic;
use think\Db;
use think\Hook;

class HistoryModel extends Model
{
    protected $name = 'xshop_history';
    protected $autoWriteTimestamp = true;
    protected $deleteTime = false;

    public static function getList()
    {
        $user = UserModel::info();
        return self::with(['product'])->where('user_id', $user->id)->order('update_time', 'DESC')->paginate(10);
    }

    public function product()
    {
        return $this->hasOne('ProductModel', 'id', 'product_id');
    }

    public function getCreateTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['create_time']) ? $data['create_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i", $value) : $value;
    }
}
