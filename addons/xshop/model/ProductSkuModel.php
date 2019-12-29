<?php

namespace addons\xshop\model;

use think\Model;

class ProductSkuModel extends Model
{
    protected $name = "xshop_product_sku";

    public static function getListBySkuIds($ids)
    {
        $ids = is_array($ids) ? $ids : explode(',', $ids);
        return self::with('product')->where('id', 'IN', $ids)->select();
    }

    public function product()
    {
        return $this->belongsTo('ProductModel', 'product_id', 'id');
    }
}
