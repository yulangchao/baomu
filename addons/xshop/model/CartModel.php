<?php

namespace addons\xshop\model;

use addons\xshop\exception\Exception;
use addons\xshop\exception\NotFoundException;
use addons\xshop\exception\NotLoginException;
use app\admin\library\xshop\Tools;
use think\Hook;

class CartModel extends Model
{
    protected $name = 'xshop_cart';
    protected $autoWriteTimestamp = true;
    protected $deleteTime = false;
    protected $hidden = [
        'user_id', 'product_id', 'sku_id', 'create_time', 'update_time', 'price', 'product.price'
    ];
    public static function getList($type = 0)
    {
        $user = UserModel::info();
        $model = self::with(['product', 'sku', 'deliveryTpl'])->where('user_id', $user->id);
        switch ($type) {
            case 1: {
                $model->where('is_selected', 1);
            }
            default: {
                break;
            }
        }
        $list = $model->select();
        return $list;
    }

    /**
     * 添加到购物车
     */
    public static function add($attributes, $forceAppend = false)
    {
        extract($attributes);
        $sku = ProductSkuModel::with(['product'])->find($sku_id);
        if (empty($sku) || empty($sku['product'])) {
            throw new NotFoundException("商品不存在");
        }
        $user = UserModel::info();
        if (!$user->isLogin()) {
            throw new NotLoginException();
        }
        if ($quantity > $sku->stock) {
            throw new Exception("商品库存不足");
        }
        $cart = self::where("user_id", $user->id)->select();
        
        foreach ($cart as $i => $row) {
            if ($row->sku_id == $sku_id && $forceAppend === false) {
                $row->quantity += $quantity;
                $row->save();
                Hook::listen('xshop_cart_add', $row);
                return $row->save();
            }
        }
        $data = [
            'product_id' => $sku->product_id,
            'sku_id' => $sku_id,
            'price' => $sku->price,
            'quantity' => $quantity,
            'user_id' => $user->id
        ];
        $cart = new CartModel;
        $cart->save($data);
        Hook::listen('xshop_cart_add', $cart);
        return $cart;
    }

    public static function edit($attributes)
    {
        extract($attributes);
        $sku = ProductSkuModel::with(['product'])->find($sku_id);
        if (empty($sku) || empty($sku['product'])) {
            throw new NotFoundException("商品不存在");
        }
        $user = UserModel::info();
        if (!$user->isLogin()) {
            throw new NotLoginException();
        }
        $cart = self::where("user_id", $user->id)->where('sku_id', $sku_id)->find();
        if (empty($cart)) {
            throw new NotFoundException();
        }
        if ($quantity > $sku->stock) {
            throw new Exception("商品库存不足");
        }
        $cart->quantity = $quantity;
        $cart->save();
        Hook::listen('xshop_cart_update', $cart);
        return $cart;
    }

    /**
     * 删除
     */
    public static function del($attributes)
    {
        extract($attributes);
        $user = UserModel::info();
        return self::where('user_id', $user->id)->where('id', 'IN', $ids)->delete();
    }

    /**
     * 清空
     */
    public static function clear()
    {
        $user = UserModel::info();
        return self::where('user_id', $user->id)->delete();
    }

    /**
     * 更新购物车选中状态
     * @param Array $ids 购物车Id数组
     */
    public static function updateStatus($attributes)
    {
        extract($attributes);
        $user = UserModel::info();
        $ids = Tools::array_integer($ids);
        $products = self::with(['product', 'sku'])->where('user_id', $user->id)->where('id', 'IN', $ids)->select();
        if (empty($products)) {
            throw new NotFoundException("没有选择商品");
        }
        if (true !== ($product = self::checkStock($products))) {
            throw new Exception("商品" . $product['product']['title'] . " " . $product['sku']['value'] . "库存不足");
        }
        self::where('user_id', $user->id)->update(['is_selected' => 0]);
        self::where('user_id', $user->id)->where('id', 'IN', $ids)->update(['is_selected' => 1]);
        return $products;
    }

    public static function checkStock($products)
    {
        foreach ($products as $i => $product) {
            if ($product['quantity'] > $product['sku']['stock']) {
                return $product;
            }
        }
        return true;
    }

    


    public function product()
    {
        return $this->hasOne('ProductModel', 'id', 'product_id');
    }

    public function sku()
    {
        return $this->hasOne('ProductSkuModel', 'id', 'sku_id');
    }

    public function deliveryTpl()
    {
        return $this->hasOne('DeliveryModel', 'id', 'delivery_id');
    }
}
