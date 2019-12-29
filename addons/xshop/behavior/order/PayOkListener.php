<?php

namespace addons\xshop\behavior\order;

use addons\xshop\model\OrderModel;
use addons\xshop\model\OrderProductModel;
use addons\xshop\model\ProductModel;
use addons\xshop\model\ProductSkuModel;
use think\Log;

/**
 * 支付成功
 */
class PayOkListener
{
    /**
     * @param OrderModel $order
     */
    public function xshopOrderPayOk(&$payload)
    {
        $this->order = $payload;
        $this->orderProducts = OrderProductModel::where('order_id', $this->order->id)->select();
        $this->addLog();
        $this->updateSoldCount();
        $this->updateStorage();
    }

    /** 记录日志 */
    public function addLog()
    {
        Log::info($this->order->toArray());
    }

    /** 更新销量 */
    public function updateSoldCount()
    {
        foreach ($this->orderProducts as $row) {
            ProductModel::where('id', $row->product_id)->setInc('sold_count', $row->quantity);
            ProductSkuModel::where('id', $row->sku_id)->setInc('sold_count', $row->quantity);
        }
    }

    /** 更新库存 */
    public function updateStorage()
    {
        foreach ($this->orderProducts as $row) {
            ProductSkuModel::where('id', $row->sku_id)->setDec('stock', $row->quantity);
        }
    }
}
