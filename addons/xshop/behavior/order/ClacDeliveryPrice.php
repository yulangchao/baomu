<?php

namespace addons\xshop\behavior\order;

use addons\xshop\model\DeliveryModel;
use addons\xshop\logic\SkuLogic;

/**
 * 计算运费
 */
class ClacDeliveryPrice
{
    /**
     * xshop_order_before_create_response
     * @param Auth $user
     * @param Array $productData (quantity, ProductModel $product, ProductSkuModel $sku)
     */
    public function xshopOrderBeforeCreateResponse(&$payload)
    {
        $products = $payload['productData'];
        $address_id = $payload['address_id'];
        SkuLogic::instance()->product($products)->clacDeliveryPrice($address_id);
    }
}
