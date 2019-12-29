<?php

namespace addons\xshop\model;

use addons\xshop\exception\Exception;
use addons\xshop\exception\NotFoundException;
use addons\xshop\exception\NotAuthorizeException;
use app\admin\library\xshop\Tools;
use addons\xshop\logic\SkuLogic;
use think\Db;
use think\Hook;

class OrderProductModel extends Model
{
    protected $name = 'xshop_order_product';
    protected $autoWriteTimestamp = true;
    protected $visible = [
        'id', 'order_id', 'product_id', 'sku_id', 'title', 'description', 'images', 'attributes',
        'price', 'quantity', 'product_price', 'order_price', 'discount_price', 'order', 'reviews',
        'buyer_review', 'create_time_text', 'product'
    ];
    protected $append = [
        'images'
    ];

    /**
     * 评价订单商品
     */
    public static function review($attributes)
    {
        extract($attributes);
        $content = empty($content) ? '' : $content;
        $user = UserModel::info();
        $orderProduct = self::with(['order', 'reviews' => function ($q) use ($user) {
            $q->where('user_id', $user->id);
        }])->where('id', $id)->find();
        if (empty($orderProduct)) {
            throw new NotFoundException("商品不存在");
        }
        if (empty($orderProduct->order)) {
            throw new NotFoundException("订单不存在");
        }
        if ($orderProduct->order['user_id'] != $user->id) {
            throw new NotAuthorizeException("您不能这么做");
        }
        if ($orderProduct->order['status'] != 2) {
            throw new NotAuthorizeException("您不能评价该商品");
        }
        if (!empty($orderProduct->reviews)) {
            throw new Exception("商品已评价");
        }

        $review = new ReviewModel;
        $review->user_id = $user->id;
        $review->order_id = $orderProduct->order_id;
        $review->product_id = $orderProduct->product_id;
        $review->sku_id = $id;
        $review->content = $content;
        $review->star = $star;
        $payload = [
            'user' => $user,
            'orderProduct' => $orderProduct,
            'review' => $review
        ];
        \think\Hook::listen('xshop_user_review_before', $payload); // 用户评价商品前
        $orderProduct->buyer_review = 1;
        $orderProduct->save();
        $review->save();
        \think\Hook::listen('xshop_user_review_after', $payload); // 用户评价商品后
        return $review;
    }
    
    public function getCreateTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['create_time']) ? $data['create_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i", $value) : $value;
    }


    public function getImagesAttr($value, $data)
    {
        return explode(',', $data['image']);
    }

    public function order()
    {
        return $this->belongsTo('OrderModel', 'order_id', 'id');
    }

    public function reviews()
    {
        return $this->hasMany('ReviewModel', 'sku_id', 'id');
    }

    public function product() {
        return $this->hasOne('ProductModel', 'id', 'product_id');
    }
}
