<?php

namespace addons\xshop\model;

use think\Model;

class ReviewModel extends Model
{
    protected $name = "xshop_review";
    protected $autoWriteTimestamp = true;
    protected $hidden = [
        'create_time', 'update_time', 'delete_time'
    ];
    protected $append = [
        'sku', 'create_time_text'
    ];

    /**
     * @param Integer $id 商品id
     */
    public static function getList($attributes)
    {
        extract($attributes);
        $model = self::with(['user', 'sku' => function ($q) use ($id) {
            $q->where('product_id', $id);
        }])->where('product_id', $id);
        $data =  $model->paginate(5)->toArray();
        if (!empty($data['data'])) {
            $star = self::where('product_id', $id)->sum('star');
            $totalStar = self::where('product_id', $id)->count() * 5;
            $data['good_review'] = number_format($star / $totalStar * 100, 2, '.', '') . "%";
        } else $data['good_review'] = '无';
        return $data;
    }

    

    public function getCreateTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['create_time']) ? $data['create_time'] : '');
        return is_numeric($value) ? date("Y-m-d", $value) : $value;
    }

    public function user()
    {
        return $this->hasOne('UserModel', 'id', 'user_id');
    }
    public function sku()
    {
        return $this->hasOne('OrderProductModel', 'product_id', 'product_id');
    }
}
