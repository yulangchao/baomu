<?php

namespace addons\xshop\behavior\order;

use think\Db;

/**
 * 确认收货后 更新商品销量
 * xshop_order_received_after
 */
class UpdateSoldData
{
    /**
     * @param Auth $user
     * @param OrderModel $order
     */
    public function xshopOrderReceivedAfter(&$payload)
    {
        $user = $payload['user'];
        $order = $payload['order'];
        (new ProductModel)->saveAll($this->buildProductData($order->id));
        (new ProductSkuModel)->saveAll($this->buildSkuData($order->id));
    }

    /**
     * 构造商品销量更新数据
     */
    public function buildProductData($order_id)
    {
        $sql = Db::table('__XSHOP_ORDER_PRODUCT__')->where('order_id', $order_id)->group("product_id")->field("product_id,sum(quantity) as quantity")->buildSql();
        $list = Db::table('__XSHOP_PRODUCT__')->alias('a')->join($sql . " b", 'a.id=b.product_id')->field("a.id,b.quantity+a.sold_count as sold_count")->select();
        return $list;
    }

    /**
     * 构造SKU销量更新数据
     */
    public function buildSkuData($order_id)
    {
        $sql = Db::table('__XSHOP_ORDER_PRODUCT__')->where('order_id', $order_id)->group("sku_id")->field("sku_id,sum(quantity) as quantity")->buildSql();
        $list = Db::table('__XSHOP_PRODUCT_SKU__')->alias('a')->join($sql . " b", 'a.id=b.sku_id')->field("a.id,b.quantity+a.sold_count as sold_count")->select();
        return $list;
    }
}
