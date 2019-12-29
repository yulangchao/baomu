<?php

namespace addons\xshop\logic;

use addons\xshop\model\ProductSkuModel;
use addons\xshop\model\DeliveryModel;
use addons\xshop\exception\NotFoundException;

class SkuLogic
{
    protected $skus = []; // ProductSku::class
    public $products = []; // <list> CartModel::class
    public $products_price = 0; // 商品总价
    public $delivery_price = 0; // 运费
    public $discount_price = 0; // 总优惠
    public $order_price = 0; // 订单最终价格

    public static function instance()
    {
        return new self;
    }
    public function product($products)
    {
        $this->products = $products;
        return $this;
    }
    /**
     * 计算商品实际总价格
     */
    public function clacRealProductsPrice()
    {
        $total = 0;
        foreach ($this->products as $product) {
            $total += $product->quantity * $product->sku['price'];
        }
        $this->products_price = number_format($total, 2, '.', '');
        return $this;
    }

    /**
     *  计算运费
     *  @param Integer $area_id 地址
     */
    public function clacDeliveryPrice($area_id)
    {
        $delivery = DeliveryModel::with(['deliveryRules'])->order("is_default", "DESC")->order("id")->find();
        if (empty($delivery)) {
            throw new NotFoundException("商家没有设置运费规则");
        }
        $address = \addons\xshop\model\AreaModel::where('id', $area_id)->find();
        if ($address->level == 3) { // 计算运费只到市一级，区县地址重定向到市一级
            $address = \addons\xshop\model\AreaModel::find($address->pid);
        }
        if (empty($address)) {
            throw new NotFoundException("您的地址信息错误");
        }
        foreach ($delivery->deliveryRules as $row) { // 查找完全一致的地址Id
            if (in_array($address->id, $row->area_ids)) {
                $rule = $row;
                break;
            }
        }
        if (empty($rule)) {
            if ($address->level == 2) { // 查找上级地址
                foreach ($delivery->deliveryRules as $row) {
                    if (in_array($address->pid, $row->area_ids)) {
                        $rule = $row;
                        break;
                    }
                }
            }
        }
        if (empty($rule)) {
            foreach ($delivery->deliveryRules as $row) {
                if (in_array(0, $row->area_ids)) {
                    $rule = $row;
                    break;
                }
            }
        }
        if (empty($rule)) {
            throw new NotFoundException("该地区暂时缺货");
        }
        
        switch ($delivery->type) {
            case 0: { // 按重量计费
                $weigh = $this->clacProductWeigh();
                $rest_weigh = ceil($weigh - 1);
                $rest_weigh = $rest_weigh < 0 ? 0 : $rest_weigh;
                $this->delivery_price = $rule->first_price + $rest_weigh * $rule->rest_price;
                break;
            }
            case 1: {
                $count = $this->clacProductCount();
                $this->delivery_price = $rule->first_price + ($count - 1) * $rule->rest_price;
                break;
            }
        }
        return $this;
    }

    /** 计算优惠金额 */
    public function clacDiscountPrice()
    {
        \think\Hook::listen('xshop_clac_discount_price', $this);
        return $this;
    }

    /** 计算商品最后价格 */
    public function clacOrderPrice()
    {
        $order_price = number_format($this->products_price + $this->delivery_price - $this->discount_price, 2, '.', '');
        $this->order_price = $order_price > 0 ? $order_price : 0.01;
        return $this;
    }

    /** 计算各项价格 */
    public function clacPrice($products, $area_id)
    {
        $this->product($products)
            ->clacRealProductsPrice()   // 计算商品价格
            ->clacDeliveryPrice($area_id)       // 计算运费
            ->clacDiscountPrice()           // 计算优惠
            ->clacOrderPrice();      // 计算订单价格
        return $this;
    }

    /** 计算商品总重量 */
    public function clacProductWeigh()
    {
        $res = 0;
        foreach ($this->products as $product) {
            $res += floatval($product->sku['weight']) * $product->quantity;
        }
        return $res;
    }

    /** 计算商品总件数 */

    public function clacProductCount()
    {
        $res = 0;
        foreach ($this->products as $product) {
            $res += $product->quantity;
        }
        return $res;
    }
}
