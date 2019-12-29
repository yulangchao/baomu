<?php

namespace app\admin\model\xshop;

use think\Model;


class Product extends Model
{

    

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'xshop_product';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    // protected $createTime = false;
    // protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'create_time_text',
        'update_time_text'
    ];

    public static function getList($params) {
        extract($params);
        $model = new self;
        if (!empty($kw)) $model->where('title', '%LIKE%', $kw);
        return $model->paginate(10);
    }

    public function getCreateTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['create_time']) ? $data['create_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getUpdateTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['update_time']) ? $data['update_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setCreateTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setUpdateTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


    public function category()
    {
        return $this->belongsTo('Category', 'category_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function attrs() {
        return $this->hasMany(ProductAttr::class, 'product_id', 'id');
    }

    public function skus() {
        return $this->hasMany('ProductSku', 'product_id', 'id');
    }
}
