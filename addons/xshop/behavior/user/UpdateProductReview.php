<?php

namespace addons\xshop\behavior\user;

use addons\xshop\model\ProductModel;
use addons\xshop\model\OrderProductModel;
use addons\xshop\model\OrderModel;
use think\Db;

/**
 * 更新商品评价数量
 * xshop_user_review_after
 */
class UpdateProductReview
{
    /**
     * @param Auth $user
     * @param OrderProductModel $orderProduct
     * @param ReviewModel $review
     */
    public function xshopUserReviewAfter(&$payload)
    {
        $review = $payload['review'];
        $product = ProductModel::where('id', $review->product_id)->find();
        if (!empty($product)) {
            $product->review_count = $product->review_count + 1;
            $product->save();
        }
        $orderProduct = OrderProductModel::where('order_id', $review->order_id)->where('buyer_review', 0)->select();
        if (empty($orderProduct)) {
            $order = OrderModel::get($review->order_id);
            $order->buyer_review = 1;
            $order->save();
        }
    }
}
