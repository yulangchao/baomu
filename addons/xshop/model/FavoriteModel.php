<?php

namespace addons\xshop\model;

use addons\xshop\exception\Exception;
use addons\xshop\exception\NotFoundException;
use addons\xshop\exception\NotLoginException;
use app\admin\library\xshop\Tools;
use addons\xshop\logic\SkuLogic;
use think\Db;
use think\Hook;

class FavoriteModel extends Model
{
    protected $name = 'xshop_favorite';
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;
    protected $deleteTime = false;

    protected $hidden = [
        'create_time'
    ];
    protected $append = [
        'create_time_text'
    ];

    public static function getList()
    {
        $user = UserModel::info();
        return self::with(['product'])->where('user_id', $user->id)->order('create_time', 'DESC')->limit(50)->select();
    }

    public static function add($attributes)
    {
        extract($attributes);
        $user = UserModel::info();
        $state = empty($state) ? 0 : 1;
        $data = self::where('user_id', $user->id)->where('product_id', $id)->find();
        if ($state == 1) {
            if ($data) {
                throw new Exception("商品已收藏");
            }
            $data = new self;
            $data->save([
                'user_id' => $user->id,
                'product_id' => $id
            ]);
        } else {
            if ($data) {
                $data->delete();
            } else {
                throw new Exception("商品已取消收藏");
            }
        }
        return true;
    }

    public function product()
    {
        return $this->belongsTo('ProductModel', 'product_id', 'id');
    }

    public function getCreateTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['create_time']) ? $data['create_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i", $value) : $value;
    }
}
